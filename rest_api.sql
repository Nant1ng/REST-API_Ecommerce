-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2021 at 11:00 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rest_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` bigint(20) NOT NULL COMMENT 'Cart ID',
  `productid` bigint(20) NOT NULL COMMENT 'Product ID',
  `userid` bigint(20) NOT NULL COMMENT 'User ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` bigint(20) NOT NULL COMMENT 'Products',
  `product_title` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Product Title',
  `description` mediumtext CHARACTER SET utf8 NOT NULL COMMENT 'Description of Product',
  `price` int(11) NOT NULL COMMENT 'Price of Product',
  `stock` enum('Y','N') CHARACTER SET utf8 NOT NULL COMMENT 'Product in Stock or not',
  `img_url` varchar(1000) CHARACTER SET utf8 NOT NULL COMMENT 'Product Image Url'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `product_title`, `description`, `price`, `stock`, `img_url`) VALUES
(1, 'NMD_R1 V2 SHOES', 'FUTURISTISKA SNEAKERS MED EN NY, FRÄSCH LOOK.', 1499, 'Y', 'https://assets.adidas.com/images/h_840,f_auto,q_auto:sensitive,fl_lossy/d54efdd118f24457b654aaf00110b4d5_9366/NMD_R1_V2_Shoes_Svart_FV9021_01_standard.jpg'),
(2, 'R.Y.V. GRAPHIC HOODIE', 'EN MJUK HOODIE MED KONSTTRYCK AV PATRICK KYLE', 899, 'Y', 'https://assets.adidas.com/images/h_840,f_auto,q_auto:sensitive,fl_lossy/56d3b756afa14aa6b8f0ac400117d5be_9366/R.Y.V._Graphic_Hoodie_Svart_GN3345_01_laydown.jpg'),
(3, 'ADICOLOR CLASSICS PRIMEBLUE SST TRACK PANTS', 'DE INGÅR I DET KLASSISKA TRÄNINGSSTÄLLET, MED ALL KOMFORT SOM HÖR TILL.', 629, 'Y', 'https://assets.adidas.com/images/w_600,f_auto,q_auto/80f5664eaf44437589baab8a00fea2a5_9366/Adicolor_Classics_Primeblue_SST_Track_Pants_Svart_GF0210.jpg'),
(4, 'Nike Air Force 1', 'Känslan lever vidare i Nike Air Force 1, den klassiska basketskon', 1149, 'Y', 'https://static.nike.com/a/images/t_PDP_864_v1/f_auto,b_rgb:f5f5f5/f094af40-f82f-4fb9-a246-e031bf6fc411/sko-air-force-1-07-ZWJrWC.png');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` bigint(20) NOT NULL COMMENT 'Session ID',
  `userid` bigint(20) NOT NULL COMMENT 'User ID',
  `accesstoken` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Access Token',
  `accesstokenexpiry` datetime NOT NULL COMMENT 'Access Token Expiry Date/Time',
  `refreshtoken` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Refresh Token',
  `refreshtokenexpiry` datetime NOT NULL COMMENT 'Refresh Token Expiry Date/Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL COMMENT 'User ID',
  `fullname` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Users Full Name',
  `email` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Users Email',
  `username` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Users Username',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Users Password',
  `useractive` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'Y' COMMENT 'Is User Active',
  `loginattempts` int(11) NOT NULL DEFAULT 0 COMMENT 'Attempts to log in',
  `role` varchar(45) NOT NULL DEFAULT 'user' COMMENT 'Roles for users and admim'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `username`, `password`, `useractive`, `loginattempts`, `role`) VALUES
(1, 'Admin', 'admin@admin.admin', 'Admin', '$2y$10$NPqP2nzKSZFAd17a.91cDOnwS4GE12MuymWdFLhNeTIZPWqHgq6..', 'Y', 0, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productcartid_fk` (`productid`),
  ADD KEY `usercartid_fk` (`userid`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accesstoken` (`accesstoken`),
  ADD UNIQUE KEY `refreshtoken` (`refreshtoken`),
  ADD KEY `sessionuserid_fk` (`userid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Cart ID';

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Products', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Session ID';

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'User ID', AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `productcartid_fk` FOREIGN KEY (`productid`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `usercartid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessionuserid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
