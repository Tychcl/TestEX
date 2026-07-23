from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select, delete, update, func, and_
from typing import Optional, List
from ..models import ContactBase
from ..schemas.contact import ContactCreate
from datetime import datetime

class ContactRepo():
    def __init__(self, session: AsyncSession):
        self.session = session
        
    async def create(self, data: ContactCreate) -> ContactBase:
        contact: ContactBase = ContactBase(**data.model_dump())
        self.session.add(contact)
        await self.session.commit()
        return contact
    
    async def get_by_id(self, c_id: int) -> Optional[ContactBase]:
        sql = select(ContactBase).where(ContactBase.id == c_id)
        result = await self.session.execute(sql)
        return result.scalar_one_or_none()
    
    async def get_all(self, offset: int = 0, limit: int = 100) -> List[ContactBase]:
        sql = select(ContactBase).offset(offset).limit(limit)
        result = await self.session.execute(sql)
        return result.scalars().all()
    
    async def count_between_dates(self, start_date: datetime, end_date: datetime) -> int:
        sql = select(func.count()).where(and_(ContactBase.date >= start_date, ContactBase.date <= end_date))
        result = await self.session.execute(sql)
        return result.scalar()