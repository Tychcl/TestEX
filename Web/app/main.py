from fastapi import FastAPI, HTTPException, Request
from app.api.router import api_router
from fastapi.responses import JSONResponse
from app.config import logger, settings
from contextlib import asynccontextmanager
from app.api.models import Base
from app.database import context
from fastapi import FastAPI
from .redis import redis_client
from .celery import celery_app
import time
from redis import Redis
import json

@asynccontextmanager
async def lifespan(app: FastAPI):
    async with context.begin() as conn:
        await conn.run_sync(Base.metadata.create_all)
    yield
    await redis_client.close()

app = FastAPI(lifespan=lifespan)

app.state.redis = redis_client
app.state.celery = celery_app

app.include_router(api_router)

@app.middleware("http")
async def logging_middleware(request: Request, call_next):
    start = time.time()
    response = await call_next(request)
    duration = time.time() - start
    logger.info(
        f"Request: {request.client.host}:{request.client.port} {request.method} {request.url.path}"
        f"Status: {response.status_code} Duration: {duration:.3f}s"
    )
    return response

@app.middleware("http")
async def rate_limiter_middleware(request: Request, call_next):
    r: Redis = request.app.state.redis
    key = f"{request.client.host}:{request.url.path}"
    try:
        count = await r.incr(key)
        if count == 1:
            await r.expire(key, settings.RATE_TIME)
        if count > settings.RATE_LIMIT:
            return JSONResponse(status_code=429, content={"message": "Too many requests"})
    except Exception as e:
        logger.error(f"Redis error in rate limiting: {e}")
    response = await call_next(request)
    return response

@app.exception_handler(HTTPException)
async def http_exception_handler(request: Request, exc: HTTPException):
    logger.error(
        f"Request: {request.client.host}:{request.client.port} {request.method} {request.url.path}"
        f"exception: {exc.status_code} message: {exc.detail}"
    )
    return JSONResponse(
        status_code=exc.status_code,
        content={"message": exc.detail, "path": request.url.path}
    )
    
@app.exception_handler(ValueError)
async def value_error_handler(request: Request, exc: ValueError):
    logger.error(
        f"Request: {request.client.host}:{request.client.port} {request.method} {request.url.path}"
        f"exception: 400 value error"
    )
    return JSONResponse(
        status_code=400,
        content={"message": str(exc), "path": request.url.path}
    )
    
@app.exception_handler(Exception)
async def global_exception_handler(request: Request, exc: Exception):
    logger.error(
            f"Request: {request.client.host}:{request.client.port} {request.method} {request.url.path}"
            f"exception: 500 {exc}"
        )
    return JSONResponse(
        status_code=500,
        content={"message": "An unexpected error occurred"}
    )