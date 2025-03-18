-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 26, 2024 at 03:51 PM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gotogro_mrms`
--

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `memberID` int NOT NULL,
  `fname` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('male','female','non-binary') COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `phno` char(12) COLLATE utf8mb4_general_ci NOT NULL,
  `registration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`memberID`, `fname`, `lname`, `dob`, `gender`, `address`, `email`, `phno`, `registration_date`) VALUES
(1, 'John', 'Deen', '1991-09-15', 'male', '123 Main St', 'johndean@gmail.com', '123456789012', '2024-10-03'),
(2, 'Jane', 'Smith', '1985-11-30', 'female', '456 Oak St', 'janesmith@example.com', '123456789013', '2024-02-12'),
(3, 'Alex', 'Taylor', '1995-07-07', 'non-binary', '789 Maple St', 'alextaylor@example.com', '123456789014', '2024-03-01'),
(4, 'Michael', 'Johnson', '1980-12-22', 'male', '234 Pine St', 'michaeljohnson@example.com', '123456789015', '2024-01-20'),
(5, 'Sarah', 'Williams', '1992-08-14', 'female', '876 Cedar St', 'sarahwilliams@example.com', '123456789016', '2024-02-25'),
(6, 'David', 'Brown', '1993-03-30', 'male', '321 Spruce St', 'davidbrown@example.com', '123456789017', '2024-03-15'),
(7, 'Emily', 'Davis', '1991-07-18', 'female', '432 Birch St', 'emilydavis@example.com', '123456789018', '2024-04-10'),
(8, 'Joshua', 'Millerm', '1994-10-05', 'male', '543 Ash St', 'joshuamiller@example.com', '123456789019', '2024-04-20'),
(9, 'Olivia', 'Grace', '1989-02-11', 'female', '654 Redwood Str', 'oliviagarcia@example.com', '123456789020', '2024-05-05'),
(10, 'Sophia', 'Martinez', '1984-11-01', 'female', '765 Walnut St', 'sophiamartinez@example.com', '123456789021', '2024-05-15'),
(11, 'Liam', 'William', '1990-02-20', 'male', '987 Oak St', 'liamwilson@example.com', '123456789022', '2024-06-01'),
(12, 'Ava', 'Anderson', '1993-09-12', 'female', '654 Birch St', 'avaanderson@example.com', '123456789023', '2024-06-15'),
(13, 'Noah', 'Thomas', '1988-04-25', 'male', '321 Maple St', 'noahthomas@example.com', '123456789024', '2024-07-10'),
(14, 'Isabella', 'Jackson', '1991-12-30', 'female', '876 Pine St', 'isabellajackson@example.com', '123456789025', '2024-07-20'),
(15, 'Ethan', 'White', '1992-05-14', 'male', '543 Cedar St', 'ethanwhite@example.com', '123456789026', '2024-08-05'),
(16, 'Mia', 'Hernandez', '1994-03-11', 'female', '123 Elm St', 'miahernandez@example.com', '123456789027', '2024-08-10'),
(17, 'Lucas', 'Martinez', '1987-08-22', 'male', '234 Maple Ave', 'lucasmartinez@example.com', '123456789028', '2024-08-15'),
(18, 'Charlotte', 'Lopez', '1996-01-30', 'female', '456 Oak Dr', 'charlottelopez@example.com', '123456789029', '2024-08-20'),
(19, 'James', 'Gonzalez', '1983-07-05', 'male', '789 Pine Ln', 'jamesgonzalez@example.com', '123456789030', '2024-09-01'),
(20, 'Amelia', 'Wilson', '1992-09-14', 'female', '321 Cedar Ct', 'ameliawilson@example.com', '123456789031', '2024-09-10'),
(21, 'Justin', 'Ho', '2003-06-17', 'male', 'No 44 Jalan Puteri', 'justiny@gmail.com', '0123458937', '2024-10-20'),
(22, 'Lalaca', 'Kobayashi', '1999-09-26', 'female', '456 Oak St', 'lalaca@example.com', '987654321098', '2024-10-20'),
(23, 'Xiao Jun', 'Lim', '2003-02-08', 'female', 'No 37 Jalan Nus, Sg', 'junnie@hehe.com', '0198472367', '2024-10-23'),
(24, 'Michelle ', 'Low', '2003-03-20', 'female', 'No 5 Jalan Camar, Kd', 'ahrong@hehe.com', '0134758314', '2024-10-23'),
(28, 'Nicholas', 'Tang', '2005-08-25', 'male', 'No 420, Jalan Sana ', 'tangwh@yipee.com', '0113339482', '2024-10-24'),
(31, 'Xuanny', 'Chan', '2004-09-21', 'female', 'No 123 Jalan SS13', 'xuanny123@hmm.com', '12390844', '2024-10-24'),
(32, 'Frank', 'Ocean', '1987-10-28', 'male', 'No 8, Jalan Pink, White Str', 'freakocean@blonded.com', '1234829372', '2024-10-24'),
(33, 'Lana ', 'Del Rey ', '1985-06-21', 'female', 'No 23, Jln 3/29', 'cinnamongirl@ldr.com', '019673275', '2024-10-24'),
(34, 'Emmanuelle', 'Proulx', '2000-08-04', 'non-binary', 'No 15, Organon Street, Montreal', 'menitrust@beluga.com', '34812749', '2024-10-24'),
(35, 'Mac', 'Miller', '1992-01-19', 'male', 'No 9, Jalan Renang', 'macmiller@circles.com', '120972370', '2024-10-24'),
(36, 'Ariana ', 'Grande', '1993-06-26', 'female', '23, Jln 8/9', 'moonlight@hehe.com', '349172242', '2024-10-24'),
(37, 'Wendy', 'Wander', '2000-09-08', 'non-binary', '99, Jln SS15', 'springspring@hehe.com', '3458276493', '2024-10-24'),
(39, 'Gaby', 'Chan', '2000-01-12', 'female', 'No 9, Jln Sana', 'gabychan@hehe.com', '982349237', '2024-10-22'),
(40, 'Phoebe ', 'Bridgers', '1994-08-17', 'female', 'No 9, Jln Mana', 'motionsickness@hehe.com', '39812749', '2024-10-24'),
(42, 'Jerusha ', 'Emmanuelle Dass', '2002-08-22', 'female', 'No 7 Jalan Sini', 'ruru@hehe.com', '893439471', '2024-10-28'),
(43, 'James', 'Bond', '1999-03-31', 'male', 'No 9 , Jalan Mana', 'james@yipee.com', '238974628', '2024-10-28'),
(44, 'Asap', 'Rocky', '2000-01-01', 'male', 'No 89, Jalan Where', 'asaprockie@yipee.com', '98720752', '2024-10-28'),
(45, 'Doja ', 'Cat', '2003-02-09', 'female', 'No 123, Jalan Ini', 'meow@hehe.com', '123498766', '2024-10-28'),
(46, 'Ashley ', 'Tang', '2003-09-09', 'female', 'No 9, Jalan Ini Lah', 'ashwee@hehe.com', '01123456789', '2024-10-28'),
(47, 'Yung Hong', 'Ho', '2003-06-17', 'male', 'No 8, Jalan Ash', 'hyh@yipee.com', '123456789', '2024-10-24');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productID` int NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `category` enum('Dairy','Vegetable','Fruits','Beverage','Pastry','Meat','Personal Care','Snacks','Grains','Household Supplies') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inv_qty` int DEFAULT '0',
  `last_restock_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `product_name`, `product_price`, `category`, `inv_qty`, `last_restock_date`) VALUES
(1, 'Apples', 2.50, 'Fruits', 18, '2024-10-21'),
(2, 'Bananas', 1.20, 'Fruits', 49, '2024-08-06'),
(3, 'Bread', 3.50, 'Pastry', 56, '2024-10-01'),
(4, 'Milk', 2.00, 'Dairy', 36, '2024-10-23'),
(5, 'Eggs', 3.20, 'Dairy', 58, '2024-08-25'),
(6, 'Chicken', 7.80, 'Meat', 68, '2024-11-06'),
(7, 'Beef', 9.50, 'Meat', 83, '2024-10-12'),
(8, 'Pasta', 1.80, 'Grains', 61, '2024-09-30'),
(9, 'Rice', 4.00, 'Grains', 57, '2024-10-10'),
(10, 'Cereal', 5.00, 'Grains', 36, '2024-10-08'),
(11, 'Cheese', 4.50, 'Dairy', 54, '2024-10-10'),
(12, 'Orange Juice', 3.25, 'Beverage', 13, '2024-04-18'),
(13, 'Tomatoes', 1.80, 'Vegetable', 27, '2024-08-23'),
(14, 'Potatoes', 2.50, 'Vegetable', 7, '2024-07-10'),
(15, 'Shampoo', 6.80, 'Personal Care', 59, '2024-09-30'),
(16, 'Toothpaste', 3.10, 'Personal Care', 29, '2024-08-14'),
(17, 'Chocolate', 2.90, 'Snacks', 7, '2024-06-09'),
(19, 'Coffee', 8.00, 'Beverage', 71, '2024-10-18'),
(20, 'Tea', 6.00, 'Beverage', 49, '2024-10-22'),
(21, 'Flour', 4.80, 'Grains', 55, '2024-10-21'),
(22, 'Bak Choy', 3.50, 'Vegetable', 23, '2024-10-24'),
(25, 'Kangkung', 4.20, 'Vegetable', 27, '2024-10-24'),
(26, 'Croissant ', 5.30, 'Pastry', 28, '2024-11-15'),
(27, 'Muruku', 7.80, 'Snacks', 26, '2024-10-23'),
(28, 'White Chocolate', 2.90, 'Snacks', 18, '2024-10-04'),
(29, 'Corn', 2.80, 'Vegetable', 25, '2024-09-03'),
(30, 'Instant Oat', 9.80, 'Grains', 43, '2024-09-03'),
(31, 'Almond', 13.40, 'Grains', 35, '2024-09-09'),
(32, 'Apple Pie', 5.60, 'Pastry', 43, '2024-11-14'),
(33, 'Hair Conditioner ', 7.90, 'Personal Care', 65, '2024-02-10'),
(34, 'Carrot', 4.50, 'Vegetable', 16, '2024-10-15');

-- --------------------------------------------------------

--
-- Table structure for table `sales_transactions`
--

CREATE TABLE `sales_transactions` (
  `transactionID` int NOT NULL,
  `memberID` int DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `purchase_date` date NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_transactions`
--

INSERT INTO `sales_transactions` (`transactionID`, `memberID`, `total_price`, `purchase_date`, `payment_method`) VALUES
(1, 1, 27.80, '2024-04-20', 'credit'),
(2, 2, 14.00, '2024-04-22', 'cash'),
(3, 3, 45.30, '2024-05-05', 'debit'),
(4, 4, 19.60, '2024-05-15', 'credit'),
(5, 5, 16.00, '2024-06-01', 'cash'),
(6, 1, 50.60, '2024-06-10', 'debit'),
(7, 3, 12.00, '2024-06-15', 'credit'),
(8, 2, 67.50, '2024-06-20', 'cash'),
(9, 4, 53.90, '2024-07-01', 'credit'),
(10, 5, 37.00, '2024-07-10', 'debit'),
(11, 2, 35.65, '2024-07-15', 'cash'),
(12, 3, 46.80, '2024-08-01', 'credit'),
(13, 4, 28.25, '2024-08-05', 'debit'),
(14, 5, 40.40, '2024-08-12', 'cash'),
(15, 1, 27.60, '2024-08-20', 'credit'),
(16, 2, 52.90, '2024-09-01', 'debit'),
(17, 3, 48.60, '2024-09-05', 'credit'),
(18, 4, 36.15, '2024-09-10', 'cash'),
(19, 5, 70.35, '2024-09-15', 'credit'),
(20, 1, 44.90, '2024-09-20', 'debit'),
(21, 6, 12.30, '2024-09-25', 'credit'),
(22, 7, 17.00, '2024-09-30', 'cash'),
(23, 8, 14.20, '2024-10-05', 'debit'),
(24, 9, 43.40, '2024-10-10', 'credit'),
(25, 10, 14.00, '2024-10-12', 'cash'),
(26, 11, 11.00, '2024-10-13', 'debit'),
(27, 12, 6.10, '2024-10-14', 'credit'),
(28, 13, 26.60, '2024-10-15', 'cash'),
(29, 14, 14.50, '2024-10-16', 'debit'),
(30, 15, 40.00, '2024-10-17', 'credit'),
(31, 16, 19.50, '2024-10-17', 'cash'),
(32, 17, 13.80, '2024-10-17', 'debit'),
(33, 18, 51.00, '2024-10-17', 'credit'),
(34, 19, 9.40, '2024-10-17', 'cash'),
(35, 20, 29.00, '2024-10-17', 'debit'),
(36, 1, 2.50, '2024-10-20', 'credit'),
(37, 4, 3.50, '2024-10-19', 'credit'),
(39, 24, 9.50, '2024-10-20', 'debit'),
(40, 24, 39.00, '2024-10-22', 'cash'),
(41, 20, 9.50, '2024-10-22', 'credit'),
(43, 35, 26.50, '2024-10-21', 'debit'),
(44, 20, 3.60, '2024-10-22', 'credit'),
(46, 40, 9.50, '2024-09-10', 'credit'),
(47, 40, 9.50, '2024-09-10', 'credit'),
(49, 37, 7.80, '2024-10-20', 'credit'),
(50, 39, 24.00, '2024-10-22', 'credit'),
(51, 35, 27.20, '2024-10-20', 'credit'),
(52, 35, 16.00, '2024-10-08', 'debit'),
(53, 40, 14.60, '2024-10-09', 'cash'),
(55, 28, 9.30, '2024-10-09', 'debit'),
(57, 39, 2.40, '2024-10-19', 'debit'),
(58, 24, 35.50, '2024-10-13', 'credit'),
(59, 31, 31.60, '2024-10-12', 'cash'),
(60, 1, 2.50, '2024-10-20', 'credit'),
(61, 40, 2.50, '2024-01-01', 'credit'),
(62, 40, 8.20, '2024-10-10', 'debit'),
(64, 39, 27.60, '2024-10-27', 'credit'),
(65, 34, 40.80, '2024-10-20', 'debit'),
(67, 7, 15.80, '2024-10-27', 'debit'),
(68, 47, 24.40, '2024-11-14', 'credit'),
(69, 16, 34.80, '2024-11-08', 'debit'),
(70, 45, 20.80, '2024-10-29', 'debit'),
(71, 32, 32.90, '2024-11-06', 'cash'),
(72, 33, 37.60, '2024-11-14', 'cash'),
(74, 10, 98.50, '2024-11-26', 'credit'),
(75, 40, 15.25, '2024-11-26', 'cash'),
(76, 33, 85.25, '2024-11-26', 'credit');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffID` int NOT NULL,
  `fname` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_picture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffID`, `fname`, `lname`, `dob`, `email`, `phone`, `password_hash`, `address`, `created_at`, `profile_picture`) VALUES
(1, 'Alice', 'Smith', '1990-04-15', 'alice.smith@example.com', '555-0100', '$2y$10$e4uZt4TdszOPuF1eJ48l3uOP/hX1b7fuTEOrQpDi3U7H8HoK9cNKa', '123 Elm St, Springfield, IL', '2024-11-02 19:05:37', NULL),
(2, 'Bob', 'Johnson', '1985-06-20', 'bob.johnson@example.com', '555-0101', '$2y$10$g4AdVj4m8cfWg4/9VuO1F.SiIMj3X/PrSl09m6EBhgvE5TrNeBAei', '456 Oak St, Springfield, IL', '2024-11-02 19:05:37', NULL),
(3, 'Catherine', 'Williams', '1992-08-12', 'catherine.williams@example.com', '555-0102', '$2y$10$w5C8pNXkm5D7FkCRGSp6l.OZzOBG8cKmHkFVlG/wQRYZsZlD/WIuG', '789 Pine St, Springfield, IL', '2024-11-02 19:05:37', NULL),
(4, 'David', 'Brown', '1988-03-30', 'david.brown@example.com', '555-0103', '$2y$10$W1m3z9w.P/u5/9O4fDCRpuM0vTAn0NtvzjBh8gjbRzff2QHc9YYXe', '321 Maple St, Springfield, IL', '2024-11-02 19:05:37', NULL),
(5, 'Emily', 'Jones', '1995-11-05', 'emily.jones@example.com', '555-0104', '$2y$10$7PhIqFjF4EDxka3JYpN1UeHJ8n8FWUmioRpHlJ5nYfFdcqJvaYh8y', '654 Birch St, Springfield, IL', '2024-11-02 19:05:37', NULL),
(6, 'Frank', 'Garcia', '1980-02-18', 'frank.garcia@example.com', '555-0105', '$2y$10$A8z2FS1g3OAH7ygh0W/0TeJ07skHoTNC1N1Zj6MEbAASivWbqVEay', '987 Cedar St, Springfield, IL', '2024-11-02 19:05:37', NULL),
(7, 'Grace', 'Martinez', '1994-09-25', 'grace.martinez@example.com', '555-0106', '$2y$10$htyOgK5T3h2IQ4c2Qz7wQuqUvYz8E2tGd/fI5V0u03.DdF9dN5Lni', '123 Spruce St, Springfield, IL', '2024-11-02 19:05:37', NULL),
(8, 'Henry', 'Davis', '1982-01-14', 'henry.davis@example.com', '555-0107', '$2y$10$K4.eJt5b2OVi0GbS9DRh1eRaIuI5m2j5VHTtiDQ/vzYP5jFY8y5.e', '456 Willow St, Springfield, IL', '2024-11-02 19:05:37', NULL),
(9, 'Irene', 'Lopez', '1986-12-08', 'irene.lopez@example.com', '555-0108', '$2y$10$4QZ3uXhbR85jQxlFWKCT9u91GzEVs3Wv8Y4nTSuUqLhujUs7sc6M2', '789 Fir St, Springfield, IL', '2024-11-02 19:05:37', NULL),
(10, 'Jack', 'Wilson', '1993-07-17', 'jack.wilson@example.com', '555-0109', '$2y$10$Zc9tCL2N7I5Di/zy.ACwBe9Z7X/zvU2t7Q.Y2uQyeu1CEJ0O8WvTe', '321 Larch St, Springfield, IL', '2024-11-02 19:05:37', NULL),
(11, 'Ashley ', 'Tang', '2000-09-09', 'ashwee@hehe.com', '012398428', '$2y$10$5WIyJaV9AmwAwquM7i.jQe0a3B84UG6m7MRpfPQlCjlvQoj95lM8K', '8, Jalan Sana', '2024-11-07 12:53:16', 'staff_profile_picture/6736c49ee357a_6736a50fe94e4_default.jpg'),
(12, 'Justin', 'Ho', '2000-06-17', 'hyh@hehe.com', '0123456788', '$2y$10$pxOQgv8xXb8OYbLf15M9q.1gMSfeTJxYvv2iV6eL1W.zCSbxxL8Lu', 'No 44 Jalan There', '2024-11-13 19:44:26', 'staff_profile_picture/67357d928627b_673502ddd15c5_jb.jpg'),
(13, 'Gary', 'Maddeline', '1999-03-04', 'gary@hehe.com', '0123456712', '$2y$10$hDpF2x1AI6rpx3UNhvFljuU7NeuS2SxRMbnrGKEOl9Y2HllHYCqRa', 'No 9 Jalan There Lah', '2024-11-14 05:30:22', 'staff_profile_picture/67358b1df0c36_mads.jpg'),
(14, 'Admin', 'admin', '1999-01-01', 'admin@gmail.com', '0123456789', '$2y$10$WUb4roTevitR20ykKmcNdOqKgui.BTqT9kduDX7jITMGSAbrsScCe', 'No Address', '2024-11-26 09:48:12', 'staff_profile_picture/default.jpg'),
(15, 'Maddie', 'Pan', '1998-10-22', 'maddiepan@gmail.com', '014129301', '$2y$10$a2uZ6x1r6iyCbTw4DE/CAeP34QfdgZ9cCJgd2mS5F1F0qQwy4Iv3y', 'INTI Subang', '2024-11-26 13:54:23', 'staff_profile_picture/6745d501f1b66_IMG_8804.jpeg'),
(16, 'Sora', 'K', '2000-01-23', 'sorak@gmail.com', '019312313', '$2y$10$bZi43flVWBYPvvIc4WaV1u12ri2F6YnJ.I/oC0j8xg.cTl0hDlkpm', 'Kota Damansara', '2024-11-26 14:13:51', 'staff_profile_picture/6745dca553582_1616085.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_item`
--

CREATE TABLE `transaction_item` (
  `transactionID` int DEFAULT NULL,
  `productID` int DEFAULT NULL,
  `quantity_sold` int NOT NULL,
  `subtotal_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_item`
--

INSERT INTO `transaction_item` (`transactionID`, `productID`, `quantity_sold`, `subtotal_price`) VALUES
(1, 1, 5, 12.50),
(1, 3, 1, 3.50),
(1, 6, 1, 7.80),
(1, 9, 1, 4.00),
(2, 2, 10, 12.00),
(2, 4, 1, 2.00),
(3, 7, 3, 28.50),
(3, 1, 2, 5.00),
(3, 9, 1, 4.00),
(3, 6, 1, 7.80),
(4, 5, 3, 9.60),
(4, 10, 2, 10.00),
(5, 3, 4, 14.00),
(5, 4, 1, 2.00),
(6, 1, 6, 15.00),
(6, 6, 2, 15.60),
(6, 10, 4, 20.00),
(7, 2, 10, 12.00),
(8, 7, 5, 47.50),
(8, 4, 10, 20.00),
(9, 3, 3, 10.50),
(9, 6, 3, 23.40),
(9, 9, 5, 20.00),
(10, 2, 12, 14.40),
(10, 4, 4, 8.00),
(10, 1, 2, 5.00),
(10, 5, 3, 9.60),
(11, 13, 5, 9.00),
(11, 14, 3, 7.50),
(11, 12, 5, 16.25),
(11, 17, 1, 2.90),
(12, 6, 2, 15.60),
(12, 7, 2, 19.00),
(12, 8, 3, 5.40),
(12, 15, 1, 6.80),
(13, 3, 3, 10.50),
(13, 17, 5, 14.50),
(13, 12, 1, 3.25),
(14, 4, 2, 4.00),
(14, 13, 6, 10.80),
(14, 15, 2, 13.60),
(14, 20, 2, 12.00),
(15, 2, 3, 3.60),
(15, 10, 2, 10.00),
(15, 19, 1, 8.00),
(15, 20, 1, 6.00),
(16, 11, 5, 22.50),
(16, 13, 8, 14.40),
(16, 9, 2, 8.00),
(16, 19, 1, 8.00),
(17, 2, 10, 12.00),
(17, 16, 4, 12.40),
(17, 17, 6, 17.40),
(17, 15, 1, 6.80),
(18, 12, 5, 16.25),
(18, 8, 5, 9.00),
(18, 17, 1, 2.90),
(18, 19, 1, 8.00),
(19, 14, 10, 25.00),
(19, 12, 5, 16.25),
(19, 3, 5, 17.50),
(19, 17, 4, 11.60),
(20, 1, 5, 12.50),
(20, 13, 6, 10.80),
(20, 15, 2, 13.60),
(20, 19, 1, 8.00),
(21, 1, 3, 7.50),
(21, 2, 4, 4.80),
(22, 3, 2, 7.00),
(22, 4, 5, 10.00),
(23, 5, 2, 6.40),
(23, 6, 1, 7.80),
(24, 7, 4, 38.00),
(24, 8, 3, 5.40),
(25, 9, 1, 4.00),
(25, 10, 2, 10.00),
(26, 11, 1, 4.50),
(26, 12, 2, 6.50),
(27, 13, 2, 3.60),
(27, 14, 1, 2.50),
(28, 15, 3, 20.40),
(28, 16, 2, 6.20),
(29, 17, 5, 14.50),
(30, 19, 2, 16.00),
(30, 20, 4, 24.00),
(31, 1, 5, 12.50),
(31, 3, 2, 7.00),
(32, 4, 3, 6.00),
(32, 6, 1, 7.80),
(33, 5, 10, 32.00),
(33, 7, 2, 19.00),
(34, 8, 3, 5.40),
(34, 9, 1, 4.00),
(35, 10, 4, 20.00),
(35, 11, 2, 9.00),
(36, 1, 1, 2.50),
(37, 3, 1, 3.50),
(39, 7, 1, 9.50),
(40, 7, 2, 19.00),
(40, 19, 2, 16.00),
(40, 9, 1, 4.00),
(41, 16, 1, 3.10),
(41, 5, 2, 6.40),
(43, 16, 1, 3.10),
(43, 5, 2, 6.40),
(43, 22, 2, 7.00),
(43, 10, 2, 10.00),
(44, 13, 2, 3.60),
(46, 7, 1, 9.50),
(47, 7, 1, 9.50),
(49, 6, 1, 7.80),
(50, 20, 4, 24.00),
(51, 4, 2, 4.00),
(51, 25, 3, 12.60),
(51, 26, 2, 10.60),
(52, 19, 2, 16.00),
(53, 5, 3, 9.60),
(53, 14, 2, 5.00),
(55, 16, 3, 9.30),
(57, 2, 2, 2.40),
(58, 26, 2, 10.60),
(58, 21, 1, 4.80),
(58, 27, 2, 15.60),
(58, 11, 1, 4.50),
(59, 7, 2, 19.00),
(59, 15, 1, 6.80),
(59, 28, 2, 5.80),
(60, 1, 1, 2.50),
(61, 1, 1, 2.50),
(62, 2, 1, 1.20),
(62, 3, 2, 7.00),
(64, 8, 2, 3.60),
(64, 19, 3, 24.00),
(65, 5, 2, 6.40),
(65, 17, 3, 8.70),
(65, 21, 4, 19.20),
(65, 12, 2, 6.50),
(67, 3, 4, 14.00),
(67, 8, 1, 1.80),
(68, 3, 2, 7.00),
(68, 5, 3, 9.60),
(68, 6, 1, 7.80),
(69, 9, 2, 8.00),
(69, 31, 2, 26.80),
(70, 4, 2, 4.00),
(70, 32, 3, 16.80),
(71, 8, 2, 3.60),
(71, 27, 2, 15.60),
(71, 17, 3, 8.70),
(71, 1, 2, 5.00),
(72, 30, 2, 19.60),
(72, 34, 4, 18.00),
(74, 10, 12, 60.00),
(74, 3, 11, 38.50),
(75, 12, 1, 3.25),
(75, 20, 2, 12.00),
(76, 5, 10, 32.00),
(76, 12, 1, 3.25),
(76, 1, 20, 50.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`memberID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `sales_transactions`
--
ALTER TABLE `sales_transactions`
  ADD PRIMARY KEY (`transactionID`),
  ADD KEY `memberID` (`memberID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `transaction_item`
--
ALTER TABLE `transaction_item`
  ADD KEY `transactionID` (`transactionID`),
  ADD KEY `productID` (`productID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `memberID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `sales_transactions`
--
ALTER TABLE `sales_transactions`
  MODIFY `transactionID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staffID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales_transactions`
--
ALTER TABLE `sales_transactions`
  ADD CONSTRAINT `sales_transactions_ibfk_1` FOREIGN KEY (`memberID`) REFERENCES `member` (`memberID`);

--
-- Constraints for table `transaction_item`
--
ALTER TABLE `transaction_item`
  ADD CONSTRAINT `transaction_item_ibfk_1` FOREIGN KEY (`transactionID`) REFERENCES `sales_transactions` (`transactionID`),
  ADD CONSTRAINT `transaction_item_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
