-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 21, 2017 at 02:31 PM
-- Server version: 5.6.26
-- PHP Version: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mobileweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `sessionmanager`
--

CREATE TABLE IF NOT EXISTS `sessionmanager` (
  `number` varchar(20) NOT NULL,
  `transaction_type` varchar(1000) DEFAULT NULL,
  `network` varchar(200) DEFAULT NULL,
  `recipientcol` varchar(200) DEFAULT NULL,
  `amountcol` varchar(200) DEFAULT NULL,
  `confirmcol` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` varchar(100) NOT NULL DEFAULT '',
  `number` varchar(20) NOT NULL,
  `transaction_type` varchar(1000) DEFAULT NULL,
  `network` varchar(200) DEFAULT NULL,
  `recipientcol` varchar(200) DEFAULT NULL,
  `amountcol` varchar(200) DEFAULT NULL,
  `confirmcol` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `number`, `transaction_type`, `network`, `recipientcol`, `amountcol`, `confirmcol`) VALUES
('99695', '0203704044', 'STATEMENT', 'MTN', '0547787834', '0547787834', 'confirmed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sessionmanager`
--
ALTER TABLE `sessionmanager`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
