-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 18, 2020 at 07:04 AM
-- Server version: 5.7.24
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ms`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(255) NOT NULL,
  `creator` int(255) NOT NULL,
  `comment_of` int(255) NOT NULL,
  `comment` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(255) NOT NULL,
  `creation_date` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `creator`, `comment_of`, `comment`, `file`, `type`, `creation_date`) VALUES
(1, 1, 8, 'c3pkZ2FoZXJ3ZWc=', 'Summer - A Time For Home Maintenance.txt', 1, '2020-01-04 18:00:00.000000'),
(2, 1, 11, 'VGhpcyBpcyBhbm90aGVyIGNvbW1lbnRzIQ==', 'fun2all Monthly SEO Service Report of Thefunfordguy.com.pdf,fun2all Monthly SEO Service Report of Thefunfordguy.com-1.pdf', 1, '2020-01-06 18:00:00.000000'),
(3, 1, 15, 'SGk=', 'flashjenkins 1st Tier Work Report (gearstrilogy.com) 11 January.xlsx', 1, '2020-01-11 11:28:21.000000'),
(4, 13, 17, 'VGhpcyBpcyB0aGUgcmVwb3J0', 'ms.sql', 1, '2020-01-12 06:18:36.000000'),
(5, 13, 17, 'QW5vdGhlcg==', 'ms-1.sql', 1, '2020-01-12 06:18:56.000000');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(255) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid` int(255) NOT NULL,
  `words` int(255) NOT NULL,
  `paid_to` int(100) NOT NULL,
  `creation_date` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `paid_date` timestamp(6) NULL DEFAULT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `name`, `paid`, `words`, `paid_to`, `creation_date`, `paid_date`, `status`) VALUES
(19, 'Invoice_2020-01-12_13(Shihab)', 196, 560, 13, '2020-01-12 06:43:47.000000', NULL, 0),
(20, 'Invoice_2020-01-12_14(Salman)', 354, 708, 14, '2020-01-12 07:42:24.000000', '2020-01-11 18:00:00.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(255) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(10000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creator` int(255) DEFAULT NULL,
  `deadline` timestamp(6) NULL DEFAULT NULL,
  `created` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `status` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `creator`, `deadline`, `created`, `status`) VALUES
(1, 'EFA MARCHANT', 'PGk+PGI+VGhpcyBpcyB0aGUgZGVzY3JpcHRpb24gbGluZSE8L2I+PC9pPg==', 1, '2020-01-04 18:00:00.000000', '2020-01-03 18:00:00.000000', 0),
(2, 'FUN2ALL', 'PGgxPjxiPkRlc2NyaXB0aW9uIGxpbmUhPC9iPjwvaDE+', 1, '2020-01-06 18:00:00.000000', '2020-01-03 18:00:00.000000', 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(255) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Admin', 'All the permissions for the role'),
(2, 'Writer Head', 'Some items'),
(3, 'Writer', ''),
(4, 'Publisher', ''),
(5, 'Visitor', '');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(255) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(10000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assignee` int(255) NOT NULL,
  `creator` int(255) NOT NULL,
  `words` int(255) NOT NULL,
  `rate` int(255) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project` int(255) NOT NULL,
  `deadline` date DEFAULT NULL,
  `created` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `status` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publishing` int(255) NOT NULL,
  `publisher` int(255) NOT NULL,
  `publish_status` int(255) NOT NULL,
  `publish_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `description`, `assignee`, `creator`, `words`, `rate`, `type`, `project`, `deadline`, `created`, `status`, `publishing`, `publisher`, `publish_status`, `publish_link`, `invoice`, `attachment`, `remarks`, `priority`) VALUES
(10, 'Get 2002 by Anne-Marie HERE', 'PGgyPjxzcGFuPk5vdGF0aW9uPGEgdGFyZ2V0PSJfYmxhbmsiIHJlbD0ibm9mb2xsb3ciIGhyZWY9Imh0dHBzOi8vZ2V0Ym9vdHN0cmFwLmNvbS9kb2NzLzQuMy91dGlsaXRpZXMvc3BhY2luZy8jbm90YXRpb24iPjwvYT48L3NwYW4+PC9oMj5TcGFjaW5nIHV0aWxpdGllcyB0aGF0IGFwcGx5IHRvIGFsbCBicmVha3BvaW50cywgZnJvbSZuYnNwO3hzJm5ic3A7dG8mbmJzcDt4bCwgaGF2ZSBubyBicmVha3BvaW50IGFiYnJldmlhdGlvbiBpbiB0aGVtLiBUaGlzIGlzIGJlY2F1c2UgdGhvc2UgY2xhc3NlcyBhcmUgYXBwbGllZCBmcm9tJm5ic3A7bWluLXdpZHRoOiAwJm5ic3A7YW5kIHVwLCBhbmQgdGh1cyBhcmUgbm90IGJvdW5kIGJ5IGEgbWVkaWEgcXVlcnkuIFRoZSByZW1haW5pbmcgYnJlYWtwb2ludHMsIGhvd2V2ZXIsIGRvIGluY2x1ZGUgYSBicmVha3BvaW50IGFiYnJldmlhdGlvbi5UaGUgY2xhc3NlcyBhcmUgbmFtZWQgdXNpbmcgdGhlIGZvcm1hdCZuYnNwO3twcm9wZXJ0eX17c2lkZXN9LXtzaXplfSZuYnNwO2ZvciZuYnNwO3hzJm5ic3A7YW5kJm5ic3A7e3Byb3BlcnR5fXtzaWRlc30te2JyZWFrcG9pbnR9LXtzaXplfSZuYnNwO2ZvciZuYnNwO3NtLCZuYnNwO21kLCZuYnNwO2xnLCBhbmQmbmJzcDt4bC4=', 13, 1, 730, 400, 'Guest Post', 1, '2020-01-06', '2020-01-04 18:00:00.000000', '1', 1, 15, 2, 'http://localhost/task.php?id=10', '', '', '', 0),
(12, 'This is a non-profit music channel from YouTube.', 'PGgzPjxzcGFuPk5lZ2F0aXZlIG1hcmdpbjxhIHRhcmdldD0iX2JsYW5rIiByZWw9Im5vZm9sbG93IiBocmVmPSJodHRwczovL2dldGJvb3RzdHJhcC5jb20vZG9jcy80LjMvdXRpbGl0aWVzL3NwYWNpbmcvI25lZ2F0aXZlLW1hcmdpbiI+PC9hPjwvc3Bhbj48L2gzPkluIENTUywmbmJzcDttYXJnaW4mbmJzcDtwcm9wZXJ0aWVzIGNhbiB1dGlsaXplIG5lZ2F0aXZlIHZhbHVlcyAocGFkZGluZyZuYnNwO2Nhbm5vdCkuIEFzIG9mIDQuMiwgd2XigJl2ZSBhZGRlZCBuZWdhdGl2ZSBtYXJnaW4gdXRpbGl0aWVzIGZvciBldmVyeSBub24temVybyBpbnRlZ2VyIHNpemUgbGlzdGVkIGFib3ZlIChlLmcuLCZuYnNwOzEsJm5ic3A7MiwmbmJzcDszLCZuYnNwOzQsJm5ic3A7NSkuIFRoZXNlIHV0aWxpdGllcyBhcmUgaWRlYWwgZm9yIGN1c3RvbWl6aW5nIGdyaWQgY29sdW1uIGd1dHRlcnMgYWNyb3NzIGJyZWFrcG9pbnRzLg==', 13, 1, 745, 400, 'Review Article', 2, '2020-01-08', '2020-01-04 18:00:00.000000', '3', 1, 15, 2, 'http://localhost/phpmyadmin/tbl_structure.php?db=ms', '', '', '', 0),
(16, 'Bootstrap includes a wide range of shorthand', '', 14, 1, 708, 500, 'Web 2.0 Article', 1, '2020-01-13', '2020-01-11 12:38:59.406478', '4', 1, 15, 2, '', 'VXARI000020', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` int(255) NOT NULL,
  `role` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fb_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `twitter_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(10000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quality` int(1) NOT NULL,
  `join_date` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `payment_method`, `payment_account`, `rate`, `role`, `fb_url`, `twitter_url`, `instagram_url`, `password`, `picture`, `quality`, `join_date`) VALUES
(1, 'Imran Hossen', 'admin@hmime.com', '01782448244', 'Sector 10, Uttara, Dhaka - 1230', 'Rocket (DBBL)', '01782448244', 0, '1', 'https://fb.me/hmimeee', 'https://twitter.com/hmimeee', 'https://instagram.com/hmimeee', '123456', 'uploads/109400191975653331_1513778808760473_2398699094051651584_o.jpg', 5, '2019-12-31 23:20:23.141109'),
(13, 'Shihab', 'shihab@gmail.com', '', '', '', '', 350, '2', '', '', '', '102030', 'uploads/393014652Sajib.jpg', 1, '0000-00-00 00:00:00.000000'),
(14, 'Salman', 'salman@gmail.com', '', '', '', '', 500, '3', '', '', '', '102030', 'uploads/675700901IMG-20191225-WA0077.jpg', 2, '0000-00-00 00:00:00.000000'),
(15, 'Sajal', 'sajal@gmail.com', '', '', '', '', 0, '4', '', '', '', '102030', '', 2, '0000-00-00 00:00:00.000000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
