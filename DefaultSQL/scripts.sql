DELIMITER //

CREATE PROCEDURE UpdateTeacherFlags(IN teacher_id INT)
BEGIN
    DECLARE last_skill_date DATE;
    DECLARE last_category_date DATE;
    DECLARE current_category_id INT;
    
    -- Получаем дату последнего повышения квалификации
    SELECT MAX(end) INTO last_skill_date 
    FROM skill 
    WHERE teacherId = teacher_id;
    
    -- Получаем дату и id последней категории
    SELECT MAX(date), categoryId INTO last_category_date, current_category_id
    FROM category 
    WHERE teacherId = teacher_id;
    
    -- Обновляем флаги
    UPDATE teacher SET
        needUpdSkill = CASE 
            WHEN last_skill_date IS NULL OR DATE_ADD(last_skill_date, INTERVAL 3 YEAR) < CURDATE() 
            THEN 1 ELSE 0 
        END,
        canUpdCategory = CASE
            WHEN current_category_id = 3 THEN 0 -- Высшая категория - нельзя повысить
            WHEN last_category_date IS NULL OR DATEDIFF(CURDATE(), last_category_date) > 365 
            THEN 1 ELSE 0 -- Можно повысить если прошло больше года
        END
    WHERE id = teacher_id;
    
END //

DELIMITER ;

-- Триггер для таблицы skill (повышение квалификации)
DELIMITER //

CREATE TRIGGER after_skill_change 
AFTER INSERT OR UPDATE OR DELETE ON skill
FOR EACH ROW
BEGIN
    DECLARE affected_teacher INT;
    
    IF INSERTING THEN
        SET affected_teacher = NEW.teacherId;
    ELSEIF UPDATING THEN
        SET affected_teacher = NEW.teacherId;
    ELSEIF DELETING THEN
        SET affected_teacher = OLD.teacherId;
    END IF;
    
    CALL UpdateTeacherFlags(affected_teacher);
END //

DELIMITER ;

-- Триггер для таблицы category (категории)
DELIMITER //

CREATE TRIGGER after_category_change 
AFTER INSERT OR UPDATE OR DELETE ON category
FOR EACH ROW
BEGIN
    DECLARE affected_teacher INT;
    
    IF INSERTING THEN
        SET affected_teacher = NEW.teacherId;
    ELSEIF UPDATING THEN
        SET affected_teacher = NEW.teacherId;
    ELSEIF DELETING THEN
        SET affected_teacher = OLD.teacherId;
    END IF;
    
    CALL UpdateTeacherFlags(affected_teacher);
END //

DELIMITER ;

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

-- Процедура формирования полного досье преподавателя
DELIMITER //

CREATE PROCEDURE GetTeacherDossier(IN teacher_id INT)
BEGIN
    -- Основная информация о преподавателе
    SELECT 
        t.id,
        t.fio,
        t.login,
        ur.name as role_name,
        t.needUpdSkill,
        t.canUpdCategory
    FROM teacher t
    LEFT JOIN userRole ur ON t.roleId = ur.id
    WHERE t.id = teacher_id;
    
    -- Текущая категория
    SELECT 
        cl.name as category_name,
        c.date as assignment_date,
        c.organ as assigned_by,
        c.num as order_number
    FROM category c
    LEFT JOIN categoryList cl ON c.categoryId = cl.id
    WHERE c.teacherId = teacher_id
    ORDER BY c.date DESC
    LIMIT 1;
    
    -- История категорий
    SELECT 
        cl.name as category_name,
        c.date as assignment_date,
        c.organ as assigned_by,
        c.num as order_number,
        c.place as educational_institution
    FROM category c
    LEFT JOIN categoryList cl ON c.categoryId = cl.id
    WHERE c.teacherId = teacher_id
    ORDER BY c.date DESC;
    
    -- Повышение квалификации (последние 3 года)
    SELECT 
        s.theme,
        s.place,
        s.start,
        s.end,
        s.hours,
        s.director,
        DATEDIFF(CURDATE(), s.end) as days_since_completion,
        CASE 
            WHEN DATE_ADD(s.end, INTERVAL 3 YEAR) < CURDATE() THEN 'Просрочена'
            WHEN DATE_ADD(s.end, INTERVAL 3 YEAR) < DATE_ADD(CURDATE(), INTERVAL 6 MONTH) THEN 'Скоро истекает'
            ELSE 'Действительна'
        END as status
    FROM skill s
    WHERE s.teacherId = teacher_id
    ORDER BY s.end DESC;
    
    -- Участие в мероприятиях
    SELECT 
        ei.name as event_name,
        el.name as event_level,
        ei.start as event_start,
        ei.end as event_end,
        er.name as teacher_role,
        s.fio as student_name,
        ead.name as student_award
    FROM event e
    LEFT JOIN eventInfo ei ON e.infoId = ei.id
    LEFT JOIN eventLevel el ON ei.level = el.id
    LEFT JOIN eventRole er ON e.teacherRoleId = er.id
    LEFT JOIN student s ON e.studentId = s.id
    LEFT JOIN eventAwardDegree ead ON e.awardId = ead.id
    WHERE e.teacherId = teacher_id
    AND ei.start >= DATE_SUB(CURDATE(), INTERVAL 2 YEAR)
    ORDER BY ei.start DESC;
    
    -- Статистика по мероприятиям
    SELECT 
        COUNT(e.id) as total_events,
        COUNT(DISTINCT ei.level) as different_levels,
        COUNT(CASE WHEN er.id IN (1,2,3) THEN 1 END) as award_events, -- Дипломы 1-3 степени
        COUNT(DISTINCT e.studentId) as unique_students
    FROM event e
    LEFT JOIN eventInfo ei ON e.infoId = ei.id
    LEFT JOIN eventRole er ON e.teacherRoleId = er.id
    WHERE e.teacherId = teacher_id
    AND ei.start >= DATE_SUB(CURDATE(), INTERVAL 3 YEAR);
    
END //

DELIMITER ;