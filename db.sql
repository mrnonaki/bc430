-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 30, 2020 at 12:52 PM
-- Server version: 5.5.64-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_bc430`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `price` float(8,2) NOT NULL,
  `ship` float(8,2) NOT NULL,
  `quantity` int(5) NOT NULL,
  `ready` int(5) NOT NULL,
  `issell` int(1) NOT NULL COMMENT '0: ไม่ขาย , 1: ขาย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address_no` varchar(20) NOT NULL,
  `address_district` varchar(20) NOT NULL,
  `address_amphoe` varchar(20) NOT NULL,
  `address_province` varchar(20) NOT NULL,
  `address_zipcode` varchar(5) NOT NULL,
  `tel` varchar(10) NOT NULL,
  `idth` varchar(13) NOT NULL,
  `register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ban` int(1) NOT NULL COMMENT '0: ปกติ , 1: ระงับ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address_no` varchar(20) NOT NULL,
  `address_district` varchar(20) NOT NULL,
  `address_amphoe` varchar(20) NOT NULL,
  `address_province` varchar(20) NOT NULL,
  `address_zipcode` varchar(5) NOT NULL,
  `tel` varchar(10) NOT NULL,
  `idth` varchar(13) NOT NULL,
  `role` int(1) NOT NULL COMMENT '1: พนักงาน , 2: เจ้าของ',
  `ban` int(1) NOT NULL COMMENT '0: ปกติ , 1: ลาออก'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `username`, `password`, `email`, `name`, `address_no`, `address_district`, `address_amphoe`, `address_province`, `address_zipcode`, `tel`, `idth`, `role`, `ban`) VALUES
(1, 'admin', '25d55ad283aa400af464c76d713c07ad', '', '', '', '', '', '', '', '', '', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(5) UNSIGNED NOT NULL,
  `employee` int(5) UNSIGNED DEFAULT NULL,
  `receipt` int(5) UNSIGNED DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `duedate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `paydate` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `payment` varchar(100) DEFAULT NULL,
  `amount` float(8,2) NOT NULL,
  `ps` varchar(500) DEFAULT NULL,
  `status` int(1) UNSIGNED NOT NULL COMMENT '0: ยกเลิก , 1: รอแจ้ง , 2: รอตรวจ , 3: ชำระแล้ว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orderlist`
--

CREATE TABLE `orderlist` (
  `id` int(5) UNSIGNED NOT NULL,
  `orders` int(5) UNSIGNED NOT NULL,
  `product` varchar(20) NOT NULL,
  `rate` float(8,2) NOT NULL,
  `ship` float(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(5) UNSIGNED NOT NULL,
  `customer` int(5) UNSIGNED NOT NULL,
  `employee` int(5) UNSIGNED DEFAULT NULL,
  `invoice` int(5) UNSIGNED DEFAULT NULL,
  `ship` varchar(500) DEFAULT NULL,
  `total` float(8,2) NOT NULL,
  `discount` float(8,2) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateship` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `dueship` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `track` varchar(20) DEFAULT NULL,
  `status` int(1) NOT NULL COMMENT '0: ยกเลิก , 1: รอแจ้ง , 2: รอตรวจ , 3: รอส่ง , 4: ส่งแล้ว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` varchar(20) NOT NULL,
  `category` int(5) UNSIGNED NOT NULL,
  `datein` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL COMMENT '1:พร้อมขาย , 2:รอชำระ , 3:รอส่ง , 4:ขายแล้ว , 5:เคลมเข้า , 6:เคลมออก'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `id` int(5) UNSIGNED NOT NULL,
  `employee` int(5) UNSIGNED DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` float(8,2) NOT NULL,
  `ps` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `warranty`
--

CREATE TABLE `warranty` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `new` varchar(20) NOT NULL,
  `ps` varchar(500) NOT NULL,
  `employee` int(5) UNSIGNED NOT NULL,
  `customer` int(5) UNSIGNED NOT NULL,
  `orderlist` int(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
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
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee` (`employee`),
  ADD KEY `receipt` (`receipt`);

--
-- Indexes for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders` (`orders`),
  ADD KEY `product` (`product`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer` (`customer`),
  ADD KEY `employee` (`employee`),
  ADD KEY `invoice` (`invoice`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee` (`employee`);

--
-- Indexes for table `warranty`
--
ALTER TABLE `warranty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `new` (`new`,`employee`,`customer`,`orderlist`),
  ADD KEY `customer` (`customer`),
  ADD KEY `orderlist` (`orderlist`),
  ADD KEY `employee` (`employee`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderlist`
--
ALTER TABLE `orderlist`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warranty`
--
ALTER TABLE `warranty`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`employee`) REFERENCES `employee` (`id`),
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`receipt`) REFERENCES `receipt` (`id`);

--
-- Constraints for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD CONSTRAINT `orderlist_ibfk_1` FOREIGN KEY (`orders`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `orderlist_ibfk_2` FOREIGN KEY (`product`) REFERENCES `product` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`invoice`) REFERENCES `invoice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`employee`) REFERENCES `employee` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category`) REFERENCES `category` (`id`);

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `receipt_ibfk_2` FOREIGN KEY (`employee`) REFERENCES `employee` (`id`);

--
-- Constraints for table `warranty`
--
ALTER TABLE `warranty`
  ADD CONSTRAINT `warranty_ibfk_4` FOREIGN KEY (`new`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `warranty_ibfk_1` FOREIGN KEY (`customer`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `warranty_ibfk_2` FOREIGN KEY (`orderlist`) REFERENCES `orderlist` (`id`),
  ADD CONSTRAINT `warranty_ibfk_3` FOREIGN KEY (`employee`) REFERENCES `employee` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
