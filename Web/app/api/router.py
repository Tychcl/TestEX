from fastapi import APIRouter
from .controllers import contact_controller, other_controller

api_router = APIRouter(prefix="/api")
api_router.include_router(contact_controller)
api_router.include_router(other_controller)