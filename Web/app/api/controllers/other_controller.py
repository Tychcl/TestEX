from fastapi import APIRouter, Depends, Request, HTTPException
from ..interfaces import IContactService
from ..dependencies import get_contact_service
from celery import Celery
from redis import Redis
from enum import Enum
from typing import Optional
from datetime import datetime, timezone, timedelta

other_controller = APIRouter(tags=['other'])

@other_controller.get('/health', response_model=dict)
async def health(request: Request):
    celery: Celery = request.app.state.celery
    redis: Redis = request.app.state.redis
    r: dict = {}
    try:
        await redis.ping()
        r['redis'] = True
    except:
        r['redis'] = False
    try:
        cp = celery.control.inspect().ping()
        if not cp:
            r['celery'] = False
        r['celery'] = True
    except:
        r['celery'] = False
    return r

class DateMode(str, Enum):
    today = 'today'
    week = 'week'
    month = 'month'
    year = 'year'
    period = 'period'

@other_controller.get('/metric', response_model=int)
async def metric(request: Request,
                mode: DateMode,
                start: Optional[datetime] = None,
                end: Optional[datetime] = None,
                contact_service: IContactService = Depends(get_contact_service)) -> int:
    now = datetime.now(timezone.utc)
    if mode == DateMode.today:
        start_date = now.replace(hour=0, minute=0, second=0, microsecond=0)
        end_date = now
    elif mode == DateMode.week:
        start_date = now - timedelta(days=7)
        end_date = now
    elif mode == DateMode.month:
        start_date = now - timedelta(days=30)
        end_date = now
    elif mode == DateMode.year:
        start_date = now - timedelta(days=365)
        end_date = now
    elif mode == DateMode.period:
        if start is None or end is None:
            raise HTTPException(status_code=400, detail="Для period необходимо указать start и end")
        if start.tzinfo is None:
            start = start.replace(tzinfo=timezone.utc)
        if end.tzinfo is None:
            end = end.replace(tzinfo=timezone.utc)
        start_date = start
        end_date = end
    else:
        raise HTTPException(status_code=400, detail="Неизвестный режим")
    if start_date > end_date:
        raise HTTPException(status_code=400, detail="start не может быть позже end")

    count = await contact_service.get_metric(start_date, end_date)
    return count