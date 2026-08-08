from abc import ABC, abstractmethod
from typing import Optional, List, Tuple
from ..schemas import UserResponse, UserUpdate

class IUserService(ABC):
    @abstractmethod
    async def update(self, user_id: int, data: UserUpdate) -> UserResponse: pass
    @abstractmethod
    async def delete(self, user_id: int) -> bool: pass
    