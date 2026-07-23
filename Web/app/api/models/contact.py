from sqlalchemy.orm import Mapped, mapped_column
from sqlalchemy import String, DateTime
from .base import Base
from datetime import datetime, timezone

class ContactBase(Base):
    __tablename__ = "contacts"

    id: Mapped[int] = mapped_column(primary_key=True, nullable=False)
    
    name: Mapped[str] = mapped_column(String(100), nullable=False)
    phone: Mapped[str] = mapped_column(String(20), nullable=False)
    email: Mapped[str] = mapped_column(String(255), nullable=False)
    message: Mapped[str] = mapped_column(String(255), nullable=False)
    
    date: Mapped[datetime] = mapped_column(DateTime(timezone=True), nullable=False, default=datetime.now(timezone.utc))

    def __repr__(self) -> str:
        return f"Contact(id={self.id}, name={self.name}, phone={self.phone}, email={self.email}, message={self.message}, date={self.date})"