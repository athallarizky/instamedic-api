-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 22, 2020 at 02:48 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `instamedic`
--

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `createdBy` varchar(25) NOT NULL,
  `consultTo` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`id`, `subject`, `description`, `createdBy`, `consultTo`) VALUES
(34, 'Saya patah hati dok', 'Cara menyembuhkan patah hati', '34', '33');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `photoMedicine` varchar(255) DEFAULT NULL,
  `addedBy` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `name`, `description`, `category`, `photoMedicine`, `addedBy`) VALUES
(15, 'Panadol', 'Obat sakit kepala', 'sakit kepala, pusing', NULL, 'admin'),
(16, 'Paracetamol', 'Obat demam', 'demam, sakit kepala', NULL, 'dokter');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `body` text NOT NULL,
  `senderName` varchar(50) NOT NULL,
  `receptName` varchar(50) NOT NULL,
  `consult_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `body`, `senderName`, `receptName`, `consult_id`) VALUES
(14, 'Assalamulaikum dok,', '34', '33', 34),
(15, 'Waalaikumsalam, ya ada apa ya?', '33', '34', 34),
(16, 'Saya ingin bertanya bagaimana caranya menyembuhkan patah hati dok?', '34', '33', 34),
(17, 'Saya habis diputusin pacar saya', '34', '33', 34),
(18, 'Wah, kasihan ya kamu. Kamu bertanya kepada orang yang tepat.', '33', '34', 34),
(19, 'Saya akan berikan beberapa tips untuk move on dari dia yang telah menyakiti hati kamu.', '33', '34', 34),
(20, 'Wah, gimana tuh dok caranya?', '34', '33', 34),
(21, 'Mudah sekali, pertama kamu harus menguatkan niat kamu untuk moveon.', '33', '34', 34),
(22, 'Terus, dok?', '34', '33', 34),
(23, 'Ya abis itu cari pacar baru lah..', '33', '34', 34),
(24, 'Hehe..', '33', '34', 34);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','doctor','user','') NOT NULL DEFAULT 'user',
  `specialist` varchar(25) DEFAULT NULL,
  `photoProfile` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `password`, `role`, `specialist`, `photoProfile`) VALUES
(32, 'Athalla Rizky', 'admin', 'admin@instamedic.com', '$2y$10$oPN2uql9PW/px0mwNKS9j.jxPbIPHVrKrwn9WV9ahxITS.HCa2Gju', 'admin', NULL, NULL),
(33, 'Rizkyta Shainy', 'dokter', 'dokter@instamedic.com', '$2y$10$zhwuNHvKFu9c60q0oB8MDON8kI.zm8qidCMAZQDm.bHV7PpdnKp8S', 'doctor', 'Sakit hati', NULL),
(34, 'Nadilla Asyfa', 'user', 'user@instamedic.com', '$2y$10$O6F7m5pIod1uGjjT0UXWiO0Jzh5KappjAL5SJ6ONTonT0k9Ip4xQe', 'user', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consult_id` (`consult_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`consult_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
