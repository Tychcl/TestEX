-- Пользователь для CRUF
CREATE USER 'user'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, UPDATE, FILE ON teacherCompetence.* TO 'data_operator'@'localhost';
FLUSH PRIVILEGES;

-- Пользователь для CRUDF
CREATE USER 'admin'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, UPDATE, FILE, DELETE ON teacherCompetence.* TO 'data_operator'@'localhost';
FLUSH PRIVILEGES;

-- полный доступ
CREATE USER 'database_admin'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, 
    CREATE TEMPORARY TABLES, LOCK TABLES, EXECUTE, CREATE VIEW, 
    SHOW VIEW, CREATE ROUTINE, ALTER ROUTINE, EVENT, TRIGGER, FILE 
ON teacherCompetence.* TO 'crud_user'@'localhost';
FLUSH PRIVILEGES;

-- Создание пользователя для бэкапов
CREATE USER 'backup'@'localhost' IDENTIFIED BY 'secure';
GRANT SELECT, RELOAD, LOCK TABLES, REPLICATION CLIENT, PROCESS, 
    SHOW VIEW, EVENT, TRIGGER 
ON teacherCompetence.* TO 'backup'@'localhost';
FLUSH PRIVILEGES;
