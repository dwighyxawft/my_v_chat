-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2023 at 10:42 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_v_chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `users_table`
--

CREATE TABLE `users_table` (
  `userID` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profileImage` varchar(255) NOT NULL,
  `sessionID` varchar(255) NOT NULL,
  `connectionID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_table`
--

INSERT INTO `users_table` (`userID`, `username`, `name`, `email`, `password`, `profileImage`, `sessionID`, `connectionID`) VALUES
(1, 'dwighyxawft', 'Timilehin Amu', 'amuoladipupo@gmail.com', '$2y$10$3850Hr6f4d2nJzlNYojQF.2dfAhvYr4eCDDdChY1conf1u3kt6h52', 'male.jpg', 'eo9p49u4dl76vtne1dl96bdhhf', 156),
(2, 'badhboidoker', 'Raheem  Akeem', 'raheemakeem@gmail.com', '$2y$10$3850Hr6f4d2nJzlNYojQF.2dfAhvYr4eCDDdChY1conf1u3kt6h52', 'male.jpg', '1l2v9ill2rc84c60lkvtbl5bf0', 147),
(3, 'dogman', 'Adebajo Damilare', 'adebajodamilare@gmail.com', '$2y$10$3850Hr6f4d2nJzlNYojQF.2dfAhvYr4eCDDdChY1conf1u3kt6h52', 'male.jpg', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users_table`
--
ALTER TABLE `users_table`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users_table`
--
ALTER TABLE `users_table`
  MODIFY `userID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
