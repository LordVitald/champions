-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 30 2019 г., 03:12
-- Версия сервера: 5.5.48
-- Версия PHP: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `champions`
--

-- --------------------------------------------------------

--
-- Структура таблицы `football_team`
--

CREATE TABLE IF NOT EXISTS `football_team` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `football_team`
--

INSERT INTO `football_team` (`id`, `name`) VALUES
(3, 'Комманда 1'),
(4, 'Комманда 2'),
(5, 'Комманда 3'),
(6, 'Комманда 4'),
(7, 'Комманда 5'),
(8, 'Комманда 6'),
(9, 'Комманда 7'),
(10, 'Комманда 8'),
(11, 'Комманда 9'),
(12, 'Комманда 10'),
(13, 'Комманда 11'),
(14, 'Комманда 12'),
(15, 'Комманда 13'),
(16, 'Комманда 14'),
(17, 'Комманда 15'),
(18, 'Комманда 16');

-- --------------------------------------------------------

--
-- Структура таблицы `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `place` text,
  `matches` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `average` double NOT NULL,
  `best` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `report`
--

INSERT INTO `report` (`id`, `team_id`, `tournament_id`, `place`, `matches`, `points`, `average`, `best`) VALUES
(1, 3, 11, NULL, 2, 5, 2.5, 3),
(2, 4, 11, '1/8', 1, 1, 1, 1),
(3, 6, 11, '1/4', 2, 3, 1.5, 2),
(4, 5, 11, '1/8', 1, 0, 0, 0),
(5, 7, 11, NULL, 2, 5, 2.5, 3),
(6, 8, 11, '1/8', 1, 1, 1, 1),
(7, 10, 11, '1/4', 2, 3, 1.5, 2),
(8, 9, 11, '1/8', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `stage`
--

CREATE TABLE IF NOT EXISTS `stage` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `f_team` int(11) DEFAULT NULL,
  `s_team` int(11) DEFAULT NULL,
  `f_score` int(11) DEFAULT NULL,
  `s_score` int(11) DEFAULT NULL,
  `type` varchar(128) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `stage`
--

INSERT INTO `stage` (`id`, `tournament_id`, `parent_id`, `f_team`, `s_team`, `f_score`, `s_score`, `type`) VALUES
(1, 11, NULL, NULL, NULL, NULL, NULL, 'final'),
(2, 11, 1, 3, 7, NULL, NULL, 'semifinal'),
(3, 11, 2, 3, 6, 3, 1, 'quarter_final'),
(4, 11, 3, 3, 4, 2, 1, 'one_eighth_final'),
(5, 11, 3, 5, 6, 0, 2, 'one_eighth_final'),
(6, 11, 2, 7, 10, 3, 1, 'quarter_final'),
(7, 11, 6, 7, 8, 2, 1, 'one_eighth_final'),
(8, 11, 6, 9, 10, 0, 2, 'one_eighth_final'),
(9, 11, 1, NULL, NULL, NULL, NULL, 'semifinal'),
(10, 11, 9, NULL, NULL, NULL, NULL, 'quarter_final'),
(11, 11, 10, 11, 12, NULL, NULL, 'one_eighth_final'),
(12, 11, 10, 13, 14, NULL, NULL, 'one_eighth_final'),
(13, 11, 9, NULL, NULL, NULL, NULL, 'quarter_final'),
(14, 11, 13, 15, 16, NULL, NULL, 'one_eighth_final'),
(15, 11, 13, 17, 18, NULL, NULL, 'one_eighth_final'),
(16, 11, NULL, NULL, NULL, NULL, NULL, 'third_place');

-- --------------------------------------------------------

--
-- Структура таблицы `tournament`
--

CREATE TABLE IF NOT EXISTS `tournament` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `begin_date` date NOT NULL,
  `first_place` int(11) DEFAULT NULL,
  `second_place` int(11) DEFAULT NULL,
  `third_place` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tournament`
--

INSERT INTO `tournament` (`id`, `name`, `begin_date`, `first_place`, `second_place`, `third_place`) VALUES
(11, 'Турнирчик', '2012-03-09', NULL, NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `football_team`
--
ALTER TABLE `football_team`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `tournament_id` (`tournament_id`);

--
-- Индексы таблицы `stage`
--
ALTER TABLE `stage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_id` (`tournament_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `f_team` (`f_team`),
  ADD KEY `s_team` (`s_team`);

--
-- Индексы таблицы `tournament`
--
ALTER TABLE `tournament`
  ADD PRIMARY KEY (`id`),
  ADD KEY `first_place` (`first_place`),
  ADD KEY `second_place` (`second_place`),
  ADD KEY `third_place` (`third_place`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `football_team`
--
ALTER TABLE `football_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT для таблицы `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `stage`
--
ALTER TABLE `stage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT для таблицы `tournament`
--
ALTER TABLE `tournament`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `stage`
--
ALTER TABLE `stage`
  ADD CONSTRAINT `stage_ibfk_1` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`id`),
  ADD CONSTRAINT `stage_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `stage` (`id`),
  ADD CONSTRAINT `stage_ibfk_3` FOREIGN KEY (`f_team`) REFERENCES `football_team` (`id`),
  ADD CONSTRAINT `stage_ibfk_4` FOREIGN KEY (`s_team`) REFERENCES `football_team` (`id`);

--
-- Ограничения внешнего ключа таблицы `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `tournament_ibfk_1` FOREIGN KEY (`first_place`) REFERENCES `football_team` (`id`),
  ADD CONSTRAINT `tournament_ibfk_2` FOREIGN KEY (`second_place`) REFERENCES `football_team` (`id`),
  ADD CONSTRAINT `tournament_ibfk_3` FOREIGN KEY (`third_place`) REFERENCES `football_team` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
