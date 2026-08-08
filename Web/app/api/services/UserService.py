from fastapi import HTTPException, status
from ..models import UserBase
from ..interfaces import IUserService
from ..interfaces import IPasswordHasherService
from typing import Optional, List, Tuple
from ..schemas import UserResponse, UserUpdate
from ..repositories import UserRepo

class UserService(IUserService):
    def __init__(self, repo: UserRepo,
                hasher: IPasswordHasherService):
        self.repo = repo
        self.hasher = hasher
    
    #update
    async def update(self, user_id: int, data: UserUpdate) -> UserResponse:
        user_orm: Optional[UserBase] = await self.repo.get_by(id=user_id, load_role=True)
        if user_orm is None:
            raise HTTPException(404, "User not found")
        update_data = data.model_dump(exclude_unset=True, exclude_none=True)
        exists_user: Optional[UserBase] = await self.repo.get_by(email=data.email)
        if exists_user and user_orm.id != exists_user.id:
            raise HTTPException(status.HTTP_400_BAD_REQUEST, f"Email already taken")
        update_data.pop('password', None)
        if 'new_password' in update_data:
            update_data['password'] = self.hasher.hash(update_data['new_password'])
            update_data.pop('new_password', None)
        updated_orm = await self.repo.update(user_orm, update_data)
        return UserResponse.model_validate(updated_orm)
    
    #delete
    async def delete(self, user_id: int) -> bool:
        return await self.repo.delete(user_id)