-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2021 at 12:03 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ci4_oauth`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `oauth_id` varchar(50) DEFAULT NULL,
  `user_username` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_img` varchar(500) NOT NULL DEFAULT 'user.png',
  `created_at` varchar(50) DEFAULT NULL,
  `updated_at` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `oauth_id`, `user_username`, `user_email`, `user_password`, `user_img`, `created_at`, `updated_at`) VALUES
(1, '116241242605635312490', 'Andi Muhyi Mayapada', 'andimayapada04@gmail.com', NULL, 'https://lh3.googleusercontent.com/a-/AOh14GjIJvT98cnU2e0o4INo3R9BZC1hxSH2L3WcpVtK=s96-c', '2021-10-19 05:51:18', '2021-10-20 04:07:03'),
(2, '106303930652429115240', 'Pro Kontra', 'prokontra830@gmail.com', NULL, 'https://lh3.googleusercontent.com/a/AATXAJyXg6n8k7r-Z2hmfSRJBRbXMDoC8MePygJOqb6C=s96-c', '2021-10-19 06:46:36', '2021-10-19 07:04:37'),
(3, NULL, 'Andi Muhyi Mayapada', 'kwang@gmail.com', '$2y$10$ny.0qTeTyeZEW5fgVROvA.F33nGTt2i4MaPH9kC8yaN00acSHNaVy', 'user.png', '2021-10-19 06:49:31', NULL),
(4, '114895378130557978038', 'Pro Mmpd', 'prommpd830@gmail.com', NULL, 'https://lh3.googleusercontent.com/a-/AOh14Ggsas061EibwlRp9AqkjfCVtY6HIKuP5NOQNmxy=s96-c', '2021-10-19 23:34:34', '2021-10-19 23:55:33'),
(5, NULL, 'prommpd830', 'apaaja613@gmail.com', '$2y$10$JjLMr5cjBn/oy7cA8IzC8eLo1ZP.z/zPl2bqj3UdhUn1egsk41eTi', 'user.png', '2021-10-20 03:06:48', NULL),
(6, '402387341470459', 'Andi Muhyi', 'andimayapada04@gmail.com', NULL, 'http://graph.facebook.com/402387341470459/picture', NULL, '2021-10-20 04:07:19'),
(7, NULL, 'admin', 'admin@gmail.com', '$2y$10$0Ecvs3YcjqNNB3wcZbPNY.1iRl1tX6VNkVfOo4yEWGnrxKjDK/jBO', 'user.png', '2021-10-20 04:05:02', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
