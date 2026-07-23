from fastapi import APIRouter, Depends, Request, HTTPException, Query
from fastapi.responses import JSONResponse
from ..schemas import ContactCreate, ContactResponse
from ..interfaces import IContactService
from ..dependencies import get_contact_service
from typing import Optional, List
from celery import Celery

contact_controller = APIRouter(prefix='/contact', tags=['contact'])

#create
@contact_controller.post("/", response_model=ContactResponse)
async def create_contact(
                    request: Request,
                    data: ContactCreate,
                    ContactService: IContactService = Depends(get_contact_service)) -> ContactResponse:
    contact: ContactResponse = await ContactService.create_contact(data)
    celery: Celery = request.app.state.celery
    celery.send_task(
        'send_email',
        args=[contact.name, contact.phone, contact.email, contact.message],
        queue='celery')
    return contact

#read
@contact_controller.get("/all", response_model=List[ContactResponse])
async def get_all_contacts(
                    skip: int = Query(0, ge=0, description="Сколько пропустить"),
                    limit: int = Query(100, ge=1, le=100, description="Сколько взять"),
                    contact_service: IContactService = Depends(get_contact_service)) -> List[ContactResponse]:
    contacts = await contact_service.get_all_contacts(skip, limit)
    return contacts

@contact_controller.get("/{id}", response_model=ContactResponse)
async def get_contact(id: int,
                    ContactService: IContactService = Depends(get_contact_service)) -> ContactResponse:
    contact: Optional[ContactResponse] = await ContactService.get_contact(id)
    if not contact:
        raise HTTPException(404, "Contact not found")
    return contact