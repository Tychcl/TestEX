from pydantic import BaseModel, EmailStr, field_validator, model_validator
from ..validators import is_valid_name, is_valid_password, is_valid_email
from typing import Optional, List

class UserRegister(BaseModel):
    name: str
    email: EmailStr
    password: str
    confirm: str
    
    @field_validator('name')
    def validate_name(cls, v):
        if not is_valid_name(v):
            raise ValueError('Name must be \"Иванов Иван Иванович\" format')
        return v

    @field_validator('password')
    def validate_password(cls, v):
        if not is_valid_password(v):
            raise ValueError('Invalid password format')
        return v

    @model_validator(mode='after')
    def check_passwords_match(self):
        if self.password != self.confirm:
            raise ValueError('Passwords do not match')
        return self
    
class UserLogin(BaseModel):
    email: EmailStr
    password: str
    
    @field_validator('password')
    def validate_password(cls, v):
        if not is_valid_password(v):
            raise ValueError('Invalid password format')
        return v
    
class UserUpdate(BaseModel):
    name: Optional[str] = None
    email: Optional[EmailStr] = None
    password: Optional[str] = None
    new_password: Optional[str] = None

    @field_validator('name')
    def validate_username(cls, v):
        if v is not None and not is_valid_name(v):
            raise ValueError('Username must contain only Latin letters')
        return v

    @field_validator('new_password')
    def validate_new_password(cls, v):
        if v is not None and not is_valid_password(v):
            raise ValueError('Invalid new password format')
        return v
    
class UserResponse(BaseModel):
    id: int
    name: str
    email: str
    is_active:bool
    role_id: int

    class Config:
        from_attributes = True