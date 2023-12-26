-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 26, 2023 at 01:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gum2`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `title`, `images`, `timestamp`) VALUES
(1, 2, 'Always Remember', 'uploads/Hitler.jpg', '2023-06-04 15:16:28'),
(4, 2, 'jfifjfjpjfpwrj', 'uploads/Screenshot_2023-10-21_00_38_44.png', '2023-12-22 16:39:29'),
(5, 2, 'd', 'uploads/Screenshot_2023-10-26_23_59_38.png', '2023-12-22 21:22:27'),
(6, 2, 'a', 'uploads/Screenshot_2023-10-27_00_00_22.png', '2023-12-22 21:24:32'),
(7, 2, 'd', 'uploads/Screenshot_2023-10-26_23_59_38.png', '2023-12-22 21:39:01'),
(8, 2, 'h', 'uploads/WhatsApp Image 2023-12-19 at 9.24.31 PM.jpeg', '2023-12-22 21:51:14'),
(9, 2, 's', 'uploads/', '2023-12-22 21:52:12'),
(10, 2, 'k', 'uploadsWhatsApp Image 2023-12-19 at 9.24.32 PM.jpeg', '2023-12-22 21:53:06'),
(11, 2, 'a', '/uploads/Screenshot_2023-12-16_22_28_55.png', '2023-12-22 21:55:36'),
(12, 2, 's', '/uploads/Screenshot_2023-10-27_00_00_22.png', '2023-12-22 21:57:01');

-- --------------------------------------------------------

--
-- Table structure for table `supplies`
--

CREATE TABLE `supplies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplies`
--

INSERT INTO `supplies` (`id`, `name`, `price`, `description`, `image_path`) VALUES
(1, 'Premier Protein', 120.00, 'A good product for a starter...', '/images/muscles-gym-dumbbells-bodybuilder-wallpaper-preview.jpg'),
(2, 'Product 1', 80.00, 'Description for Product 1', '/images/muscles-gym-dumbbells-bodybuilder-wallpaper-preview.jpg'),
(3, 'Product 2', 95.00, 'Description for Product 2', '/images/muscles-gym-dumbbells-bodybuilder-wallpaper-preview.jpg'),
(4, 'Product 3', 110.00, 'Description for Product 3', '/images/muscles-gym-dumbbells-bodybuilder-wallpaper-preview.jpg'),
(5, 'Product 4', 70.00, 'Description for Product 4', '/images/muscles-gym-dumbbells-bodybuilder-wallpaper-preview.jpg'),
(6, 'Product 5', 120.00, 'Description for Product 5', '/images/muscles-gym-dumbbells-bodybuilder-wallpaper-preview.jpg'),
(7, 'Product 6', 85.00, 'Description for Product 6', '/images/muscles-gym-dumbbells-bodybuilder-wallpaper-preview.jpg'),
(8, 'Product 7', 100.00, 'Description for Product 7', '/images/muscles-gym-dumbbells-bodybuilder-wallpaper-preview.jpg'),
(9, 'Product 8', 110.00, 'Description for Product 8', '/images/muscles-gym-dumbbells-bodybuilder-wallpaper-preview.jpg'),
(10, 'Product 9', 75.00, 'Description for Product 9', '/images/muscles-gym-dumbbells-bodybuilder-wallpaper-preview.jpg'),
(11, 'Product 10', 130.00, 'Description for Product 10', '/images/muscles-gym-dumbbells-bodybuilder-wallpaper-preview.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tel_number` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `tel_number`, `password`, `profile_picture`, `role`) VALUES
(2, 'Ali', 'ama252@usal.edu.lb', '71298948', '$2y$10$1Dvl7cT86SkUkthwekSVoOtPzhlx/XUk6X9KCqt0UVr3BX51FK5oG', 'wallpaperflare.com_wallpaper (10).jpg', 'user'),
(7, 'Maryam', 'mar@mar.com', NULL, '$2y$10$K0oUCfHyCknzrScuY1cO7OiigtDNsp/SYIz/pOJel5AYj3L84d/9a', NULL, 'user'),
(8, 'admin', 'admin@admin.com', NULL, '$2y$10$NfOo4dVRSehpCUODMi6FS.9RxS/BRNlDzdjtMcC8BKZX1cxZFQUli', NULL, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `supplies`
--
ALTER TABLE `supplies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `supplies`
--
ALTER TABLE `supplies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
