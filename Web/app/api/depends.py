from .models import UserBase
from typing import Optional
from fastapi import HTTPException, status, Request
from functools import wraps
from .interfaces import IJWTService, IPasswordHasherService, ICookieService, IAuthService, IUserService
from .services import JWTService, PasswordHasherService, CookieService, AuthService, UserService
from .repositories import UserRepo
from sqlalchemy.ext.asyncio import AsyncSession
from fastapi import Depends
from app.database import get_context

#repo
async def user_repo(session: AsyncSession = Depends(get_context)) -> UserRepo:
    return UserRepo(session=session)

#regular services
async def jwt_service() -> IJWTService:
    return JWTService()

async def password_hasher_service() -> IPasswordHasherService:
    return PasswordHasherService()

async def cookie_service() -> ICookieService:
    return CookieService()

#services
async def auth_service(repo: UserRepo = Depends(user_repo),
                       hasher: IPasswordHasherService = Depends(password_hasher_service),
                       jwt: IJWTService = Depends(jwt_service),
                       cook: ICookieService = Depends(cookie_service)) -> IAuthService:
    return AuthService(repo=repo, hasher=hasher, jwt_service=jwt, cookie_service=cook)

async def user_service(repo: UserRepo = Depends(user_repo),
                       hasher: IPasswordHasherService = Depends(password_hasher_service)) -> IUserService:
    return UserService(repo=repo, hasher=hasher)

#AUTH
async def get_authorized_user(request: Request) -> Optional[UserBase]:
    try:
        user: Optional[UserBase] = request.state.user
    except AttributeError:
        user = None
    if user is None:
        raise HTTPException(status.HTTP_401_UNAUTHORIZED, "unauthorized")
    return user

async def get_user(request: Request) -> Optional[UserBase]:
    try:
        user: Optional[UserBase] = request.state.user
    except AttributeError:
        user = None
    return user

async def auth_check(request: Request) -> UserBase:
    user: Optional[UserBase] = await get_authorized_user(request)
    endpoint = request.scope.get("endpoint")
    if endpoint is None:
        raise HTTPException(status.HTTP_500_INTERNAL_SERVER_ERROR, "endpoint is none")
    role_needed: Optional[int] = getattr(endpoint, "_role_required", None)
    if role_needed is not None and role_needed < user.role_id:
        raise HTTPException(status.HTTP_403_FORBIDDEN, {"user": {"id": user.id, "role_id": user.role_id},"role_needed": role_needed, "msg": "access denied"})
    return user
    
def role_required(role_required: int):
    def decorator(func):
        func._role_required = role_required
        @wraps(func)
        async def wrapper(*args, **kwargs):
            return await func(*args, **kwargs)
        return wrapper
    return decorator