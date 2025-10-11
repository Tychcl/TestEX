-- Создаем таблицу для логов
CREATE TABLE audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(50) NOT NULL,
    operation ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    record_id INT NOT NULL,
    old_values JSON,
    new_values JSON,
    changed_by VARCHAR(100),
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Универсальный триггер для логирования (пример для таблицы teacher)
DELIMITER //

CREATE TRIGGER audit_teacher_changes
AFTER INSERT OR UPDATE OR DELETE ON teacher
FOR EACH ROW
BEGIN
    DECLARE user_name VARCHAR(100);
    
    -- Получаем текущего пользователя (в реальной системе из переменной сессии)
    SELECT USER() INTO user_name;
    
    IF INSERTING THEN
        INSERT INTO audit_log (table_name, operation, record_id, new_values, changed_by)
        VALUES ('teacher', 'INSERT', NEW.id, 
                JSON_OBJECT('fio', NEW.fio, 'login', NEW.login, 'roleId', NEW.roleId),
                user_name);
    ELSEIF UPDATING THEN
        INSERT INTO audit_log (table_name, operation, record_id, old_values, new_values, changed_by)
        VALUES ('teacher', 'UPDATE', NEW.id,
                JSON_OBJECT('fio', OLD.fio, 'login', OLD.login, 'roleId', OLD.roleId),
                JSON_OBJECT('fio', NEW.fio, 'login', NEW.login, 'roleId', NEW.roleId),
                user_name);
    ELSEIF DELETING THEN
        INSERT INTO audit_log (table_name, operation, record_id, old_values, changed_by)
        VALUES ('teacher', 'DELETE', OLD.id,
                JSON_OBJECT('fio', OLD.fio, 'login', OLD.login, 'roleId', OLD.roleId),
                user_name);
    END IF;
END //

DELIMITER ;