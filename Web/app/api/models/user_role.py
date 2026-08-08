from sqlalchemy.orm import Mapped, relationship, mapped_column
from sqlalchemy import String
from .base import Base

class UserRoleBase(Base):
    __tablename__ = "user_roles"
    id: Mapped[int] = mapped_column(primary_key=True)
    name: Mapped[str] = mapped_column(String(30))

    users = relationship("UserBase", back_populates="role")
    
    def ADMIN() -> "UserRoleBase":
        return UserRoleBase(id=1, name="admin")
    
    def USER() -> "UserRoleBase":
        return UserRoleBase(id=2, name="user")