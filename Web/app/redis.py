import redis.asyncio as redis
from app.config import settings
from typing import Optional

redis_client = redis.Redis(host=settings.REDIS_HOST,
                           port=settings.REDIS_PORT,
                           db=0, password=settings.REDIS_PASSWORD,
                           #ssl=True, 
                           #ssl_cert_reqs=None
                           )

async def check_redis_connection() -> bool:
    try:
        await redis_client.ping()
        return True
    except Exception:
        return False