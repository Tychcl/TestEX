from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.responses import JSONResponse
from ..schemas import ContentChange
from ..depends import get_authorized_user, role_required, auth_check
from ..models import UserBase
import string
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from ..models import UserRoleBase
from app.database import get_context
import random
from typing import Optional

s = string.ascii_letters + string.digits
content = [{"id": i, "content": ''.join(random.choices(s, k=random.randint(5,12))), "role_access": random.randint(1, 2)} for i in range(1,random.randint(4, 12))]

content_controller = APIRouter(prefix="/content", tags=["content"])

@role_required(1)
@content_controller.post("/admin/change")
async def change(data: ContentChange,
                User: UserBase = Depends(auth_check),
                session: AsyncSession = Depends(get_context)) -> JSONResponse:
    global content
    if data.id < 1:
       raise HTTPException(400, "id must be >= 1")
    if data.role < 1:
        raise HTTPException(400, "role must be >= 1")
    try:
        c: dict = content[data.id - 1]
    except:
        raise HTTPException(404, "content with that id not found")
    if data.role:
        sql = select(UserRoleBase).where(UserRoleBase.id == data.role)
        result = await session.execute(sql)
        role: Optional[UserRoleBase] = result.scalar_one_or_none()
        if role:
            c['role_access'] = data.role
        else:
            raise HTTPException(404, "role with that id not found")
    if data.content:
        c['content'] = data.content
    content[data.id - 1] = c
    return JSONResponse(content=c)

@content_controller.get("/")
async def get_content(User: UserBase = Depends(get_authorized_user)) -> JSONResponse:
    global content
    result: list = []
    for c in content:
        if c['role_access'] >= User.role_id:
            result.append(c)
    return JSONResponse(content=result)