-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 15, 2025 at 01:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `description`) VALUES
(6, 'Developer Blog', 'Focus on detailing the services offered, such as skincare consultations, product recommendations, etc.'),
(7, 'Design Blog', 'A space dedicated to exploring the world of aesthetics and creativity. Dive into topics like graphic design, typography, UI/UX trends, and practical tips to refine your creative skills.'),
(9, 'Real-time Blog', 'Stay updated with the latest news, trends, and developments as they happen. From breaking stories to real-time event coverage, this blog keeps you in the loop.'),
(10, 'Environmental Blog', 'An eco-conscious blog that highlights pressing environmental issues, sustainable living practices, green innovations, and ways to contribute to a healthier planet.'),
(11, 'Memes Blog', 'A lighthearted corner for meme enthusiasts, showcasing the funniest, trendiest, and most relatable memes from across the internet to brighten your day.'),
(12, 'Business Blog', 'This Blog Contains real business blogs, and difficulties or fun fact that business handled.');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `author_id` int(11) UNSIGNED NOT NULL,
  `is_featured` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `body`, `thumbnail`, `date_time`, `category_id`, `author_id`, `is_featured`) VALUES
(21, 'Why My Startup Pivoted: Lessons Learned', 'Apple latest launch event introduced some game-changing tools for designers. Here&#039;s what stood out and how it could influence design trends. Apple latest launch event introduced some game-changing tools for designers. Here&#039;s what stood out and how it could influence design trends. Apple latest launch event introduced some game-changing tools for designers. Apple latest launch event introduced some game-changing tools for designers.', '17396180501733000363blog3.jpg', '2025-02-15 06:40:33', 7, 27, 0),
(24, 'Checkiing', 'Apple latest launch event introduced some game-changing tools for designers. Here&#039;s what stood out and how it could influence design trends. Appl...Apple latest launch event introduced some game-changing tools for designers. Here&#039;s what stood out and how it could influence design trends. Appl...', '1739617986-1732832151-blog1.jpg', '2025-02-15 11:13:06', 7, 21, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `reset_code` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `avatar`, `is_admin`, `reset_code`) VALUES
(21, 'karthi', 'kumar', 'blueboy', 'karthikumarofficial24@gmail.com', '$2y$10$/GUWuO9G2nryFE085zIhueWzbc7o/qSuV2KiGLNK6Y5t4PHzmQU.e', '1732953752avatar1.JPG', 1, 5200),
(27, 'checking', 'user1', 'checkinguser1', 's.k807807807807@gmail.com', '$2y$10$X63SMPGUTKyDNUjq0Ekc/enpQKjl.NQeV.7TzjLeM4mbLXHenPiCa', '17396006061734949515-avatar6.png', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_blog_category` (`category_id`),
  ADD KEY `FK_blog_author` (`author_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `FK_blog_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
