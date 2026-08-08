from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select, or_
from sqlalchemy.orm import selectinload
from typing import Optional
from ..models import UserBase, UserRoleBase
from ..schemas import UserRegister

class UserRepo():
    def __init__(self, session: AsyncSession):
        self.session = session
    
    #create
    async def create(self, data: UserRegister) -> UserBase:
        user: UserBase = UserBase(**data.model_dump(exclude={'confirm'}))
        user.role_id = UserRoleBase.USER().id
        self.session.add(user)
        await self.session.commit()
        return user
    
    #read
    async def get_by(self, 
                    id: Optional[int] = None, 
                    email: Optional[str] = None,
                    load_role: bool = True,
                    is_active: bool = True) -> Optional[UserBase]:
        opt: list = []
        if load_role:
            opt.append(selectinload(UserBase.role))
        conditions: list = []
        if id:
            conditions.append(UserBase.id == id)
        if email:
            conditions.append(UserBase.email == email)
        sql = select(UserBase)
        if len(conditions) > 0:
            sql = sql.where(or_(*conditions)).where(UserBase.is_active == is_active)
        else:
            return None
        if len(opt) > 0:
            sql = sql.options(*opt)
        result = await self.session.execute(sql)
        return result.scalar_one_or_none()
    
    #update
    async def update(self, user: UserBase, update_data: dict) -> UserBase:
        for field, value in update_data.items():
            setattr(user, field, value)
        await self.session.commit()
        return user
    
    #delete
    async def delete(self, id: int) -> bool:
        user: Optional[UserBase] = await self.get_by(id=id, load_role=False)
        if not user:
            return False
        await self.update(user, {'is_active': False})
        return True
        