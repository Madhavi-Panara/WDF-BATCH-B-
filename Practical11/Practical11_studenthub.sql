-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2026 at 07:15 PM
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
-- Database: `studenthub`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(150) NOT NULL,
  `event_date` date NOT NULL,
  `category` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_date`, `category`, `location`) VALUES
(1, 'Coding Competition', '2026-10-10', 'Technical', 'Programming Lab'),
(2, 'College Cultural Fest', '2026-10-15', 'Cultural', 'Main Auditorium'),
(3, 'AI and Machine Learning Seminar', '2026-10-20', 'Technical', 'Seminar Hall'),
(4, 'Football Tournament', '2026-10-25', 'Sports', 'College Ground');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `registration_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`registration_id`, `student_id`, `event_id`, `registration_date`) VALUES
(1, 1, 1, '2026-10-06'),
(2, 2, 2, '2026-10-06'),
(3, 3, 3, '2026-10-06'),
(4, 4, 4, '2026-10-06'),
(5, 1, 3, '2026-10-06');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_iid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `course` varchar(50) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_iid`, `name`, `email`, `course`, `year`) VALUES
(1, 'Madhvi Panara', 'madhvi@gmail.com', 'B.Tech', 2),
(2, 'Riya Patel', 'riya@gmail.com', 'B.Tech', 2),
(3, 'Dhruvi Shah', 'dhruvi@gmail.com', 'BCA', 1),
(4, 'Nisha Patel', 'nisha@gmail.com', 'B.Tech', 3),
(5, 'Aarav Shah', 'aarav@gmail.com', 'B.Tech', 1),
(6, 'Diya Patel', 'diya@gmail.com', 'B.Tech', 2),
(7, 'Krish Mehta', 'krish@gmail.com', 'BCA', 1),
(8, 'Anaya Desai', 'anaya@gmail.com', 'BCA', 2),
(9, 'Rohan Joshi', 'rohan@gmail.com', 'B.Tech', 3),
(10, 'Isha Trivedi', 'isha@gmail.com', 'MCA', 1),
(11, 'Dev Parmar', 'dev@gmail.com', 'B.Tech', 4),
(12, 'Mira Shah', 'mira@gmail.com', 'BBA', 1),
(13, 'Yash Patel', 'yash@gmail.com', 'BCA', 3),
(14, 'Kiara Mehta', 'kiara@gmail.com', 'B.Tech', 2),
(15, 'Neel Desai', 'neel@gmail.com', 'MCA', 2),
(16, 'Riya Joshi', 'riya.joshi@gmail.com', 'BBA', 2),
(17, 'Arjun Trivedi', 'arjun@gmail.com', 'B.Tech', 1),
(18, 'Sara Parmar', 'sara@gmail.com', 'BBA', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `course` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `student_id`, `course`, `year`, `mobile`, `role`) VALUES
(6, 'student01', 'student@gmail.com', '$2y$10$sbQv4SLtPZnFaef6m4LLjOrVNCvOtKF4ofymNKjG3BiqaNmC2RNXa', 'Test', 'Student', 'STU001', '0', 2, '9876543210', 'student'),
(7, 'admin01', 'admin@gmail.com', '$2y$10$V4ZF8hLw8XFcA0Xbndl.ROL8LZ8vRzi9qr0u7T9cUqC4iqbes8i2S', 'Test', 'Admin', 'ADM001', '0', 0, '9876543211', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`registration_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_iid`);

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
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_iid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
