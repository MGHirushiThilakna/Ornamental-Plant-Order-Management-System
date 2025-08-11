-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2024 at 02:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opoms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `Product_ID` varchar(11) NOT NULL,
  `Size_ID` varchar(11) NOT NULL,
  `Color_ID` varchar(11) NOT NULL,
  `Qty` int(11) NOT NULL,
  `Customer_ID` varchar(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`Product_ID`, `Size_ID`, `Color_ID`, `Qty`, `Customer_ID`) VALUES
('P2', 'S2', 'C7', 1, 'CU2'),
('P4', 'S9', 'C9', 1, 'CU1');

-- --------------------------------------------------------

--
-- Table structure for table `charges`
--

CREATE TABLE `charges` (
  `charge_ID` varchar(10) NOT NULL,
  `Charge_Type` varchar(50) DEFAULT NULL,
  `Location` varchar(60) DEFAULT NULL,
  `Value` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `clr_id` varchar(10) NOT NULL,
  `item_id` varchar(10) NOT NULL,
  `color` varchar(15) NOT NULL,
  `quantity` int(11) NOT NULL,
  `u_price` decimal(10,0) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `clr_id`, `item_id`, `color`, `quantity`, `u_price`, `status`) VALUES
(9, 'CLR1', 'ITM1', '#ef4eb4', 9, 0, 'Active'),
(10, 'CLR2', 'ITM1', '#e33131', 6, 0, 'Active'),
(11, 'CLR3', 'ITM3', '#b90909', 65, 0, 'Active'),
(12, 'CLR4', 'ITM3', '#000000', 655, 0, 'Active'),
(13, 'CLR5', 'ITM4', '#b90909', 65, 0, 'Active'),
(14, 'CLR6', 'ITM4', '#000000', 32, 0, 'Active'),
(15, 'CLR7', 'ITM5', '#771d1d', 65, 0, 'Active'),
(16, 'CLR8', 'ITM5', '#445074', 23, 0, 'Active'),
(17, 'CLR9', 'ITM5', '#972020', 756, 0, 'Active'),
(18, 'CLR10', 'ITM5', '#000000', 45, 0, 'Active'),
(19, 'CLR11', 'ITM6', '#d0169e', 1000, 0, 'Active'),
(20, 'CLR12', 'ITM6', '#fbd68c', 80, 0, 'Active'),
(21, 'CLR13', 'ITM7', '#000000', 324, 0, 'Inactive'),
(22, 'CLR14', 'ITM8', '#000000', 324, 0, 'Inactive'),
(23, 'CLR15', 'ITM8', '#941010', 23432, 0, 'Inactive'),
(24, 'CLR16', 'ITM10', '#a2079c', 78, 0, 'Active'),
(25, 'CLR17', 'ITM10', '#c12a10', 500, 0, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_ID` varchar(10) NOT NULL,
  `FName` varchar(100) NOT NULL,
  `LName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Contact_NO` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Customer_ID`, `FName`, `LName`, `Email`, `Password`, `Address`, `Contact_NO`) VALUES
('CU1', 'Adhil', 'Fairooz', 'adhilzats@gmail.com', '$2y$10$iX9pTOost/O8PP3MqyhHzOKT1CVosLpJac5mxgf8SQ5696MKrBhJe', '71/9, Nawala Road, Nugegoda', '0724118560'),
('CU2', 'Hashini', 'Dharshika', 'smartworktech2000@gmail.com', '$2y$10$ru.9vuNhm4zAw3dZwTAc6u1g5K8LBLaz52ufDpc52Y7SDM6mai7ha', 'kottawa', '0756558560'),
('CU3', 'Hirushi', 'Thilakna', 'hirushithilakna@gmail.com', '$2y$10$cn0pRUJn4GnTEc/hz2vVAeUOt4FYqPWJmyqJajOEm/9DPLXaHbP26', 'ane manda', '1234567890'),
('CU4', 'Yusra', 'Fairooz', 'fairoozyusra@gmail.com', '$2y$10$uwUeAi8ktgaOhHVx.O01Oel5M.ECtn/X4eRGKMejsPbVQcrti5QaS', '71/9,Nawala Road Nugegoda', '0786112487'),
('CU5', 'MG', 'Thilakna', 'mgthilakna@gmail.com', '$2y$10$hlkQyWS5H/yVVjtNWe9B9.vj9ZDrg0lDLsQwlRrP28RxFOZaZA6cS', NULL, NULL),
('CU7', 'Ruvi', 'hh', 'ruvinihimasha@gmail.com', '$2y$10$JjhBzx65TpfzNFR0qABPj.tbkP5POXfBVO/UAyeVVP00hpGhgx2UG', NULL, NULL),
('CU9', 'aaa', 'ddd', 'hirushi@gmail.com', '$2y$10$Q3MH66K7mXzBbJFlrKj.4u96N3xxL1wWVBUB0dORHCQFPFr4PufJm', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_offer`
--

CREATE TABLE `customer_offer` (
  `Promo_ID` varchar(11) NOT NULL,
  `Customer_ID` varchar(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliver_driver`
--

CREATE TABLE `deliver_driver` (
  `Driver_ID` varchar(11) NOT NULL,
  `FName` varchar(50) DEFAULT NULL,
  `LName` varchar(50) DEFAULT NULL,
  `Vehicle_No` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Contact_No` varchar(10) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `deliver_driver`
--

INSERT INTO `deliver_driver` (`Driver_ID`, `FName`, `LName`, `Vehicle_No`, `Email`, `Password`, `Contact_No`, `Status`) VALUES
('D1', 'Lakmal', 'Perera', 'TY-5645', 'mgthilakna@gmail.com', '$2y$10$po2M6Iv8uRzZtxMXzH3tjeGPVCYtPE.sqF3l4DXHAow4NlSfuIwrS', '0755467253', 'Active'),
('D2', 'Chamika', 'Silva', 'AD-4563', 'lahiru@gmail.com', '$2y$10$oq5M5LA6nh2X4f2NUjl/Z.4gEkoDv1uIwRumrfEE0kC9u/p9ucona', '0765423123', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Emp_ID` varchar(11) NOT NULL,
  `FName` varchar(50) NOT NULL,
  `LName` varchar(50) NOT NULL,
  `Job_Role` varchar(100) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Contact_No` varchar(20) DEFAULT NULL,
  `Emp_status` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Emp_ID`, `FName`, `LName`, `Job_Role`, `Email`, `Password`, `Contact_No`, `Emp_status`) VALUES
('E6', 'Hashini', 'Savindi', 'Admin', 'ruvinihimasha@gmail.com', '$2y$10$bq7kJy81VMYz9ifOjZkzZuVpdyOLdGD0hCzbRA8IOxxSiCEcSjV9q', '0726427187', 'Active'),
('E3', 'Hirushi', 'Thilakna', 'Admin', 'hirushithilakna@gmail.com', '$2y$10$2U60JgRcnHNlHj9fHqN/8.LhM41XVoieka3aBKdO5k8R5Ev78k10i', '0755467277', 'Active'),
('E4', 'Avishka', 'De Silva', 'Staff', 'tharukamendis20@gmail.com', '$2y$10$mRjGuCtyVkGHMpWF8bRvzezGRhGx5OksXG1X055yxNP9ba2fZi/0W', '0755467253', 'Active'),
('E5', 'Chamika', 'Silva', 'Staff', 'lahiru@gmail.com', '$2y$10$i.jmxztBwVYOKW/wC6ngJO/wAVMEZH0igJBcN1idjdglwD5yzTaDq', '0765423123', 'Inactive'),
('E7', 'Kumara', 'Soysa', 'Stock_Keeper', 'kasunsampath277@gmail.com', '$2y$10$Of1FvuapbuLO08u.iHLxnuUofvyicQ0se7bZj9bTxMlMfOwV4Zlx6', '0724562346', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `generate_id`
--

CREATE TABLE `generate_id` (
  `ID_Type` varchar(10) NOT NULL,
  `ID_no` int(11) NOT NULL,
  `Id_Prefix` varchar(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `generate_id`
--

INSERT INTO `generate_id` (`ID_Type`, `ID_no`, `Id_Prefix`) VALUES
('product', 5, 'P'),
('size', 12, 'S'),
('color', 10, 'C'),
('mainCat', 25, 'M'),
('subCat', 6, 's'),
('brand', 4, 'B'),
('supplier', 4, 'SU'),
('offers', 12, 'OF'),
('customer', 10, 'CU'),
('charge', 13, 'CH'),
('payment', 45, 'PA'),
('invoice', 45, 'IN'),
('order', 54, 'O'),
('employee', 8, 'E'),
('delivery', 3, 'D');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `Invoice_ID` varchar(11) NOT NULL,
  `sub_Total` decimal(6,2) DEFAULT NULL,
  `Discount` decimal(6,2) DEFAULT NULL,
  `Order_Date` date DEFAULT NULL,
  `Payment_ID` varchar(11) DEFAULT NULL,
  `order_status` varchar(50) DEFAULT NULL,
  `Emp_ID` varchar(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `item_id` varchar(10) NOT NULL,
  `name` varchar(250) NOT NULL,
  `price` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `colors` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `watering` text NOT NULL,
  `light` text NOT NULL,
  `soil` text NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `item_id`, `name`, `price`, `category`, `description`, `colors`, `quantity`, `watering`, `light`, `soil`, `status`) VALUES
(11, 'ITM1', 'Rose', 0, 'M16', 'fgfdgdfg', 'Yes', 0, 'gfghjgjh', 'ghfjhgj', 'ghgjghj', 'Active'),
(12, 'ITM2', 'Rose', 1000, 'M16', 'fgfdgdfg', 'No', 4, 'dgfdg', 'fdgfd', 'gfdgfd', 'Active'),
(13, 'ITM3', 'sdfasdf', 0, 'M18', 'asdfa', 'Yes', 0, 'jgjghjgh', 'dfhfgjh', 'gfhjgfjgh', 'Active'),
(14, 'ITM4', 'Madhawa', 0, 'M18', 'dfsgd', 'Yes', 0, 'fhgjghjhgj', 'hgjgjghj', 'ghjhgjgh', 'Active'),
(15, 'ITM5', 'Madhawa', 0, 'M18', 'dfsgd', 'Yes', 0, 'normal watering ', 'normal sunlight', 'normal soil', 'Active'),
(16, 'ITM6', 'Orchid', 0, 'M16', 'gfdsgsg', 'Yes', 1080, 'normal watering\r\n', 'normal sunlight', 'normal soil', 'Active'),
(17, 'ITM7', 'asdf', 0, 'M18', 'asdfasd', 'Yes', 324, 'normal watering ', 'normal sunlight', 'normal soil', 'Inactive'),
(18, 'ITM8', 'asdfasdfasdf', 0, 'M18', 'asdfasd', 'Yes', 23756, 'dfgsdf', 'sdfgsdfd', 'sdfgsdfg', 'Inactive'),
(19, 'ITM9', 'Rose', 500, 'M16', 'This is flowering plant', 'No', 600, 'normal', 'normal sunlight', 'normal soil', 'Active'),
(20, 'ITM10', 'Orchid', 0, 'M16', 'gfhfghgfh', 'Yes', 578, 'normal watering', 'normal sunlight ', 'wet soil', 'Active'),
(23, 'ITM11', 'Rose', 0, '<br />\r\n<b>Warning</b>:  Undefined variable $main_', 'fgfdgdfg\"', 'No', 0, 'gfghjgjh', 'ghfjhgj', 'ghgjghj\"', 'Active'),
(24, 'ITM12', 'Rose', 0, '<br />\r\n<b>Warning</b>:  Undefined variable $main_', 'fgfdgdfg\"', 'No', 0, 'gfghjgjh', 'ghfjhgj', 'ghgjghj\"', 'Active'),
(25, 'ITM13', 'Rose', 0, '<br />\r\n<b>Warning</b>:  Undefined variable $main_', 'fgfdgdfg\"', 'No', 0, 'gfghjgjh', 'ghfjhgj', 'ghgjghj\"', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `main_category`
--

CREATE TABLE `main_category` (
  `Main_ID` varchar(10) NOT NULL,
  `Category_type` varchar(10) NOT NULL,
  `Cate_Name` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `main_category`
--

INSERT INTO `main_category` (`Main_ID`, `Category_type`, `Cate_Name`) VALUES
('M20', 'Indoor', 'Air Purifying plant'),
('M19', 'Indoor', 'Table plant'),
('M18', 'Indoor', 'Hanging plant'),
('M17', 'Outdoor', 'Landscape Plant'),
('M16', 'Outdoor', 'Flower'),
('M1', 'Indoor', 'Cactus'),
('M22', 'Outdoor', 'Aquatic Plant'),
('M23', 'Outdoor', 'Palm Plant'),
('M24', 'Outdoor', 'Grasses');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `noti_id` varchar(10) NOT NULL,
  `noti_title` varchar(100) NOT NULL,
  `noti_desc` text NOT NULL,
  `noti_img` longblob NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`noti_id`, `noti_title`, `noti_desc`, `noti_img`, `status`) VALUES
('NOT1', 'New-year offers', 'fdgsgd', 0x4e4f54312e6a7067, 'Active'),
('NOT2', 'April season offer', 'hfghfghf', 0x4e4f54322e706e67, 'Inactive'),
('NOT3', 'X-mas offers', 'hghgfhg', 0x4e4f54332e6a7067, 'Active'),
('NOT4', 'Mid-year offers', 'fdhgfh', 0x4e4f54342e6a7067, 'Inactive'),
('NOT5', 'X-mas offers 2', 'fdgdfhg', 0x4e4f54352e6a7067, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `payment_bankdeposit`
--

CREATE TABLE `payment_bankdeposit` (
  `Payment_ID` varchar(11) NOT NULL,
  `postal_charges` decimal(10,2) DEFAULT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  `Delivery_Address` varchar(255) DEFAULT NULL,
  `Contact_NO` varchar(10) DEFAULT NULL,
  `Payment_Proof` longblob DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_cod`
--

CREATE TABLE `payment_cod` (
  `Payment_ID` varchar(11) NOT NULL,
  `Delivery_Fee` decimal(10,2) DEFAULT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  `Delivery_Address` varchar(255) DEFAULT NULL,
  `Driver_ID` varchar(11) DEFAULT NULL,
  `Contact_NO` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_pickup`
--

CREATE TABLE `payment_pickup` (
  `Payment_ID` varchar(10) NOT NULL,
  `Contact_NO` varchar(10) DEFAULT NULL,
  `Total` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `private_offers`
--

CREATE TABLE `private_offers` (
  `Promo_ID` varchar(11) NOT NULL,
  `Offer_Name` varchar(50) NOT NULL,
  `Offer_Title` varchar(100) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Discount_Type` varchar(50) DEFAULT NULL,
  `Discount` decimal(10,2) DEFAULT NULL,
  `TotalBillValue` varchar(10) DEFAULT NULL,
  `Valid_From_Date` date DEFAULT NULL,
  `Valid_To_Date` date DEFAULT NULL,
  `claimed_Status` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `public_offers`
--

CREATE TABLE `public_offers` (
  `Promo_ID` varchar(11) NOT NULL,
  `Offer_Name` varchar(50) NOT NULL,
  `Offer_Title` varchar(100) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Discount_Type` varchar(50) DEFAULT NULL,
  `Discount` decimal(10,2) DEFAULT NULL,
  `TotalBillValue` varchar(10) DEFAULT NULL,
  `Valid_From_Date` date DEFAULT NULL,
  `Valid_To_Date` date DEFAULT NULL,
  `IMG` longblob DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD KEY `Product_ID` (`Product_ID`),
  ADD KEY `Size_ID` (`Size_ID`),
  ADD KEY `Color_ID` (`Color_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`);

--
-- Indexes for table `charges`
--
ALTER TABLE `charges`
  ADD PRIMARY KEY (`charge_ID`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_ID`);

--
-- Indexes for table `customer_offer`
--
ALTER TABLE `customer_offer`
  ADD PRIMARY KEY (`Promo_ID`,`Customer_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`);

--
-- Indexes for table `deliver_driver`
--
ALTER TABLE `deliver_driver`
  ADD PRIMARY KEY (`Driver_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Emp_ID`);

--
-- Indexes for table `generate_id`
--
ALTER TABLE `generate_id`
  ADD PRIMARY KEY (`ID_Type`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`Invoice_ID`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `main_category`
--
ALTER TABLE `main_category`
  ADD PRIMARY KEY (`Main_ID`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`noti_id`);

--
-- Indexes for table `payment_bankdeposit`
--
ALTER TABLE `payment_bankdeposit`
  ADD PRIMARY KEY (`Payment_ID`);

--
-- Indexes for table `payment_cod`
--
ALTER TABLE `payment_cod`
  ADD PRIMARY KEY (`Payment_ID`),
  ADD KEY `Driver_ID` (`Driver_ID`);

--
-- Indexes for table `payment_pickup`
--
ALTER TABLE `payment_pickup`
  ADD PRIMARY KEY (`Payment_ID`);

--
-- Indexes for table `private_offers`
--
ALTER TABLE `private_offers`
  ADD PRIMARY KEY (`Promo_ID`);

--
-- Indexes for table `public_offers`
--
ALTER TABLE `public_offers`
  ADD PRIMARY KEY (`Promo_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
