from fastapi.responses import JSONResponse, RedirectResponse
from fastapi import HTTPException, status
from ..repositories import UserRepo
from ..models.user import UserBase
from ..interfaces import IAuthService
from ..interfaces import IPasswordHasherService, IJWTService, ICookieService
from ..schemas import UserResponse, UserLogin, UserRegister
from app.config import settings
from typing import Optional, Tuple

class AuthService(IAuthService):
    def __init__(self, 
                 repo: UserRepo, 
                 hasher: IPasswordHasherService,
                 jwt_service: IJWTService,
                 cookie_service: ICookieService ):
        self.repo = repo
        self.hasher = hasher
        self.jwt_service = jwt_service
        self.cookie_service = cookie_service

    async def signup(self, data: UserRegister) -> Optional[UserResponse]:
            exists: Optional[UserBase] = await self.repo.get_by(email=data.email, load_role=False)
            if exists and exists.is_active:
                raise HTTPException(status_code=status.HTTP_409_CONFLICT, detail="User with this email already exists")
            data.password = self.hasher.hash(data.password)
            user: UserBase = await self.repo.create(data)
            return UserResponse.model_validate(user)

    async def signin(self, data: UserLogin) -> Tuple[Optional[UserResponse], Optional[JSONResponse]]:
        user: Optional[UserBase] = await self.repo.get_by(email=data.email, load_role=True)
        if user and self.hasher.verify(data.password, user.password):
            user_data: dict = user.to_dict
            access_token: str = self.jwt_service.create_access_token(user_data)
            refresh_token: str = self.jwt_service.create_refresh_token(user_data)
            response: JSONResponse = JSONResponse(content=user_data, status_code=200)
            self.cookie_service.set_cookie(response, settings.JWT_STRING, access_token, settings.JWT_LIFETIME)
            self.cookie_service.set_cookie(response, settings.REFRESH_STRING, refresh_token, settings.REFRESH_LIFETIME)
            return (UserResponse.model_validate(user), response)
        return (None, None)
    
    async def logout(self) -> JSONResponse:
        response: JSONResponse = JSONResponse(content={"msg": "Success"})
        self.cookie_service.delete_cookie(response, settings.JWT_STRING)
        self.cookie_service.delete_cookie(response, settings.REFRESH_STRING)
        return response
    
    async def change_password(self, id: int, new_password: str) -> bool:
        try:
            user: UserBase = await self.repo.get_by(id=id)
            data: dict = {"password": self.hasher.hash(new_password)}
            await self.repo.update(user, data)
            return True
        except Exception as e:
            return False