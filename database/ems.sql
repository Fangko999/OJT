-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 01, 2024 lúc 07:18 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ems`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `departments`
--

INSERT INTO `departments` (`id`, `name`, `parent_id`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Phòng nhân sự', 0, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
(2, 'Phòng tài chính', 0, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
(3, 'Phòng công nghệ thông tin', 0, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
(4, 'Phòng tuyển dụng', 1, 1, '2024-10-31 19:45:57', 0, '2024-10-31 12:45:57', 1),
(5, 'Phòng đào tạo và phát triển', 1, 1, '2024-10-31 18:43:09', 0, '2024-10-31 11:43:09', 1),
(6, 'Phòng Kế toán', 2, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
(7, 'Phòng Quản lý chi phí', 2, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
(8, 'Phòng Phát triển phần mềm', 3, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
(9, 'Phòng An ninh thông tin', 3, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
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
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '1024_10_11_departments', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2024_10_15_023630_user_attendance', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `position` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `department_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `phone_number`, `status`, `position`, `created_at`, `updated_at`, `created_by`, `updated_by`, `role`) VALUES
(1, 1, 'Duyy', 'duyy@gmail.com', NULL, '$2y$10$SAPJievw5UXTYrbcHtxfH.BZ2S/yWJjeA67MNpyIZY.xPJLCISIy.', NULL, NULL, 1, 'Admin', NULL, '2024-10-31 10:57:30', NULL, NULL, 1),
(31, 4, 'kokkk', 'kokkk@gmail.com', NULL, '$2y$12$WgjdVo.sSte/hIgEJXE8bOiqc1v97RnpA8EdU99nBZ5xG5c16.wS.', NULL, '0987654321', 1, 'Nhân Viên', '2024-10-31 11:50:32', '2024-10-31 12:45:56', 1, 1, 1),
(35, 3, 'cococ3', 'cococ3@gmail.com', NULL, '$2y$12$3zkXy7c09CRhoaM0NQuYwe3h0eUj.sKArexc4FdmY1RuTmQTqzWRW', NULL, '987654321', 1, 'Nhân Viên', '2024-10-31 11:51:50', '2024-10-31 22:12:31', 1, 1, 2),
(36, 5, 'cococ4', 'cococ4@gmail.com', NULL, '$2y$12$oZ8lYoSljLh6eK2nDbhE5eHEHtdqHGN3U8IoTQs4eMSyGZtrjqDYu', NULL, '987654321', 1, 'Nhân Viên', '2024-10-31 11:51:50', '2024-10-31 22:12:31', 1, 1, 2),
(38, 7, 'cococ6', 'cococ6@gmail.com', NULL, '$2y$12$xCzBuGThaGfzYZaSyNt58.8RWkYBoARcW0PmytGSpnKlyMYHTTfkm', NULL, '987654321', 1, 'Nhân Viên', '2024-10-31 11:51:51', '2024-10-31 22:12:32', 1, 1, 2),
(39, 8, 'cococ7', 'cococ7@gmail.com', NULL, '$2y$12$tM0e61CX334wyznaXCEOCO6c1w.yYX6vlGTuXRQcTdZMOxK/N7Sk2', NULL, '987654321', 1, 'Nhân Viên', '2024-10-31 11:51:51', '2024-10-31 22:12:32', 1, 1, 2),
(40, 9, 'cococ8', 'cococ8@gmail.com', NULL, '$2y$12$XHtabXYqsGWg9zK3Ops/S.ucv3Lhi3RGeHlsK9q5p9SVY1kTbvStG', NULL, '987654321', 1, 'Nhân Viên', '2024-10-31 11:51:51', '2024-10-31 22:12:32', 1, 1, 2),
(43, 2, 'cococ2', 'cococ2@gmail.com', NULL, '$2y$12$Vpi0cC1CbOtAhKSpyf2fb.fhGYOsuuH19jZtuEKj/uTseR7p/zs0.', NULL, '987654321', 1, 'Nhân Viên', '2024-10-31 22:12:31', '2024-10-31 22:12:31', 1, NULL, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_attendance`
--

CREATE TABLE `user_attendance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `created_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_department_id_foreign` (`department_id`);

--
-- Chỉ mục cho bảng `user_attendance`
--
ALTER TABLE `user_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_attendance_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT cho bảng `user_attendance`
--
ALTER TABLE `user_attendance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_attendance`
--
ALTER TABLE `user_attendance`
  ADD CONSTRAINT `user_attendance_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
