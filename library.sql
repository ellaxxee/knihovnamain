-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost:8889
-- Vytvořeno: Stř 04. úno 2026, 18:26
-- Verze serveru: 8.0.40
-- Verze PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `library`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `publication_year` int DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `total_copies` int DEFAULT '0',
  `available_copies` int DEFAULT '0',
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Vypisuji data pro tabulku `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `publication_year`, `genre`, `cover`, `total_copies`, `available_copies`, `added_at`, `description`) VALUES
(1, 'Malý princ', 'Antoine de Saint-Exupéry', '9782070612758', 1943, 'Pohádka', 'images/covers/c27f21b9295c9fb3b3a93135881ebcae_malyprinc.jpeg', 5, 5, '2025-10-29 14:11:04', NULL),
(2, 'Babička', 'Božena Němcová', '9788086264130', 1855, 'Klasická literatura', 'images/covers/88258855afd7c661ef0b6f7e53bdde46_babicka.jpeg', 3, 3, '2025-10-29 14:11:11', NULL),
(3, '1984', 'George Orwell', '9780451524935', 1949, 'Dystopie', 'images/covers/d810b2a4e4a84962dc8f444f0c480dde_obrazek-2026-02-04-121727097.png', 4, 4, '2025-10-29 15:09:53', NULL),
(4, 'Harry Potter a Kámen mudrců', 'J. K. Rowling', '9788000021964', 1997, 'Fantasy', 'images/covers/fd09046489c650c9a051217d6345bb4e.jpeg', 6, 6, '2025-10-29 14:11:17', NULL),
(5, 'Kytice', 'Karel Jaromír Erben', '9788072870392', 1853, 'Poezie', NULL, 3, 3, '2025-10-29 14:10:58', NULL),
(6, 'Zločin a trest', 'Fjodor Michajlovič Dostojevskij', '9780140449136', 1866, 'Román', 'images/covers/72a46b558cec23ee59a178c0c1000983.jpeg', 4, 4, '2025-10-29 14:11:21', NULL),
(7, 'Na západní frontě klid', 'Erich Maria Remarque', '9788025732342', 1929, 'Válečný román', NULL, 2, 2, '2025-10-29 14:10:53', NULL),
(8, 'Pýcha a předsudek', 'Jane Austen', '9780141439518', 1813, 'Román', NULL, 5, 5, '2025-10-29 14:10:50', NULL),
(9, 'Saturnin', 'Zdeněk Jirotka', '9788020429025', 1942, 'Humor', 'images/covers/55748b104a5aa30e37d1474465760dba.jpeg', 3, 3, '2025-10-29 15:09:53', NULL),
(10, 'Hobit aneb Cesta tam a zase zpátky', 'J. R. R. Tolkien', '9788025710500', 1937, 'Fantasy', NULL, 7, 7, '2025-10-29 14:10:44', NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `loans`
--

CREATE TABLE `loans` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `loaned_at` datetime NOT NULL,
  `due_at` datetime NOT NULL,
  `returned_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Vypisuji data pro tabulku `loans`
--

INSERT INTO `loans` (`id`, `user_id`, `book_id`, `loaned_at`, `due_at`, `returned_at`) VALUES
(1, 7, 3, '2026-02-04 12:10:29', '2026-02-18 12:10:29', '2026-02-04 17:19:25'),
(2, 7, 9, '2026-02-04 12:10:45', '2026-02-18 12:10:45', '2026-02-04 17:19:21'),
(3, 7, 6, '2026-02-04 12:10:46', '2026-02-18 12:10:46', '2026-02-04 17:19:19');

-- --------------------------------------------------------

--
-- Struktura tabulky `reservations`
--

CREATE TABLE `reservations` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `reservation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `pickup_date` date DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','picked_up','returned') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `review_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','librarian','student') DEFAULT 'student',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(5, 'mark.novak', 'mark', 'novak', 'marknovak@gmail.com', '$2y$10$RsYm9lVQ/OeToOwGWFz6augn7LyA60U99RpPrMgqgPRI8c4YN282e', 'admin', '2025-10-22 18:09:42'),
(6, 'turkovq', 'Eliška', 'Turková', 'turkovaeliska5@gmail.com', '$2y$10$e6R/0zxqvCnPenHquoHcK.J1ss3OGapvkh3YZiAlw5gj0C/qUP7AC', 'admin', '2026-01-06 16:56:20'),
(7, 'student', 'Lewis', 'Novak', 'lewisnovak@gmail.com', '$2y$10$GihyOVGv5MXEP4uaYSxm2.RGXRlIarvYHdKilhAD8GnJfqOKMxj6S', 'student', '2026-02-04 12:10:05');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `returned_at` (`returned_at`);

--
-- Indexy pro tabulku `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexy pro tabulku `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pro tabulku `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pro tabulku `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
