-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2023 at 04:52 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodbank`
--

-- --------------------------------------------------------

--
-- Table structure for table `adult`
--

CREATE TABLE `adult` (
  `Fam_id` int(10) NOT NULL,
  `adult_id` varchar(40) NOT NULL,
  `cals_needed` int(10) NOT NULL,
  `gender` char(1) NOT NULL CHECK (`gender` in ('M','F'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `back_employee`
--

CREATE TABLE `back_employee` (
  `Bemp_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `child`
--

CREATE TABLE `child` (
  `Fam_id` int(10) NOT NULL,
  `child_id` varchar(40) NOT NULL,
  `cals_needed` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clothe`
--

CREATE TABLE `clothe` (
  `Clothe_id` varchar(40) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` varchar(5) NOT NULL CHECK (`size` in ('XXS','XS','S','M','L','XL','XXL')),
  `gender` char(1) NOT NULL CHECK (`gender` in ('M','F','U')),
  `description` varchar(100) NOT NULL,
  `Corder_no` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clothing_inventory`
--

CREATE TABLE `clothing_inventory` (
  `type` varchar(100) NOT NULL,
  `size` varchar(5) NOT NULL CHECK (`size` in ('XXS','XS','S','M','L','XL','XXL')),
  `gender` char(1) NOT NULL CHECK (`gender` in ('M','F','U')),
  `qty` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `c_order`
--

CREATE TABLE `c_order` (
  `Corder_no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `c_supplies`
--

CREATE TABLE `c_supplies` (
  `Corder_no` int(10) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` varchar(5) NOT NULL CHECK (`size` in ('XXS','XS','S','M','L','XL','XXL')),
  `gender` char(1) NOT NULL CHECK (`gender` in ('M','F','U'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Emp_id` int(10) NOT NULL,
  `Fname` varchar(30) NOT NULL,
  `Lname` varchar(30) NOT NULL,
  `Semp_id` int(10) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `role` varchar(15) NOT NULL CHECK (`role` in ('Supervisor','Front','Back'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Emp_id`, `Fname`, `Lname`, `Semp_id`, `username`, `password`, `role`) VALUES
(1, 'Admin', 'Admin', NULL, 'admin', 'admin', 'Supervisor');

-- --------------------------------------------------------

--
-- Table structure for table `family`
--

CREATE TABLE `family` (
  `Fam_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `Food_id` varchar(40) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL CHECK (`type` in ('FV','Grain','Meat','Dairy','Other')),
  `calories` int(10) UNSIGNED NOT NULL,
  `Forder_no` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_inventory`
--

CREATE TABLE `food_inventory` (
  `name` varchar(100) NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `front_employee`
--

CREATE TABLE `front_employee` (
  `Femp_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `f_order`
--

CREATE TABLE `f_order` (
  `Forder_no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `f_supplies`
--

CREATE TABLE `f_supplies` (
  `Forder_no` int(10) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `Order_no` int(10) NOT NULL,
  `Picked_up` bit(1) NOT NULL,
  `Bemp_id` int(10) DEFAULT NULL,
  `type` varchar(15) NOT NULL CHECK (`type` in ('Food','Clothe')),
  `Ready_for_pick_up` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `Femp_id` int(10) NOT NULL,
  `Order_no` int(10) NOT NULL,
  `Fam_id` int(10) NOT NULL,
  `date` varchar(20) NOT NULL,
  `time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `replenish_c`
--

CREATE TABLE `replenish_c` (
  `Semp_id` int(10) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` varchar(5) NOT NULL CHECK (`size` in ('XXS','XS','S','M','L','XL','XXL')),
  `gender` char(1) NOT NULL CHECK (`gender` in ('M','F','U'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `replenish_f`
--

CREATE TABLE `replenish_f` (
  `Semp_id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supervisor`
--

CREATE TABLE `supervisor` (
  `Semp_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supervisor`
--

INSERT INTO `supervisor` (`Semp_id`) VALUES
(1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adult`
--
ALTER TABLE `adult`
  ADD PRIMARY KEY (`Fam_id`,`adult_id`),
  ADD UNIQUE KEY `adult_id` (`adult_id`);

--
-- Indexes for table `back_employee`
--
ALTER TABLE `back_employee`
  ADD PRIMARY KEY (`Bemp_id`);

--
-- Indexes for table `child`
--
ALTER TABLE `child`
  ADD PRIMARY KEY (`Fam_id`,`child_id`),
  ADD UNIQUE KEY `child_id` (`child_id`);

--
-- Indexes for table `clothe`
--
ALTER TABLE `clothe`
  ADD PRIMARY KEY (`type`,`size`,`gender`,`Clothe_id`),
  ADD UNIQUE KEY `Clothe_id` (`Clothe_id`),
  ADD KEY `Corder_no` (`Corder_no`);

--
-- Indexes for table `clothing_inventory`
--
ALTER TABLE `clothing_inventory`
  ADD PRIMARY KEY (`type`,`size`,`gender`);

--
-- Indexes for table `c_order`
--
ALTER TABLE `c_order`
  ADD PRIMARY KEY (`Corder_no`);

--
-- Indexes for table `c_supplies`
--
ALTER TABLE `c_supplies`
  ADD PRIMARY KEY (`Corder_no`,`type`,`size`,`gender`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Emp_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `Semp_id` (`Semp_id`);

--
-- Indexes for table `family`
--
ALTER TABLE `family`
  ADD PRIMARY KEY (`Fam_id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`name`,`Food_id`),
  ADD UNIQUE KEY `Food_id` (`Food_id`),
  ADD KEY `Forder_no` (`Forder_no`);

--
-- Indexes for table `food_inventory`
--
ALTER TABLE `food_inventory`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `front_employee`
--
ALTER TABLE `front_employee`
  ADD PRIMARY KEY (`Femp_id`);

--
-- Indexes for table `f_order`
--
ALTER TABLE `f_order`
  ADD PRIMARY KEY (`Forder_no`);

--
-- Indexes for table `f_supplies`
--
ALTER TABLE `f_supplies`
  ADD PRIMARY KEY (`Forder_no`,`name`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`Order_no`),
  ADD KEY `Bemp_id` (`Bemp_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`Femp_id`,`Order_no`,`Fam_id`);

--
-- Indexes for table `replenish_c`
--
ALTER TABLE `replenish_c`
  ADD PRIMARY KEY (`Semp_id`,`type`,`size`,`gender`);

--
-- Indexes for table `replenish_f`
--
ALTER TABLE `replenish_f`
  ADD PRIMARY KEY (`Semp_id`,`name`);

--
-- Indexes for table `supervisor`
--
ALTER TABLE `supervisor`
  ADD PRIMARY KEY (`Semp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `Emp_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `family`
--
ALTER TABLE `family`
  MODIFY `Fam_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `Order_no` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clothe`
--
ALTER TABLE `clothe`
  ADD CONSTRAINT `clothe_ibfk_1` FOREIGN KEY (`Corder_no`) REFERENCES `c_order` (`Corder_no`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`Semp_id`) REFERENCES `supervisor` (`Semp_id`);

--
-- Constraints for table `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `food_ibfk_1` FOREIGN KEY (`Forder_no`) REFERENCES `f_order` (`Forder_no`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`Bemp_id`) REFERENCES `back_employee` (`Bemp_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
