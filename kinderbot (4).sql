-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2026 at 04:01 PM
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
-- Database: `kinderbot`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `objective` text DEFAULT NULL,
  `materials` text DEFAULT NULL,
  `overview` text NOT NULL,
  `skills_competencies` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rodin_comment` text DEFAULT NULL,
  `activity_comment` text DEFAULT NULL,
  `feedback_comment` text DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `class_id`, `title`, `objective`, `materials`, `overview`, `skills_competencies`, `created_at`, `updated_at`, `rodin_comment`, `activity_comment`, `feedback_comment`, `is_published`) VALUES
(1, 1, 'Activity 1', 'BUILD A ROBOT\r\n', 'LEGO kit, Colouring pencils, Split pins, Scissors, Glue, Robot ppt step\r\n', 'Ro-Din will build his first robot using LEGO parts,  learning how pieces connect to form a functioning  machine while exploring the concept of robots as  helpers in various tasks.\r\n', 'Fine motor skills\r\nHand-eye coordination\r\nTeamwork\r\nCreative thinking\r\n', '2026-04-27 15:53:50', NULL, 'Rodin wants to start building', 'Rodin says: Let\'s clap\r\n', ' Rodin wants to color and glue', 1),
(2, 2, 'Activity 2', 'Spinning Top', 'LEGO kit, Colouring pencils, One CD, Scissors, Glue, Short sticks, Circular, cardboards, Robot ppt step\r\n', 'Ro-Din will create and decorate a spinning top,  exploring concepts of rotation, speed, and how colors  blend when in motion.\r\n', 'Pattern recognition, Basic physics, Color theory, Fine motor skills\r\n', '2026-04-30 18:44:49', '2026-04-30 18:44:49', 'Rodin wants to spin...\r\n', 'THAT’S SPIN-TASTIC!\r\n', 'Rodin loves to spin\r\n', 1),
(3, 1, 'Activity 2', 'Spinning Top', 'LEGO kit\r\nColoring pencils\r\nOne CD\r\nScissors\r\nGlue\r\nShort sticks\r\nCircular cardboards\r\nRobot ppt step', 'RoDin will create and decorate a spinning top, exploring concepts of rotation, speed, and how colors blend when in motion', 'Pattern recognition\r\nBasic physics\r\nColor theory\r\nFine motor skills', '2026-05-11 18:14:17', NULL, 'Rodin wants to spin', 'THAT’S SPIN-TASTIC!', 'Rodin is creating some magic', 1);

-- --------------------------------------------------------

--
-- Table structure for table `activity_animations`
--

CREATE TABLE `activity_animations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_completions`
--

CREATE TABLE `activity_completions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `activity_completion_status_id` bigint(20) UNSIGNED NOT NULL,
  `completion_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_completions`
--

INSERT INTO `activity_completions` (`id`, `student_id`, `activity_id`, `activity_completion_status_id`, `completion_date`) VALUES
(1, 1, 1, 2, '2026-04-27 17:18:12'),
(2, 2, 1, 2, '2026-05-01 03:14:51');

-- --------------------------------------------------------

--
-- Table structure for table `activity_completion_statuses`
--

CREATE TABLE `activity_completion_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_completion_statuses`
--

INSERT INTO `activity_completion_statuses` (`id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Not Completed', '2026-05-03 17:51:57', NULL),
(2, 'Completed', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activity_steps`
--

CREATE TABLE `activity_steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_steps`
--

INSERT INTO `activity_steps` (`id`, `activity_id`, `description`, `image_path`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'BUILD THE ROBOT\r\n', 'resources/Picture1.2.2.png', 1, '2026-04-27 16:31:35', NULL),
(2, 1, 'HOLD THE HANDLE IN THE BACK\r\n', 'resources/Picture1.2.3.png', 2, '2026-04-27 16:32:28', NULL),
(3, 1, 'TURN THE HANDLE\r\n', 'resources/Picture1.2.4.png', 3, '2026-04-27 16:33:02', NULL),
(4, 1, 'MANIPULATE AND INTERPRET\r\n', 'resources/Picture1.2.5.png', 4, '2026-04-27 16:33:37', NULL),
(5, 3, 'BUILDING THE SPINNING TOP', 'resources/Picture2.2.2.png', 1, '2026-05-11 18:14:17', NULL),
(6, 3, 'PLACE THE SPINNER', 'resources/Picture2.2.3.png', 2, '2026-05-11 18:14:17', NULL),
(7, 3, 'HOLD AND TURN THE HANDLE FAST', 'resources/Picture2.2.4.png', 3, '2026-05-11 18:14:17', NULL),
(8, 3, 'INTERPRET THE RESULT', 'resources/Picture2.2.5.png', 4, '2026-05-11 18:14:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `competency_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`id`, `student_id`, `teacher_id`, `competency_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 4, 'aaaa', '2026-04-27 17:12:05', NULL),
(2, 2, 1, 1, 4, NULL, '2026-05-01 03:14:51', '2026-05-01 03:14:51'),
(3, 2, 1, 2, 3, NULL, '2026-05-01 03:14:51', '2026-05-01 03:14:51'),
(4, 2, 1, 3, 2, NULL, '2026-05-01 03:14:51', '2026-05-01 03:14:51'),
(5, 2, 1, 4, 3, NULL, '2026-05-01 03:14:51', '2026-05-01 03:14:51');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `grade_level` varchar(255) DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `program_id`, `name`, `grade_level`, `order_index`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'KG1', '0', 0, 1, '2026-04-22 15:25:30', NULL),
(2, 1, 'KG2', '1', 0, 1, '2026-04-22 15:26:10', NULL),
(3, 1, 'KG3', '2', 0, 1, '2026-04-22 15:26:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `competencies`
--

CREATE TABLE `competencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `competencies`
--

INSERT INTO `competencies` (`id`, `activity_id`, `name`, `description`) VALUES
(1, 1, 'skill1', 'Fine Motor Skills'),
(2, 1, 'skill2', 'Hand-eye coordination '),
(3, 1, 'skill3', 'Teamwork'),
(4, 1, 'skill4', 'Creative thinking'),
(5, 3, 'skill_1', 'Pattern recognition'),
(6, 3, 'skill_2', 'Basic physics'),
(7, 3, 'skill_3', 'Color theory'),
(8, 3, 'skill_4', 'Fine motor skills');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `notes` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `section_id`, `enrollment_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(2, 1, 1, '2026-04-23', 'active', '', '2026-04-23 15:47:40', NULL),
(3, 2, 2, '2026-04-23', 'active', '', '2026-04-23 15:48:58', NULL),
(4, 3, 3, '2026-04-23', 'active', '', '2026-04-23 15:49:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `sender_type` varchar(255) NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_type` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `sender_type`, `receiver_id`, `receiver_type`, `subject`, `message`, `is_read`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'coordinator', 2, 'teacher', NULL, 'hi', 1, '2026-04-29 14:25:31', '2026-04-29 14:25:31', NULL),
(2, 2, 'teacher', 1, 'coordinator', NULL, 'hi', 0, '2026-04-29 14:53:52', '2026-04-29 14:53:52', NULL),
(3, 1, 'coordinator', 2, 'teacher', NULL, 'hi', 1, '2026-04-29 14:54:27', '2026-04-29 14:54:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2026_04_07_203814_create_roles_table', 1),
(4, '2026_04_08_133727_create_activity_completion_statuses_table', 1),
(5, '2026_04_08_140000_create_users_table', 1),
(6, '2026_04_08_141751_create_programs_table', 1),
(7, '2026_04_08_141926_create_classes_table', 1),
(8, '2026_04_08_142052_create_teachers_table', 1),
(9, '2026_04_08_142222_create_students_table', 1),
(10, '2026_04_08_142350_create_parents_table', 1),
(11, '2026_04_08_142556_create_parent_student_table', 1),
(12, '2026_04_08_142646_create_sections_table', 1),
(13, '2026_04_08_142747_create_enrollments_table', 1),
(14, '2026_04_08_143646_create_activities_table', 1),
(15, '2026_04_08_143739_create_resources_table', 1),
(16, '2026_04_08_143826_create_competencies_table', 1),
(17, '2026_04_08_143910_create_assessments_table', 1),
(18, '2026_04_08_144008_create_activity_completions_table', 1),
(19, '2026_04_09_193153_create_teacher_activity_log_table', 1),
(20, '2026_04_18_133957_create_messages_table', 1),
(21, '2026_04_23_105434_create_activity_steps_table', 1),
(22, '2026_04_27_165148_create_activity_animations_table', 1),
(23, '2026_04_28_162117_add_rodin_comment_to_activities_table', 2),
(24, '2026_04_28_162333_add_activity_comment_to_activities_table', 2),
(25, '2026_04_28_163058_add_feedback_comment_to_activities_table', 2),
(26, '2026_04_28_165132_add_rodin_comment_to_activities_table', 3),
(27, '2026_04_28_165146_add_activity_comment_to_activities_table', 3),
(28, '2026_04_28_165200_add_feedback_comment_to_activities_table', 3),
(29, '2026_04_28_165903_add_is_published_to_activities_table', 4),
(30, '2026_04_30_131257_rename_activity_columns', 5),
(31, '2026_04_29_191835_add_deleted_at_to_messages_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `user_id`, `full_name`, `phone`, `email`, `created_at`, `updated_at`) VALUES
(1, 3, 'Test Parent', '09 765 234', 'parent@test.com', '2026-04-23 15:42:08', NULL),
(2, 7, 'Test Parent 1', '06 543 234', 'parent1@test.com', '2026-04-23 15:42:58', NULL),
(3, 8, 'Test Parent 2', '09 654 146', 'parent2@test.com', '2026-04-23 15:43:40', NULL),
(4, 13, 'Test Parent 3', '03982743', 'parent3@test.com', '2026-05-11 18:17:40', '2026-05-11 18:17:40');

-- --------------------------------------------------------

--
-- Table structure for table `parent_student`
--

CREATE TABLE `parent_student` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parent_student`
--

INSERT INTO `parent_student` (`id`, `parent_id`, `student_id`) VALUES
(8, 1, 1),
(10, 2, 2),
(11, 3, 3),
(13, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Mindscape Kinderbot', 'Kinderbot Learning Platform Program', 1, '2026-04-27 14:31:37', '2026-04-27 14:31:37');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `activity_id`, `title`, `file_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'child_img1', 'resources/Picture1.1.1.jpg', '2026-04-27 16:00:00', NULL),
(5, 1, 'act1', 'resources/Picture1.3.2.png', '2026-04-27 16:24:27', NULL),
(6, 1, 'act2', 'resources/Picture1.4.png', '2026-04-27 16:25:04', NULL),
(7, 1, 'feedback1', 'resources/fb1.png', '2026-04-27 16:28:54', NULL),
(8, 1, 'feedback2', 'resources/fb2.png', '2026-04-27 16:30:22', NULL),
(9, 1, 'feedback3', 'resources/fb3.png', '2026-04-27 16:30:50', NULL),
(10, 3, 'Hero Image', 'resources/Picture2.1.1.jpg', '2026-05-11 18:14:17', NULL),
(11, 3, 'Switch Image 1', 'resources/Picture2.3.2.png', '2026-05-11 18:14:17', NULL),
(12, 3, 'Switch Image 2', 'resources/Picture2.4.png', '2026-05-11 18:14:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'coordinator', NULL, NULL),
(2, 'teacher', NULL, NULL),
(3, 'parent', NULL, NULL),
(4, 'student', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `max_students` int(11) NOT NULL DEFAULT 25,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `class_id`, `section_name`, `teacher_id`, `max_students`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'A', 1, 25, 1, '2026-04-23 15:27:23', NULL),
(2, 2, 'A', 2, 25, 1, '2026-04-23 15:28:04', NULL),
(3, 3, 'A', 3, 25, 1, '2026-04-23 15:29:04', NULL),
(4, 1, 'B', 1, 25, 1, '2026-04-23 15:29:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('mNhWmuvud4RyRno15WUnVFBISt8E3vjTxq0a3pn1', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ0hXcXJxcnZFdEkyTmdLZkd2QWptNmZzcHJhY1p3bnQ5a0tYUDNkSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvYWN0aXZpdGllcyI7czo1OiJyb3V0ZSI7Tjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1778532224);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `full_name`, `date_of_birth`, `created_at`, `updated_at`) VALUES
(1, 4, 'Test Student', '2023-07-11', '2026-04-22 15:20:21', '2026-04-22 15:20:21'),
(2, 9, 'Test Student 1', '2022-02-13', '2026-04-22 15:21:44', '2026-04-22 15:21:44'),
(3, 10, 'Test Student 2', '2021-11-22', '2026-04-22 15:23:25', '2026-04-22 15:23:25'),
(5, 14, 'Test Student 3', '2023-04-11', '2026-05-11 18:18:26', '2026-05-11 18:18:26');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `full_name`, `email`, `phone`, `qualification`, `hire_date`, `created_at`, `updated_at`) VALUES
(1, 2, 'Test Teacher', 'teacher@test.com', '03 455 674', NULL, NULL, '2026-04-22 14:51:07', NULL),
(2, 5, 'Test Teacher 1', 'teacher1@test.com', '07 625 267', NULL, NULL, '2026-04-22 14:52:25', NULL),
(3, 6, 'Test Teacher 2', 'teacher2@test.com', '07 561 425', NULL, NULL, '2026-04-22 14:55:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_activity_log`
--

CREATE TABLE `teacher_activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `teacher` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `duration` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'coordinator@test.com', '$2y$12$eNv8w06pK8vgf6fK.66DkuRQveF.0xvZ1u3DG8RAoZniQmPUCSMI2', 1, 1, '2026-04-27 14:31:37', '2026-04-27 14:31:37'),
(2, 'teacher@test.com', '$2y$12$8zcbaFsjbJce6zpe4hUXxO.kOahuj63DFa0t0v3e1JfmCwT8zbRyi', 2, 1, '2026-04-22 17:41:36', '2026-04-22 14:41:36'),
(3, 'parent@test.com', '$2y$12$orOIl5cvJQdDJNcBbCV5V.KtNIqCnOa3S0LV.o6AzTF6hDTeWjllW', 3, 1, '2026-04-22 14:42:45', '2026-04-22 14:42:45'),
(4, 'test.student@student.kinderbot.com', '$2y$12$n3W8eotMCl/1rFomk83nVO4rZ7MUHHXFMLt0xZN4EsUUs/NEjXlyS', 3, 1, '2026-04-22 14:43:51', '2026-04-22 14:43:51'),
(5, 'teacher1@test.com', '$2y$12$YRGq8fdMZ.3Dfmj9i2Gw1ug6oxIpJo7eZUJazqoLqfstOqelFvXdG', 2, 1, '2026-04-22 14:44:56', '2026-04-22 14:44:56'),
(6, 'teacher2@test.com', '$2y$12$iW8xRXmEfMuiA7exhmBecuKKEyF5Mh8D8imqqi4Z.LlqBiBsszsvO', 2, 1, '2026-04-22 14:45:50', '2026-04-22 14:45:50'),
(7, 'parent1@test.com', '$2y$12$A4L82zrAcqzyLf2cirXli.ad4GFBa4UViNKMiNSYEu2tn.cmBZtf6', 3, 1, '2026-04-22 14:46:37', '2026-04-22 14:46:37'),
(8, 'parent2@test.com', '$2y$12$gOiyW.nwXHFr29qWzGS7auwOCJdIlGm6hr/JtnuHisfvfTvejMNIi', 3, 1, '2026-04-22 14:47:22', '2026-04-22 14:47:22'),
(9, 'test.student.1@student.kinderbot.com', '$2y$12$NpLVPD5I7HmLvJxVpaQq1.FADC/JfEi1GVFkkLGdx7n4mgomSR5Ki', 3, 1, '2026-04-22 14:48:31', '2026-04-22 14:48:31'),
(10, 'test.student.2@student.kinderbot.com', '$2y$12$9TjTrIwutWo374kIaqi0tedGUjhfr9YU2pMMXTTsg/VjYN6uB2Ww.', 3, 1, '2026-04-22 14:49:20', '2026-04-22 14:49:20'),
(13, 'parent3@test.com', '$2y$12$iit7IQbD420G4MLWf.Y8xuS0RgfAbwX0mtJgdAVWRSQFhF3bpMyZW', 3, 1, '2026-05-11 18:17:40', '2026-05-11 18:17:40'),
(14, 'test.student.3@student.kinderbot.com', '$2y$12$jp12wyCyRezviRvPg1y18eZO7H1HCXErytG77zRRx3Wb1.N3EsYL.', 3, 1, '2026-05-11 18:18:26', '2026-05-11 18:18:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_class_id_foreign` (`class_id`);

--
-- Indexes for table `activity_animations`
--
ALTER TABLE `activity_animations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_animations_activity_id_foreign` (`activity_id`);

--
-- Indexes for table `activity_completions`
--
ALTER TABLE `activity_completions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_completions_student_id_foreign` (`student_id`),
  ADD KEY `activity_completions_activity_id_foreign` (`activity_id`),
  ADD KEY `activity_completions_activity_completion_status_id_foreign` (`activity_completion_status_id`);

--
-- Indexes for table `activity_completion_statuses`
--
ALTER TABLE `activity_completion_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_steps`
--
ALTER TABLE `activity_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_steps_activity_id_foreign` (`activity_id`);

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assessments_student_id_foreign` (`student_id`),
  ADD KEY `assessments_teacher_id_foreign` (`teacher_id`),
  ADD KEY `assessments_competency_id_foreign` (`competency_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classes_program_id_foreign` (`program_id`);

--
-- Indexes for table `competencies`
--
ALTER TABLE `competencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `competencies_activity_id_foreign` (`activity_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollments_student_id_foreign` (`student_id`),
  ADD KEY `enrollments_section_id_foreign` (`section_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parents_user_id_foreign` (`user_id`);

--
-- Indexes for table `parent_student`
--
ALTER TABLE `parent_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_student_parent_id_foreign` (`parent_id`),
  ADD KEY `parent_student_student_id_foreign` (`student_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resources_activity_id_foreign` (`activity_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sections_class_id_foreign` (`class_id`),
  ADD KEY `sections_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `students_user_id_foreign` (`user_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teachers_user_id_foreign` (`user_id`);

--
-- Indexes for table `teacher_activity_log`
--
ALTER TABLE `teacher_activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `activity_animations`
--
ALTER TABLE `activity_animations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_completions`
--
ALTER TABLE `activity_completions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `activity_completion_statuses`
--
ALTER TABLE `activity_completion_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `activity_steps`
--
ALTER TABLE `activity_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `competencies`
--
ALTER TABLE `competencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `parent_student`
--
ALTER TABLE `parent_student`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `teacher_activity_log`
--
ALTER TABLE `teacher_activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_animations`
--
ALTER TABLE `activity_animations`
  ADD CONSTRAINT `activity_animations_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_completions`
--
ALTER TABLE `activity_completions`
  ADD CONSTRAINT `activity_completions_activity_completion_status_id_foreign` FOREIGN KEY (`activity_completion_status_id`) REFERENCES `activity_completion_statuses` (`id`),
  ADD CONSTRAINT `activity_completions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_completions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `activity_steps`
--
ALTER TABLE `activity_steps`
  ADD CONSTRAINT `activity_steps_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assessments`
--
ALTER TABLE `assessments`
  ADD CONSTRAINT `assessments_competency_id_foreign` FOREIGN KEY (`competency_id`) REFERENCES `competencies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assessments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assessments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `competencies`
--
ALTER TABLE `competencies`
  ADD CONSTRAINT `competencies_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parent_student`
--
ALTER TABLE `parent_student`
  ADD CONSTRAINT `parent_student_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parent_student_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sections_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
