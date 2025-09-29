# Название проекта
Краткое описание проекта (1-2 предложения).

![Логотип](путь/к/изображению) <!-- Опционально -->

## 🚀 Возможности
- Основная функциональность 1
- Основная функциональность 2
- Интеграция с [API/сервисом]

## 📦 Установка
1. Добавить в web .env:
``` .env
DATABASE_HOST=db
DATABASE_PORT=3306
DATABASE_NAME=
DATABASE_USER=root
DATABASE_PASSWORD=
JWT=
```
2. Добавить в web .env:

# 🐳 Docker Шпаргалка для проекта (API + DB)

## 📋 Проект
- **web** - PHP API приложение (Apache + PHP 7.4)
- **db** - MySQL 8.0 база данных

---

## 🚀 Основные команды

### Запуск и остановка
```bash
# Запуск в фоне
docker-compose up -d

# Запуск с пересборкой
docker-compose up -d --build

# Остановка
docker-compose down

# Остановка с удалением данных БД
docker-compose down -v

# Перезапуск
docker-compose restart

# Принудительное пересоздание
docker-compose up -d --force-recreate
```

### Статус и логи
```bash
# Статус контейнеров
docker-compose ps

# Логи всех сервисов
docker-compose logs

# Логи в реальном времени
docker-compose logs -f

# Логи только API
docker-compose logs -f web

# Логи только БД
docker-compose logs -f db

# Последние 50 строк логов
docker-compose logs --tail=50
```

---

## 🔧 Работа с контейнерами

### Вход в контейнеры
```bash
# Войти в API контейнер
docker-compose exec web bash

# Войти в БД контейнер
docker-compose exec db bash

# Выполнить команду без входа
docker-compose exec web php -v
docker-compose exec web composer --version
```

### Копирование файлов
```bash
# Из контейнера на хост
docker-compose cp web:/var/www/html/logs/ ./logs/

# С хоста в контейнер
docker-compose cp ./config.php web:/var/www/html/
```

---

## 🗃️ Управление базой данных

### Резервное копирование
```bash
# Создать дамп БД
docker-compose exec db mysqldump -u root -proot_password teacherCompetence > backup_$(date +%Y%m%d_%H%M%S).sql

# Создать дамп только структуры
docker-compose exec db mysqldump -u root -proot_password --no-data teacherCompetence > structure_$(date +%Y%m%d).sql
```

### Восстановление БД
```bash
# Восстановить из дампа
docker-compose exec -T db mysql -u root -proot_password teacherCompetence < backup.sql

# Восстановить и перезаписать
docker-compose exec db mysql -u root -proot_password -e "DROP DATABASE teacherCompetence; CREATE DATABASE teacherCompetence;"
docker-compose exec -T db mysql -u root -proot_password teacherCompetence < backup.sql
```

### Работа с MySQL
```bash
# Подключиться к MySQL
docker-compose exec db mysql -u root -proot_password teacherCompetence

# Проверить состояние БД
docker-compose exec db mysqladmin -u root -proot_password ping

# Показать размер БД
docker-compose exec db mysql -u root -proot_password -e "SELECT table_schema 'Database', SUM(data_length + index_length) / 1024 / 1024 'Size (MB)' FROM information_schema.tables WHERE table_schema = 'teacherCompetence';"
```

---

## ⚙️ Команды для разработки

### PHP и Composer
```bash
# Обновить зависимости
docker-compose exec web composer update

# Установить новые пакеты
docker-compose exec web composer require package/name

# Оптимизировать автозагрузку
docker-compose exec web composer dump-autoload -o

# Проверить безопасность пакетов
docker-compose exec web composer audit
```

### Propel ORM
```bash
# Выполнить миграции
docker-compose exec web composer run-script schema-update

# Сгенерировать модели
docker-compose exec web composer run-script propel:build

# Показать статус миграций
docker-compose exec web composer run-script propel:status
```

### Тестирование API
```bash
# Проверить доступность API
curl http://localhost:8080/api/user/signin

# Тестовый запрос к API
curl -X POST http://localhost:8080/api/user/signin \
  -H "Content-Type: application/json" \
  -d '{"login":"test","password":"test"}'
```

---

## 🧹 Очистка и обслуживание

### Очистка контейнеров
```bash
# Удалить остановленные контейнеры
docker container prune

# Удалить все неиспользуемые образы
docker image prune -a

# Удалить неиспользуемые volumes
docker volume prune

# Полная очистка системы
docker system prune -a
```

### Мониторинг ресурсов
```bash
# Статистика использования ресурсов
docker stats

# Показать использование диска
docker system df

# Детальная информация об использовании
docker system df -v
```

---

## 🔍 Диагностика проблем

### Проверка здоровья
```bash
# Проверить здоровье контейнеров
docker-compose ps

# Проверить логины в БД
docker-compose exec db mysql -u root -proot_password -e "SHOW PROCESSLIST;"

# Проверить ошибки PHP
docker-compose exec web tail -f /var/log/apache2/error.log
```

### Сетевые проблемы
```bash
# Проверить сетевые подключения
docker network ls
docker network inspect teacher-competence_app_network

# Проверить проброс портов
docker port teacher_competence_web
docker port teacher_competence_db
```

### Проблемы с производительностью
```bash
# Показать самые тяжелые контейнеры
docker stats --no-stream | sort -k3 -h -r

# Проверить использование памяти
docker stats --format "table {{.Name}}\t{{.CPUPerc}}\t{{.MemUsage}}"
```

---

## 📊 Мониторинг в реальном времени

```bash
# Одновременный просмотр логов API и БД
docker-compose logs -f web db

# Мониторинг ресурсов + логи
docker stats & docker-compose logs -f

# Проверка доступности сервисов
watch -n 5 'curl -s http://localhost:8080/api/user/signin > /dev/null && echo "API: OK" || echo "API: FAIL"; docker-compose exec db mysqladmin -u root -proot_password ping > /dev/null && echo "DB: OK" || echo "DB: FAIL"'
```

---

## 🚨 Экстренные ситуации

### Если БД не запускается
```bash
# Проверить логи БД
docker-compose logs db

# Проверить права на папку данных
ls -la db/data/

# Пересоздать БД с чистого листа
docker-compose down -v
docker-compose up -d db
```

### Если API не отвечает
```bash
# Перезапустить только API
docker-compose restart web

# Проверить ошибки PHP
docker-compose exec web tail -f /var/log/apache2/error.log

# Проверить доступность изнутри контейнера
docker-compose exec web curl http://localhost/
```

---

## 💡 Советы

1. **Всегда используйте `docker-compose down` перед выключением** - это корректно остановит контейнеры
2. **Регулярно делайте бэкапы БД** - данные в volumes могут быть потеряны
3. **Используйте `.env` файл для паролей**
4. **Мониторьте логи в разработке** - `docker-compose logs -f`
5. **Периодически очищайте систему** - `docker system prune`