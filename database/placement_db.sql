-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2026 at 07:04 AM
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
-- Database: `placement_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `email`, `password`) VALUES
(1, 'NIET Placement Cell', 'admin@niet.co.in', '$2y$10$bHSeSIRDgf8sUB9b1Va9XunxnlbeAdcvxVNTUpr1n7DVtr3Qyol9i');

-- --------------------------------------------------------

--
-- Table structure for table `job_openings`
--

CREATE TABLE `job_openings` (
  `job_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `min_cgpa` decimal(3,2) DEFAULT NULL,
  `package` decimal(5,2) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `company_type` enum('Product Based','Service Based') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_openings`
--

INSERT INTO `job_openings` (`job_id`, `company_name`, `role_id`, `min_cgpa`, `package`, `logo_url`, `company_type`) VALUES
(1, 'Google', 3, 8.50, 32.50, 'https://www.google.com/s2/favicons?sz=128&domain=google.com', 'Product Based'),
(2, 'Google', 4, 8.50, 35.00, 'https://www.google.com/s2/favicons?sz=128&domain=google.com', 'Product Based'),
(3, 'Microsoft', 7, 8.00, 28.00, 'https://www.google.com/s2/favicons?sz=128&domain=microsoft.com', 'Product Based'),
(4, 'Microsoft', 8, 8.20, 30.00, 'https://www.google.com/s2/favicons?sz=128&domain=microsoft.com', 'Product Based'),
(5, 'Amazon', 2, 7.50, 30.00, 'https://www.google.com/s2/favicons?sz=128&domain=amazon.com', 'Product Based'),
(6, 'Adobe', 10, 8.00, 25.00, 'https://www.google.com/s2/favicons?sz=128&domain=adobe.com', 'Product Based'),
(7, 'Meta', 1, 6.00, 3.60, 'https://www.google.com/s2/favicons?sz=128&domain=meta.com', 'Service Based'),
(8, 'Meta', 3, 7.00, 7.50, 'https://www.google.com/s2/favicons?sz=128&domain=meta.com', 'Service Based'),
(9, 'Infosys', 2, 6.50, 4.50, 'https://www.google.com/s2/favicons?sz=128&domain=infosys.com', 'Service Based'),
(10, 'Accenture', 5, 6.50, 4.50, 'https://www.google.com/s2/favicons?sz=128&domain=accenture.com', 'Service Based'),
(11, 'Accenture', 1, 6.50, 6.50, 'https://www.google.com/s2/favicons?sz=128&domain=accenture.com', 'Service Based'),
(12, 'Capgemini', 1, 6.00, 4.00, 'https://www.google.com/s2/favicons?sz=128&domain=capgemini.com', 'Service Based'),
(13, 'Cisco', 7, 8.00, 18.00, 'https://www.google.com/s2/favicons?sz=128&domain=cisco.com', 'Product Based'),
(14, 'Oracle', 5, 7.50, 16.50, 'https://www.google.com/s2/favicons?sz=128&domain=oracle.com', 'Product Based'),
(15, 'NVIDIA', 6, 8.50, 22.00, 'https://www.google.com/s2/favicons?sz=128&domain=nvidia.com', 'Product Based'),
(16, 'NVIDIA', 4, 8.50, 26.00, 'https://www.google.com/s2/favicons?sz=128&domain=nvidia.com', 'Product Based'),
(17, 'Swiggy', 6, 8.00, 15.00, 'https://www.google.com/s2/favicons?sz=128&domain=swiggy.com', 'Product Based'),
(18, 'Zomato', 1, 7.80, 18.00, 'https://www.google.com/s2/favicons?sz=128&domain=zomato.com', 'Product Based'),
(19, 'Amazon', 2, 7.50, 14.00, 'https://www.google.com/s2/favicons?sz=128&domain=amazon.com', 'Product Based'),
(20, 'Genpact', 1, 6.00, 3.50, 'https://www.google.com/s2/favicons?sz=128&domain=genpact.com', 'Service Based');

-- --------------------------------------------------------

--
-- Table structure for table `job_roles`
--

CREATE TABLE `job_roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_roles`
--

INSERT INTO `job_roles` (`role_id`, `role_name`) VALUES
(1, 'Frontend Developer'),
(2, 'Backend Developer'),
(3, 'Full Stack Developer'),
(4, 'Data Scientist'),
(5, 'Data Analyst'),
(6, 'Machine Learning Engineer'),
(7, 'DevOps Engineer'),
(8, 'Cloud Architect'),
(9, 'Cybersecurity Analyst'),
(10, 'UI/UX Designer');

-- --------------------------------------------------------

--
-- Table structure for table `role_skills`
--

CREATE TABLE `role_skills` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `skill_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_skills`
--

INSERT INTO `role_skills` (`id`, `role_id`, `skill_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 5),
(6, 2, 7),
(7, 2, 8),
(8, 2, 23),
(9, 3, 1),
(10, 3, 3),
(11, 3, 5),
(12, 3, 7),
(13, 3, 25),
(14, 4, 6),
(15, 4, 12),
(16, 4, 13),
(17, 5, 6),
(18, 5, 7),
(19, 5, 15),
(20, 6, 6),
(21, 6, 13),
(22, 7, 10),
(23, 7, 11),
(24, 7, 20),
(25, 9, 21),
(26, 9, 20),
(27, 10, 14),
(28, 10, 4),
(29, 8, 10),
(30, 8, 11),
(31, 8, 22),
(32, 8, 20);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int(11) NOT NULL,
  `skill_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `skill_name`) VALUES
(18, 'Algorithms'),
(10, 'AWS'),
(16, 'C++'),
(2, 'CSS'),
(21, 'Cybersecurity'),
(17, 'Data Structures'),
(9, 'Django'),
(11, 'Docker'),
(23, 'Express.js'),
(14, 'Figma'),
(20, 'Git'),
(1, 'HTML'),
(8, 'Java'),
(3, 'JavaScript'),
(22, 'Kubernetes'),
(19, 'MongoDB'),
(5, 'Node.js'),
(12, 'Pandas'),
(25, 'PHP'),
(6, 'Python'),
(4, 'React.js'),
(7, 'SQL'),
(15, 'Tableau'),
(13, 'TensorFlow'),
(24, 'TypeScript');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `roll_number` varchar(50) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `cgpa` decimal(3,2) DEFAULT 0.00,
  `passing_year` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `roll_number`, `year`, `cgpa`, `passing_year`, `email`, `password`) VALUES
(1, 'Pari', '135', 2, 8.65, 2028, '0241csai139@niet.co.in', 'pari'),
(2, 'Aarav Sharma', '136', 2, 9.42, 2028, 's2@niet.co.in', 's2'),
(3, 'Ishani Gupta', '137', 2, 8.12, 2028, 's3@niet.co.in', 's3'),
(4, 'Vihaan Malhotra', '138', 2, 6.65, 2028, 's4@niet.co.in', 's4'),
(5, 'Ananya Singh', '139', 3, 9.42, 2027, 's5@niet.co.in', 's5'),
(6, 'Arjun Verma', '140', 3, 7.45, 2027, 's6@niet.co.in', 's6'),
(7, 'Saanvi Iyer', '141', 3, 9.12, 2027, 's7@niet.co.in', 's7'),
(8, 'Rohan Mehra', '142', 4, 7.10, 2026, 's8@niet.co.in', 's8'),
(9, 'Kavya Reddy', '143', 4, 8.45, 2026, 's9@niet.co.in', 's9'),
(10, 'Aryan Khan', '144', 4, 6.80, 2026, 's10@niet.co.in', 's10'),
(11, 'Diya Bose', '145', 2, 6.80, 2028, 's11@niet.co.in', 's11'),
(12, 'Kabir Joshi', '146', 2, 6.80, 2028, 's12@niet.co.in', 's12'),
(13, 'Myra Saxena', '147', 2, 9.42, 2028, 's13@niet.co.in', 's13'),
(14, 'Advait Patil', '148', 3, 7.85, 2027, 's14@niet.co.in', 's14'),
(15, 'Kiara Dsouza', '149', 3, 7.80, 2027, 's15@niet.co.in', 's15'),
(16, 'Devansh Nair', '150', 3, 7.80, 2027, 's16@niet.co.in', 's16'),
(17, 'Riya Kapoor', '151', 4, 7.80, 2026, 's17@niet.co.in', 's17'),
(18, 'Reyansh Mittal', '152', 4, 7.80, 2026, 's18@niet.co.in', 's18'),
(19, 'Zoya Ahmed', '153', 4, 7.80, 2026, 's19@niet.co.in', 's19'),
(20, 'Atharv Kulkarni', '154', 2, 7.80, 2028, 's20@niet.co.in', 's20'),
(21, 'Tara Deshmukh', '155', 2, 7.80, 2028, 's21@niet.co.in', 's21'),
(22, 'Ishaan Choudhury', '156', 2, 7.80, 2028, 's22@niet.co.in', 's22'),
(23, 'Avni Pandey', '157', 3, 7.80, 2027, 's23@niet.co.in', 's23'),
(24, 'Yuvraj Thakur', '158', 3, 7.80, 2027, 's24@niet.co.in', 's24'),
(25, 'Sia Bhatia', '159', 3, 7.80, 2027, 's25@niet.co.in', 's25'),
(26, 'Arnav Mishra', '160', 4, 7.80, 2026, 's26@niet.co.in', 's26'),
(27, 'Anvi Rao', '161', 4, 7.80, 2026, 's27@niet.co.in', 's27'),
(28, 'Shaurya Bajaj', '162', 4, 7.80, 2026, 's28@niet.co.in', 's28'),
(29, 'Prisha Agarwal', '163', 2, 7.80, 2028, 's29@niet.co.in', 's29'),
(30, 'Rudransh Tiwari', '164', 2, 7.80, 2028, 's30@niet.co.in', 's30'),
(31, 'Amara Sethi', '165', 2, 7.80, 2028, 's31@niet.co.in', 's31'),
(32, 'Vivaan Shah', '166', 3, 7.80, 2027, 's32@niet.co.in', 's32'),
(33, 'Gauri Das', '167', 3, 7.80, 2027, 's33@niet.co.in', 's33'),
(34, 'Ranbir Das', '168', 3, 7.80, 2027, 's34@niet.co.in', 's34'),
(35, 'Navya Jain', '169', 4, 7.80, 2026, 's35@niet.co.in', 's35'),
(36, 'Daksh Parekh', '170', 4, 8.10, 2026, 's36@niet.co.in', 's36'),
(37, 'Vanya Sharma', '171', 4, 8.10, 2026, 's37@niet.co.in', 's37'),
(38, 'Tushar Goel', '172', 2, 8.10, 2028, 's38@niet.co.in', 's38'),
(39, 'Jiya Bansal', '173', 2, 8.10, 2028, 's39@niet.co.in', 's39'),
(40, 'Rishi Khanna', '174', 2, 9.42, 2028, 's40@niet.co.in', 's40'),
(41, 'Tanvi Mahajan', '175', 3, 7.56, 2027, 's41@niet.co.in', 's41'),
(42, 'Hriday Pal', '176', 3, 8.28, 2027, 's42@niet.co.in', 's42'),
(43, 'Mishka Soni', '177', 3, 7.85, 2027, 's43@niet.co.in', 's43'),
(44, 'Samar Trivedi', '178', 4, 6.65, 2026, 's44@niet.co.in', 's44'),
(45, 'Sara Varma', '179', 4, 6.80, 2026, 's45@niet.co.in', 's45'),
(46, 'Eklavya Bhatt', '180', 4, 6.80, 2026, 's46@niet.co.in', 's46'),
(47, 'Meher Gill', '181', 2, 6.80, 2028, 's47@niet.co.in', 's47'),
(48, 'Laksh Rawat', '182', 2, 6.65, 2028, 's48@niet.co.in', 's48'),
(49, 'Kashvi Som', '183', 2, 8.08, 2028, 's49@niet.co.in', 's49'),
(50, 'Ayan Mukherji', '184', 3, 7.72, 2027, 's50@niet.co.in', 's50');

-- --------------------------------------------------------

--
-- Table structure for table `student_skills`
--

CREATE TABLE `student_skills` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `skill_id` int(11) DEFAULT NULL,
  `status` enum('Mastered','Working On') DEFAULT 'Working On'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_skills`
--

INSERT INTO `student_skills` (`id`, `student_id`, `skill_id`, `status`) VALUES
(143, 1, 17, 'Working On'),
(144, 1, 6, 'Mastered'),
(149, 1, 16, 'Working On'),
(150, 1, 1, 'Mastered'),
(153, 1, 18, 'Working On'),
(154, 1, 2, 'Mastered'),
(155, 1, 7, 'Mastered'),
(262, 1, 4, 'Mastered'),
(265, 1, 25, 'Mastered'),
(275, 1, 20, 'Mastered'),
(276, 1, 3, 'Mastered'),
(317, 8, 1, 'Mastered'),
(318, 8, 2, 'Mastered'),
(319, 8, 3, 'Mastered'),
(320, 8, 4, 'Mastered'),
(321, 45, 1, 'Mastered'),
(322, 45, 2, 'Mastered'),
(323, 45, 3, 'Mastered'),
(324, 45, 4, 'Mastered'),
(325, 15, 5, 'Mastered'),
(326, 15, 7, 'Mastered'),
(327, 15, 8, 'Mastered'),
(328, 15, 23, 'Mastered'),
(329, 49, 5, 'Mastered'),
(330, 49, 7, 'Mastered'),
(331, 49, 8, 'Mastered'),
(332, 49, 23, 'Mastered'),
(333, 12, 1, 'Mastered'),
(334, 12, 3, 'Mastered'),
(335, 12, 5, 'Mastered'),
(336, 12, 7, 'Mastered'),
(337, 12, 25, 'Mastered'),
(338, 38, 1, 'Mastered'),
(339, 38, 3, 'Mastered'),
(340, 38, 5, 'Mastered'),
(341, 38, 7, 'Mastered'),
(342, 38, 25, 'Mastered'),
(343, 22, 6, 'Mastered'),
(344, 22, 12, 'Mastered'),
(345, 22, 13, 'Mastered'),
(346, 50, 6, 'Mastered'),
(347, 50, 12, 'Mastered'),
(348, 50, 13, 'Mastered'),
(349, 19, 6, 'Mastered'),
(350, 19, 7, 'Mastered'),
(351, 19, 15, 'Mastered'),
(352, 41, 6, 'Mastered'),
(353, 41, 7, 'Mastered'),
(354, 41, 15, 'Mastered'),
(355, 6, 6, 'Mastered'),
(356, 6, 13, 'Mastered'),
(357, 33, 6, 'Mastered'),
(358, 33, 13, 'Mastered'),
(359, 27, 10, 'Mastered'),
(360, 27, 11, 'Mastered'),
(361, 27, 20, 'Mastered'),
(362, 44, 10, 'Mastered'),
(363, 44, 11, 'Mastered'),
(364, 44, 20, 'Mastered'),
(365, 9, 10, 'Mastered'),
(366, 9, 11, 'Mastered'),
(367, 9, 22, 'Mastered'),
(368, 9, 20, 'Mastered'),
(369, 35, 10, 'Mastered'),
(370, 35, 11, 'Mastered'),
(371, 35, 22, 'Mastered'),
(372, 35, 20, 'Mastered'),
(373, 11, 21, 'Mastered'),
(374, 11, 20, 'Mastered'),
(375, 47, 21, 'Mastered'),
(376, 47, 20, 'Mastered'),
(377, 14, 14, 'Mastered'),
(378, 14, 4, 'Mastered'),
(379, 39, 14, 'Mastered'),
(380, 39, 4, 'Mastered'),
(381, 2, 1, 'Mastered'),
(382, 2, 5, 'Working On'),
(383, 3, 6, 'Mastered'),
(384, 3, 12, 'Working On'),
(385, 16, 10, 'Mastered'),
(386, 16, 22, 'Working On'),
(387, 25, 21, 'Mastered'),
(388, 25, 3, 'Working On'),
(389, 30, 14, 'Mastered'),
(390, 30, 2, 'Working On'),
(391, 4, 17, 'Working On'),
(392, 5, 17, 'Working On'),
(393, 7, 17, 'Working On'),
(394, 10, 17, 'Working On'),
(395, 13, 17, 'Working On'),
(396, 17, 17, 'Working On'),
(397, 18, 17, 'Working On'),
(398, 20, 17, 'Working On'),
(399, 21, 17, 'Working On'),
(400, 23, 17, 'Working On'),
(401, 24, 17, 'Working On'),
(402, 26, 17, 'Working On'),
(403, 28, 17, 'Working On'),
(404, 29, 17, 'Working On'),
(405, 31, 17, 'Working On'),
(406, 32, 17, 'Working On'),
(407, 34, 17, 'Working On'),
(408, 36, 17, 'Working On'),
(409, 37, 17, 'Working On'),
(410, 40, 17, 'Working On'),
(411, 42, 17, 'Working On'),
(412, 43, 17, 'Working On'),
(413, 46, 17, 'Working On'),
(414, 48, 17, 'Working On');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `job_openings`
--
ALTER TABLE `job_openings`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `job_roles`
--
ALTER TABLE `job_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_skills`
--
ALTER TABLE `role_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`),
  ADD UNIQUE KEY `skill_name` (`skill_name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `roll_number` (`roll_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `student_skills`
--
ALTER TABLE `student_skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_skill` (`student_id`,`skill_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job_openings`
--
ALTER TABLE `job_openings`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `job_roles`
--
ALTER TABLE `job_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `role_skills`
--
ALTER TABLE `role_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `student_skills`
--
ALTER TABLE `student_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=422;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `job_openings`
--
ALTER TABLE `job_openings`
  ADD CONSTRAINT `job_openings_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `job_roles` (`role_id`) ON DELETE SET NULL;

--
-- Constraints for table `role_skills`
--
ALTER TABLE `role_skills`
  ADD CONSTRAINT `role_skills_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `job_roles` (`role_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_skills`
--
ALTER TABLE `student_skills`
  ADD CONSTRAINT `student_skills_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
