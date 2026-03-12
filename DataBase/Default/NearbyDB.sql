-- Создание базы данных
CREATE DATABASE IF NOT EXISTS NearbyDB
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE NearbyDB;

-- =====================================================
-- Таблица пользователей (физические лица)
-- =====================================================
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL COMMENT 'ФИО',
    city VARCHAR(100) COMMENT 'Город',
    birth_year YEAR NULL COMMENT 'Год рождения',
    about TEXT NULL COMMENT 'Краткая информация о себе',
    profile_picture VARCHAR(500) NULL COMMENT 'Ссылка на фото профиля',
    email VARCHAR(255) UNIQUE COMMENT 'Email для входа',
    phone VARCHAR(20) COMMENT 'Телефон',
    password_hash VARCHAR(255) COMMENT 'Хеш пароля',
    vk_id VARCHAR(100) COMMENT 'ID ВКонтакте',
    tg_id VARCHAR(100) COMMENT 'ID Telegram',
    balance DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Текущий баланс нирбиков',
    availability_status ENUM('available', 'busy', 'offline') DEFAULT 'available' COMMENT 'Статус доступности',
    is_admin BOOLEAN DEFAULT FALSE,
    is_moderator BOOLEAN DEFAULT FALSE,
    last_seen_at TIMESTAMP NULL COMMENT 'Последнее посещение',
    is_online BOOLEAN DEFAULT FALSE COMMENT 'Флаг онлайн',
    average_rating DECIMAL(3,2) DEFAULT 0.00 COMMENT 'Средний рейтинг (на основе оценок)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_vk (vk_id),
    INDEX idx_tg (tg_id)
) ENGINE=InnoDB COMMENT 'Физические лица (пользователи)';

-- =====================================================
-- Таблица организаций (юридические лица)
-- =====================================================
CREATE TABLE organizations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COMMENT 'Название организации',
    inn VARCHAR(20) NULL COMMENT 'ИНН (может отсутствовать)',
    contact_person VARCHAR(255) COMMENT 'Контактное лицо (ФИО)',
    contact_phone VARCHAR(20) COMMENT 'Контактный телефон',
    contact_email VARCHAR(255) COMMENT 'Контактный email',
    description TEXT COMMENT 'Описание деятельности',
    logo VARCHAR(500) NULL COMMENT 'Логотип',
    verified BOOLEAN DEFAULT FALSE COMMENT 'Подтверждена модератором',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB COMMENT 'Юридические лица (организации)';

-- =====================================================
-- Членство пользователей в организациях
-- =====================================================
CREATE TABLE organization_members (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL COMMENT 'Пользователь',
    organization_id INT UNSIGNED NOT NULL COMMENT 'Организация',
    role ENUM('owner', 'manager', 'member') NOT NULL DEFAULT 'member' COMMENT 'Роль внутри организации',
    status ENUM('pending', 'active') DEFAULT 'pending' COMMENT 'Статус членства (ожидает подтверждения / активен)',
    added_by INT UNSIGNED COMMENT 'Кто добавил/пригласил (ссылка на users)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
    FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_member (user_id, organization_id)
) ENGINE=InnoDB COMMENT 'Связь пользователей с организациями и роли в них';

-- =====================================================
-- Образование пользователя
-- =====================================================
CREATE TABLE user_education (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    institution VARCHAR(255) NOT NULL COMMENT 'Учебное заведение',
    degree VARCHAR(255) COMMENT 'Уровень образования',
    field_of_study VARCHAR(255) COMMENT 'Специальность / направление',
    start_year YEAR NOT NULL,
    end_year YEAR NULL COMMENT 'Год окончания (если null — учится до сих пор)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id)
) ENGINE=InnoDB COMMENT 'Образование пользователя';

-- =====================================================
-- Простые задачи
-- =====================================================
CREATE TABLE tasks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    needed_volunteers INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Сколько волонтеров нужно',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    location VARCHAR(255) COMMENT 'Место выполнения',
    reward DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Награда в нирбиках',
    status ENUM('searching', 'in_progress', 'completed', 'cancelled') DEFAULT 'searching',
    created_by_user_id INT UNSIGNED NULL COMMENT 'Создатель – пользователь (если задача от физлица)',
    created_by_organization_id INT UNSIGNED NULL COMMENT 'Создатель – организация (если задача от юрлица)',
    deadline DATETIME NULL COMMENT 'Срок выполнения',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by_organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_creator_user (created_by_user_id),
    INDEX idx_creator_org (created_by_organization_id),
    CHECK (created_by_user_id IS NOT NULL OR created_by_organization_id IS NOT NULL)
) ENGINE=InnoDB COMMENT 'Простые задачи';

-- =====================================================
-- Отклики/назначения на простые задачи (только пользователи)
-- =====================================================
CREATE TABLE task_volunteers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    task_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    status ENUM('pending', 'accepted', 'rejected', 'cancelled') DEFAULT 'pending' COMMENT 'Статус участия',
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_task_volunteer (task_id, user_id)
) ENGINE=InnoDB COMMENT 'Волонтеры, выполняющие простые задачи';

-- =====================================================
-- Проекты
-- =====================================================
CREATE TABLE projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('A', 'B') NOT NULL COMMENT 'A - с ролями по дням, B - без разделения по дням',
    created_by_user_id INT UNSIGNED NULL COMMENT 'Создатель – пользователь',
    created_by_organization_id INT UNSIGNED NULL COMMENT 'Создатель – организация',
    status ENUM('searching', 'active', 'completed', 'cancelled') DEFAULT 'searching',
    start_date DATE COMMENT 'Дата начала проекта',
    end_date DATE COMMENT 'Дата окончания проекта',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by_organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_creator_user (created_by_user_id),
    INDEX idx_creator_org (created_by_organization_id),
    CHECK (created_by_user_id IS NOT NULL OR created_by_organization_id IS NOT NULL)
) ENGINE=InnoDB COMMENT 'Проекты (большие задачи)';

-- =====================================================
-- Роли в проектах
-- =====================================================
CREATE TABLE project_roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    total_needed INT UNSIGNED COMMENT 'Для типа B: общее количество людей на эту роль',
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    INDEX idx_project (project_id)
) ENGINE=InnoDB COMMENT 'Роли, доступные в проекте';

-- =====================================================
-- Потребность в ролях по дням (только для типа A)
-- =====================================================
CREATE TABLE project_role_daily_needs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id INT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    needed_count INT UNSIGNED NOT NULL COMMENT 'Сколько человек нужно на эту дату',
    FOREIGN KEY (role_id) REFERENCES project_roles(id) ON DELETE CASCADE,
    UNIQUE KEY unique_role_date (role_id, date)
) ENGINE=InnoDB COMMENT 'Ежедневная потребность в ролях (для типа A)';

-- =====================================================
-- Участники проекта (назначенные на роли) – только пользователи
-- =====================================================
CREATE TABLE project_participants (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    project_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    status ENUM('invited', 'active', 'rejected', 'left') DEFAULT 'invited',
    invited_by INT UNSIGNED COMMENT 'Кто пригласил (админ/овнер)',
    joined_at TIMESTAMP NULL COMMENT 'Когда принял приглашение или был назначен',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES project_roles(id) ON DELETE CASCADE,
    FOREIGN KEY (invited_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_project_user_role (user_id, project_id, role_id)
) ENGINE=InnoDB COMMENT 'Участники проектов и их роли';

-- =====================================================
-- Дни, на которые назначен участник (для типа A)
-- =====================================================
CREATE TABLE participant_days (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    participant_id INT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (participant_id) REFERENCES project_participants(id) ON DELETE CASCADE,
    UNIQUE KEY unique_participant_day (participant_id, date)
) ENGINE=InnoDB COMMENT 'Даты участия (для типа A)';

-- =====================================================
-- Мелкие задачи внутри проекта
-- =====================================================
CREATE TABLE project_tasks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    date DATE COMMENT 'Если привязана к конкретному дню',
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    INDEX idx_project (project_id)
) ENGINE=InnoDB COMMENT 'Мелкие задачи внутри проекта';

-- =====================================================
-- Исполнители мелких задач проекта (только пользователи)
-- =====================================================
CREATE TABLE project_task_volunteers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_task_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    status ENUM('assigned', 'completed') DEFAULT 'assigned',
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_task_id) REFERENCES project_tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_task_volunteer (project_task_id, user_id)
) ENGINE=InnoDB COMMENT 'Волонтеры, выполняющие мелкие задачи проекта';

-- =====================================================
-- Оценки пользователей друг другом (только пользователи)
-- =====================================================
CREATE TABLE ratings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rater_id INT UNSIGNED NOT NULL COMMENT 'Кто оценивает (пользователь)',
    rated_user_id INT UNSIGNED NOT NULL COMMENT 'Кого оценивают (пользователь)',
    rating TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    task_id INT UNSIGNED NULL COMMENT 'Ссылка на простую задачу (если оценка за неё)',
    project_id INT UNSIGNED NULL COMMENT 'Ссылка на проект (если оценка за участие в проекте)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rater_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (rated_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE SET NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL,
    INDEX idx_rater (rater_id),
    INDEX idx_rated (rated_user_id),
    UNIQUE KEY unique_task_rating (rater_id, rated_user_id, task_id),
    UNIQUE KEY unique_project_rating (rater_id, rated_user_id, project_id)
) ENGINE=InnoDB COMMENT 'Оценки (рейтинг)';

-- =====================================================
-- Чаты (личные и групповые)
-- =====================================================
CREATE TABLE chats (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type ENUM('personal', 'group') NOT NULL,
    name VARCHAR(255) NULL COMMENT 'Название группового чата',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB COMMENT 'Чаты';

-- =====================================================
-- Участники чатов (только пользователи)
-- =====================================================
CREATE TABLE chat_members (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    chat_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_read_message_id INT UNSIGNED NULL COMMENT 'Последнее прочитанное сообщение',
    FOREIGN KEY (chat_id) REFERENCES chats(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_chat_user (chat_id, user_id)
) ENGINE=InnoDB COMMENT 'Участники чатов';

-- =====================================================
-- Сообщения
-- =====================================================
CREATE TABLE messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    chat_id INT UNSIGNED NOT NULL,
    sender_id INT UNSIGNED NOT NULL COMMENT 'Отправитель (пользователь)',
    content_type ENUM('text', 'image', 'file', 'voice') DEFAULT 'text',
    content TEXT COMMENT 'Текст сообщения или ссылка на файл',
    file_url VARCHAR(500) NULL COMMENT 'URL файла (если есть)',
    transcribed_text TEXT NULL COMMENT 'Распознанный текст голосового сообщения',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chat_id) REFERENCES chats(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_chat (chat_id),
    INDEX idx_sender (sender_id)
) ENGINE=InnoDB COMMENT 'Сообщения';

-- =====================================================
-- Уведомления (включая приглашения)
-- =====================================================
CREATE TABLE notifications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    type ENUM(
        'project_invite',
        'task_invite',
        'task_status_change',
        'project_status_change',
        'new_message',
        'rating_received',
        'organization_verified'
    ) NOT NULL,
    content TEXT NOT NULL,
    reference_type ENUM('task', 'project', 'chat', 'user') NULL,
    reference_id INT UNSIGNED NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_read (is_read)
) ENGINE=InnoDB COMMENT 'Уведомления пользователей';

-- =====================================================
-- Транзакции баланса (история начислений/списаний) – только для пользователей
-- =====================================================
CREATE TABLE balance_transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    amount DECIMAL(10,2) NOT NULL COMMENT 'Изменение баланса (может быть отрицательным)',
    balance_after DECIMAL(10,2) NOT NULL COMMENT 'Баланс после операции',
    type ENUM('task_reward', 'task_cancellation', 'project_reward', 'withdrawal', 'refill') NOT NULL,
    reference_type ENUM('task', 'project') NULL,
    reference_id INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB COMMENT 'История изменений баланса';

-- Добавим связь для last_read_message_id в chat_members (после создания messages)
ALTER TABLE chat_members
    ADD CONSTRAINT fk_last_read_message
    FOREIGN KEY (last_read_message_id) REFERENCES messages(id) ON DELETE SET NULL;

-- =====================================================
-- Триггеры для автоматического пересчёта среднего рейтинга
-- =====================================================

DELIMITER //

-- AFTER INSERT
CREATE TRIGGER trg_ratings_after_insert
AFTER INSERT ON ratings
FOR EACH ROW
BEGIN
    UPDATE users
    SET average_rating = (
        SELECT COALESCE(AVG(rating), 0)
        FROM ratings
        WHERE rated_user_id = NEW.rated_user_id
    )
    WHERE id = NEW.rated_user_id;
END //

-- AFTER UPDATE
CREATE TRIGGER trg_ratings_after_update
AFTER UPDATE ON ratings
FOR EACH ROW
BEGIN
    IF OLD.rated_user_id != NEW.rated_user_id THEN
        -- Для старого пользователя
        UPDATE users
        SET average_rating = (
            SELECT COALESCE(AVG(rating), 0)
            FROM ratings
            WHERE rated_user_id = OLD.rated_user_id
        )
        WHERE id = OLD.rated_user_id;

        -- Для нового пользователя
        UPDATE users
        SET average_rating = (
            SELECT COALESCE(AVG(rating), 0)
            FROM ratings
            WHERE rated_user_id = NEW.rated_user_id
        )
        WHERE id = NEW.rated_user_id;
    ELSE
        UPDATE users
        SET average_rating = (
            SELECT COALESCE(AVG(rating), 0)
            FROM ratings
            WHERE rated_user_id = NEW.rated_user_id
        )
        WHERE id = NEW.rated_user_id;
    END IF;
END //

-- AFTER DELETE
CREATE TRIGGER trg_ratings_after_delete
AFTER DELETE ON ratings
FOR EACH ROW
BEGIN
    UPDATE users
    SET average_rating = (
        SELECT COALESCE(AVG(rating), 0)
        FROM ratings
        WHERE rated_user_id = OLD.rated_user_id
    )
    WHERE id = OLD.rated_user_id;
END //

DELIMITER ;