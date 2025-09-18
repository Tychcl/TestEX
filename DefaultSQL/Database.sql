-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 11 2025 г., 08:46
-- Версия сервера: 8.0.30
-- Версия PHP: 7.4.30

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
  `awardId` int NOT NULL COMMENT 'Награда',
  `document` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Документ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `end` date NOT NULL COMMENT 'Дата окончания',
  `level` int DEFAULT NULL COMMENT 'Уровень проведения'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Чемпионаты';

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
  `secretary` varchar(65) NOT NULL COMMENT 'Секретарь',
  `docPath` text NOT NULL COMMENT 'Путь до документа'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Повышение квалификации действует 3 года';

-- --------------------------------------------------------

--
-- Структура таблицы `student`
--

CREATE TABLE `student` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `surname` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Фамилия',
  `name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Имя',
  `midname` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Отчество'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Студенты';

-- --------------------------------------------------------

--
-- Структура таблицы `teacher`
--

CREATE TABLE `teacher` (
  `id` int NOT NULL COMMENT 'Идентификатор',
  `surname` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Фамилия',
  `name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Имя',
  `midname` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Отчество',
  `login` text NOT NULL COMMENT 'Логин',
  `password` text NOT NULL COMMENT 'Пароль',
  `roleId` int NOT NULL COMMENT 'роль',
  `needUpdSkill` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Необходимость обновить данные об квалификации',
  `canUpdCategory` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Возможность обновить категорию'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Преподаватели';

--
-- Дамп данных таблицы `teacher`
--

INSERT INTO `teacher` (`id`, `surname`, `name`, `midname`, `login`, `password`, `roleId`, `needUpdSkill`, `canUpdCategory`) VALUES
(1, 'Ощепков', 'Александр', 'Олегович', 'Aoshepkov', 'admin', 2, 1, 1);

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
(1, 'teacher'),
(2, 'admin');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор';

--
-- AUTO_INCREMENT для таблицы `categoryList`
--
ALTER TABLE `categoryList`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `event`
--
ALTER TABLE `event`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор';

--
-- AUTO_INCREMENT для таблицы `eventAwardDegree`
--
ALTER TABLE `eventAwardDegree`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `eventInfo`
--
ALTER TABLE `eventInfo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор';

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор';

--
-- AUTO_INCREMENT для таблицы `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `userRole`
--
ALTER TABLE `userRole`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор', AUTO_INCREMENT=3;

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
