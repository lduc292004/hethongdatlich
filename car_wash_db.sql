-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 10, 2026 lúc 05:56 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `car_wash_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `status` enum('Chờ rửa','Đang rửa','Đã xong','Đã hủy') DEFAULT 'Chờ rửa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `car_id`, `booking_date`, `booking_time`, `status`, `created_at`) VALUES
(1, 2, 1, '2026-06-10', '08:00:00', 'Đã xong', '2026-06-10 02:15:07'),
(2, 2, 2, '2026-06-10', '08:00:00', 'Đã hủy', '2026-06-10 02:16:00'),
(3, 2, 2, '2026-06-10', '08:00:00', 'Đã hủy', '2026-06-10 02:37:12'),
(4, 2, 2, '2026-06-10', '08:00:00', 'Đã xong', '2026-06-10 02:51:50'),
(5, 2, 1, '2026-06-10', '09:30:00', 'Đã xong', '2026-06-10 02:54:21'),
(6, 4, 3, '2026-06-10', '08:00:00', 'Đã xong', '2026-06-10 03:25:08'),
(7, 4, 3, '2026-06-11', '11:00:00', 'Đã hủy', '2026-06-10 03:37:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_cars`
--

CREATE TABLE `customer_cars` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_type` varchar(20) NOT NULL COMMENT 'Xe 2 banh / Xe 4 banh',
  `brand_name` varchar(50) NOT NULL COMMENT 'Honda, Yamaha, Toyota...',
  `license_plate` varchar(20) NOT NULL,
  `car_model` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `customer_cars`
--

INSERT INTO `customer_cars` (`id`, `user_id`, `car_type`, `brand_name`, `license_plate`, `car_model`) VALUES
(1, 2, '', '', '51G-123.45', 'Toyota Vios'),
(2, 2, '', '', '51H-678.90', 'Honda Civic'),
(3, 4, 'Xe 2 bánh', 'Honda', '72B1-04349', 'winner');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
(1, 'Quản Trị Viên', 'admin@gmail.com', NULL, '$2y$10$XP5owDeTEqi6MvfF.Br97O2pLf0FQhxqZqLXL1Frh3k04KdE8evum', 'admin', '2026-06-10 01:58:52'),
(2, 'Lương Hoài Đức', 'lduc292004@gmail.com', NULL, '$2y$10$mC36M/OidwK7R3S3g2a8eexhFbeD3gOaMCV7uFj1J7EbyzFvFp7W.', 'customer', '2026-06-10 01:58:52'),
(3, 'luong hoai duc', 'sieuxathu24@gmail.com', '0392548504', '$2y$10$1FpJvnOQq/224j8Lc.GRlOmD4dUwBPehp/geBWFknhh1N50ELUsAe', 'customer', '2026-06-10 03:20:27'),
(4, 'luong hoai duc', 'lduc020904@gmail.com', '0348328532', '$2y$10$sVLkm9S0TY9.h2MkrgjQz.9zTtZbNmYZA4O8hNsOiR.J3ZILGTgCK', 'customer', '2026-06-10 03:24:07');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Chỉ mục cho bảng `customer_cars`
--
ALTER TABLE `customer_cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `customer_cars`
--
ALTER TABLE `customer_cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `customer_cars` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `customer_cars`
--
ALTER TABLE `customer_cars`
  ADD CONSTRAINT `customer_cars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
