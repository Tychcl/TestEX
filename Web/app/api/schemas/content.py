from pydantic import BaseModel
from typing import Optional

class ContentChange(BaseModel):
    id: int
    content: Optional[str] = None
    role: Optional[int] = None