from sqlalchemy.orm import Mapped, mapped_column, relationship
from sqlalchemy import String, ForeignKey, Boolean
from .base import Base

class UserBase(Base):
    __tablename__ = "users"

    id: Mapped[int] = mapped_column(primary_key=True, nullable=False)
    name: Mapped[str] = mapped_column(String(255), unique=False, nullable=False)
    email: Mapped[str] = mapped_column(String(255), unique=False, nullable=False)
    password: Mapped[str] = mapped_column(String(255), nullable=False)

    is_active: Mapped[bool] = mapped_column(Boolean(), nullable=False, default=True)
    
    role_id: Mapped[int] = mapped_column(ForeignKey("user_roles.id"), nullable=False)
    role: Mapped["UserRoleBase"] = relationship("UserRoleBase", back_populates="users")

    def __repr__(self) -> str:
        return f"User(id={self.id}, username={self.name}, email={self.email}, role_id={self.role_id})"

    @property
    def to_dict(self) -> dict:
        data: dict = super().to_dict
        data.pop('password', None)
        if 'role' in self.__dict__ and self.role is not None:
            data['role'] = self.role.to_dict
        return data