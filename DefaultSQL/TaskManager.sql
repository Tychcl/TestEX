-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: db:3306
-- Время создания: Дек 16 2025 г., 20:26
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
-- База данных: `TaskManager`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Statuses`
--

CREATE TABLE `Statuses` (
  `Id` tinyint UNSIGNED NOT NULL,
  `Name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Statuses`
--

INSERT INTO `Statuses` (`Id`, `Name`) VALUES
(1, 'Ожидает'),
(2, 'В процессе'),
(3, 'Выполнено');

-- --------------------------------------------------------

--
-- Структура таблицы `Tasks`
--

CREATE TABLE `Tasks` (
  `Id` int NOT NULL,
  `Title` text NOT NULL,
  `Description` text NOT NULL,
  `Status` tinyint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Statuses`
--
ALTER TABLE `Statuses`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `Tasks`
--
ALTER TABLE `Tasks`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Status` (`Status`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Statuses`
--
ALTER TABLE `Statuses`
  MODIFY `Id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `Tasks`
--
ALTER TABLE `Tasks`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Tasks`
--
ALTER TABLE `Tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`Status`) REFERENCES `Statuses` (`Id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
