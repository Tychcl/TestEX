from abc import ABC, abstractmethod
from ..schemas import UserLogin, UserResponse, UserRegister
from fastapi.responses import JSONResponse, RedirectResponse
from typing import Optional, Tuple

class IAuthService(ABC):
    @abstractmethod
    async def signup(self, data: UserRegister) -> Optional[UserResponse]: pass
    @abstractmethod
    async def signin(self, data: UserLogin) -> Tuple[Optional[UserResponse], Optional[JSONResponse]]: pass
    @abstractmethod    
    async def logout(self) -> RedirectResponse: pass
    @abstractmethod    
    async def change_password(self, id: int, new_password: str) -> bool: pass