-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2019 at 03:54 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `payment`
--

-- --------------------------------------------------------

--
-- Table structure for table `assign_tasks`
--

CREATE TABLE `assign_tasks` (
  `id` int(11) NOT NULL,
  `fname` varchar(60) NOT NULL,
  `lname` varchar(60) NOT NULL,
  `username` varchar(60) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_no` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `task` varchar(60) NOT NULL,
  `complete` int(11) NOT NULL,
  `comments` varchar(60) NOT NULL,
  `complains` varchar(60) NOT NULL,
  `paid` int(11) NOT NULL,
  `salary` int(11) NOT NULL,
  `paid_by` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `assign_tasks`
--

INSERT INTO `assign_tasks` (`id`, `fname`, `lname`, `username`, `date`, `id_no`, `due_date`, `task`, `complete`, `comments`, `complains`, `paid`, `salary`, `paid_by`) VALUES
(6, 'test', 'test', 'test', '2019-04-10 15:00:40', 34615813, '2019-04-25', 'House cleanig', 1, 'good job', 'none', 1, 28700, 'boniface');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `apply` varchar(11) NOT NULL,
  `id` int(11) NOT NULL,
  `fname` varchar(60) NOT NULL,
  `lname` varchar(60) NOT NULL,
  `id_no` int(11) NOT NULL,
  `account_no` int(11) NOT NULL,
  `photo` varchar(60) NOT NULL,
  `resident` varchar(60) NOT NULL,
  `tel_no` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(11) NOT NULL,
  `userlevel` int(60) NOT NULL DEFAULT '0',
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `gender` tinyint(11) NOT NULL,
  `task` int(11) NOT NULL,
  `work` varchar(60) NOT NULL,
  `dfrom` varchar(60) NOT NULL,
  `back` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`apply`, `id`, `fname`, `lname`, `id_no`, `account_no`, `photo`, `resident`, `tel_no`, `username`, `password`, `userlevel`, `reg_date`, `gender`, `task`, `work`, `dfrom`, `back`) VALUES
('completed', 1, 'Allan', 'Kimani', 32452379, 4688, '../upload/pakistan-ssg.jpg', 'town', 724356477, 'allan', 'allan', 0, '2019-06-11 09:54:13', 1, 0, 'cooking', '19/04/2019', '30458'),
('applied', 2, 'james', 'kamau', 34615813, 5658595, '../upload/gnevzydgtkrklib5rof5ba9c14346dff.jpg', 'mako', 724356478, 'kamau', 'kamau', 0, '2019-06-01 14:48:16', 1, 0, 'cleaning', '6/06./2019', '');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `apply` varchar(11) NOT NULL,
  `id` int(11) NOT NULL,
  `fname` varchar(60) NOT NULL,
  `lname` varchar(60) NOT NULL,
  `id_no` int(11) NOT NULL,
  `account_no` int(11) NOT NULL,
  `photo` varchar(60) NOT NULL,
  `resident` varchar(60) NOT NULL,
  `tel_no` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(11) NOT NULL,
  `userlevel` int(60) NOT NULL DEFAULT '0',
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `gender` tinyint(11) NOT NULL,
  `task` int(11) NOT NULL,
  `reason` varchar(60) NOT NULL,
  `dfrom` varchar(60) NOT NULL,
  `back` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`apply`, `id`, `fname`, `lname`, `id_no`, `account_no`, `photo`, `resident`, `tel_no`, `username`, `password`, `userlevel`, `reg_date`, `gender`, `task`, `reason`, `dfrom`, `back`) VALUES
('approved', 4, 'jane', 'wairimu', 30698541, 30698533, '../upload/IMG_20160210_170937.jpg', 'town', 729879632, 'jane', '8a8deed4462', 0, '2019-03-03 10:04:16', 0, 1, 'my wedding', '2-09-2018', '5-09-2018'),
('pending', 11, 'cynthia', 'ndugu', 32323345, 2147483647, '../upload/pakistan-ssg.jpg', 'town', 753222222, 'ndungu', 'ndungu', 0, '2018-11-10 15:02:17', 0, 0, '', '', ''),
('', 12, 'john', 'mwangi', 74333333, 2147483647, '../upload/pakistan-ssg.jpg', 'mako', 876543212, 'john', 'john', 0, '2018-07-25 11:16:52', 1, 0, '', '', ''),
('', 13, 'test', 'test', 34615813, 9678696, '../upload/pakistan-ssg.jpg', 'mako', 724356477, 'test', 'test', 0, '2019-03-05 15:00:40', 1, 0, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `leave`
--

CREATE TABLE `leave` (
  `id` int(11) NOT NULL,
  `fname` varchar(60) NOT NULL,
  `lname` varchar(60) NOT NULL,
  `usernme` varchar(60) NOT NULL,
  `id_no` int(11) NOT NULL,
  `tel_no` int(11) NOT NULL,
  `reason` varchar(60) NOT NULL,
  `dateapplied` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `return` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE `notice` (
  `id` int(11) NOT NULL,
  `m_to` text NOT NULL,
  `m_from` text NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '0',
  `seen` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notice`
--

INSERT INTO `notice` (`id`, `m_to`, `m_from`, `message`, `date`, `status`, `seen`) VALUES
(1, 'Employee', 'Admin', 'you will be paid after completing your cleaning work', '2019-02-26 06:20:44', 0, 0),
(6, 'Employee', 'Admin', 'finalize your tasks', '2019-03-05 14:49:53', 0, 0),
(4, 'Employee', 'Admin', 'U will be paid after work done is complete', '2019-07-24 11:28:21', 0, 0),
(5, 'Manager', 'Admin', 'do not pay the employees', '2019-03-05 14:49:31', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `objective`
--

CREATE TABLE `objective` (
  `id` int(11) NOT NULL,
  `mission` varchar(60) NOT NULL,
  `vision` varchar(60) NOT NULL,
  `motto` varchar(60) NOT NULL,
  `update_by` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `fname` varchar(60) NOT NULL,
  `lname` varchar(60) NOT NULL,
  `id_no` int(11) NOT NULL,
  `pay_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `amount` int(11) NOT NULL,
  `tax` int(11) NOT NULL,
  `nhif` int(11) NOT NULL,
  `others` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `payed_by` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `fname`, `lname`, `id_no`, `pay_date`, `amount`, `tax`, `nhif`, `others`, `total`, `payed_by`) VALUES
(3, 'liz', 'ntheny', 45678888, '2019-02-27 14:40:17', 26000, 4160, 500, 0, 21340, 'boniface'),
(4, 'test', 'test', 34615813, '2019-03-05 15:00:40', 35000, 5600, 500, 200, 28700, 'boniface');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `id_no` int(11) NOT NULL,
  `photo` varchar(60) NOT NULL,
  `residence` varchar(255) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `gender` tinyint(11) NOT NULL,
  `userlevel` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `fname`, `lname`, `id_no`, `photo`, `residence`, `tel`, `username`, `password`, `reg_date`, `gender`, `userlevel`) VALUES
(4, 'boniface', 'mwangi', 30657956, '', 'thika', '716540693', 'bonnie', '9cb2dacf1872472239ff7a6fc9fade66803a5d90', '2019-05-29 08:30:34', 1, 1),
(3, 'duncan', 'makewa', 30657967, '', 'Thika', '0723964850', 'dun', 'dun', '2019-05-22 08:26:16', 1, 2),
(6, 'chris', 'kamau', 34210075, '', 'thika', '741608907', 'chris', '2bd28dd7cbb402e9e9132e992950e21794632990', '2019-07-17 05:36:52', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `taskname` text NOT NULL,
  `amount` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `taskname`, `amount`, `status`) VALUES
(4, 'Window repair', 45000, 0),
(8, 'trimming grass', 20000, 0),
(5, 'House cleanig', 35000, 0),
(6, 'Gardener', 26000, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assign_tasks`
--
ALTER TABLE `assign_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave`
--
ALTER TABLE `leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `objective`
--
ALTER TABLE `objective`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_no` (`id_no`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assign_tasks`
--
ALTER TABLE `assign_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `leave`
--
ALTER TABLE `leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `objective`
--
ALTER TABLE `objective`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
