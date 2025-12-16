# Запуск
- В папке с файлом docker-compose.yml запустить команду **docker-compose up -d**
- После запуска всех контейнеров запустить установку библиотек командой **docker exec -d TaskManagerApi composer install** для избежания ошибки с ssl, также автоматически обновится схема и создадутся классы бля ОРМ
- Можно тестировать после создания классов

[https://.postman.co/workspace/My-Workspace~1d554eaf-c6c7-4778-8c62-452640c56c0f/collection/38607761-35717427-1a48-407f-b4f0-4afc1c59e5bf?action=share&creator=38607761](Postman)