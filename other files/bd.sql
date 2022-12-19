-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Дек 19 2022 г., 20:23
-- Версия сервера: 10.6.11-MariaDB-1:10.6.11+maria~ubu2004
-- Версия PHP: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `1400-19_workshop`
--
CREATE DATABASE IF NOT EXISTS `1400-19_workshop` DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci;
USE `1400-19_workshop`;

-- --------------------------------------------------------

--
-- Структура таблицы `order`
--

CREATE TABLE `order` (
  `order_number` varchar(80) NOT NULL,
  `status` varchar(100) NOT NULL,
  `total` int(11) NOT NULL,
  `date` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


-- --------------------------------------------------------

--
-- Структура таблицы `order_service`
--

CREATE TABLE `order_service` (
  `id` int(11) NOT NULL,
  `order_id` varchar(80) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


-- --------------------------------------------------------

--
-- Структура таблицы `service`
--

CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Дамп данных таблицы `service`
--

INSERT INTO `service` (`id_service`, `name`, `price`) VALUES
(1, 'Диагностика', 200),
(2, 'Чистка системного блока от пыли и грязи, замена термопасты', 2000),
(3, 'Переустановка ОС', 1700),
(4, 'Чистка ноутбука от пыли и грязи, замена термопасты', 2600),
(5, 'Замена материнской платы', 700),
(6, 'Настройка операционной системы, оптимизация', 500),
(7, 'Выявление и/или лечение вирусов', 500),
(8, 'Поиск драйвера устройства или прикладной программы в сети Интернет', 300),
(9, 'Установка драйвера одного устройства или прикладной программы', 50),
(10, 'Чистка системного блока от пыли и грязи, замена термопасты', 2000),
(11, 'Чистка ноутбука от пыли и грязи, замена термопасты', 2600),
(12, 'Настройка операционной системы, оптимизация', 500),
(13, 'Выявление и/или лечение вирусов', 500),
(14, 'Поиск драйвера устройства или прикладной программы в сети Интернет', 300),
(15, 'Установка драйвера одного устройства или прикладной программы', 50);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `token` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_number`),
  ADD KEY ` foreign_user` (`user_id`);

--
-- Индексы таблицы `order_service`
--
ALTER TABLE `order_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY ` foreign_order` (`order_id`),
  ADD KEY ` foreign_service` (`service_id`);

--
-- Индексы таблицы `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `order_service`
--
ALTER TABLE `order_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT для таблицы `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1421;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT ` foreign_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `order_service`
--
ALTER TABLE `order_service`
  ADD CONSTRAINT ` foreign_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_number`),
  ADD CONSTRAINT ` foreign_service` FOREIGN KEY (`service_id`) REFERENCES `service` (`id_service`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
