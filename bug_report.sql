-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Авг 20 2025 г., 22:07
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
-- База данных: `bug_report`
--

-- --------------------------------------------------------

--
-- Структура таблицы `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `description` varchar(2048) NOT NULL,
  `priority` varchar(128) NOT NULL,
  `status` varchar(32) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `title`, `description`, `priority`, `status`, `created_at`) VALUES
(3, 1, 'ыаыавыаывавыаываываываыв', 'ыаыавыаывавыаываываываывыаыавыаывавыаываываываывыаыавыаывавыаываываываывыаыавыаывавыаываываываыв', 'Low', 'Исправлено', '2025-08-17'),
(4, 1, 'asdasdasdasdasdasdasdasdasdasd', 'asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd', 'HARD', 'Исправлено', '2025-08-17'),
(5, 1, 'asdasdasdasdasd132123123', 'asdasdasdasdasdasdasdasdasd1231231231231asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd', 'HARD', 'Отложено', '2025-08-17'),
(6, 2, 'new report user vadimky', 'new report user vadimkynew report user vadimkynew report user vadimky', 'Middle', 'Исправлено', '2025-08-17'),
(7, 1, 'qwerttyttqwerttyttqwerttytt', 'qwerttyttqwerttyttqwerttyttqwerttyttqwerttyttqwerttyttqwerttyttqwerttyttqwerttyttqwerttyttqwerttyttqwerttyttqwerttytt', 'Low', 'В процессе', '2025-08-18'),
(8, 3, 'test admin report', 'HELLO WORLD!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!', 'HARD', 'В процессе', '2025-08-18'),
(9, 3, 'test xss from admin ^_^', '<script>alert(\'its XSS atack\')</script', 'Low', 'Исправлено', '2025-08-19'),
(10, 3, 'fix list project \"bug_report\"', '1. вынести функции и маршруты из index.php \r\n2. экранировать пользовательских данных\r\n3. убедится что csrf токены находятся в каждой в форме\r\n4. вынести js код из разметки в соответствующие файлы', 'Middle', 'В процессе', '2025-08-19'),
(11, 3, 'asdasdasdsadadasdasdasd', 'asdsaaaaaaaaaaaaaaaaaaasdsaaaaaaaaaaaaaaaaaaasdsaaaaaaaaaaaaaaaaaa', 'Low', 'В процессе', '2025-08-19'),
(12, 3, '              helloo000000000', 'helloo000000000helloo000000000helloo000000000helloo000000000helloo000000000helloo000000000helloo000000000helloo000000000helloo000000000helloo000000000helloo000000000', 'HARD', 'В процессе', '2025-08-19');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`, `created_at`) VALUES
(1, 'aaaa', 'alek@mail.ru', '$2y$10$SdeXCwll/tOoQjjt8Iz/R.J3FYFbFyn.JfKwlnUrySGCSoo.2boC.', '2025-08-17'),
(2, 'vadimka', 'vd@mail.ru', '$2y$10$Gdu31yN0YoLtOTDttj0pW.rH7UlJrJZ9UDena/KO6Gt5CpIchb18K', '2025-08-17'),
(3, 'admin', 'admin@mail.ru', '$2y$10$KgGO/myXHQEB7PHqEjWuiO5.Ou.RWHux9YXNT..iXTIU00bJ8W.T2', '2025-08-18');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
