from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.responses import JSONResponse
from ..schemas import UserResponse, UserUpdate
from ..interfaces import IUserService
from ..depends import user_service, get_authorized_user
from ..models import UserBase

user_controller = APIRouter(prefix="/user", tags=["user"])

@user_controller.post("/me/update")
async def update(data: UserUpdate,
                UserService: IUserService = Depends(user_service),
                User: UserBase = Depends(get_authorized_user)) -> JSONResponse:
    updated: UserResponse = await UserService.update(User.id, data)
    return JSONResponse(content=updated.model_dump())

@user_controller.post("/me/delete")
async def delete(UserService: IUserService = Depends(user_service),
                User: UserBase = Depends(get_authorized_user)) -> JSONResponse:
    result: bool = await UserService.delete(User.id)
    return JSONResponse(content={"result": result})