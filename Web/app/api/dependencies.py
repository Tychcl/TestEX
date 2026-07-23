from fastapi import Depends
from .repositories import ContactRepo
from app.database import get_context
from sqlalchemy.ext.asyncio import AsyncSession
from .interfaces import IContactService
from .services import ContactService

async def get_contact_repo(session: AsyncSession = Depends(get_context)) -> ContactRepo:
    return ContactRepo(session=session)

async def get_contact_service(repo: ContactRepo = Depends(get_contact_repo)) -> IContactService:
    return ContactService(repo=repo)