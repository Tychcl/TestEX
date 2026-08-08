from fastapi import FastAPI, HTTPException, Request
from fastapi.responses import JSONResponse
from app.config import logger
from .api.middlewares import user_middleware
from .api.services import JWTService, CookieService, PasswordHasherService
from sqlalchemy import select
from contextlib import asynccontextmanager
from app.api.models import Base
from app.api.models import UserRoleBase
from app.database import context, AsyncSessionLocal
from fastapi import FastAPI
from .api.router import api_router

@asynccontextmanager
async def lifespan(app: FastAPI):
    async with context.begin() as conn:
        await conn.run_sync(Base.metadata.create_all)
    async with AsyncSessionLocal() as session:
        stmt = select(UserRoleBase)
        result = await session.execute(stmt)
        if result.scalars().first() is None:
            session.add_all([
                UserRoleBase.ADMIN(),
                UserRoleBase.USER(),
            ])
            await session.commit()
    yield

app = FastAPI(lifespan=lifespan)

app.state.jwt_service = JWTService()
app.state.cookie_service = CookieService()
app.state.hash_service = PasswordHasherService()

app.middleware("http")(user_middleware)

app.include_router(api_router)

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