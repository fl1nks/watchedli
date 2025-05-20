-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Май 18 2025 г., 18:09
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `watchedl`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `computer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `computer_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `bookings`
--

INSERT INTO `bookings` (`id`, `computer_id`, `user_id`, `booking_date`, `booking_time`, `computer_name`) VALUES
(41, 1, 1, '2025-03-26', '18:37:00', 'МойПК-01'),
(42, 1, 1, '2025-03-26', '17:41:00', 'МойПК-01'),
(43, 1, 1, '2025-03-05', '16:43:00', 'МойПК-01'),
(44, 1, 1, '2025-03-26', '02:46:00', 'МойПК-01'),
(45, 1, 1, '2025-04-02', '16:21:00', 'МойПК-01');

-- --------------------------------------------------------

--
-- Структура таблицы `computers`
--

CREATE TABLE `computers` (
  `id` int(11) NOT NULL,
  `computer_name` varchar(128) NOT NULL,
  `specs` varchar(256) NOT NULL,
  `price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `computers`
--

INSERT INTO `computers` (`id`, `computer_name`, `specs`, `price`) VALUES
(1, 'МойПК-01', 'Intel i9, 32GB RAM, 512GB SSD', 150.00),
(4, 'Workstation', 'AMD Ryzen 9, 32GB RAM, 1TB NVMe', 1500.00),
(14, 'ва', 'uyhjihgufyguhiv', 600.00);

-- --------------------------------------------------------

--
-- Структура таблицы `managers`
--

CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `login` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `managers`
--

INSERT INTO `managers` (`id`, `login`, `password`) VALUES
(1, 'admin', '123456789'),
(11, 'kro11ik0', '$2y$10$/BCQIdUZFqPU3yv4v7iT9.p7xIAsmSdAq8Qemjt3WgrEudY83gA7K'),
(12, 'kro11ik012', '$2y$10$M9maUcG465hLDH2O2BR0R.NJJmKXqm7TiNxDg/4jB0EDxg38nJmDm'),
(13, 'kro11ik01231', '$2y$10$niK20ls8F5t7giJgdDneD.i3fsFQFha7PKA5ccCxAtjXkFjDm8yku'),
(14, 'kro11ik0123', '$2y$10$4Yj84FEj8VtW0RkQEwAotuV2rUUXYOQjuGlkY/cxC7ojTAl3NKaaG');

-- --------------------------------------------------------

--
-- Структура таблицы `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL,
  `method` varchar(50) NOT NULL,
  `number_card` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `payment`
--

INSERT INTO `payment` (`id`, `user_id`, `amount`, `date`, `method`, `number_card`) VALUES
(34, 22, 30.00, '2025-04-29 16:22:57', 'Карта', '1229029128192819'),
(35, 23, 12313.00, '2025-04-29 22:51:05', 'Карта', '1229029128192819');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `computer_id` int(11) NOT NULL,
  `start_time` date NOT NULL,
  `end_time` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `surname` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `data_reg` date DEFAULT curdate(),
  `balance` decimal(20,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `surname`, `email`, `data_reg`, `balance`) VALUES
(1, 'test', 'test@example.com', '2025-03-25', 30550.00),
(22, 'Кретова', '578014000aa66be2a9d56caa98db2df67f31c9da6b24685c6222ccd1955c5161', '2025-04-29', 30.00),
(23, 'Кретова', 'dc6413f34433a07aace6065d39f86bb23d04ef4984861c585ed652ec907b390d', '2025-04-29', 12313.00),
(24, '21', 'f4d0d434c0d9b86c600a317f9c46dc1ab7bab2d8665b07b35e49852266c377e7', '2025-04-29', 0.00),
(25, '212', '41fa4db95d61115abba5f3f008fca761b492d399814cff31be30d23b6feca49f', '2025-05-03', 0.00),
(26, '212', '070644309abb0eb756e646ebacfb613126f69f1a15cb6ba3eaa1a25b8a3f04c9', '2025-05-03', 0.00),
(27, '21', '5080a2b3c7b6309ce075d7f3fd824fa72fa400ad005a421607ca4f3cdd02af4f', '2025-05-03', 0.00),
(28, 'test', '8b35f89e21b758d36dedccf6afc3a5086f9ff6187810d9092e31a46c2955d458', '2025-05-03', 0.00),
(29, 'test', '5c3c1858a143b5e8fa3c77abda265af92e5f57498bd7beefcaabf360b99c6f76', '2025-05-03', 0.00),
(30, 'test1', '44fe9cb4c560ec403be84134a72016028568e6b623137fc134468a5fc0d98f29', '2025-05-03', 0.00),
(31, 'test1', '5cf675d49d51152e3dcd50e036fbc208116fb22248f71c24890b8690bc595049', '2025-05-03', 0.00),
(32, 'aw', 'ab6d2495645ee48c83c0a5ded268be7c4d327f5fb499cdfb54605e00d63de6a0', '2025-05-06', 0.00),
(33, 'qefeq', 'f3c5a41a0b824f896d3fd6ca3984b15ce180899eb27ac953e2eacfbfcac7b2c8', '2025-05-06', 0.00),
(34, 'kro11ik0', '408f1f85f11e7e06e4676184ff276ff918dd326354dd745e0476eb5a77cbf8ca', '2025-05-06', 0.00),
(35, 'kro11ik02313', '5c424a9a159567a020457c73d2891478e90f9bed59617d2358812abe9eb87ae5', '2025-05-06', 0.00);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `computer_id` (`computer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `computers`
--
ALTER TABLE `computers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_payment` (`user_id`,`date`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `computer_id` (`computer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT для таблицы `computers`
--
ALTER TABLE `computers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `managers`
--
ALTER TABLE `managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`computer_id`) REFERENCES `computers` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`computer_id`) REFERENCES `computers` (`id`),
  ADD CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
