from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.responses import JSONResponse
from ..schemas import UserRegister, UserResponse, UserLogin
from ..interfaces import IAuthService
from ..depends import auth_service

auth_controller = APIRouter(prefix="/auth", tags=["auth"])

@auth_controller.post("/register")
async def register(data: UserRegister,
                   AuthService: IAuthService = Depends(auth_service)) -> JSONResponse:
    user: UserResponse = await AuthService.signup(data)
    return JSONResponse(content=user.model_dump())

@auth_controller.post("/login")
async def login(data: UserLogin,
                AuthService: IAuthService = Depends(auth_service)) -> JSONResponse:
    user, response = await AuthService.signin(data)
    if user is None:
        raise HTTPException(status.HTTP_400_BAD_REQUEST, "invalid email or password")
    return response

@auth_controller.post("/signout")
async def logout(AuthService: IAuthService = Depends(auth_service)) -> JSONResponse:
    return await AuthService.logout()