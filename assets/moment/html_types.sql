-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 08, 2024 at 08:02 AM
-- Server version: 10.5.9-MariaDB
-- PHP Version: 7.4.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nvtoan_todo`
--

-- --------------------------------------------------------

--
-- Table structure for table `html_types`
--

CREATE TABLE `html_types` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `html_types`
--

INSERT INTO `html_types` (`id`, `title`, `value`, `color`, `icon`, `filename`, `group`) VALUES
(1, 'text', 'Văn bản', 'success', 'assets/images/text-column-icon.svg', '', 'Thông tin'),
(2, 'number', 'Số', 'warning', 'assets/images/numeric-column-icon.svg', '', 'Thông tin'),
(3, 'file', 'Tệp tin', 'info', 'assets/images/file-column-icon.svg', '', 'Thông tin'),
(5, 'date', 'Ngày', 'success', 'assets/images/date-column-icon.svg', '', 'Thông tin'),
(6, 'status', 'Trạng thái', 'success', 'assets/images/color-column-icon.svg', '', 'Đo lường'),
(10, 'people', 'Người dùng', 'danger', 'assets/images/multiple-person-column-icon.svg', '', 'Nhân sự'),
(11, 'timeline', 'Thời hạn', 'info', 'assets/images/timerange-column-icon.svg', '', 'Đo lường'),
(12, 'department', 'Đơn vị', 'info', 'assets/images/department-svgrepo-com.svg', '', 'Nhân sự'),
(16, 'longtext', 'Đoạn văn bản', 'info', 'assets/images/long-text-column-icon-v2a.png', '', 'Thông tin'),
(17, 'percent', 'Tiến độ', 'success', 'assets/images/columns-battery-column-icon-v2a.png', NULL, 'Đo lường'),
(18, 'loop', 'Vòng lập', 'danger', 'assets/images/loop-svgrepo-com.svg', NULL, NULL),
(19, 'gender', 'Giới tính', 'success', 'assets/images/gender-sign-svgrepo-com.svg', NULL, 'Nhân sự'),
(20, 'email', 'Địa chỉ Email', 'warning', 'assets/images/email-column-icon-v2a.png', NULL, 'Nhân sự'),
(21, 'phone', 'Số điện thoại', 'info', 'assets/images/phone-column-icon-v2a.png', NULL, 'Nhân sự'),
(22, 'priority', 'Độ ưu tiên', 'primary', 'assets/images/priority.png', NULL, 'Đo lường'),
(23, 'connecttable', 'Kết nối', 'danger', 'assets/images/board-relation-column-icon-v2a.png', '', ''),
(24, 'dependenttask', 'Phụ thuộc', 'warning', 'assets/images/dependency-column-icon-v2a.png', NULL, NULL),
(25, 'confirm', 'Xác nhận lãnh đạo', 'success', 'assets/images/boolean-column-icon-v2a.png', NULL, NULL),
(26, 'link', 'Link', 'success', 'assets/images/link-column-icon-v2a.png', '', 'Thông tin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `html_types`
--
ALTER TABLE `html_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `html_types`
--
ALTER TABLE `html_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
