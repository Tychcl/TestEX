from abc import abstractmethod, ABC
from typing import Optional, List
from ..schemas import ContactCreate, ContactResponse
from datetime import datetime

class IContactService(ABC):
    @abstractmethod
    async def create_contact(self, data: ContactCreate) -> ContactResponse: pass
    
    @abstractmethod
    async def get_contact(self, contact_id: int) -> Optional[ContactResponse]: pass
    
    @abstractmethod
    async def get_all_contacts(self, skip: int = 0, limit: int = 100) -> List[ContactResponse]: pass
    
    @abstractmethod
    async def get_metric(self, start: datetime, end: datetime) -> int: pass