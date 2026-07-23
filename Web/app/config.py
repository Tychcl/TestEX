from fastapi.templating import Jinja2Templates
import os
import ssl
import logging
from logging.handlers import RotatingFileHandler
from pydantic_settings import BaseSettings

class Settings(BaseSettings):
    BASE_URL: str
    BASE_DIR: str = os.path.dirname(os.path.abspath(__file__))
    #Redis
    REDIS_PASSWORD: str
    REDIS_USER: str
    REDIS_USER_PASSWORD: str
    REDIS_HOST: str
    REDIS_PORT: int
    #DataBase
    DB_HOST: str
    DB_PORT: int
    DB_NAME: str
    DB_USER: str
    DB_PASSWORD: str
    #SMTP
    SMTP_MAIL: str
    SMTP_MAIL_PWD: str
    OWNER_MAIL: str
    #AI
    MI_TOKEN: str
    #rate limiter
    RATE_LIMIT: int = 3
    RATE_TIME: int = 60 #seconds

    @property
    def DB_URL(self) -> str:
        return f"postgresql+asyncpg://{self.DB_USER}:{self.DB_PASSWORD}@{self.DB_HOST}:{self.DB_PORT}/{self.DB_NAME}"
    
    @property
    def REDIS_URL(self):
        return f"redis://{self.REDIS_USER}:{self.REDIS_USER_PASSWORD}@{self.REDIS_HOST}:{self.REDIS_PORT}/0"

ssl_options = {"ssl_cert_reqs": ssl.CERT_NONE}
settings = Settings()
templates = Jinja2Templates(directory=os.path.join(settings.BASE_DIR, "web/templates"))
logger = logging.getLogger(__name__)
logger.setLevel(logging.INFO)
formatter = logging.Formatter(
    '{"time": "%(asctime)s", "level": "%(levelname)s", "name": "%(name)s", "message": "%(message)s"}'
)
file_handler = RotatingFileHandler(
    "logs/app.log", maxBytes=10_000_000, backupCount=5
)
file_handler.setFormatter(formatter)
logger.addHandler(file_handler)

templates.context_processors.append(lambda request: {"user": request.state.user.to_dict if request.state.user else None})