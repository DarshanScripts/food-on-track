-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2024 at 09:21 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodontrack`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aId` int(11) NOT NULL,
  `firstName` varchar(15) NOT NULL,
  `lastName` varchar(15) NOT NULL,
  `email` varchar(35) NOT NULL,
  `password` char(32) DEFAULT NULL,
  `creationDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aId`, `firstName`, `lastName`, `email`, `password`, `creationDate`) VALUES
(1, 'Hiyan', 'Jain', 'hiyanjain1@gmail.com', '1e6fb03267c9367717e1f0e043d1dad5', '2024-06-02 08:58:23');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cId` int(11) NOT NULL,
  `mId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cId` int(11) NOT NULL,
  `firstName` varchar(15) NOT NULL,
  `lastName` varchar(15) NOT NULL,
  `email` varchar(35) NOT NULL,
  `password` char(32) DEFAULT NULL,
  `creationDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cId`, `firstName`, `lastName`, `email`, `password`, `creationDate`) VALUES
(1, 'Darshan', 'Shah', 'darshanshah@gmail.com', '450f33555c0618bdc305e49f5d45afff', '2024-06-02 09:08:24');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `mId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `price` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`mId`, `name`, `type`, `description`, `price`, `image`) VALUES
(1, 'Samosa', 'Veg', 'Samosa is a fried or baked pastry with a savoury filling, such as spiced potatoes, onions, peas, cheese, meat, or lentils.', '15', 'samosa.jpg'),
(2, 'Jalebi', 'Veg', 'Jalebi is made by deep-frying maida flour batter in pretzel or circular shapes, which are then soaked in sugar syrup.', '49', 'jalebi.jpg'),
(3, 'Naan', 'Veg', 'Naan is a leavened, oven-baked flatbread.', '12', 'naan.jpg'),
(4, 'Paneer Bhurji', 'Veg', 'Scrambled Indian cottage cheese with onion, tomatoes and spices.', '50', 'paneer-bhurji.jpg'),
(5, 'Pulav', 'Veg', 'Vegetable Pulao (Veg Pulav) is a spicy rice dish prepared by cooking rice with various vegetables and spices.', '60', 'pulav.jpg'),
(6, 'Biryani', 'Non Veg', 'Chicken Biryani is a delicious savory rice dish loaded with spicy marinated chicken, caramelized onions, and flavorful saffron rice.', '100', 'biryani.jpg'),
(7, 'Fish', 'Non Veg', 'Fish fry is a meal containing battered or breaded fried fish.', '100', 'fish.jpg'),
(8, 'Butter Chicken', 'Non Veg', 'Butter chicken or makhan murg is a dish, originating in the Indian subcontinent, of chicken in a mildly spiced tomato sauce.', '99', 'Butter-Chicken.jpg'),
(10, 'Gulab Jamun', 'Veg', 'It is a sweet.', '10', 'gulab_jamun.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` int(11) NOT NULL,
  `cId` int(11) DEFAULT NULL,
  `mId` int(11) DEFAULT NULL,
  `orderDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `cId`, `mId`, `orderDate`) VALUES
(4, 1, 2, '2024-06-02 07:20:06'),
(5, 1, 3, '2024-06-02 07:20:06'),
(6, 1, 4, '2024-06-02 07:20:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aId`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cId`,`mId`),
  ADD KEY `mId` (`mId`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cId`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`mId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `cId` (`cId`),
  ADD KEY `mId` (`mId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `aId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `mId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`cId`) REFERENCES `customer` (`cId`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`mId`) REFERENCES `menu` (`mId`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cId`) REFERENCES `customer` (`cId`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`mId`) REFERENCES `menu` (`mId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
