-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 21, 2019 at 11:01 PM
-- Server version: 5.7.27-0ubuntu0.16.04.1
-- PHP Version: 7.1.30-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exam`
--
CREATE DATABASE IF NOT EXISTS `exam` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `exam`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_group`
--

CREATE TABLE `admin_group` (
  `id` int(11) NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_limit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_group`
--

INSERT INTO `admin_group` (`id`, `group_name`, `group_description`, `group_banner`, `employee_limit`) VALUES
(1, 'Dragon army', 'fight for freedome', 'a90f6eb92ce17fab0439ca57386b8e9c.jpeg', 3),
(2, 'Jollibee', 'Pabida', 'b092728490504a20f0e258554863eb65.jpeg', 3),
(3, 'asd', 'asd', 'd71ff2bceb95c61c8a2d13538612f8c7.jpeg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`id`, `username`, `admin_password`, `email`) VALUES
(2, 'admin', '$2y$13$2XHhJEl7TzChuHX6LTBGR.WUZfx0mauLH0I4lfITO0H9w5yKji4A2', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` datetime NOT NULL,
  `employee_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_employed` datetime NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_group_id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `last_name`, `first_name`, `middle_name`, `birth_date`, `employee_address`, `tel_number`, `gender`, `date_employed`, `salary`, `employee_id`, `employee_group_id`, `email`) VALUES
(1, 'Chan', 'Jose', 'Mari', '2019-10-01 00:00:00', 'jingle', '(123) 456-7890', 'Male', '2019-10-07 00:00:00', '5.00', '1', 1, 'josemari.chan@gmail.com'),
(2, 'Kazuto', 'Kirigaya', 'Kirito', '2002-10-18 00:00:00', 'Aincrad', '(000) 000-0000', 'Male', '2019-10-08 00:00:00', '233.51', '2', 1, 'employee@gmail.com'),
(5, 'Chan', 'Jose', 'Mari', '2019-10-01 00:00:00', 'Session', '(000) 000-0000', 'Male', '2019-10-08 00:00:00', '233.51', '3', 2, 'josemari.chan@gmail.com'),
(12, 'Kazuto', 'a', 'test', '2019-10-14 00:00:00', 'e', '(123) 456-7890', 'Male', '2019-10-14 00:00:00', '3.00', 'entry2', 2, 'employee@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `employee_invitation`
--

CREATE TABLE `employee_invitation` (
  `id` int(11) NOT NULL,
  `employee_group_id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invited_at` datetime NOT NULL,
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_invitation`
--

INSERT INTO `employee_invitation` (`id`, `employee_group_id`, `username`, `email`, `password`, `status`, `invited_at`, `employee_id`) VALUES
(1, 1, 'josemari.chan', 'josemari.chan@gmail.com', 'da93f', 'Accepted', '2019-10-16 10:14:41', 1),
(2, 1, 'employee', 'employee@gmail.com', '5b4ea', 'Accepted', '2019-10-16 10:16:47', 2),
(3, 2, 'josemari.chan', 'josemari.chan@gmail.com', '6f5fc', 'Accepted', '2019-10-16 11:00:48', 5);

-- --------------------------------------------------------

--
-- Table structure for table `employee_user`
--

CREATE TABLE `employee_user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plain_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_group_id` int(11) NOT NULL,
  `employee_id_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_user`
--

INSERT INTO `employee_user` (`id`, `username`, `email`, `password`, `plain_password`, `employee_group_id`, `employee_id_id`) VALUES
(1, 'josemari.chan', 'josemari.chan@gmail.com', '$2y$13$e.KM4ruUz0bmKoObiEeTcelk27sFzM.B0KhE9.rBfJgqWCwosAB3u', 'da93f', 1, 1),
(2, 'employee', 'employee@gmail.com', '$2y$13$A84qZ9luFoZRn3V6aS.wc.qdOn8NtQX9tE7qOu4dnmCDOGQk62bZO', '5b4ea', 1, 2),
(3, 'josemari.chan1', 'josemari.chan@gmail.com', '$2y$13$rtU2nA7wA8f05bgaI37i0OhN0S/zSlntRFbKk9ADwlV6.cv8ZfaBu', '6f5fc', 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `invitation`
--

CREATE TABLE `invitation` (
  `id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  `long_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plain_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invitation_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invited_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `video_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `playlist` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `upload_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `removed_at` datetime NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `employee_id`, `video_id`, `playlist`, `upload_filename`, `removed_at`, `status`, `item_group_id`) VALUES
(11, 2, 'Gb-rcQTymEA', '', NULL, '2019-10-21 22:46:54', 'Removed', 1),
(12, 2, 'SampleVideo_360x240_1mb.mp4', '', 'f23943a52537c671dbefe7d59eeb6fd9.mp4', '2019-10-21 22:53:41', 'Removed', 1),
(13, 2, 'rF8ieTby4VI', '', NULL, '2019-10-21 22:53:55', 'Add', 1);

-- --------------------------------------------------------

--
-- Table structure for table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190916071157', '2019-09-16 07:12:56'),
('20190917085524', '2019-09-17 08:57:31'),
('20190918014646', '2019-09-18 01:48:12'),
('20190918015831', '2019-09-18 01:58:46'),
('20190920055028', '2019-09-20 05:51:17'),
('20190924021646', '2019-09-24 02:17:46'),
('20190924083053', '2019-09-24 08:31:37'),
('20190925021247', '2019-09-25 02:13:16'),
('20190926062334', '2019-09-26 06:24:04'),
('20191001082750', '2019-10-02 06:37:29'),
('20191002065122', '2019-10-02 06:51:53'),
('20191007080729', '2019-10-07 08:08:09'),
('20191008012249', '2019-10-08 01:23:03'),
('20191008060740', '2019-10-08 06:07:51'),
('20191010015334', '2019-10-10 01:54:02'),
('20191010052007', '2019-10-10 05:20:15'),
('20191011005122', '2019-10-11 00:51:42'),
('20191011045230', '2019-10-11 04:53:23'),
('20191011073538', '2019-10-11 07:35:52'),
('20191011075720', '2019-10-11 07:57:30'),
('20191014004735', '2019-10-14 00:47:57'),
('20191014010217', '2019-10-14 01:02:46'),
('20191014013732', '2019-10-14 01:37:40'),
('20191014023647', '2019-10-14 02:37:40'),
('20191014024649', '2019-10-15 07:04:36'),
('20191016052819', '2019-10-16 05:29:27'),
('20191016053147', '2019-10-16 05:32:38'),
('20191016061504', '2019-10-16 06:15:28'),
('20191018014412', '2019-10-18 01:46:09'),
('20191018025808', '2019-10-18 02:58:33'),
('20191018032625', '2019-10-18 03:26:43'),
('20191018043140', '2019-10-18 04:32:03'),
('20191018043418', '2019-10-18 04:34:36'),
('20191018060900', '2019-10-18 06:09:18'),
('20191021033649', '2019-10-21 03:37:28');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `employee_payroll_id` int(11) NOT NULL,
  `group_payroll_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL,
  `start_coverage` datetime NOT NULL,
  `end_coverage` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `employee_payroll_id`, `group_payroll_id`, `amount`, `payment_date`, `start_coverage`, `end_coverage`) VALUES
(1, 1, 1, '10000.00', '2019-10-08 00:00:00', '2019-10-08 00:00:00', '2019-10-31 00:00:00'),
(2, 1, 1, '100.50', '2019-10-31 00:00:00', '2019-10-16 00:00:00', '2019-10-30 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_group_id` int(11) NOT NULL,
  `video_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` int(11) NOT NULL,
  `start_seconds` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`id`, `employee_id`, `employee_group_id`, `video_id`, `item_id`, `start_seconds`) VALUES
(25, 1, 1, 'rF8ieTby4VI', 13, 0),
(26, 2, 1, 'rF8ieTby4VI', 13, 24);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `group_id`, `first_name`, `last_name`) VALUES
(1, 'user', '$2y$13$Dfh1bPX1hd9eWFSQINq0Be893x0Yn2txq5tBt2wBaslA7Csgq0SmG', 'user@gmail.com', 1, 'Cj', 'to'),
(2, 'user1', '$2y$13$otfOX3rwp2BTFppGYhMHgucISpIn/X.ey5HNnjWIjSH9dCMxmKva6', 'user1@gmail.com', 2, 'User', 'qwe');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_group`
--
ALTER TABLE `admin_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_AD8A54A9F85E0677` (`username`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5D9F75A18C03F15C` (`employee_id`),
  ADD KEY `IDX_5D9F75A16E6B8880` (`employee_group_id`);

--
-- Indexes for table `employee_invitation`
--
ALTER TABLE `employee_invitation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_69CAD1766E6B8880` (`employee_group_id`),
  ADD KEY `IDX_69CAD1768C03F15C` (`employee_id`);

--
-- Indexes for table `employee_user`
--
ALTER TABLE `employee_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_384A9C0EF85E0677` (`username`),
  ADD KEY `IDX_384A9C0E9749932E` (`employee_id_id`),
  ADD KEY `IDX_384A9C0E6E6B8880` (`employee_group_id`);

--
-- Indexes for table `invitation`
--
ALTER TABLE `invitation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F11D61A2312B5ED4` (`long_id`),
  ADD KEY `IDX_F11D61A21ED93D47` (`user_group_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E11EE94D8C03F15C` (`employee_id`),
  ADD KEY `IDX_E11EE94D9259118C` (`item_group_id`);

--
-- Indexes for table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_499FBCC64374F03E` (`employee_payroll_id`),
  ADD KEY `IDX_499FBCC6C6E2158` (`group_payroll_id`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7FFD7F638C03F15C` (`employee_id`),
  ADD KEY `IDX_7FFD7F636E6B8880` (`employee_group_id`),
  ADD KEY `IDX_7FFD7F63126F525E` (`item_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649FE54D947` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_group`
--
ALTER TABLE `admin_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `employee_invitation`
--
ALTER TABLE `employee_invitation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `employee_user`
--
ALTER TABLE `employee_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `invitation`
--
ALTER TABLE `invitation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `FK_5D9F75A16E6B8880` FOREIGN KEY (`employee_group_id`) REFERENCES `admin_group` (`id`);

--
-- Constraints for table `employee_invitation`
--
ALTER TABLE `employee_invitation`
  ADD CONSTRAINT `FK_69CAD1766E6B8880` FOREIGN KEY (`employee_group_id`) REFERENCES `admin_group` (`id`),
  ADD CONSTRAINT `FK_69CAD1768C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`);

--
-- Constraints for table `employee_user`
--
ALTER TABLE `employee_user`
  ADD CONSTRAINT `FK_384A9C0E6E6B8880` FOREIGN KEY (`employee_group_id`) REFERENCES `admin_group` (`id`),
  ADD CONSTRAINT `FK_384A9C0E9749932E` FOREIGN KEY (`employee_id_id`) REFERENCES `employee` (`id`);

--
-- Constraints for table `invitation`
--
ALTER TABLE `invitation`
  ADD CONSTRAINT `FK_F11D61A21ED93D47` FOREIGN KEY (`user_group_id`) REFERENCES `admin_group` (`id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `FK_E11EE94D8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`),
  ADD CONSTRAINT `FK_E11EE94D9259118C` FOREIGN KEY (`item_group_id`) REFERENCES `admin_group` (`id`);

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `FK_499FBCC64374F03E` FOREIGN KEY (`employee_payroll_id`) REFERENCES `employee` (`id`),
  ADD CONSTRAINT `FK_499FBCC6C6E2158` FOREIGN KEY (`group_payroll_id`) REFERENCES `admin_group` (`id`);

--
-- Constraints for table `queue`
--
ALTER TABLE `queue`
  ADD CONSTRAINT `FK_7FFD7F63126F525E` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `FK_7FFD7F636E6B8880` FOREIGN KEY (`employee_group_id`) REFERENCES `admin_group` (`id`),
  ADD CONSTRAINT `FK_7FFD7F638C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649FE54D947` FOREIGN KEY (`group_id`) REFERENCES `admin_group` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
