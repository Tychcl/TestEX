from ..repositories import ContactRepo
from ..interfaces import IContactService
from ..schemas import ContactCreate, ContactResponse
from ..models import ContactBase
from typing import Optional, List
from datetime import datetime

class ContactService(IContactService):
    def __init__(self, repo:ContactRepo):
        self.repo = repo
        
    async def create_contact(self, data: ContactCreate) -> ContactResponse:
        contact: ContactBase = await self.repo.create(data)
        return ContactResponse.model_validate(contact)
    
    async def get_contact(self, contact_id: int) -> Optional[ContactResponse]: 
        contact: Optional[ContactBase] = await self.repo.get_by_id(contact_id)
        if not contact:
            return None
        return ContactResponse.model_validate(contact)
    
    async def get_all_contacts(self, skip: int = 0, limit: int = 100) -> List[ContactResponse]: 
        contacts: List[ContactBase] = await self.repo.get_all(skip, limit)
        return [ContactResponse.model_validate(c) for c in contacts]
    
    async def get_metric(self, start: datetime, end: datetime) -> int:
        return await self.repo.count_between_dates(start, end)