-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: db:3306
-- Время создания: Окт 13 2025 г., 22:36
-- Версия сервера: 8.0.43
-- Версия PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `teacherCompetence`
--

DELIMITER $$
--
-- Процедуры
--
CREATE DEFINER=`root`@`%` PROCEDURE `GetTeacherDossier` (IN `teacher_id` INT)   BEGIN
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
    
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `UpdateAllTeachersFlags` ()   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE teacher_id INT;
    DECLARE cur CURSOR FOR SELECT id FROM teacher;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur;
    
    read_loop: LOOP
        FETCH cur INTO teacher_id;
        IF done THEN
            LEAVE read_loop;
        END IF;
        CALL UpdateTeacherFlags(teacher_id);
    END LOOP;
    
    CLOSE cur;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `UpdateTeacherFlags` (IN `teacher_id` INT)   BEGIN
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
    WHERE teacherId = teacher_id
    GROUP BY categoryId;
    
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
    
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `teacherId` int NOT NULL COMMENT 'Преподаватель',
  `organ` text NOT NULL COMMENT 'Орган издавший приказ',
  `date` date NOT NULL COMMENT 'Дата',
  `num` int NOT NULL COMMENT 'Номер приказа',
  `post` text NOT NULL COMMENT 'Должность',
  `place` text NOT NULL COMMENT 'Образовательная организация',
  `categoryId` int NOT NULL COMMENT 'Присвоенная категория'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `teacherId`, `organ`, `date`, `num`, `post`, `place`, `categoryId`) VALUES
(16, 1, '123', '2025-12-12', 123, '123', '123', 1);

--
-- Триггеры `category`
--
DELIMITER $$
CREATE TRIGGER `after_category_delete` AFTER DELETE ON `category` FOR EACH ROW BEGIN
    CALL UpdateTeacherFlags(OLD.teacherId);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_category_insert` AFTER INSERT ON `category` FOR EACH ROW BEGIN
    CALL UpdateTeacherFlags(NEW.teacherId);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_category_update` AFTER UPDATE ON `category` FOR EACH ROW BEGIN
    CALL UpdateTeacherFlags(NEW.teacherId);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `categoryList`
--

CREATE TABLE `categoryList` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `name` varchar(65) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Наименование '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `categoryList`
--

INSERT INTO `categoryList` (`id`, `name`) VALUES
(1, 'Молодой специалист'),
(2, 'Первая квалификационная категория'),
(3, 'Высшая квалификационная категория'),
(4, 'Педагог-наставник'),
(5, 'Педагог-методист');

-- --------------------------------------------------------

--
-- Структура таблицы `event`
--

CREATE TABLE `event` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `infoId` int NOT NULL COMMENT 'Информация',
  `teacherId` int NOT NULL COMMENT 'Преподаватель',
  `teacherRoleId` int NOT NULL COMMENT 'Роль участия',
  `studentId` int NOT NULL COMMENT 'Студент',
  `awardId` int NOT NULL COMMENT 'Награда'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `event`
--

INSERT INTO `event` (`id`, `infoId`, `teacherId`, `teacherRoleId`, `studentId`, `awardId`) VALUES
(1, 3, 7, 1, 1, 1),
(2, 3, 7, 1, 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `eventAwardDegree`
--

CREATE TABLE `eventAwardDegree` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Наименование '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Степени награждения';

--
-- Дамп данных таблицы `eventAwardDegree`
--

INSERT INTO `eventAwardDegree` (`id`, `name`) VALUES
(1, 'Награда 1 степени'),
(2, 'Награда 2 степени'),
(3, 'Награда 3 степени'),
(4, 'Участник');

-- --------------------------------------------------------

--
-- Структура таблицы `eventInfo`
--

CREATE TABLE `eventInfo` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Наименование',
  `start` date NOT NULL COMMENT 'Дата начала ',
  `end` date DEFAULT NULL COMMENT 'Дата окончания',
  `level` int DEFAULT NULL COMMENT 'Уровень проведения',
  `zip` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Чемпионаты';

--
-- Дамп данных таблицы `eventInfo`
--

INSERT INTO `eventInfo` (`id`, `name`, `start`, `end`, `level`, `zip`) VALUES
(3, 'test', '2025-09-24', '2025-09-25', 4, '/var/www/html/files/events/test_2025-09-24_2025-09-25_Всероссийский.zip'),
(27, '123', '1212-12-12', '1212-12-12', 1, '/var/www/html/files/events/123_1212-12-12_1212-12-12_Образовательное учреждение.zip');

-- --------------------------------------------------------

--
-- Структура таблицы `eventLevel`
--

CREATE TABLE `eventLevel` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Наименование'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Уровни чемпионатов';

--
-- Дамп данных таблицы `eventLevel`
--

INSERT INTO `eventLevel` (`id`, `name`) VALUES
(1, 'Образовательное учреждение'),
(2, 'Региональный'),
(3, 'Межрегиональный'),
(4, 'Всероссийский');

-- --------------------------------------------------------

--
-- Структура таблицы `eventRole`
--

CREATE TABLE `eventRole` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `name` varchar(45) NOT NULL COMMENT 'Наименование'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Роль участия';

--
-- Дамп данных таблицы `eventRole`
--

INSERT INTO `eventRole` (`id`, `name`) VALUES
(1, 'Диплом 1 степени'),
(2, 'Диплом 2 степени'),
(3, 'Диплом 3 степени'),
(4, 'Участник');

-- --------------------------------------------------------

--
-- Структура таблицы `propel_migration`
--

CREATE TABLE `propel_migration` (
  `version` int DEFAULT '0',
  `execution_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `skill`
--

CREATE TABLE `skill` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `teacherId` int NOT NULL COMMENT 'Преподаватель',
  `num` int NOT NULL COMMENT 'Номер удостоверения',
  `regNum` int NOT NULL COMMENT 'Регистрационный номер',
  `city` varchar(65) NOT NULL COMMENT 'Город',
  `date` date NOT NULL COMMENT 'Дата выдачи',
  `place` text NOT NULL COMMENT 'Место прохождения',
  `start` date NOT NULL COMMENT 'Дата начала',
  `end` date NOT NULL COMMENT 'Дата окончания',
  `theme` text NOT NULL COMMENT 'Тема',
  `hours` int NOT NULL COMMENT 'Объем в часах',
  `director` varchar(65) NOT NULL COMMENT 'Руководитель',
  `secretary` varchar(65) NOT NULL COMMENT 'Секретарь'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Повышение квалификации действует 3 года';

--
-- Триггеры `skill`
--
DELIMITER $$
CREATE TRIGGER `after_skill_delete` AFTER DELETE ON `skill` FOR EACH ROW BEGIN
    CALL UpdateTeacherFlags(OLD.teacherId);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_skill_insert` AFTER INSERT ON `skill` FOR EACH ROW BEGIN
    CALL UpdateTeacherFlags(NEW.teacherId);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_skill_update` AFTER UPDATE ON `skill` FOR EACH ROW BEGIN
    CALL UpdateTeacherFlags(NEW.teacherId);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `student`
--

CREATE TABLE `student` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `fio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ФИО'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Студенты';

--
-- Дамп данных таблицы `student`
--

INSERT INTO `student` (`id`, `fio`) VALUES
(1, 'Фамилия Имя Отчество'),
(2, 'Фамилияяяя Имяяяя Отчествоооо');

-- --------------------------------------------------------

--
-- Структура таблицы `teacher`
--

CREATE TABLE `teacher` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `fio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'ФИО',
  `login` text NOT NULL COMMENT 'Логин',
  `password` text NOT NULL COMMENT 'Пароль',
  `roleId` int NOT NULL COMMENT 'роль',
  `needUpdSkill` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Необходимость обновить данные об квалификации',
  `canUpdCategory` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Возможность обновить категорию'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Преподаватели';

--
-- Дамп данных таблицы `teacher`
--

INSERT INTO `teacher` (`id`, `fio`, `login`, `password`, `roleId`, `needUpdSkill`, `canUpdCategory`) VALUES
(1, 'Очень Важная Тучка', 'tychcl', '$2y$10$iOUx/MEm8g1ztoczR/Z1K.bYEBvuoCRtd954NcJAZXACYj5Xqum/i', 1, 1, 0),
(7, 'Фамилия Имя Отчество', 'test', '$2y$10$CLvK0fEjy4G7z1.N8i5Jxee/b8B5DRFTdhEulQ6qTjNI6gijuMQ2S', 2, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `userRole`
--

CREATE TABLE `userRole` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Роль'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `userRole`
--

INSERT INTO `userRole` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'teacher');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher` (`teacherId`),
  ADD KEY `category` (`categoryId`);

--
-- Индексы таблицы `categoryList`
--
ALTER TABLE `categoryList`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_ibfk_1` (`infoId`),
  ADD KEY `teacher` (`teacherId`),
  ADD KEY `teacherRole` (`teacherRoleId`),
  ADD KEY `student` (`studentId`),
  ADD KEY `award` (`awardId`);

--
-- Индексы таблицы `eventAwardDegree`
--
ALTER TABLE `eventAwardDegree`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `eventInfo`
--
ALTER TABLE `eventInfo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level` (`level`);

--
-- Индексы таблицы `eventLevel`
--
ALTER TABLE `eventLevel`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `eventRole`
--
ALTER TABLE `eventRole`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher` (`teacherId`);

--
-- Индексы таблицы `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role` (`roleId`);

--
-- Индексы таблицы `userRole`
--
ALTER TABLE `userRole`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `categoryList`
--
ALTER TABLE `categoryList`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `event`
--
ALTER TABLE `event`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `eventAwardDegree`
--
ALTER TABLE `eventAwardDegree`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `eventInfo`
--
ALTER TABLE `eventInfo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `eventLevel`
--
ALTER TABLE `eventLevel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `eventRole`
--
ALTER TABLE `eventRole`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `skill`
--
ALTER TABLE `skill`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор';

--
-- AUTO_INCREMENT для таблицы `student`
--
ALTER TABLE `student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `userRole`
--
ALTER TABLE `userRole`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `category_ibfk_2` FOREIGN KEY (`categoryId`) REFERENCES `categoryList` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`infoId`) REFERENCES `eventInfo` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `event_ibfk_3` FOREIGN KEY (`teacherRoleId`) REFERENCES `eventRole` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `event_ibfk_4` FOREIGN KEY (`studentId`) REFERENCES `student` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `event_ibfk_5` FOREIGN KEY (`awardId`) REFERENCES `eventAwardDegree` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `eventInfo`
--
ALTER TABLE `eventInfo`
  ADD CONSTRAINT `eventinfo_ibfk_1` FOREIGN KEY (`level`) REFERENCES `eventLevel` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `skill`
--
ALTER TABLE `skill`
  ADD CONSTRAINT `skill_ibfk_1` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`roleId`) REFERENCES `userRole` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
