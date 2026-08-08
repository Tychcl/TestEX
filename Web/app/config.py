from fastapi.templating import Jinja2Templates
import os
import ssl
import logging
from logging.handlers import RotatingFileHandler
from pydantic_settings import BaseSettings

class Settings(BaseSettings):
    BASE_DIR: str = os.path.dirname(os.path.abspath(__file__))
    #DataBase
    DB_HOST: str
    DB_PORT: int
    DB_NAME: str
    DB_USER: str
    DB_PASSWORD: str
    #JWT
    JWT_LIFETIME: int
    JWT_STRING: str
    JWT_SECRET: str
    REFRESH_LIFETIME: int
    REFRESH_STRING: str
    REFRESH_SECRET: str
    ALGORITHM_SECRET: str
    
    @property
    def DB_URL(self) -> str:
        return f"postgresql+asyncpg://{self.DB_USER}:{self.DB_PASSWORD}@{self.DB_HOST}:{self.DB_PORT}/{self.DB_NAME}"

settings = Settings()

logger = logging.getLogger(__name__)
logger.setLevel(logging.INFO)