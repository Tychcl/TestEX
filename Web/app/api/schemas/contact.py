from pydantic import BaseModel, EmailStr, field_validator
from ..validators import is_valid_name, is_valid_phone
from datetime import datetime

class ContactCreate(BaseModel):
    name: str
    phone: str
    email: EmailStr
    message: str
    
    @field_validator('name')
    def name_validate(cls, v):
        if not is_valid_name(v):
            raise ValueError("Name wrong format")
        l = len(v)
        if l > 100:
            raise ValueError(f"Name maximum length 100, your length={l}")
        return v
    
    @field_validator('phone')
    def phone_validate(cls, v):
        if not is_valid_phone(v):
            raise ValueError("Phone wrong format")
        l = len(v)
        if l > 20:
            raise ValueError(f"Phone maximum length 20, your length={l}")
        return v
    
    @field_validator('message')
    def message_validate(cls, v):
        if v is None:
            raise ValueError("Message required")
        l = len(v)
        if l > 255:
            raise ValueError(f"Message maximum length 255, your length={l}")
        elif l < 20:
            raise ValueError(f"Message minimum length 20, your length={l}")
        return v
    
class ContactResponse(BaseModel):
    id: int
    name: str
    phone: str
    email: str
    message: str
    date: datetime

    class Config:
        from_attributes = True