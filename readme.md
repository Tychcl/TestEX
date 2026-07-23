### 1. Как запустить проект:
- Настройка переменных окружения
	файл `.env.example` переименовать в `.env`
    1. Получить токен для ИИ по этой [инструкции](https://docs.mistral.ai/getting-started/quickstarts/developer/first-api-request#step-1)
		Вставить токен в .env в параметр MI_TOKEN
	2. Получить для почты GMAIL пароль приложения по [ссылке](https://myaccount.google.com/u/3/apppasswords)
		пример: `dpmn dcrx qbhy rqzu`
		Вставить почту для которой был получен пароль в .env в параметр SMTP_MAIL
		Вставить сам пароль в .env в параметр SMTP_MAIL_PWD
	3. Главная почта
		в .env в параметр OWNER_MAIL вставить почту на которую будут присылаться контакты
- Инструкция по установке и запуску
	Первоначально на ПК должен быть докер
	В корневой папке проекта(где находится `docker-compose.yml` файл) через консоль прописать `docker compose up -d --build`
	После запуска для доступа к апи нужно перейти по http://localhost:8080/docs
### 2. Стек технологий:
- Backend: python, fastapi, fastapi\[all], sqlalchemy, python-dotenv, asyncpg, celery\==5.4.0, redis\==5.2.1, requests\==2.32.3
- AI: mistral ai api

### 3. Архитектура:
- Структура проекта
	Root_folder
    ├───Database
	│   └───Default `папка для sql файлов создания БД`
	├───redisdata
	│   └───appendonlydir
	└───Web `Папка проекта`
	    ├───app
	    │   └───api
	    │        ├───controllers `Папка с контроллерами`
	    │        ├───interfaces `Интерфейсы сервисов`
	    │        ├───models `Модели БД`
	    │        ├───repositories `Репозитории БД`
	    │        ├───schemas `Схемы для запросов и ответов`
	    │        └───services `Сервисы`
	    └───logs `Папка с логами`
- Паттерны проектирования
    многослойная архитектура
    DI - fastapi depends, внедрение зависимостей
- Объяснение выбора технологий
    fastapi - легкий фреймворк с изначально всеми необходимыми инструментами
    redis - брокер и кэш, 2 в 1
    celery - воркер для трудоемких и время затратных задач, так раз таки как отправка mail и ожидание ответа от ИИ
    sqlalchemy - ORM с которым я знаком
    docker - быстрый деплой и исключения случая, когда на разных ПК что то может работать не так

### 4. Реализация API:
- Описание эндпоинтов

| Метод | Эндпоинт          | Описание                                                    | Статус успеха |
| ----- | ----------------- | ----------------------------------------------------------- | ------------- |
| POST  | /api/contact/     | Новое обращение                                             | 200           |
| GET   | /api/contact/all  | Получить все обращения из бд                                | 200           |
| GET   | /api/contact/{id} | Получить конкретное обращение, <br>если не найдено, то None | 200           |
| GET   | /api/metric       | Получить кол-во обращений за период                         | 200           |
| GET   | /api/health       | Получить информацию о доступности к сервисам                | 200           |
- Примеры запросов/ответов

| Эндпоинт          | Curl                                                                                                                                                                                                                                                    |
| ----------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| /api/contact/     | curl -X 'POST' \ 'http://localhost:8080/api/contact/' \ -H 'accept: application/json' \ -H 'Content-Type: application/json' \ -d '{ "name": "ИМЯ", "phone": "+71234567890", "email": "user@example.com", "message": "Какое то сообщение в обращении" }' |
| /api/contact/all  | curl -X 'GET' \ 'http://localhost:8080/api/contact/all?skip=0&limit=100' \ -H 'accept: application/json'                                                                                                                                                |
| /api/contact/{id} | curl -X 'GET' \ 'http://localhost:8080/api/contact/1' \ -H 'accept: application/json'                                                                                                                                                                   |
| /api/metric       | curl -X 'GET' \ 'http://localhost:8080/api/metric?mode=month' \ -H 'accept: application/json'                                                                                                                                                           |
| /api/health       | curl -X 'GET' \ 'http://localhost:8080/api/health' \ -H 'accept: application/json'                                                                                                                                                                      |

- Валидация и обработка ошибок
    Валидация в схеме `ContactCreate` для создания обращения с помощью field_validator и кастомных regex формул
    Обработка ошибок реализована с помощью exception_handler
### 5. AI-интеграция:
- Какие AI-инструменты и для чего
    **Провайдер**: Mistral AI (модель `mistral-small-latest`)
    **Функция**: генерация вежливого ответа на сообщение пользователя
    **Бесплатный**
- Как реализован fallback
    Вызов метода получения ответа обернут в try/except
    При любых ошибках и статусе != 200 возвращается None
- Промпты, которые использовали
    Системный промпт для вежливых ответов
    ```You are a professional and friendly assistant replying to contact form messages.Your response must be concise, helpful, and personalized — thank the user, address their question, and offer further assistance if relevant.Reply in the same language as the user.Keep tone warm but business-like.Your entire reply MUST be under 255 characters(including spaces and punctuation).Ideally 1–2 short sentences.```
### 6. Что сделано с помощью AI:
- Какие части кода генерировались
    Системный промпт для запроса
- Какие промпты использовали
    
- Что пришлось исправлять вручную
    Чуть подправил ограничения для ответа нейросети

### 7. Хранение данных:
- Как реализовано хранение логов
    Логи пишутся в файл `logs/app.log` с ротацией: максимум 10 МБ на файл, хранится 5 архивов.
    Мидлвара для логирования запроса и ответа на него
- Как реализован rate limiting
    Мидлвара с редис
    Для ключа берется ip и путь запроса, преобразуются в ключ
    По ключу при каждом запросе увеличивается значение
    Если запрос с этим ключем первый раз, устанавливается время жизни кэша
    Если колличество запросов по ключу > максимума, то возвращается ответ с кодом 429
	Иначе вызывается следующий этап
- Где хранится статистика**
	Статистика собирается за определенный период при запросе