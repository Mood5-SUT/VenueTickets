-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2026 at 09:09 PM
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
-- Database: `web-project`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `organizer_id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED DEFAULT NULL,
  `seat_map_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `doors_open` time DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `event_type` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `age_restriction` int(11) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `resale_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `resale_price_cap_percentage` decimal(5,2) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `organizer_id`, `venue_id`, `seat_map_id`, `event_date`, `end_date`, `doors_open`, `status`, `event_type`, `image_url`, `banner_url`, `age_restriction`, `is_featured`, `resale_enabled`, `resale_price_cap_percentage`, `metadata`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'OM KALTHOM', '7afl 8na2 kawkb shar2 allah yr7mha estmt3 han3isha tany w estmt3 b a8niha fi 2026 fi CFC', 2, 1, NULL, '2026-05-18 19:00:00', NULL, NULL, 'published', 'concert', '/storage/events/IGcGE0kO85fLi8XkcWctOEjCH1MxUfi7steqoSg9.jpg', NULL, NULL, 0, 1, NULL, '{\"base_price\":\"1000\"}', '2026-05-08 12:07:23', '2026-05-12 10:37:37', NULL),
(2, 'Barcelona vs Real Madrid', 'calsico el ard', 2, 2, NULL, '2026-08-18 23:38:00', NULL, NULL, 'published', 'sports', '/storage/events/RMJkub70zl2ZHqNvYT7grhb1un4P4FbA8wk68Rp9.jpg', NULL, NULL, 0, 1, NULL, '{\"base_price\":\"200\"}', '2026-05-08 17:39:39', '2026-05-12 10:02:10', NULL),
(3, 'Micheal Jakson', 'el 2ostora el 7ayah king el pop micheal jaskon now in cfc', 2, 1, NULL, '2026-05-15 23:40:00', NULL, NULL, 'published', 'concert', '/storage/events/jjsWV6d4b492z1HfJb6Ruaaw9ZZ9T9WIQzmyYmYE.jpg', NULL, NULL, 0, 1, NULL, '{\"base_price\":\"2000\"}', '2026-05-08 17:42:15', '2026-05-12 10:08:42', NULL),
(4, 'Cairokee & One direction', NULL, 2, 1, NULL, '2026-05-21 00:07:00', NULL, NULL, 'published', 'theater', '/storage/events/eIBdzfAjeXJHqgrBDubERH706vnXvVyeJ7yt1o2b.png', NULL, NULL, 0, 1, NULL, '{\"base_price\":\"1500.0\"}', '2026-05-08 18:08:07', '2026-05-12 11:03:56', NULL),
(5, 'cyber attacks', NULL, 2, 1, NULL, '2026-07-23 16:07:00', NULL, NULL, 'published', 'conference', '/storage/events/l9U4MEYecu3ozk1wmP8X8mmthmbg27vwGA4oEi39.jpg', NULL, NULL, 0, 1, NULL, '{\"base_price\":\"150.00\"}', '2026-05-12 10:07:25', '2026-05-12 10:08:55', NULL),
(6, 'Abdel baseet hamouda & Bob Marley', 'for the first time Abdel Baseet Hamouda & Bob Marley in one concert order your ticket now because its limited', 2, 1, NULL, '2026-05-19 16:50:00', NULL, NULL, 'published', 'concert', '/storage/events/30V2m7CNFxpnNmaCdcO6IU8mAeNYdtfiYk6PZkSt.png', NULL, NULL, 0, 1, NULL, '{\"base_price\":\"1500\"}', '2026-05-12 10:51:37', '2026-05-12 10:51:37', NULL);

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
-- Table structure for table `failed_payments`
--

CREATE TABLE `failed_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_provider` varchar(255) NOT NULL,
  `error_code` varchar(255) DEFAULT NULL,
  `error_message` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `raw_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_response`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_07_193800_create_venues_table', 1),
(5, '2026_05_07_193810_create_seat_maps_table', 1),
(6, '2026_05_07_193815_create_zones_table', 1),
(7, '2026_05_07_193820_create_events_table', 1),
(8, '2026_05_07_193830_create_seats_table', 1),
(9, '2026_05_07_193840_create_organizer_details_table', 1),
(10, '2026_05_07_194000_create_pricing_tiers_table', 1),
(11, '2026_05_07_194040_create_resale_listings_table', 1),
(12, '2026_05_07_194050_create_orders_table', 1),
(13, '2026_05_07_194060_create_tickets_table', 1),
(14, '2026_05_07_194100_create_promo_codes_table', 1),
(15, '2026_05_07_194110_create_promo_code_usage_table', 1),
(16, '2026_05_07_194120_create_payouts_table', 1),
(17, '2026_05_07_194130_create_user_bans_table', 1),
(18, '2026_05_07_194140_create_audit_logs_table', 1),
(19, '2026_05_07_194150_create_failed_payments_table', 1),
(20, '2026_05_07_194200_create_scan_logs_table', 1),
(21, '2026_05_07_194210_create_platform_settings_table', 1),
(22, '2026_05_08_134740_create_permission_tables', 1),
(23, '2026_05_12_000000_create_seat_holds_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 2),
(5, 'App\\Models\\User', 3),
(5, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 6),
(5, 'App\\Models\\User', 7);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `service_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `promo_code` varchar(255) DEFAULT NULL,
  `resale_listing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `refund_reason` text DEFAULT NULL,
  `refund_id` varchar(255) DEFAULT NULL,
  `billing_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`billing_info`)),
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `event_id`, `subtotal`, `service_fee`, `discount_amount`, `total_amount`, `currency`, `status`, `payment_status`, `payment_method`, `payment_id`, `promo_code`, `resale_listing_id`, `paid_at`, `refunded_at`, `refund_reason`, `refund_id`, `billing_info`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 'VT-69FE4B19D8521', 4, 1, 3000.00, 0.00, 0.00, 3000.00, 'USD', 'completed', 'paid', 'Credit Card (Mock)', NULL, NULL, NULL, '2026-05-08 17:44:09', NULL, NULL, NULL, NULL, NULL, '2026-05-08 17:44:09', '2026-05-08 17:44:09'),
(2, 'VT-69FE4B54B0837', 4, 3, 40000.00, 0.00, 0.00, 40000.00, 'USD', 'completed', 'paid', 'Credit Card (Mock)', NULL, NULL, NULL, '2026-05-08 17:45:08', NULL, NULL, NULL, NULL, NULL, '2026-05-08 17:45:08', '2026-05-08 17:45:08'),
(3, 'VT-69FE4FF3C1FA1', 4, 3, 3000.00, 0.00, 0.00, 3000.00, 'USD', 'completed', 'paid', 'Credit Card (Mock)', NULL, NULL, NULL, '2026-05-08 18:04:51', NULL, NULL, NULL, NULL, NULL, '2026-05-08 18:04:51', '2026-05-08 18:04:51'),
(4, 'VT-69FE5243C3C88', 4, 1, 2000.00, 0.00, 0.00, 2000.00, 'USD', 'completed', 'paid', 'Credit Card (Mock)', NULL, NULL, NULL, '2026-05-08 18:14:43', NULL, NULL, NULL, NULL, NULL, '2026-05-08 18:14:43', '2026-05-08 18:14:43'),
(5, 'VT-69FE535597418', 6, 1, 4500.00, 0.00, 0.00, 4500.00, 'USD', 'completed', 'paid', 'Credit Card (Mock)', NULL, NULL, NULL, '2026-05-08 18:19:17', NULL, NULL, NULL, NULL, NULL, '2026-05-08 18:19:17', '2026-05-08 18:19:17'),
(6, 'VT-69FE5389BD797', 6, 4, 4000.00, 0.00, 0.00, 4000.00, 'USD', 'completed', 'paid', 'Credit Card (Mock)', NULL, NULL, NULL, '2026-05-08 18:20:09', NULL, NULL, NULL, NULL, NULL, '2026-05-08 18:20:09', '2026-05-08 18:20:09'),
(7, 'ORD-69FF56CF50651', 6, 3, 20000.00, 0.00, 0.00, 20000.00, 'USD', 'completed', 'paid', 'Credit Card (Mock)', NULL, NULL, NULL, '2026-05-09 12:46:23', NULL, NULL, NULL, NULL, NULL, '2026-05-09 12:46:23', '2026-05-09 12:46:23'),
(8, 'ORD-6A030592389D3', 6, 1, 2000.00, 0.00, 0.00, 2000.00, 'USD', 'completed', 'paid', 'Stripe', NULL, NULL, NULL, '2026-05-12 07:48:50', NULL, NULL, NULL, NULL, NULL, '2026-05-12 07:48:50', '2026-05-12 07:48:50'),
(9, 'ORD-6A03092ED0C1C', 6, 3, 1500.00, 0.00, 0.00, 1500.00, 'USD', 'completed', 'paid', 'Stripe', NULL, NULL, NULL, '2026-05-12 08:04:14', NULL, NULL, NULL, NULL, NULL, '2026-05-12 08:04:14', '2026-05-12 08:04:14'),
(10, 'ORD-6A0318911F9BB', 7, 4, 600.00, 0.00, 0.00, 600.00, 'USD', 'completed', 'paid', 'Stripe', NULL, NULL, NULL, '2026-05-12 09:09:53', NULL, NULL, NULL, NULL, NULL, '2026-05-12 09:09:53', '2026-05-12 09:09:53'),
(11, 'ORD-6A03194359925', 7, 4, 6000.00, 0.00, 0.00, 6000.00, 'USD', 'completed', 'paid', 'Stripe', NULL, NULL, NULL, '2026-05-12 09:12:51', NULL, NULL, NULL, NULL, NULL, '2026-05-12 09:12:51', '2026-05-12 09:12:51');

-- --------------------------------------------------------

--
-- Table structure for table `organizer_details`
--

CREATE TABLE `organizer_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `tax_id` varchar(255) DEFAULT NULL,
  `business_phone` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `suspension_reason` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `stripe_connect_id` varchar(255) DEFAULT NULL,
  `payouts_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizer_details`
--

INSERT INTO `organizer_details` (`id`, `user_id`, `company_name`, `tax_id`, `business_phone`, `website`, `description`, `status`, `rejection_reason`, `suspension_reason`, `approved_at`, `approved_by`, `stripe_connect_id`, `payouts_enabled`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 2, 'My', NULL, '01011111111', NULL, NULL, 'approved', NULL, NULL, '2026-05-08 12:05:07', 1, NULL, 1, NULL, '2026-05-08 11:54:25', '2026-05-08 12:05:07');

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
-- Table structure for table `payouts`
--

CREATE TABLE `payouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organizer_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `stripe_payout_id` varchar(255) DEFAULT NULL,
  `stripe_transfer_id` varchar(255) DEFAULT NULL,
  `period_start` datetime NOT NULL,
  `period_end` datetime NOT NULL,
  `description` text DEFAULT NULL,
  `order_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`order_ids`)),
  `processed_at` timestamp NULL DEFAULT NULL,
  `failure_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'access_admin', 'web', '2026-05-08 10:59:36', '2026-05-08 10:59:36'),
(2, 'manage_events', 'web', '2026-05-08 10:59:36', '2026-05-08 10:59:36'),
(3, 'create_events', 'web', '2026-05-08 10:59:36', '2026-05-08 10:59:36'),
(4, 'edit_events', 'web', '2026-05-08 10:59:36', '2026-05-08 10:59:36'),
(5, 'delete_events', 'web', '2026-05-08 10:59:36', '2026-05-08 10:59:36'),
(6, 'manage_venues', 'web', '2026-05-08 10:59:36', '2026-05-08 10:59:36'),
(7, 'create_venues', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(8, 'edit_venues', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(9, 'manage_users', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(10, 'ban_users', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(11, 'unban_users', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(12, 'manage_organizers', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(13, 'approve_organizers', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(14, 'manage_orders', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(15, 'refund_orders', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(16, 'void_tickets', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(17, 'scan_tickets', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(18, 'manage_roles', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(19, 'manage_promo_codes', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(20, 'view_finance', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(21, 'process_payouts', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(22, 'manage_system', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(23, 'view_audit_log', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `platform_settings`
--

CREATE TABLE `platform_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `label` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pricing_tiers`
--

CREATE TABLE `pricing_tiers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `sold_count` int(11) NOT NULL DEFAULT 0,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `max_per_order` int(11) DEFAULT NULL,
  `min_per_order` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pricing_tiers`
--

INSERT INTO `pricing_tiers` (`id`, `event_id`, `name`, `price`, `quantity`, `sold_count`, `starts_at`, `ends_at`, `is_active`, `description`, `max_per_order`, `min_per_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'VIP', 1500.00, 30, 0, '2026-05-08 20:28:00', '2026-05-10 19:00:00', 1, NULL, 10, 2, '2026-05-08 17:29:25', '2026-05-08 17:29:25'),
(3, 3, 'VVIP', 20000.00, 30, 0, '2026-05-08 20:42:00', '2026-08-20 23:40:00', 1, NULL, 10, 1, '2026-05-08 17:42:27', '2026-05-08 17:42:27'),
(4, 3, 'VIP', 2000.00, 30, 0, '2026-05-08 20:42:00', '2026-08-20 23:40:00', 1, NULL, 10, 1, '2026-05-08 17:42:39', '2026-05-08 17:42:39'),
(6, 3, 'standerd', 1500.00, 50, 0, '2026-05-08 21:06:00', '2026-08-20 23:40:00', 1, NULL, 5, 1, '2026-05-08 18:06:24', '2026-05-08 18:06:24'),
(8, 4, 'VIP', 2000.00, 50, 0, '2026-05-08 21:09:00', '2026-05-19 00:07:00', 1, NULL, 5, 1, '2026-05-08 18:09:22', '2026-05-08 18:09:22'),
(9, 4, 'standerd', 600.00, 50, 0, '2026-05-08 21:09:00', '2026-05-19 00:07:00', 1, NULL, 5, 1, '2026-05-08 18:09:41', '2026-05-08 18:09:41'),
(10, 4, 'VVIP', 3000.00, 50, 0, '2026-05-08 21:09:00', '2026-05-19 00:07:00', 1, NULL, 5, 1, '2026-05-08 18:09:51', '2026-05-08 18:09:51'),
(11, 1, 'standerd', 1000.00, 50, 0, '2026-05-08 21:11:00', '2026-05-10 19:00:00', 1, NULL, 5, 1, '2026-05-08 18:12:05', '2026-05-08 18:12:05'),
(12, 1, 'VVIP', 3000.00, 50, 0, '2026-05-12 12:35:00', '2026-05-20 19:00:00', 1, NULL, 30, 1, '2026-05-12 09:35:33', '2026-05-12 09:35:33'),
(13, 6, 'VVIP', 3000.00, 50, 0, '2026-05-12 13:51:00', '2026-05-19 16:50:00', 1, NULL, 30, 1, '2026-05-12 10:52:04', '2026-05-12 10:52:04'),
(14, 6, 'standerd', 1000.00, NULL, 0, '2026-05-12 13:52:00', '2026-05-19 16:50:00', 1, NULL, 5, 1, '2026-05-12 10:52:09', '2026-05-12 10:52:09'),
(15, 6, 'VIP', 1000.00, NULL, 0, '2026-05-12 13:52:00', '2026-05-19 16:50:00', 1, NULL, 5, 1, '2026-05-12 10:52:17', '2026-05-12 10:52:17');

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `max_uses` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `max_uses_per_user` int(11) NOT NULL DEFAULT 1,
  `starts_at` datetime DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `scope` varchar(255) NOT NULL DEFAULT 'global',
  `description` text DEFAULT NULL,
  `applicable_events` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_events`)),
  `applicable_tiers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`applicable_tiers`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promo_codes`
--

INSERT INTO `promo_codes` (`id`, `code`, `type`, `value`, `min_order_amount`, `max_discount_amount`, `max_uses`, `used_count`, `max_uses_per_user`, `starts_at`, `expires_at`, `is_active`, `scope`, `description`, `applicable_events`, `applicable_tiers`, `created_at`, `updated_at`) VALUES
(1, 'MESSI10', 'percentage', 10.00, NULL, NULL, NULL, 0, 1, '2026-05-12 13:58:00', '2026-05-20 13:58:00', 1, 'global', NULL, NULL, NULL, '2026-05-12 07:58:15', '2026-05-12 07:58:15');

-- --------------------------------------------------------

--
-- Table structure for table `promo_code_usage`
--

CREATE TABLE `promo_code_usage` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `promo_code_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resale_listings`
--

CREATE TABLE `resale_listings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `asking_price` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `flag_reason` text DEFAULT NULL,
  `price_cap_percentage` decimal(5,2) DEFAULT NULL,
  `exceeds_price_cap` tinyint(1) NOT NULL DEFAULT 0,
  `sold_at` timestamp NULL DEFAULT NULL,
  `buyer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(2, 'admin', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(3, 'organizer', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(4, 'staff', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37'),
(5, 'customer', 'web', '2026-05-08 10:59:37', '2026-05-08 10:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(3, 3),
(4, 1),
(4, 2),
(4, 3),
(5, 1),
(6, 1),
(6, 2),
(7, 1),
(8, 1),
(9, 1),
(9, 2),
(10, 1),
(11, 1),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(17, 1),
(17, 2),
(17, 3),
(17, 4),
(18, 1),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(22, 1),
(23, 1),
(23, 2);

-- --------------------------------------------------------

--
-- Table structure for table `scan_logs`
--

CREATE TABLE `scan_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `scanned_by` bigint(20) UNSIGNED NOT NULL,
  `scan_result` varchar(255) NOT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `zone_id` bigint(20) UNSIGNED NOT NULL,
  `seat_map_id` bigint(20) UNSIGNED NOT NULL,
  `seat_number` varchar(255) NOT NULL,
  `row_label` varchar(255) NOT NULL,
  `row_number` int(11) NOT NULL,
  `column_number` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `price_override` decimal(10,2) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seat_holds`
--

CREATE TABLE `seat_holds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `row` varchar(255) NOT NULL,
  `seat_number` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seat_holds`
--

INSERT INTO `seat_holds` (`id`, `event_id`, `user_id`, `session_id`, `row`, `seat_number`, `expires_at`, `created_at`, `updated_at`) VALUES
(4, 2, 6, 'sMxwd1A1nTNiJOIfdO2PCama2TvyJFJIcLlziRYj', 'B', '19', '2026-05-12 08:42:02', '2026-05-12 08:32:02', '2026-05-12 08:32:02'),
(5, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '12', '2026-05-12 10:03:18', '2026-05-12 08:32:04', '2026-05-12 09:53:18'),
(6, 2, 6, 'sMxwd1A1nTNiJOIfdO2PCama2TvyJFJIcLlziRYj', 'B', '20', '2026-05-12 08:42:14', '2026-05-12 08:32:14', '2026-05-12 08:32:14'),
(8, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '6', '2026-05-12 10:03:13', '2026-05-12 08:35:03', '2026-05-12 09:53:13'),
(10, 2, 6, 'sMxwd1A1nTNiJOIfdO2PCama2TvyJFJIcLlziRYj', 'B', '11', '2026-05-12 08:45:27', '2026-05-12 08:35:27', '2026-05-12 08:35:27'),
(11, 2, 6, 'sMxwd1A1nTNiJOIfdO2PCama2TvyJFJIcLlziRYj', 'B', '10', '2026-05-12 08:45:28', '2026-05-12 08:35:28', '2026-05-12 08:35:28'),
(12, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '1', '2026-05-12 10:04:40', '2026-05-12 08:38:49', '2026-05-12 09:54:40'),
(17, 2, 7, 'IYJS16p9lvfk7BoKV41vF3xUVkVId123Fi6XybT5', 'C', '48', '2026-05-12 09:43:42', '2026-05-12 09:33:42', '2026-05-12 09:33:42'),
(18, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '3', '2026-05-12 10:03:11', '2026-05-12 09:33:43', '2026-05-12 09:53:11'),
(20, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '2', '2026-05-12 10:09:08', '2026-05-12 09:53:02', '2026-05-12 09:59:08'),
(22, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '4', '2026-05-12 10:03:12', '2026-05-12 09:53:12', '2026-05-12 09:53:12'),
(23, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '5', '2026-05-12 10:03:12', '2026-05-12 09:53:12', '2026-05-12 09:53:12'),
(24, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '7', '2026-05-12 10:03:13', '2026-05-12 09:53:13', '2026-05-12 09:53:13'),
(25, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '8', '2026-05-12 10:03:14', '2026-05-12 09:53:14', '2026-05-12 09:53:14'),
(26, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '9', '2026-05-12 10:03:14', '2026-05-12 09:53:14', '2026-05-12 09:53:14'),
(27, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '11', '2026-05-12 10:03:16', '2026-05-12 09:53:16', '2026-05-12 09:53:16'),
(28, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '10', '2026-05-12 10:03:17', '2026-05-12 09:53:17', '2026-05-12 09:53:17'),
(29, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '13', '2026-05-12 10:03:19', '2026-05-12 09:53:19', '2026-05-12 09:53:19'),
(30, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '14', '2026-05-12 10:03:20', '2026-05-12 09:53:20', '2026-05-12 09:53:20'),
(31, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '15', '2026-05-12 10:03:20', '2026-05-12 09:53:20', '2026-05-12 09:53:20'),
(32, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '16', '2026-05-12 10:03:21', '2026-05-12 09:53:21', '2026-05-12 09:53:21'),
(33, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '17', '2026-05-12 10:03:22', '2026-05-12 09:53:22', '2026-05-12 09:53:22'),
(34, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '18', '2026-05-12 10:03:23', '2026-05-12 09:53:23', '2026-05-12 09:53:23'),
(35, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '19', '2026-05-12 10:03:24', '2026-05-12 09:53:24', '2026-05-12 09:53:24'),
(36, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '20', '2026-05-12 10:03:25', '2026-05-12 09:53:25', '2026-05-12 09:53:25'),
(37, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '21', '2026-05-12 10:03:25', '2026-05-12 09:53:25', '2026-05-12 09:53:25'),
(38, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '22', '2026-05-12 10:03:27', '2026-05-12 09:53:27', '2026-05-12 09:53:27'),
(39, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '23', '2026-05-12 10:03:28', '2026-05-12 09:53:28', '2026-05-12 09:53:28'),
(40, 2, 7, 'a40Z3NnNEqoDJUrBCpO7gTIIumLNnmMzfbKHx76V', 'A', '24', '2026-05-12 10:03:29', '2026-05-12 09:53:29', '2026-05-12 09:53:29'),
(41, 5, 7, 'AQdhb4RrL2mU4zLRoAJoMjNxxpxWQqjSy9YgQ1nN', 'C', '6', '2026-05-12 10:17:42', '2026-05-12 10:07:42', '2026-05-12 10:07:42'),
(42, 5, 7, 'AQdhb4RrL2mU4zLRoAJoMjNxxpxWQqjSy9YgQ1nN', 'B', '8', '2026-05-12 10:17:43', '2026-05-12 10:07:43', '2026-05-12 10:07:43'),
(43, 5, 7, 'zicZaCxtsCNcelE2DW3Oy6nhDWAmBMo5QbP5PpwH', 'B', '7', '2026-05-12 12:39:49', '2026-05-12 12:29:49', '2026-05-12 12:29:49'),
(44, 5, 7, 'zicZaCxtsCNcelE2DW3Oy6nhDWAmBMo5QbP5PpwH', 'B', '6', '2026-05-12 12:39:50', '2026-05-12 12:29:50', '2026-05-12 12:29:50'),
(45, 4, 7, 'zicZaCxtsCNcelE2DW3Oy6nhDWAmBMo5QbP5PpwH', 'C', '9', '2026-05-12 13:01:28', '2026-05-12 12:51:28', '2026-05-12 12:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `seat_maps`
--

CREATE TABLE `seat_maps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `venue_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `layout_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`layout_data`)),
  `total_seats` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seat_maps`
--

INSERT INTO `seat_maps` (`id`, `name`, `venue_id`, `description`, `layout_data`, `total_seats`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Pro', 1, NULL, '{\"zones\":[]}', 1000, 1, '2026-05-08 11:38:55', '2026-05-08 11:38:55'),
(2, 'Max', 1, NULL, '{\"zones\":[]}', 550, 1, '2026-05-08 11:39:15', '2026-05-08 11:39:15');

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
('zicZaCxtsCNcelE2DW3Oy6nhDWAmBMo5QbP5PpwH', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoib25CRndqWEg1RkVoUDlGVmZ6elByTDVEUGt4ZU84VnFEMDA5VHh5VyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9', 1778606090);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_number` varchar(255) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `pricing_tier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `seat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `row` varchar(255) DEFAULT NULL,
  `seat_number` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `checked_in_at` datetime DEFAULT NULL,
  `checked_in_by` bigint(20) UNSIGNED DEFAULT NULL,
  `transfer_code` varchar(255) DEFAULT NULL,
  `transferred_to` bigint(20) UNSIGNED DEFAULT NULL,
  `transferred_at` timestamp NULL DEFAULT NULL,
  `void_reason` text DEFAULT NULL,
  `email_sent` tinyint(1) NOT NULL DEFAULT 0,
  `email_sent_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_number`, `qr_code`, `order_id`, `event_id`, `user_id`, `pricing_tier_id`, `seat_id`, `section`, `row`, `seat_number`, `price`, `status`, `checked_in_at`, `checked_in_by`, `transfer_code`, `transferred_to`, `transferred_at`, `void_reason`, `email_sent`, `email_sent_at`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 'TKT-69FE4B19DC4E3', '5aeae9f2c04b88df15f2d1d5cd35a9bd4d1b5e1c899ae33fb93c5e073e35714d', 1, 1, 4, 1, NULL, NULL, 'A', '5', 1500.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 17:44:09', '2026-05-08 17:44:09'),
(2, 'TKT-69FE4B19DE68C', '7424457f719b92fddcb209b10cca5af49ddc4b84072a01eac458614ddd05e77a', 1, 1, 4, 1, NULL, NULL, 'A', '6', 1500.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 17:44:09', '2026-05-08 17:44:09'),
(3, 'TKT-69FE4B54B2A91', '582497413b7730f567ab3756d5a52c0569a266783f60ce785f249d62f85d66b4', 2, 3, 4, 3, NULL, NULL, 'A', '2', 20000.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 17:45:08', '2026-05-08 17:45:08'),
(4, 'TKT-69FE4B54B3879', 'ac81610f279ef430cf0d3ac61dd633bdc570fa0aeb125a78e8e0f4fbed98b3c9', 2, 3, 4, 3, NULL, NULL, 'A', '3', 20000.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 17:45:08', '2026-05-08 17:45:08'),
(5, 'TKT-69FE4FF3C7A1B', '3a0b33d548720bfd0657aada638d325db36917d7bc0c4b2379c978ee8d7f524a', 3, 3, 4, NULL, NULL, NULL, 'VIP', '1', 1500.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 18:04:51', '2026-05-08 18:04:51'),
(6, 'TKT-69FE4FF3CA6A7', 'af7567192b8591bf21f96ce7bfdc73a6b8c8e48660b5d77f8c8d95a29303131e', 3, 3, 4, NULL, NULL, NULL, 'VIP', '2', 1500.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 18:04:51', '2026-05-08 18:04:51'),
(7, 'TKT-69FE5243C781F', 'c6b3f72c258bfa133e4ceac98fd3ff0ed048b2f89bca6d5a695cc0bfd77ecdea', 4, 1, 4, NULL, NULL, NULL, 'VVIP', '1', 2000.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 18:14:43', '2026-05-08 18:14:43'),
(8, 'TKT-69FE53559937D', 'fff70ca4210f84302959fbc4ce89a584a796200e0bd759b4a02c6e3a5b34c0c5', 5, 1, 6, 1, NULL, NULL, 'VIP', '1', 1500.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 18:19:17', '2026-05-08 18:19:17'),
(9, 'TKT-69FE53559A1AC', '1fd4f2737de645e8bb14713fa850af2546a4d7b303dbea2f31405d0836f16bd3', 5, 1, 6, 1, NULL, NULL, 'VIP', '2', 1500.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 18:19:17', '2026-05-08 18:19:17'),
(10, 'TKT-69FE53559B99A', '4409d9a5d98a4056bf7a1c5d8fdbb2b7686598179235195f34032944c747bb8d', 5, 1, 6, 1, NULL, NULL, 'VIP', '3', 1500.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 18:19:17', '2026-05-08 18:19:17'),
(11, 'TKT-69FE5389BF099', '6ece8c900e9a07cfcb39ca98ec1cc55e00e52ad2b601b50cb8b188ea3311e303', 6, 4, 6, 8, NULL, NULL, 'B', '3', 2000.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 18:20:09', '2026-05-08 18:20:09'),
(12, 'TKT-69FE5389C0570', '539ecdae977839134b0b953561f318646ea157b1265c834a5a71cbde6636690f', 6, 4, 6, 8, NULL, NULL, 'B', '6', 2000.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-08 18:20:09', '2026-05-08 18:20:09'),
(13, 'TKT-69FF56CF5488A', '067ff2fe8c031855686261155e98e1495b5cd58e5e18c1ecfe02b3001797a15e', 7, 3, 6, 3, NULL, NULL, 'VVIP', '1', 20000.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-09 12:46:23', '2026-05-09 12:46:23'),
(14, 'TKT-6A0305923CEE0', '140722736ea44094a7fa8251bccde206726cd9fe2c1652adc1931b0b3ca5b8ef', 8, 1, 6, 11, NULL, NULL, 'Standard', '1', 1000.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-12 07:48:50', '2026-05-12 07:48:50'),
(15, 'TKT-6A0305923F205', '8162dca6cd0c253625d2c49763a4df5ed2c3c3ca9c2a3e02f1839015c461013f', 8, 1, 6, 11, NULL, NULL, 'Standard', '2', 1000.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-12 07:48:50', '2026-05-12 07:48:50'),
(16, 'TKT-6A03092ED42A2', 'fcdd53dc77d65907f78c60783a7bf358ccacef5abd883aa5d5e802a032c4e781', 9, 3, 6, 6, NULL, NULL, 'Standard', '1', 1500.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-12 08:04:14', '2026-05-12 08:04:14'),
(17, 'TKT-6A03189122739', '100be341baa829f820bbbfa84cab9fcee13104ebf78d00b8440872be8b4dbe13', 10, 4, 7, 9, NULL, NULL, 'C', '8', 600.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-12 09:09:53', '2026-05-12 09:09:53'),
(18, 'TKT-6A0319435C3D8', '6834489338e1ffb6b56f8d6715a2b09015a0ea96fd9e8ba9fadfc0795498d4f7', 11, 4, 7, 10, NULL, NULL, 'B', '9', 3000.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-12 09:12:51', '2026-05-12 09:12:51'),
(19, 'TKT-6A0319435C9FE', '165ea6300b87fe864f8e22ba88b815d428357db0bef3072d175af693346ef8e8', 11, 4, 7, 10, NULL, NULL, 'A', '5', 3000.00, 'valid', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-05-12 09:12:51', '2026-05-12 09:12:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `avatar`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@venuetickets.com', '2026-05-08 10:59:38', '$2y$12$j.lmu4Nx7mjEEbril.B2guuSo0PwbxR1pmQtknN.SFSsJT6m88reO', NULL, NULL, 1, NULL, '2026-05-08 10:59:38', '2026-05-08 10:59:38'),
(2, 'Mahmoud', 'mahmoud@gmail.com', NULL, '$2y$12$WpOFe8IQYbza1F3vW3.MoO9Rni0RMIS/HupMmjnRqFDCgpBY4F6Ra', NULL, NULL, 1, NULL, '2026-05-08 11:54:25', '2026-05-08 11:54:25'),
(3, 'Tom', 'tom@gmail.com', NULL, '$2y$12$vQ/0tiO3W.L/IHLY600WmuLETlc20FNafPLJRfhHU9uXKqOSRz0am', NULL, NULL, 1, NULL, '2026-05-08 11:55:17', '2026-05-08 11:55:17'),
(4, 'sausan badr', 'sausan@example.com', NULL, '$2y$12$QY8i4MqE96sAfw16HwZSSub6bMuiDbFqHqoWMOT6rUp8RAylqqw9W', NULL, NULL, 1, NULL, '2026-05-08 17:31:29', '2026-05-08 17:31:29'),
(5, 'mahmoud el bezawy', 'mahmod@gmail.com', NULL, '$2y$12$8b4cRm9SsfRcDq/VQse5B.hgEZwPGTFWLkSRaEr5ggmtbWaukkAiK', NULL, NULL, 1, NULL, '2026-05-08 17:48:03', '2026-05-08 17:48:03'),
(6, 'iten amer', 'iten@exapmle.com', NULL, '$2y$12$Ot2b/bO00sbb0S6FMMntCeiKV8vgAPBNXqieuGtCVuge9KhJJzHW6', NULL, NULL, 1, NULL, '2026-05-08 18:18:12', '2026-05-08 18:18:12'),
(7, 'saad el so8yar', 'sa3d@so8yar.com', NULL, '$2y$12$N2fi58kUpyDSAevScm.ySumie6kuKvbhOFFEX2fT3SrDYtQEztvXW', NULL, NULL, 1, NULL, '2026-05-12 08:50:58', '2026-05-12 08:50:58');

-- --------------------------------------------------------

--
-- Table structure for table `user_bans`
--

CREATE TABLE `user_bans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `banned_by` bigint(20) UNSIGNED NOT NULL,
  `reason` text NOT NULL,
  `banned_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `unbanned_at` timestamp NULL DEFAULT NULL,
  `unbanned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `accessibility_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`accessibility_info`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `name`, `address`, `city`, `state`, `country`, `postal_code`, `capacity`, `phone`, `email`, `website`, `description`, `image_url`, `amenities`, `accessibility_info`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CFC', 'New Cairo', 'Cairo', NULL, 'Egypt', NULL, 5000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-05-08 11:37:00', '2026-05-08 11:37:00', NULL),
(2, 'Cairo Stadium', 'Egypt', 'Cairo', 'madint nasr', 'Egypt', '13311', 1000000, '01098711959', 'kahera@gmail.com', 'https://7aflat_zaman.com', NULL, NULL, NULL, NULL, 1, '2026-05-08 17:37:11', '2026-05-08 17:37:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seat_map_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#007bff',
  `default_price` decimal(10,2) NOT NULL,
  `capacity` int(11) NOT NULL,
  `rows` int(11) NOT NULL DEFAULT 1,
  `columns` int(11) NOT NULL DEFAULT 1,
  `seat_numbers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seat_numbers`)),
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`),
  ADD KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`);

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
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_organizer_id_foreign` (`organizer_id`),
  ADD KEY `events_venue_id_foreign` (`venue_id`),
  ADD KEY `events_seat_map_id_foreign` (`seat_map_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `failed_payments`
--
ALTER TABLE `failed_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `failed_payments_user_id_foreign` (`user_id`),
  ADD KEY `failed_payments_order_id_foreign` (`order_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_event_id_foreign` (`event_id`);

--
-- Indexes for table `organizer_details`
--
ALTER TABLE `organizer_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizer_details_user_id_foreign` (`user_id`),
  ADD KEY `organizer_details_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payouts`
--
ALTER TABLE `payouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payouts_organizer_id_foreign` (`organizer_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `platform_settings`
--
ALTER TABLE `platform_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `platform_settings_key_unique` (`key`);

--
-- Indexes for table `pricing_tiers`
--
ALTER TABLE `pricing_tiers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pricing_tiers_event_id_foreign` (`event_id`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promo_codes_code_unique` (`code`);

--
-- Indexes for table `promo_code_usage`
--
ALTER TABLE `promo_code_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promo_code_usage_promo_code_id_foreign` (`promo_code_id`),
  ADD KEY `promo_code_usage_user_id_foreign` (`user_id`),
  ADD KEY `promo_code_usage_order_id_foreign` (`order_id`);

--
-- Indexes for table `resale_listings`
--
ALTER TABLE `resale_listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resale_listings_seller_id_foreign` (`seller_id`),
  ADD KEY `resale_listings_event_id_foreign` (`event_id`),
  ADD KEY `resale_listings_buyer_id_foreign` (`buyer_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `scan_logs`
--
ALTER TABLE `scan_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scan_logs_ticket_id_foreign` (`ticket_id`),
  ADD KEY `scan_logs_event_id_foreign` (`event_id`),
  ADD KEY `scan_logs_scanned_by_foreign` (`scanned_by`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seats_zone_id_foreign` (`zone_id`),
  ADD KEY `seats_seat_map_id_foreign` (`seat_map_id`);

--
-- Indexes for table `seat_holds`
--
ALTER TABLE `seat_holds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_seat_hold` (`event_id`,`row`,`seat_number`),
  ADD KEY `seat_holds_user_id_foreign` (`user_id`);

--
-- Indexes for table `seat_maps`
--
ALTER TABLE `seat_maps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seat_maps_venue_id_foreign` (`venue_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tickets_ticket_number_unique` (`ticket_number`),
  ADD UNIQUE KEY `tickets_qr_code_unique` (`qr_code`),
  ADD UNIQUE KEY `tickets_transfer_code_unique` (`transfer_code`),
  ADD KEY `tickets_order_id_foreign` (`order_id`),
  ADD KEY `tickets_event_id_foreign` (`event_id`),
  ADD KEY `tickets_user_id_foreign` (`user_id`),
  ADD KEY `tickets_pricing_tier_id_foreign` (`pricing_tier_id`),
  ADD KEY `tickets_seat_id_foreign` (`seat_id`),
  ADD KEY `tickets_checked_in_by_foreign` (`checked_in_by`),
  ADD KEY `tickets_transferred_to_foreign` (`transferred_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_bans`
--
ALTER TABLE `user_bans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_bans_user_id_foreign` (`user_id`),
  ADD KEY `user_bans_banned_by_foreign` (`banned_by`),
  ADD KEY `user_bans_unbanned_by_foreign` (`unbanned_by`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zones_seat_map_id_foreign` (`seat_map_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_payments`
--
ALTER TABLE `failed_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `organizer_details`
--
ALTER TABLE `organizer_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `platform_settings`
--
ALTER TABLE `platform_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pricing_tiers`
--
ALTER TABLE `pricing_tiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `promo_code_usage`
--
ALTER TABLE `promo_code_usage`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resale_listings`
--
ALTER TABLE `resale_listings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `scan_logs`
--
ALTER TABLE `scan_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seat_holds`
--
ALTER TABLE `seat_holds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `seat_maps`
--
ALTER TABLE `seat_maps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_bans`
--
ALTER TABLE `user_bans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_seat_map_id_foreign` FOREIGN KEY (`seat_map_id`) REFERENCES `seat_maps` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `events_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `failed_payments`
--
ALTER TABLE `failed_payments`
  ADD CONSTRAINT `failed_payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `failed_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organizer_details`
--
ALTER TABLE `organizer_details`
  ADD CONSTRAINT `organizer_details_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `organizer_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payouts`
--
ALTER TABLE `payouts`
  ADD CONSTRAINT `payouts_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pricing_tiers`
--
ALTER TABLE `pricing_tiers`
  ADD CONSTRAINT `pricing_tiers_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `promo_code_usage`
--
ALTER TABLE `promo_code_usage`
  ADD CONSTRAINT `promo_code_usage_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promo_code_usage_promo_code_id_foreign` FOREIGN KEY (`promo_code_id`) REFERENCES `promo_codes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promo_code_usage_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resale_listings`
--
ALTER TABLE `resale_listings`
  ADD CONSTRAINT `resale_listings_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `resale_listings_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resale_listings_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scan_logs`
--
ALTER TABLE `scan_logs`
  ADD CONSTRAINT `scan_logs_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scan_logs_scanned_by_foreign` FOREIGN KEY (`scanned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scan_logs_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_seat_map_id_foreign` FOREIGN KEY (`seat_map_id`) REFERENCES `seat_maps` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seats_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seat_holds`
--
ALTER TABLE `seat_holds`
  ADD CONSTRAINT `seat_holds_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seat_holds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seat_maps`
--
ALTER TABLE `seat_maps`
  ADD CONSTRAINT `seat_maps_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_checked_in_by_foreign` FOREIGN KEY (`checked_in_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_pricing_tier_id_foreign` FOREIGN KEY (`pricing_tier_id`) REFERENCES `pricing_tiers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_transferred_to_foreign` FOREIGN KEY (`transferred_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_bans`
--
ALTER TABLE `user_bans`
  ADD CONSTRAINT `user_bans_banned_by_foreign` FOREIGN KEY (`banned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_bans_unbanned_by_foreign` FOREIGN KEY (`unbanned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_bans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `zones`
--
ALTER TABLE `zones`
  ADD CONSTRAINT `zones_seat_map_id_foreign` FOREIGN KEY (`seat_map_id`) REFERENCES `seat_maps` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
