-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2026 at 11:27 PM
-- Server version: 11.4.13-MariaDB-cll-lve-log
-- PHP Version: 8.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `totaxqrf_aspi`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(180) NOT NULL,
  `details` varchar(500) NOT NULL DEFAULT '',
  `ip_address` varchar(45) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `admin_id`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 1, 'diploma_program_saved', 'কম্পিউটার সায়েন্স অ্যান্ড টেকনোলজি', '103.136.107.19', '2026-11-02 09:50:58'),
(2, 1, 'diploma_program_saved', 'ইলেকট্রিক্যাল টেকনোলজি', '103.136.107.19', '2026-11-02 09:51:09'),
(3, 1, 'diploma_program_saved', 'কম্পিউটার সায়েন্স অ্যান্ড টেকনোলজি', '103.136.107.4', '2026-11-02 11:19:40'),
(4, 1, 'diploma_program_saved', 'কম্পিউটার সায়েন্স অ্যান্ড টেকনোলজি', '103.136.107.4', '2026-11-02 11:20:44'),
(5, 1, 'diploma_program_saved', 'ইলেকট্রিক্যাল টেকনোলজি', '103.136.107.4', '2026-11-02 11:22:53'),
(6, 1, 'diploma_program_saved', 'কম্পিউটার সায়েন্স অ্যান্ড টেকনোলজি', '103.136.107.4', '2026-11-02 11:23:08'),
(7, 1, 'course_saved', 'কম্পিউটার অপারেশন', '103.136.107.28', '2026-11-02 11:26:40'),
(8, 1, 'course_saved', 'গ্রাফিক্স ডিজাইন ফর ফ্রিল্যান্সিং', '103.136.107.28', '2026-11-02 11:47:26'),
(9, 1, 'course_saved', 'গ্রাফিক্স ডিজাইন', '103.136.107.28', '2026-11-02 11:49:05'),
(10, 1, 'course_saved', 'ইলেকট্রিক্যাল ইনস্টলেশন অ্যান্ড মেইনটেন্যান্স', '103.136.107.28', '2026-11-02 11:54:04'),
(11, 1, 'course_saved', 'অটোক্যাড 2D ও 3D', '103.136.107.4', '2026-11-02 11:56:00'),
(12, 1, 'course_saved', 'কনজিউমার ইলেকট্রনিক্স', '103.136.107.4', '2026-11-02 11:57:48'),
(13, 1, 'messages_updated', 'হোমপেজের বার্তা ও প্রোফাইল আপডেট', '103.136.107.28', '2026-11-02 13:23:33'),
(14, 1, 'site_settings_updated', 'সাইট সেটিংস আপডেট', '103.136.107.28', '2026-11-02 14:15:28'),
(15, 1, 'content_post_created', 'বড় ছবি বা ভিডিও-সমৃদ্ধ পোস্টে কভার ছবি দিন এবং মূল কনটেন্ট', '103.136.107.28', '2026-11-02 18:41:13'),
(16, 1, 'page_content_updated', 'প্রতিষ্ঠান পরিচিতি Global Editor কনটেন্ট আপডেট', '103.136.107.4', '2026-11-02 19:01:14'),
(17, 1, 'page_content_updated', 'প্রতিষ্ঠান পরিচিতি Global Editor কনটেন্ট আপডেট', '103.136.107.4', '2026-11-02 20:10:52'),
(18, 1, 'page_content_updated', 'প্রতিষ্ঠান পরিচিতি Global Editor কনটেন্ট আপডেট', '103.136.107.4', '2026-11-02 20:11:17'),
(19, 1, 'page_content_updated', 'প্রতিষ্ঠান পরিচিতি Global Editor কনটেন্ট আপডেট', '103.136.107.4', '2026-11-02 20:33:18'),
(20, 1, 'page_content_updated', 'প্রতিষ্ঠাতার পরিচিতি Global Editor কনটেন্ট আপডেট', '103.136.107.28', '2026-11-03 03:11:49'),
(21, 1, 'page_content_updated', 'আসহাব সিরাজ ফাউন্ডেশন Global Editor কনটেন্ট আপডেট', '103.136.107.28', '2026-11-03 03:11:58'),
(22, 1, 'page_content_updated', 'প্রতিষ্ঠাতার পরিচিতি Global Editor কনটেন্ট আপডেট', '103.136.107.28', '2026-11-03 03:12:11'),
(23, 1, 'page_content_updated', 'আসহাব সিরাজ ফাউন্ডেশন Global Editor কনটেন্ট আপডেট', '103.136.107.28', '2026-11-03 03:12:20'),
(24, NULL, 'social_media_updated', 'সোশ্যাল মিডিয়া লিংক ও প্রদর্শন সেটিংস আপডেট', '', '2026-11-03 08:46:09');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(80) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'Tarique', '$2y$10$DrLi53Au8FTXEE0KEbA4JuAjvYaXpp5gWffiAZo8guFOheRoFhsm2', '2026-10-14 08:21:43');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `applicant_name` varchar(180) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `course_id` int(10) UNSIGNED DEFAULT NULL,
  `education` varchar(180) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `status` enum('new','contacted','enrolled','closed') NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content_posts`
--

CREATE TABLE `content_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(240) NOT NULL,
  `category` enum('news','event','workshop','notice') NOT NULL DEFAULT 'news',
  `excerpt` varchar(600) NOT NULL DEFAULT '',
  `cover_image` varchar(255) NOT NULL DEFAULT '',
  `content_json` longtext NOT NULL,
  `event_date` datetime DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(220) NOT NULL,
  `level_label` varchar(100) NOT NULL DEFAULT '',
  `category` varchar(100) NOT NULL DEFAULT 'NSDA Skill Course',
  `summary` text NOT NULL,
  `icon` varchar(80) NOT NULL DEFAULT 'fa-graduation-cap',
  `image_path` varchar(255) NOT NULL DEFAULT 'assets/campus-5.jpg',
  `duration_label` varchar(120) NOT NULL DEFAULT '',
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `level_label`, `category`, `summary`, `icon`, `image_path`, `duration_label`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'কম্পিউটার অপারেশন', 'লেভেল ২ ও ৩', 'NSDA Skill Course', 'কম্পিউটার ব্যবহার, অফিস অ্যাপ্লিকেশন ও ডিজিটাল কর্মদক্ষতা উন্নয়নের প্রশিক্ষণ।', 'fa-desktop', 'live/uploads/site/course-20261102112640-d0c80f52.png', 'ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী', 'active', 1, '2026-11-02 03:25:09', '2026-11-02 11:26:40'),
(2, 'গ্রাফিক্স ডিজাইন', 'লেভেল ৩', 'NSDA Skill Course', 'ডিজাইন সফটওয়্যার, ভিজ্যুয়াল কমিউনিকেশন ও বাস্তবভিত্তিক ডিজাইন প্র্যাকটিস।', 'fa-pen-nib', 'live/uploads/site/course-20261102114905-e13cef13.png', 'ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী', 'active', 2, '2026-11-02 03:25:09', '2026-11-02 11:49:05'),
(3, 'গ্রাফিক্স ডিজাইন ফর ফ্রিল্যান্সিং', 'লেভেল ৪', 'NSDA Skill Course', 'ডিজাইন পোর্টফোলিও, ক্লায়েন্ট ব্রিফ ও ফ্রিল্যান্সিং প্রস্তুতিমূলক দক্ষতা।', 'fa-laptop-code', 'live/uploads/site/course-20261102114726-fe42f917.png', 'ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী', 'active', 3, '2026-11-02 03:25:09', '2026-11-02 11:47:26'),
(4, 'ইলেকট্রিক্যাল ইনস্টলেশন অ্যান্ড মেইনটেন্যান্স', 'লেভেল ২ ও ৩', 'NSDA Skill Course', 'নিরাপদ বৈদ্যুতিক ইনস্টলেশন, টুলস ব্যবহার ও ব্যবহারিক রক্ষণাবেক্ষণ প্রশিক্ষণ।', 'fa-bolt', 'live/uploads/site/course-20261102115404-49d4a528.png', 'ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী', 'active', 4, '2026-11-02 03:25:09', '2026-11-02 11:54:04'),
(5, 'অটোক্যাড 2D ও 3D', 'লেভেল ৩', 'NSDA Skill Course', 'টেকনিক্যাল ড্রয়িং, 2D ড্রাফটিং ও 3D মডেলিংয়ের প্রাথমিক থেকে প্রয়োগভিত্তিক প্রশিক্ষণ।', 'fa-drafting-compass', 'live/uploads/site/course-20261102115600-30c18c55.png', 'ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী', 'active', 5, '2026-11-02 03:25:09', '2026-11-02 11:56:00'),
(6, 'কনজিউমার ইলেকট্রনিক্স', 'লেভেল ১', 'NSDA Skill Course', 'ইলেকট্রনিক্স যন্ত্রাংশ শনাক্তকরণ, মৌলিক পরীক্ষা ও নিরাপদ কাজের অনুশীলন।', 'fa-microchip', 'live/uploads/site/course-20261102115748-f2053d98.png', 'ব্যাচ ও অনুমোদিত কোর্স কাঠামো অনুযায়ী', 'active', 6, '2026-11-02 03:25:09', '2026-11-02 11:57:48');

-- --------------------------------------------------------

--
-- Table structure for table `diploma_programs`
--

CREATE TABLE `diploma_programs` (
  `id` int(10) UNSIGNED NOT NULL,
  `technology_name` varchar(220) NOT NULL,
  `code` varchar(50) NOT NULL DEFAULT '',
  `session_label` varchar(100) NOT NULL DEFAULT '',
  `current_semester` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL DEFAULT 'assets/campus-5.jpg',
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diploma_programs`
--

INSERT INTO `diploma_programs` (`id`, `technology_name`, `code`, `session_label`, `current_semester`, `description`, `image_path`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 'ইলেকট্রিক্যাল টেকনোলজি', 'ET', '২০২৪ - ২০২৭', '১ম - ৪র্থ', 'চার বছর মেয়াদি এই শিক্ষাক্রমে বিদ্যুৎ উৎপাদন, সঞ্চালন, বণ্টন এবং ইলেকট্রিক্যাল সার্কিট ও ডিভাইসের ওপর তাত্ত্বিক ও ব্যবহারিক জ্ঞান প্রদান করা হয়, যা শিক্ষার্থীদের দক্ষ প্রকৌশলী হিসেবে গড়ে তোলে।', 'live/uploads/site/diploma-20261102112253-b627c1eb.png', 'active', 2, '2026-11-02 09:49:45', '2026-11-02 11:22:53'),
(3, 'কম্পিউটার সায়েন্স অ্যান্ড টেকনোলজি', 'CST', '২০২৪ - ২০২৭', '১ম - ৪র্থ', 'চার বছর মেয়াদি এই শিক্ষাক্রমে সফটওয়্যার ডেভেলপমেন্ট, হার্ডওয়্যার, নেটওয়ার্কিং এবং প্রোগ্রামিং বিষয়ে তাত্ত্বিক ও ব্যবহারিক শিক্ষা প্রদান করা হয়, যা শিক্ষার্থীদের আইটি সেক্টরে ক্যারিয়ার গড়তে প্রস্তুত করে।', 'live/uploads/site/diploma-20261102111940-02267d2e.png', 'active', 1, '2026-11-02 11:19:40', '2026-11-02 11:23:08');

-- --------------------------------------------------------

--
-- Table structure for table `downloadable_forms`
--

CREATE TABLE `downloadable_forms` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(220) NOT NULL,
  `slug` varchar(180) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'সাধারণ ফরম',
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(120) NOT NULL,
  `file_size` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `show_home` tinyint(1) NOT NULL DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `download_count` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `downloadable_forms`
--

INSERT INTO `downloadable_forms` (`id`, `title`, `slug`, `description`, `category`, `file_path`, `original_name`, `mime_type`, `file_size`, `status`, `show_home`, `start_date`, `end_date`, `download_count`, `created_at`, `updated_at`) VALUES
(1, 'উপবৃত্তির আবেদন ফরম', 'stipend-application', 'কারিগরি শিক্ষা অধিদপ্তরের স্টাইপেন্ড MIS সফটওয়্যারে উপবৃত্তির জন্য শিক্ষার্থী আবেদন ফরম (SAF)। ফরমটি ডাউনলোড করে প্রয়োজনীয় তথ্য পূরণ করুন এবং প্রতিষ্ঠানের নির্দেশনা অনুযায়ী জমা দিন।', 'উপবৃত্তি ও আর্থিক সহায়তা', 'uploads/forms/form-20261111051653-dc18be3f3f.pdf', 'উপবৃত্তির আবেদন ফরম.pdf', 'application/pdf', 366583, 'published', 1, NULL, NULL, 25, '2026-11-11 05:11:49', '2026-11-18 09:47:25');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(220) NOT NULL DEFAULT '',
  `image_path` varchar(255) NOT NULL,
  `alt_text` varchar(220) NOT NULL DEFAULT '',
  `status` enum('published','hidden') NOT NULL DEFAULT 'published',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `title`, `image_path`, `alt_text`, `status`, `sort_order`, `created_at`) VALUES
(1, 'আমাদের ক্যাম্পাস', 'assets/campus-1.webp', 'আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট ক্যাম্পাস', 'published', 10, '2026-11-02 13:04:44'),
(2, 'প্রশিক্ষণ কার্যক্রম', 'assets/campus-2.webp', 'প্রশিক্ষণ কার্যক্রম', 'published', 20, '2026-11-02 13:04:44'),
(3, 'কারিগরি শিক্ষার গুরুত্ব বিষয়ক সেমিনার', 'assets/campus-3.webp', 'কারিগরি শিক্ষার গুরুত্ব বিষয়ক সেমিনার', 'published', 30, '2026-11-02 13:04:44'),
(4, 'আমাদের শিক্ষার্থী', 'assets/campus-5.jpg', 'আমাদের শিক্ষার্থী', 'published', 40, '2026-11-02 13:04:44');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(220) NOT NULL,
  `notice_date` date NOT NULL,
  `description` text NOT NULL,
  `pdf_path` varchar(255) NOT NULL,
  `pdf_original_name` varchar(255) NOT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'published',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `title`, `notice_date`, `description`, `pdf_path`, `pdf_original_name`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'শারদীয় দুর্গাপূজা ও শ্রী শ্রী লক্ষ্মীপূজার ছুটি', '2026-10-15', '', 'uploads/notices/f07143574d195098889e98eda8e2a6013871.pdf', '26.10 - শারদীয় দুর্গাপূজা ও শ্রী শ্রী লক্ষ্মীপূজার ছুটি.pdf', 'published', 1, '2026-10-14 08:26:14', '2026-10-15 06:28:32'),
(2, 'আবাসিক আবেদন ফরম সংগ্রহ', '2026-11-02', '', 'uploads/notices/445b1c0f1239c3a9f8c6417a9d1ec3fe42e4.pdf', '26.11 - আবাসিক আবেদন ফরম সংগ্রহ.pdf', 'published', 1, '2026-11-02 10:47:10', '2026-11-02 11:32:25'),
(3, 'শ্রী শ্রী শ্যামাপূজার ছুটি', '2026-11-05', '', 'uploads/notices/f8335492cc2e9cedf8a98a401169d7beb0d4.pdf', '26.11  - শ্রী শ্রী শ্যামাপূজা.pdf', 'published', 1, '2026-11-05 06:27:06', '2026-11-05 06:27:06'),
(4, 'উপবৃত্তির জন্য আবেদন ও ফরম ডাউনলোড', '2026-11-11', '<p style=\"text-align:center; margin:20px 0;\">\r\n  <a href=\"/live/form.php?slug=stipend-application\"\r\n     target=\"_blank\"\r\n     rel=\"noopener\"\r\n     style=\"display:inline-block; background:#087a45; color:#fff; padding:12px 25px; border-radius:6px; text-decoration:none; font-weight:bold;\">\r\n     ⬇ উপবৃত্তির আবেদন ফরম — ডাউনলোড / বিস্তারিত\r\n  </a>\r\n</p>', 'uploads/notices/6e00853785d1899fadae8eb1d788c4cb6501.pdf', '26.11 - উবৃত্তির জন্য আবেদন ও ফরম ডাউনলোড.pdf', 'published', 1, '2026-11-11 06:57:22', '2026-11-11 06:58:26');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`setting_key`, `setting_value`, `updated_at`) VALUES
('address', 'দক্ষিণ হাশিমপুর, দোহাজারী, চন্দনাইশ, চট্টগ্রাম', '2026-11-02 03:25:09'),
('admission_status', 'ভর্তি চলছে', '2026-11-02 03:25:09'),
('diploma_programs', '', '2026-11-02 14:15:28'),
('diploma_semesters', '', '2026-11-02 14:15:28'),
('email', 'info@aspi.edu.bd', '2026-11-02 03:25:09'),
('facebook', 'https://www.facebook.com/ashabsirajpolytechnicinstitute', '2026-11-02 03:25:09'),
('footer_note', 'দক্ষতা অর্জনের দীপ্ত প্রতিজ্ঞায় গঠিত একটি আধুনিক কারিগরি শিক্ষা প্রতিষ্ঠান।', '2026-11-02 03:25:09'),
('foundation_about', '', '2026-11-02 14:15:28'),
('founder_bio', '', '2026-11-02 14:15:28'),
('founder_name', '', '2026-11-02 14:15:28'),
('founder_role', '', '2026-11-02 14:15:28'),
('hero_image', 'assets/campus-1.webp', '2026-11-02 03:25:09'),
('hero_kicker', 'NSDA SKILL DEVELOPMENT', '2026-11-02 03:25:09'),
('hero_text', 'চাহিদাভিত্তিক ব্যবহারিক প্রশিক্ষণের মাধ্যমে কর্মজীবনের জন্য প্রয়োজনীয় দক্ষতা অর্জন করুন।', '2026-11-02 03:25:09'),
('hero_title', 'দক্ষতা অর্জন করুন, গড়ুন আপনার ভবিষ্যৎ', '2026-11-02 03:25:09'),
('institution_intro', '', '2026-11-02 14:15:28'),
('message1_name', 'নুরুল ইসলাম', '2026-11-02 13:23:33'),
('message1_photo', 'live/uploads/site/message-profile-20261102132333-9961050b.png', '2026-11-02 13:23:33'),
('message1_role', 'সভাপতি', '2026-11-02 13:23:33'),
('message1_text', 'শিক্ষা জাতির মেরুদণ্ড এবং দক্ষতাভিত্তিক শিক্ষা একটি সমৃদ্ধ, আত্মনির্ভরশীল ও উন্নত জাতি গঠনের অন্যতম প্রধান হাতিয়ার। বর্তমান প্রতিযোগিতামূলক বিশ্বে সাধারণ শিক্ষার পাশাপাশি কারিগরি ও বৃত্তিমূলক শিক্ষার গুরুত্ব অপরিসীম। প্রযুক্তিনির্ভর এই যুগে দক্ষতা অর্জনের মাধ্যমে তরুণ প্রজন্মকে কর্মমুখী, আত্মনির্ভরশীল ও দেশ গঠনে উপযুক্ত করে তোলা আমাদের অন্যতম লক্ষ্য।\r\n\r\nআসহাব সিরাজ ফাউন্ডেশন প্রতিষ্ঠালগ্ন থেকেই শিক্ষার প্রসার, মানবসম্পদের উন্নয়ন এবং সামাজিক অগ্রগতিতে অবদান রাখার প্রত্যয়ে কাজ করে যাচ্ছে। এই লক্ষ্য বাস্তবায়নের অংশ হিসেবে আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট দক্ষ, যোগ্য, নৈতিকতাসম্পন্ন ও প্রযুক্তিজ্ঞানসমৃদ্ধ জনশক্তি গড়ে তোলার জন্য নিরলসভাবে কাজ করছে।\r\n\r\nআমরা বিশ্বাস করি, মানসম্মত কারিগরি শিক্ষা একজন শিক্ষার্থীকে শুধু কর্মসংস্থানের উপযোগী করে তোলে না, বরং তাকে উদ্যোক্তা হিসেবে গড়ে ওঠার এবং দেশের অর্থনৈতিক উন্নয়নে অবদান রাখার সুযোগ সৃষ্টি করে। তাই শিক্ষার্থীদের আধুনিক প্রযুক্তিগত জ্ঞান, ব্যবহারিক দক্ষতা, শৃঙ্খলাবোধ ও মানবিক মূল্যবোধে সমৃদ্ধ করে গড়ে তোলাই আমাদের অঙ্গীকার।\r\n\r\nআমি আশা করি, আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউটের শিক্ষক, কর্মকর্তা, কর্মচারী, শিক্ষার্থী ও অভিভাবকদের সম্মিলিত প্রচেষ্টায় প্রতিষ্ঠানটি কারিগরি শিক্ষার ক্ষেত্রে একটি আদর্শ ও আস্থার প্রতিষ্ঠানে পরিণত হবে।\r\n\r\nপরিশেষে, প্রতিষ্ঠানের সার্বিক উন্নতি, শিক্ষার্থীদের উজ্জ্বল ভবিষ্যৎ এবং দেশ ও জাতির কল্যাণ কামনা করছি।\r\n\r\nধন্যবাদান্তে,\r\nসভাপতি\r\nআসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট', '2026-11-02 13:23:33'),
('message2_name', 'আফনান ইসলাম', '2026-11-02 13:23:33'),
('message2_photo', 'live/uploads/site/message-profile-20261102132333-01091838.png', '2026-11-02 13:23:33'),
('message2_role', 'চেয়ারম্যান - আসহাব সিরাজ ফাউন্ডেশন', '2026-11-02 13:23:33'),
('message2_text', 'একটি উন্নত ও সমৃদ্ধ বাংলাদেশ গড়ে তুলতে হলে তরুণ প্রজন্মকে দক্ষতা, জ্ঞান, প্রযুক্তি ও নৈতিকতার সমন্বয়ে গড়ে তুলতে হবে। বর্তমান বিশ্বে কর্মসংস্থান ও অর্থনৈতিক অগ্রগতির জন্য কারিগরি শিক্ষার বিকল্প নেই। বাস্তবমুখী ও আধুনিক প্রযুক্তিনির্ভর শিক্ষা তরুণদের আত্মকর্মসংস্থান সৃষ্টি, উদ্যোক্তা হিসেবে আত্মপ্রকাশ এবং জাতীয় উন্নয়নে কার্যকর ভূমিকা রাখার সুযোগ করে দেয়।\r\n\r\nআসহাব সিরাজ ফাউন্ডেশনের অন্যতম উদ্দেশ্য হলো শিক্ষার প্রসার ঘটানো এবং নতুন প্রজন্মের জন্য সম্ভাবনাময় ভবিষ্যৎ নির্মাণে ভূমিকা রাখা। এ উদ্দেশ্যকে সামনে রেখে আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট শিক্ষার্থীদের মানসম্মত ডিপ্লোমা ইঞ্জিনিয়ারিং শিক্ষা, ব্যবহারিক প্রশিক্ষণ এবং কর্মমুখী দক্ষতা অর্জনের সুযোগ প্রদানে প্রতিশ্রুতিবদ্ধ।\r\n\r\nআমরা এমন একটি শিক্ষাবান্ধব পরিবেশ গড়ে তুলতে চাই, যেখানে প্রত্যেক শিক্ষার্থী তার মেধা ও যোগ্যতার বিকাশ ঘটিয়ে আত্মবিশ্বাসের সঙ্গে ভবিষ্যতের চ্যালেঞ্জ মোকাবিলা করতে পারবে। আধুনিক ল্যাবরেটরি, ব্যবহারিক শিক্ষার সুযোগ, অভিজ্ঞ শিক্ষকমণ্ডলী এবং শৃঙ্খলাপূর্ণ শিক্ষাব্যবস্থার মাধ্যমে শিক্ষার্থীদের পেশাগত জীবনের জন্য প্রস্তুত করাই আমাদের প্রত্যাশা।\r\n\r\nআমি মনে করি, শিক্ষা প্রতিষ্ঠানের প্রকৃত সাফল্য শুধু পরীক্ষার ফলাফলে সীমাবদ্ধ নয়; বরং শিক্ষার্থীদের দক্ষতা, সততা, দায়িত্ববোধ এবং কর্মজীবনে সফলতার মধ্যেই সেই সাফল্যের প্রকৃত প্রতিফলন ঘটে।\r\n\r\nআসহাব সিরাজ পলিটেকনিক ইনস্টিটিউটের অগ্রযাত্রায় সংশ্লিষ্ট সকলের আন্তরিক সহযোগিতা কামনা করছি। আমাদের সম্মিলিত প্রচেষ্টায় এই প্রতিষ্ঠান দক্ষ মানবসম্পদ গঠন ও জাতীয় উন্নয়নে গুরুত্বপূর্ণ অবদান রাখবে—এটাই আমার প্রত্যাশা।\r\n\r\nসকল শিক্ষার্থীর সুন্দর, সফল ও সম্ভাবনাময় ভবিষ্যৎ কামনা করছি।\r\n\r\nধন্যবাদান্তে,\r\nচেয়ারম্যান\r\nআসহাব সিরাজ ফাউন্ডেশন', '2026-11-02 13:23:33'),
('message3_name', 'প্রকৌ. মৃদুল কান্তি দেবনাথ', '2026-11-02 13:23:33'),
('message3_photo', 'live/uploads/site/message-profile-20261102132333-eec15b2d.jpg', '2026-11-02 13:23:33'),
('message3_role', 'অধ্যক্ষ', '2026-11-02 13:23:33'),
('message3_text', 'শিক্ষা মানুষের জ্ঞান, চিন্তা, দক্ষতা ও মননশীলতার বিকাশ ঘটায়। আর কারিগরি শিক্ষা সেই জ্ঞানকে বাস্তব জীবনে প্রয়োগের মাধ্যমে কর্মদক্ষতা অর্জনের পথ সুগম করে। চতুর্থ শিল্পবিপ্লব ও প্রযুক্তিনির্ভর বিশ্বব্যবস্থায় দক্ষ প্রকৌশলী ও প্রশিক্ষিত জনশক্তির চাহিদা ক্রমাগত বৃদ্ধি পাচ্ছে। এ বাস্তবতায় মানসম্মত কারিগরি শিক্ষা গ্রহণের মাধ্যমে শিক্ষার্থীদের নিজেদের ভবিষ্যৎ গড়ার পাশাপাশি দেশের উন্নয়নে অবদান রাখার সুযোগ রয়েছে।\r\n\r\nআসহাব সিরাজ পলিটেকনিক ইনস্টিটিউটে আমরা শিক্ষার্থীদের শুধু পাঠ্যবইয়ের জ্ঞানে সীমাবদ্ধ রাখতে চাই না; বরং তাত্ত্বিক জ্ঞানের পাশাপাশি ব্যবহারিক দক্ষতা, প্রযুক্তিগত সক্ষমতা, সৃজনশীলতা, শৃঙ্খলা ও নৈতিক মূল্যবোধে সমৃদ্ধ করে গড়ে তুলতে প্রতিশ্রুতিবদ্ধ। আমাদের লক্ষ্য হলো, প্রতিটি শিক্ষার্থী যেন নিজ নিজ প্রযুক্তিতে দক্ষতা অর্জন করে আত্মবিশ্বাসের সঙ্গে কর্মজীবনে প্রবেশ করতে পারে।\r\n\r\nপ্রতিষ্ঠানে মানসম্মত শিক্ষা, ব্যবহারিক প্রশিক্ষণ, আধুনিক প্রযুক্তির ব্যবহার এবং শিক্ষার্থীবান্ধব পরিবেশ নিশ্চিত করার জন্য আমরা নিরলসভাবে কাজ করে যাচ্ছি। একই সঙ্গে শিক্ষার্থীদের নিয়মিত অধ্যয়ন, ক্লাসে উপস্থিতি, ব্যবহারিক কাজের প্রতি মনোযোগ এবং সময়ানুবর্তিতার ওপর বিশেষ গুরুত্ব দেওয়া হয়।\r\n\r\nআমি বিশ্বাস করি, শিক্ষক, শিক্ষার্থী ও অভিভাবকদের পারস্পরিক সহযোগিতা এবং সম্মিলিত প্রচেষ্টার মাধ্যমে শিক্ষার কাঙ্ক্ষিত লক্ষ্য অর্জন সম্ভব। শিক্ষার্থীদের মনে রাখতে হবে, শুধু সনদ অর্জনই শিক্ষার চূড়ান্ত উদ্দেশ্য নয়; প্রকৃত লক্ষ্য হলো জ্ঞানকে দক্ষতায় রূপান্তরিত করে নিজের, পরিবারের ও দেশের কল্যাণে কাজে লাগানো।\r\n\r\nআসহাব সিরাজ পলিটেকনিক ইনস্টিটিউটের প্রতিটি শিক্ষার্থী দক্ষ, সৎ, দায়িত্বশীল ও মানবিক মানুষ হিসেবে গড়ে উঠুক—এই প্রত্যাশা করি। প্রতিষ্ঠানের সার্বিক উন্নয়ন এবং শিক্ষার্থীদের উজ্জ্বল ভবিষ্যৎ নিশ্চিত করতে আমি সকলের আন্তরিক সহযোগিতা কামনা করছি।\r\n\r\n।একটাই লক্ষ্য, হতে হবে দক্ষ।\r\n\r\nসকলের জন্য শুভকামনা।\r\n\r\nইতি,\r\nপ্রকৌ. মৃদুল কান্তি দেবনাথ\r\nঅধ্যক্ষ  \r\nআসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট', '2026-11-02 13:23:33'),
('nav_about_label', 'প্রতিষ্ঠান পরিচিতি', '2026-11-02 09:49:45'),
('nav_about_url', '#about', '2026-11-02 09:49:45'),
('nav_apply_label', 'কোর্স আবেদন', '2026-11-02 09:49:45'),
('nav_contact_label', 'যোগাযোগ', '2026-11-02 09:49:45'),
('nav_contact_url', '#contact', '2026-11-02 09:49:45'),
('nav_courses_label', 'NSDA কোর্স', '2026-11-02 09:49:45'),
('nav_courses_url', '#courses', '2026-11-02 09:49:45'),
('nav_diploma_label', 'ডিপ্লোমা', '2026-11-02 09:49:45'),
('nav_diploma_url', '#diploma', '2026-11-02 09:49:45'),
('nav_foundation_label', 'ফাউন্ডেশন', '2026-11-02 09:49:45'),
('nav_foundation_url', '#foundation', '2026-11-02 09:49:45'),
('nav_gallery_label', 'ফটো গ্যালারি', '2026-11-02 10:42:25'),
('nav_gallery_url', '#gallery', '2026-11-02 10:42:25'),
('nav_news_label', 'খবর ও ইভেন্ট', '2026-11-02 13:04:44'),
('nav_news_url', '/live/posts.php', '2026-11-02 13:04:44'),
('nav_notices_label', 'বিজ্ঞপ্তি', '2026-11-02 09:49:45'),
('nav_notices_url', '/live/notices.php', '2026-11-02 09:49:45'),
('notice_intro', 'আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউটের সর্বশেষ অফিসিয়াল বিজ্ঞপ্তি ও আপডেট।', '2026-11-02 03:25:09'),
('page_foundation_content', '{\"time\":1793675569790,\"blocks\":[{\"id\":\"aspi-0\",\"type\":\"header\",\"data\":{\"text\":\"আসহাব সিরাজ ফাউন্ডেশন\",\"level\":2}},{\"id\":\"aspi-1\",\"type\":\"paragraph\",\"data\":{\"text\":\"প্রতিষ্ঠাতার পরিচিতি\",\"level\":2}}],\"version\":\"aspi-global-1.2\"}', '2026-11-03 03:12:20'),
('page_foundation_content_title', 'আসহাব সিরাজ ফাউন্ডেশন', '2026-11-03 03:11:58'),
('page_founder_content', '{\"time\":1793675561469,\"blocks\":[{\"id\":\"aspi-0\",\"type\":\"header\",\"data\":{\"text\":\"আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট\",\"level\":2}},{\"id\":\"aspi-1\",\"type\":\"paragraph\",\"data\":{\"text\":\"প্রতিষ্ঠাতার পরিচিতি\",\"level\":2}}],\"version\":\"aspi-global-1.2\"}', '2026-11-03 03:12:11'),
('page_founder_content_title', 'প্রতিষ্ঠাতার পরিচিতি', '2026-11-03 03:11:49'),
('page_institution_content', '{\"time\":1793651628158,\"blocks\":[{\"id\":\"aspi-0\",\"type\":\"header\",\"data\":{\"text\":\"আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট\",\"level\":2}},{\"id\":\"aspi-1\",\"type\":\"paragraph\",\"data\":{\"text\":\"<strong>একটাই লক্ষ্য, হতে হবে দক্ষ।</strong>\",\"level\":2}},{\"id\":\"aspi-2\",\"type\":\"header\",\"data\":{\"text\":\"দক্ষতা অর্জনের দীপ্ত প্রতিজ্ঞায় গঠিত একটি আধুনিক কারিগরি শিক্ষা প্রতিষ্ঠান\",\"level\":3}},{\"id\":\"aspi-3\",\"type\":\"paragraph\",\"data\":{\"text\":\"আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট চট্টগ্রাম জেলার চন্দনাইশ উপজেলার দোহাজারী পৌরসভার দক্ষিণ হাশিমপুরে অবস্থিত একটি বেসরকারি কারিগরি শিক্ষা প্রতিষ্ঠান। ২০১৯ সালে প্রতিষ্ঠিত এই প্রতিষ্ঠানটি শিক্ষার্থীদের আধুনিক প্রযুক্তিনির্ভর, মানসম্মত ও কর্মমুখী শিক্ষা প্রদানের মাধ্যমে দক্ষ মানবসম্পদ গড়ে তোলার লক্ষ্যে কাজ করে যাচ্ছে।\",\"level\":2}},{\"id\":\"aspi-4\",\"type\":\"paragraph\",\"data\":{\"text\":\"বর্তমান বিশ্বের প্রতিযোগিতামূলক শ্রমবাজারে কারিগরি শিক্ষার গুরুত্ব অপরিসীম। প্রচলিত শিক্ষার পাশাপাশি হাতে-কলমে বাস্তবভিত্তিক প্রশিক্ষণ, প্রযুক্তিগত জ্ঞান ও পেশাগত দক্ষতা অর্জনের মাধ্যমে একজন শিক্ষার্থী নিজেকে দক্ষ কর্মী, আত্মনির্ভরশীল উদ্যোক্তা কিংবা ভবিষ্যৎ প্রযুক্তিবিদ হিসেবে গড়ে তুলতে পারে। এই বাস্তবতাকে গুরুত্ব দিয়ে আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট শিক্ষার্থীদের তাত্ত্বিক জ্ঞানের পাশাপাশি ব্যবহারিক দক্ষতা অর্জনের উপযোগী শিক্ষার পরিবেশ নিশ্চিত করতে প্রতিশ্রুতিবদ্ধ।\",\"level\":2}},{\"id\":\"aspi-5\",\"type\":\"header\",\"data\":{\"text\":\"আমাদের শিক্ষাক্রম\",\"level\":3}},{\"id\":\"aspi-6\",\"type\":\"paragraph\",\"data\":{\"text\":\"প্রতিষ্ঠানে বাংলাদেশ কারিগরি শিক্ষা বোর্ডের (BTEB) অধীন ডিপ্লোমা-ইন-ইঞ্জিনিয়ারিং শিক্ষাক্রম পরিচালিত হয়। বর্তমানে কম্পিউটার সায়েন্স অ্যান্ড টেকনোলজি এবং ইলেকট্রিক্যাল টেকনোলজিতে শিক্ষার্থীদের কারিগরি শিক্ষা গ্রহণের সুযোগ রয়েছে।\",\"level\":2}},{\"id\":\"aspi-7\",\"type\":\"paragraph\",\"data\":{\"text\":\"শিক্ষার্থীদের প্রযুক্তিগত জ্ঞান বৃদ্ধি, বাস্তব সমস্যা সমাধানের সক্ষমতা তৈরি এবং কর্মক্ষেত্রের উপযোগী দক্ষতা অর্জনের লক্ষ্যে পাঠদান ও ব্যবহারিক কার্যক্রম পরিচালনা করা হয়। পাশাপাশি শিক্ষার্থীদের শৃঙ্খলা, দায়িত্ববোধ, নৈতিকতা, সময়ানুবর্তিতা ও পেশাগত আচরণ গড়ে তোলার ওপর গুরুত্ব দেওয়া হয়।\",\"level\":2}},{\"id\":\"aspi-8\",\"type\":\"header\",\"data\":{\"text\":\"আধুনিক শিক্ষা ও ব্যবহারিক প্রশিক্ষণ\",\"level\":3}},{\"id\":\"aspi-9\",\"type\":\"paragraph\",\"data\":{\"text\":\"আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট শিক্ষার্থীদের জন্য একটি আধুনিক ও শিক্ষাবান্ধব পরিবেশ গড়ে তুলতে সচেষ্ট। প্রতিষ্ঠানের নিজস্ব ভূমি, আধুনিক ল্যাবরেটরি ও ওয়ার্কশপ, মাল্টিমিডিয়া-সুবিধাসম্পন্ন শ্রেণিকক্ষ এবং সিসিটিভি-নির্ভর নিরাপত্তাব্যবস্থা শিক্ষার্থীদের শিক্ষা ও প্রশিক্ষণ কার্যক্রমকে সহায়তা করে।\",\"level\":2}},{\"id\":\"aspi-10\",\"type\":\"paragraph\",\"data\":{\"text\":\"শ্রেণিকক্ষের পাঠদানের পাশাপাশি ব্যবহারিক ক্লাস, হাতে-কলমে কাজ, প্রযুক্তিনির্ভর শিক্ষা এবং বাস্তব কর্মক্ষেত্রের উপযোগী প্রশিক্ষণের মাধ্যমে শিক্ষার্থীদের দক্ষতা বিকাশে গুরুত্ব দেওয়া হয়।\",\"level\":2}},{\"id\":\"aspi-11\",\"type\":\"header\",\"data\":{\"text\":\"কারিগরি ও কর্মমুখী প্রশিক্ষণ\",\"level\":3}},{\"id\":\"aspi-12\",\"type\":\"paragraph\",\"data\":{\"text\":\"ডিপ্লোমা-ইন-ইঞ্জিনিয়ারিং শিক্ষাক্রমের পাশাপাশি দক্ষতাভিত্তিক প্রশিক্ষণের প্রসারেও প্রতিষ্ঠানটি গুরুত্ব প্রদান করে। সংশ্লিষ্ট কর্তৃপক্ষের অনুমোদন ও প্রযোজ্য ব্যবস্থার আওতায় বিভিন্ন স্বল্পমেয়াদি কারিগরি প্রশিক্ষণ পরিচালনার মাধ্যমে শিক্ষার্থীদের কর্মসংস্থান ও আত্মকর্মসংস্থানের উপযোগী করে গড়ে তোলার লক্ষ্য রয়েছে।\",\"level\":2}},{\"id\":\"aspi-13\",\"type\":\"header\",\"data\":{\"text\":\"শিক্ষার্থীবান্ধব পরিবেশ ও সুযোগ-সুবিধা\",\"level\":3}},{\"id\":\"aspi-14\",\"type\":\"paragraph\",\"data\":{\"text\":\"শিক্ষার্থীদের শিক্ষা গ্রহণের উপযোগী পরিবেশ নিশ্চিত করতে প্রতিষ্ঠানটি প্রয়োজনীয় অবকাঠামো ও সুবিধা উন্নয়নে গুরুত্ব দেয়। এর মধ্যে রয়েছে—\",\"level\":2}},{\"id\":\"aspi-15\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"items\":[{\"content\":\"কম্পিউটার সায়েন্স অ্যান্ড টেকনোলজি ও ইলেকট্রিক্যাল টেকনোলজিতে ডিপ্লোমা শিক্ষা।\"},{\"content\":\"ব্যবহারিক শিক্ষার জন্য ল্যাবরেটরি ও ওয়ার্কশপ।\"},{\"content\":\"মাল্টিমিডিয়া-সুবিধাসম্পন্ন শ্রেণিকক্ষ।\"},{\"content\":\"সিসিটিভি-নির্ভর নিরাপত্তাব্যবস্থা।\"},{\"content\":\"শিক্ষার্থীদের জন্য আবাসিক বা হোস্টেল-সুবিধার ব্যবস্থা।\"},{\"content\":\"বিশেষ চাহিদাসম্পন্ন শিক্ষার্থীদের চলাচলে সহায়ক র‍্যাম্প।\"},{\"content\":\"শিক্ষার্থীদের মেধা, মনন ও দক্ষতা বিকাশে সহায়ক শিক্ষা কার্যক্রম।\"}]}},{\"id\":\"aspi-16\",\"type\":\"header\",\"data\":{\"text\":\"আমাদের লক্ষ্য\",\"level\":3}},{\"id\":\"aspi-17\",\"type\":\"paragraph\",\"data\":{\"text\":\"মানসম্মত কারিগরি শিক্ষা ও আধুনিক প্রযুক্তিনির্ভর প্রশিক্ষণের মাধ্যমে দক্ষ, সৃজনশীল, আত্মবিশ্বাসী এবং কর্মক্ষম মানবসম্পদ তৈরি করা। শিক্ষার্থীদের মধ্যে বাস্তবমুখী জ্ঞান, সমস্যা সমাধানের সক্ষমতা, নৈতিক মূল্যবোধ ও আত্মনির্ভরশীলতার মানসিকতা গড়ে তোলাই আমাদের অন্যতম প্রধান লক্ষ্য।\",\"level\":2}},{\"id\":\"aspi-18\",\"type\":\"header\",\"data\":{\"text\":\"আমাদের উদ্দেশ্য\",\"level\":3}},{\"id\":\"aspi-19\",\"type\":\"paragraph\",\"data\":{\"text\":\"১. আধুনিক ও মানসম্মত কারিগরি শিক্ষা প্রদান করা।\",\"level\":2}},{\"id\":\"aspi-20\",\"type\":\"paragraph\",\"data\":{\"text\":\"২. তাত্ত্বিক জ্ঞানের পাশাপাশি ব্যবহারিক দক্ষতা বৃদ্ধি করা।\",\"level\":2}},{\"id\":\"aspi-21\",\"type\":\"paragraph\",\"data\":{\"text\":\"৩. প্রযুক্তিনির্ভর কর্মসংস্থান ও আত্মকর্মসংস্থানের সুযোগ তৈরিতে সহায়তা করা।\",\"level\":2}},{\"id\":\"aspi-22\",\"type\":\"paragraph\",\"data\":{\"text\":\"৪. শিক্ষার্থীদের নৈতিকতা, শৃঙ্খলা ও দায়িত্বশীলতার চর্চায় উদ্বুদ্ধ করা।\",\"level\":2}},{\"id\":\"aspi-23\",\"type\":\"paragraph\",\"data\":{\"text\":\"৫. দেশের শিল্প, প্রযুক্তি ও অর্থনৈতিক উন্নয়নে অবদান রাখতে সক্ষম দক্ষ জনশক্তি তৈরি করা।\",\"level\":2}},{\"id\":\"aspi-24\",\"type\":\"paragraph\",\"data\":{\"text\":\"৬. শিক্ষার্থীদের উচ্চতর শিক্ষা, পেশাগত উন্নয়ন ও ভবিষ্যৎ কর্মজীবনের জন্য প্রস্তুত করা।\",\"level\":2}},{\"id\":\"aspi-25\",\"type\":\"header\",\"data\":{\"text\":\"আমাদের অঙ্গীকার\",\"level\":3}},{\"id\":\"aspi-26\",\"type\":\"paragraph\",\"data\":{\"text\":\"আমরা বিশ্বাস করি, কারিগরি শিক্ষা শুধু একটি সনদ অর্জনের মাধ্যম নয়; এটি দক্ষতা, আত্মবিশ্বাস ও আত্মনির্ভরশীলতা অর্জনের পথ। তাই প্রতিটি শিক্ষার্থীকে তার সম্ভাবনা বিকাশের সুযোগ প্রদান এবং বাস্তব কর্মজীবনের উপযোগী করে গড়ে তোলার লক্ষ্যে আমরা প্রতিশ্রুতিবদ্ধ।\",\"level\":2}},{\"id\":\"aspi-27\",\"type\":\"paragraph\",\"data\":{\"text\":\"<strong>আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট—দক্ষতা অর্জনের দীপ্ত প্রতিজ্ঞায়, আত্মনির্ভর ভবিষ্যৎ গড়ার প্রত্যয়ে।</strong>\",\"level\":2}},{\"id\":\"aspi-28\",\"type\":\"paragraph\",\"data\":{\"text\":\"<strong>প্রতিষ্ঠান:</strong> আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট<br><strong>অবস্থান:</strong> দক্ষিণ হাশিমপুর, দোহাজারী পৌরসভা, চন্দনাইশ, চট্টগ্রাম।&nbsp;<br><strong>প্রতিষ্ঠাকাল:</strong> ২০১৯ সাল&nbsp;<br><strong>ওয়েবসাইট:</strong> <a href=\\\"https://aspi.edu.bd\\\" target=\\\"_blank\\\" rel=\\\"noopener noreferrer\\\">https://aspi.edu.bd</a>&nbsp;<br><strong>মূলমন্ত্র:</strong> একটাই লক্ষ্য, হতে হবে দক্ষ।\",\"level\":2}}],\"version\":\"aspi-global-1.2\"}', '2026-11-02 20:33:18'),
('page_institution_content_title', 'প্রতিষ্ঠান পরিচিতি', '2026-11-02 19:01:14'),
('phone', '+8801847310310', '2026-11-02 14:15:28'),
('site_name', 'আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট', '2026-11-02 03:25:09'),
('social_facebook_url', 'https://www.facebook.com/ashabsirajpolytechnicinstitute', '2026-11-03 08:46:09'),
('social_github_url', '', '2026-11-03 08:46:09'),
('social_instagram_url', '', '2026-11-03 08:46:09'),
('social_linkedin_url', '', '2026-11-03 08:46:09'),
('social_pinterest_url', '', '2026-11-03 08:46:09'),
('social_show_footer', '1', '2026-11-03 08:46:09'),
('social_show_header', '1', '2026-11-03 08:46:09'),
('social_telegram_url', '', '2026-11-03 08:46:09'),
('social_threads_url', '', '2026-11-03 08:46:09'),
('social_tiktok_url', '', '2026-11-03 08:46:09'),
('social_website_url', '', '2026-11-03 08:46:09'),
('social_whatsapp_url', 'https://wa.me/+8801847310310', '2026-11-03 08:46:09'),
('social_x_url', '', '2026-11-03 08:46:09'),
('social_youtube_url', '', '2026-11-03 08:46:09'),
('tagline', 'একটাই লক্ষ্য, হতে হবে দক্ষ।', '2026-11-02 03:25:09'),
('whatsapp', '+8801847310310', '2026-11-02 14:15:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activity_date` (`created_at`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_applications_status_date` (`status`,`created_at`),
  ADD KEY `fk_application_course` (`course_id`);

--
-- Indexes for table `content_posts`
--
ALTER TABLE `content_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_posts_public` (`status`,`published_at`,`id`),
  ADD KEY `idx_posts_category` (`category`,`status`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_courses_status_order` (`status`,`sort_order`);

--
-- Indexes for table `diploma_programs`
--
ALTER TABLE `diploma_programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_diploma_status_order` (`status`,`sort_order`);

--
-- Indexes for table `downloadable_forms`
--
ALTER TABLE `downloadable_forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_forms_status` (`status`),
  ADD KEY `idx_forms_category` (`category`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gallery_status_order` (`status`,`sort_order`,`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notice_status_date` (`status`,`notice_date`),
  ADD KEY `fk_notice_admin` (`created_by`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `content_posts`
--
ALTER TABLE `content_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `diploma_programs`
--
ALTER TABLE `diploma_programs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `downloadable_forms`
--
ALTER TABLE `downloadable_forms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_application_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notices`
--
ALTER TABLE `notices`
  ADD CONSTRAINT `fk_notice_admin` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
