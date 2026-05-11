-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 25/07/2025 às 03:06
-- Versão do servidor: 10.11.10-MariaDB-log
-- Versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u732546852_91932`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `button_link` varchar(255) NOT NULL DEFAULT '/raspadinhas',
  `image_url` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `banners`
--

INSERT INTO `banners` (`id`, `title`, `button_link`, `image_url`, `order`, `active`, `created_at`, `updated_at`) VALUES
(1, 'ACHOU GANHOU', '/raspadinhas', '/banners/2QrH3EYB4sn3zo3shsucmwsgpSoVyHuVYM41bsdZ.webp', 1, 1, '2025-07-23 23:52:24', '2025-07-23 23:52:24'),
(2, '1000 COM 1 REAL', '/raspadinhas', '/banners/1yGB2WoHFo2TScbWbv5nyBOVuKtTbLUGNyqShUMj.webp', 2, 1, '2025-07-23 23:52:50', '2025-07-23 23:52:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `config`
--

CREATE TABLE `config` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `app_name` varchar(255) NOT NULL DEFAULT 'Sistema de Raspadinhas',
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `min_deposit_amount` decimal(10,2) NOT NULL DEFAULT 1.00,
  `max_deposit_amount` decimal(10,2) NOT NULL DEFAULT 10000.00,
  `min_withdraw_amount` decimal(10,2) NOT NULL DEFAULT 10.00,
  `max_withdraw_amount` decimal(10,2) NOT NULL DEFAULT 50000.00,
  `description` text DEFAULT 'A melhor plataforma de raspadinhas online do Brasil. Ganhe prêmios incríveis, PIX na unha e muito mais!',
  `keywords` text DEFAULT 'raspadinha, sorte, prêmios, jogos, online, brasil, pix',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `primary_color` varchar(255) NOT NULL DEFAULT '#4ADE80',
  `secondary_color` varchar(255) NOT NULL DEFAULT '#1F2937',
  `accent_color` varchar(255) NOT NULL DEFAULT '#6366F1',
  `background_color` varchar(255) NOT NULL DEFAULT '#000000',
  `foreground_color` varchar(255) NOT NULL DEFAULT '#FFFFFF',
  `muted_color` varchar(255) NOT NULL DEFAULT '#374151',
  `muted_foreground_color` varchar(255) NOT NULL DEFAULT '#9CA3AF',
  `card_color` varchar(255) NOT NULL DEFAULT '#111827',
  `card_foreground_color` varchar(255) NOT NULL DEFAULT '#FFFFFF',
  `border_color` varchar(255) NOT NULL DEFAULT '#374151',
  `input_color` varchar(255) NOT NULL DEFAULT '#374151',
  `ring_color` varchar(255) NOT NULL DEFAULT '#4ADE80',
  `auto_withdraw_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `auto_withdraw_max_amount` decimal(10,2) NOT NULL DEFAULT 1000.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `config`
--

INSERT INTO `config` (`id`, `app_name`, `logo`, `favicon`, `footer_text`, `contact_email`, `contact_phone`, `address`, `min_deposit_amount`, `max_deposit_amount`, `min_withdraw_amount`, `max_withdraw_amount`, `description`, `keywords`, `created_at`, `updated_at`, `primary_color`, `secondary_color`, `accent_color`, `background_color`, `foreground_color`, `muted_color`, `muted_foreground_color`, `card_color`, `card_foreground_color`, `border_color`, `input_color`, `ring_color`, `auto_withdraw_enabled`, `auto_withdraw_max_amount`) VALUES
(1, 'Sistema de Raspadinhas', '/images/wqBhdba8EX3lir2aJjFi3Zp9jhZ967hwrAUGubHV.webp', '/images/pjR5qW8QOgixc9V7LyQFHEh98SJ1XRbYtZwYVhOV.webp', 'Sistema de Raspadinhas - Sua sorte está aqui!', 'contato@raspadinhas.com.br', '(11) 99999-9999', 'São Paulo, SP - Brasil', 10.00, 10000.00, 10.00, 50000.00, 'A melhor plataforma de raspadinhas online do Brasil. Ganhe prêmios incríveis, PIX na unha e muito mais!', 'raspadinha, sorte, prêmios, jogos, online, brasil, pix', '2025-07-22 23:40:30', '2025-07-23 23:40:15', '#28e504', 'oklch(26.9% 0 0)', 'oklch(26.9% 0 0)', '#000000', 'oklch(98.5% 0 0)', 'oklch(26.9% 0 0)', 'oklch(70.8% 0 0)', 'oklch(20.5% 0 0)', 'oklch(98.5% 0 0)', 'oklch(26.9% 0 0)', 'oklch(26.9% 0 0)', '#28e504', 0, 1000.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT 'DEPOSITO',
  `payment_id` varchar(255) NOT NULL,
  `external_id` varchar(255) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','paid','expired','cancelled','failed') NOT NULL DEFAULT 'pending',
  `gateway` enum('primebank','mock','commission') DEFAULT 'primebank',
  `qr_code` text DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `gatewayskeys`
--

CREATE TABLE `gatewayskeys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `primebank_client_id` varchar(255) DEFAULT NULL,
  `primebank_client_secret` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `gatewayskeys`
--

INSERT INTO `gatewayskeys` (`id`, `primebank_client_id`, `primebank_client_secret`, `created_at`, `updated_at`) VALUES
(1, 'pix up clinte id', 'pix up clinte id', '2025-07-22 23:39:21', '2025-07-25 03:05:39');

-- --------------------------------------------------------

--
-- Estrutura para tabela `jogos_historico`
--

CREATE TABLE `jogos_historico` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `raspadinha_id` bigint(20) UNSIGNED NOT NULL,
  `raspadinha_name` varchar(255) NOT NULL,
  `prize_id` bigint(20) UNSIGNED DEFAULT NULL,
  `prize_name` varchar(255) DEFAULT NULL,
  `prize_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `prize_img` varchar(255) DEFAULT NULL,
  `status` enum('win','loss') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_07_16_235201_create_config_table', 1),
(2, '2025_07_17_161923_create_users_table', 1),
(3, '2025_07_18_140611_create_raspadinhas_table', 1),
(4, '2025_07_18_140705_create_raspadinha_prizes_table', 1),
(5, '2025_07_21_164643_create_banners_table', 1),
(6, '2025_07_21_170345_update_banners_table_remove_unused_fields', 1),
(7, '2025_07_21_175937_create_ultimos_ganhos_table', 1),
(8, '2025_07_21_194857_add_hot_column_to_raspadinhas_table', 1),
(9, '2025_07_21_221118_add_img_column_to_raspadinha_prizes_table', 1),
(10, '2025_07_21_233947_create_gatewayskeys_table', 1),
(11, '2025_07_21_234503_create_deposits_table', 1),
(12, '2025_07_22_121401_add_deposit_withdraw_limits_to_config_table', 1),
(13, '2025_07_22_121409_add_deposit_withdraw_limits_to_config_table', 1),
(14, '2025_07_23_000000_create_withdrawals_table', 1),
(15, '2024_03_20_000000_create_jogos_historico_table', 2),
(16, '2024_03_21_000000_add_referral_code_to_users', 3),
(17, '2024_03_21_000001_add_commission_to_users', 3),
(18, '2024_03_21_000002_add_referred_by_to_users', 4),
(19, '2025_07_23_162609_add_description_to_deposits_table', 5),
(20, '2025_07_23_163040_add_commission_gateway_to_deposits_table', 6),
(21, '2025_07_23_164655_add_is_influencer_to_users_table', 7),
(22, '2024_03_22_000000_add_transaction_id_to_withdrawals_table', 8),
(23, '2024_03_22_000001_add_theme_and_auto_withdraw_to_config', 9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `raspadinhas`
--

CREATE TABLE `raspadinhas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `totalbuy` int(11) NOT NULL DEFAULT 0,
  `value` decimal(10,2) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `hot` tinyint(1) NOT NULL DEFAULT 0,
  `max_sales` int(11) DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `raspadinhas`
--

INSERT INTO `raspadinhas` (`id`, `name`, `photo`, `title`, `description`, `totalbuy`, `value`, `active`, `hot`, `max_sales`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(5, 'raspadinha-magica', '/raspadinhas/CsDcbRYVkUwXwyhphYdJ3uV7GXFuHEjUw3d7c6l7.webp', 'Raspadinha Mágica', 'Raspadinha Mágica', 0, 50.00, 1, 1, NULL, '2025-07-23 18:15:00', '2026-01-30 18:15:00', '2025-07-23 21:15:38', '2025-07-25 02:40:57'),
(10, 'centavo-da-sorte', '/raspadinhas/Qpsfo2Ur77hav0PqwK3ACLjuyCIt1xTlXDbwA7Em.webp', 'Centavo da Sorte', 'PRÊMIOS DE ATÉ R$ 1.000,00', 0, 0.50, 1, 1, NULL, NULL, NULL, '2025-07-24 15:41:36', '2025-07-25 02:10:51'),
(11, 'Sorte Instantânea', '/raspadinhas/WSTgsmYBBaFz8ITqFTXMn8mhpL2ymI2mHzfcsXm2.webp', 'Sorte Instantânea', 'PRÊMIOS DE ATÉ R$ 2.500,00', 0, 1.00, 1, 1, NULL, NULL, NULL, '2025-07-24 15:53:32', '2025-07-25 02:09:01'),
(12, 'Raspa Relâmpago', '/raspadinhas/U5zUS3xbVUaWi58OIAzFnC1DwZT6uDwirrGImJsZ.webp', 'Raspa Relâmpago', 'PRÊMIOS DE ATÉ R$ 15.000,00', 0, 5.00, 1, 1, NULL, NULL, NULL, '2025-07-24 16:09:24', '2025-07-25 02:29:54'),
(13, 'Raspadinha Suprema', '/raspadinhas/74Nkx0H3RchJFEFCRTkZobTZ0sg7h1BCqg1DE32G.webp', 'Raspadinha Suprema', 'PRÊMIOS DE ATÉ R$ 5.000,00', 0, 2.00, 1, 1, NULL, NULL, NULL, '2025-07-24 20:36:51', '2025-07-25 02:09:36');

-- --------------------------------------------------------

--
-- Estrutura para tabela `raspadinha_prizes`
--

CREATE TABLE `raspadinha_prizes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `raspadinha_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `probability` decimal(5,2) NOT NULL,
  `display_value` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `is_jackpot` tinyint(1) NOT NULL DEFAULT 0,
  `max_wins` int(11) DEFAULT NULL,
  `current_wins` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `raspadinha_prizes`
--

INSERT INTO `raspadinha_prizes` (`id`, `raspadinha_id`, `name`, `value`, `probability`, `display_value`, `img`, `is_jackpot`, `max_wins`, `current_wins`, `active`, `created_at`, `updated_at`) VALUES
(20, 5, 'Nada', 0.00, 70.62, 'R$ 0,00', NULL, 0, NULL, 0, 1, '2025-07-23 21:15:38', '2025-07-25 02:30:10'),
(25, 5, '20.000 Reais', 20000.00, 0.00, 'R$ 20000,00', '/prizes/3NsBQCkU8AsDVUKMpPQigjINaBJE7p3RRu1zlCRB.webp', 1, 0, 0, 1, '2025-07-23 21:34:13', '2025-07-23 23:10:40'),
(26, 5, 'Moto CG 160 Start', 16500.00, 0.00, 'R$ 16500,00', '/prizes/wcZx4lvOwVev3tlFmCV6npsCfOvFjK1dVjaGZngC.webp', 0, 1, 0, 1, '2025-07-23 21:43:27', '2025-07-24 22:35:44'),
(27, 5, 'Moto Honda Biz 110i', 13000.00, 0.00, 'R$ 13000,00', '/prizes/6dpZopNLeuWO4KIBEX2YWgCLXugMDK6OGYQZoyUR.webp', 0, 1, 0, 1, '2025-07-23 21:44:03', '2025-07-23 22:01:14'),
(28, 5, 'Moto Honda Pop 110i', 11500.00, 0.00, 'R$ 11500,00', '/prizes/3bjXRyO9t5l4A9EIP0m30aiv5yKniq5WpjWZkrLu.webp', 0, 1, 0, 1, '2025-07-23 21:44:44', '2025-07-23 21:44:44'),
(29, 5, 'iPhone 15 Pro', 11000.00, 0.00, 'R$ 11000,00', '/prizes/XaTF5rpngpCrzqNTZECWNs051N67QRRsnRePLYx3.webp', 0, 1, 0, 1, '2025-07-23 21:45:32', '2025-07-24 22:23:38'),
(30, 5, '10.000 Reais', 10000.00, 0.00, 'R$ 10000,00', '/prizes/8SGBtYgCUBYIMw6yMGdEtWljgmi96ka4QyJXeQPc.webp', 0, 1, 0, 1, '2025-07-23 21:46:01', '2025-07-23 21:46:01'),
(31, 5, 'iPhone 15 Pro Max', 9500.00, 0.00, 'R$ 9500,00', '/prizes/CXzXpLKHg0gH7Tm4UYjH9xgMVkTOdymTqy1RXQaV.webp', 0, 1, 0, 1, '2025-07-23 21:46:24', '2025-07-23 21:46:24'),
(32, 5, 'Geladeira Frost Free', 7500.00, 0.00, 'R$ 7500,00', '/prizes/Z9DA9GWz8XA9axyIz94lRm9dLIF0Zv08ARaqqRdG.webp', 0, 1, 0, 1, '2025-07-23 21:47:35', '2025-07-24 22:34:47'),
(33, 5, 'Apple Watch Ultra 2', 7500.00, 0.00, 'R$ 7500,00', '/prizes/mSaeC4nNxquNZxfiwSrg5dGRf9y57WuUCRKMDdqh.webp', 0, 1, 0, 1, '2025-07-23 21:48:06', '2025-07-23 21:48:06'),
(34, 5, 'Churrasqueira a gás GS Performance', 5000.00, 0.00, 'R$ 5000,00', '/prizes/YTsv2iX4WEyr3lwNzUz1uoDpLEHIlRYAaCgLSmkc.webp', 0, 1, 0, 1, '2025-07-23 21:48:35', '2025-07-23 21:59:24'),
(35, 5, 'iPhone 15', 5000.00, 0.00, 'R$ 5000,00', '/prizes/g9WMns7MjIhxU2jcw8MLKhTjPkEk7jxOdyHmBa5T.webp', 0, 1, 0, 1, '2025-07-23 21:49:01', '2025-07-24 22:26:31'),
(36, 5, '5.000 Reais', 5000.00, 0.00, 'R$ 5000,00', '/prizes/8OM9MBF4fWah5K9gixnYGEhljbN5tv7LgVy0LkKE.webp', 0, 1, 0, 1, '2025-07-23 21:50:23', '2025-07-23 21:50:23'),
(37, 5, 'PlayStation 5', 4500.00, 0.00, 'R$ 4500,00', '/prizes/lgwq88W6y99W0v6Zei1q6889P9AYpDv9F3BhhCtH.webp', 0, 0, 0, 1, '2025-07-23 21:50:50', '2025-07-24 22:42:02'),
(38, 5, 'iPhone 12', 2500.00, 0.00, 'R$ 2500,00', '/prizes/HH22Km9LbnFyGiw3yVzpG09TugMqQQuc1CDOkdb9.webp', 0, 0, 0, 1, '2025-07-23 21:51:16', '2025-07-24 22:42:16'),
(39, 5, 'Apple AirPods 3ª geração', 1900.00, 0.00, 'R$ 1900,00', '/prizes/Hw0gQZTWpKmw94QqCtJnT7UhmH93T4Jq920y9cFg.webp', 0, 1, 0, 1, '2025-07-23 21:51:59', '2025-07-24 22:38:56'),
(40, 5, 'Air Force 1 x AMBUSH', 1700.00, 0.00, 'R$ 1700,00', '/prizes/xdCN0LKXDNnHRl1B9gQpgdVHVJXP8sOLnffYJCMk.webp', 0, 1, 0, 1, '2025-07-23 21:52:26', '2025-07-23 21:52:26'),
(41, 5, 'Air Force 1 Low Retro', 1200.00, 0.00, 'R$ 1200,00', '/prizes/RCR1VphXIO3MWg3Rm0lin2JPKRWpFfy8zAuB9mlW.webp', 0, 1, 0, 1, '2025-07-23 21:52:45', '2025-07-23 21:52:45'),
(42, 5, 'Air Jordan 1 Low Purple', 1100.00, 0.00, 'R$ 1100,00', '/prizes/6iPoORYHf22U2yDfopPRR0eriJhTtigJKkMKEayw.webp', 0, 1, 0, 1, '2025-07-23 21:53:11', '2025-07-24 22:39:51'),
(43, 5, '1.000 Reais', 1000.00, 0.00, 'R$ 1000,00', '/prizes/JY4JWrmbDiWEO20UI3QNNtutBHLC0udFOBfCeq4d.webp', 0, 3, 0, 1, '2025-07-23 21:53:37', '2025-07-24 22:42:11'),
(44, 5, '700 Reais', 700.00, 1.01, 'R$ 700,00', '/prizes/aRguxakZbQwr2vb7fFLSnzN9QSMmKzBfdl7Nsydn.webp', 0, NULL, 0, 1, '2025-07-23 21:54:04', '2025-07-24 21:12:07'),
(45, 5, '500 Reais', 500.00, 2.02, 'R$ 500,00', '/prizes/197loyiByg96cuPMNVHmMODqyT25bWx1U2S17HCM.webp', 0, NULL, 0, 1, '2025-07-23 21:54:38', '2025-07-24 21:12:01'),
(46, 5, '100 Reais', 100.00, 5.04, 'R$ 100,00', '/prizes/36cMDSsNSv8xGK2y1jYObC2JXFus8wYne0qEfOko.webp', 0, NULL, 0, 1, '2025-07-23 21:55:04', '2025-07-24 21:11:53'),
(47, 5, '50 Reais', 50.00, 9.06, 'R$ 50,00', '/prizes/OrmjV1IXaKVXvKjFHuQRtyljo3x8EieYpTn2eT6s.webp', 0, NULL, 0, 1, '2025-07-23 21:55:28', '2025-07-24 22:13:13'),
(48, 5, 'Capinha transparente para iPhone 15', 30.00, 0.91, 'R$ 30,00', '/prizes/kpEgt91VNqq90CiwKcfYmcHaZwvWTUWPdMJwq8Rr.webp', 0, NULL, 0, 1, '2025-07-23 21:55:56', '2025-07-24 18:49:00'),
(49, 5, '10 Reais', 10.00, 11.07, 'R$ 10,00', '/prizes/JK1XATH5nTDGdNGdONtGXlUTIkNb5S0gL7YToAgq.webp', 0, NULL, 0, 1, '2025-07-23 21:56:32', '2025-07-25 02:40:57'),
(50, 5, '5 Reais', 5.00, 0.22, 'R$ 5,00', '/prizes/UgdwCI1TOmosLhPf94zUpoUoIrcLELVmFYIQeRW1.webp', 0, NULL, 0, 1, '2025-07-23 21:57:26', '2025-07-24 08:37:47'),
(51, 5, '1 Real', 1.00, 0.05, 'R$ 1,00', '/prizes/MYeYVv9g0czLzYaNMCt0miLHDQYYwT8sDyB3ETns.webp', 0, NULL, 0, 1, '2025-07-23 21:57:51', '2025-07-24 21:11:32'),
(72, 10, 'Nada', 0.00, 86.20, 'R$ 0,00', NULL, 0, NULL, 0, 1, '2025-07-24 15:41:36', '2025-07-25 02:10:51'),
(73, 10, 'R$ 2,00', 2.00, 2.00, 'R$ 2,00', '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 0, NULL, 0, 1, '2025-07-24 15:41:36', '2025-07-25 01:43:41'),
(74, 10, '3 Reais', 3.00, 1.50, 'R$ 3,00', '/prizes/ADcvahhxcvNX3Hz8KtFKeX2LjlON3jaI3PQlGbqq.webp', 0, NULL, 0, 1, '2025-07-24 15:41:36', '2025-07-25 02:09:54'),
(77, 10, '0,50 Centavos', 0.50, 4.00, 'R$ 0,50', '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 0, NULL, 0, 1, '2025-07-24 15:42:42', '2025-07-25 02:10:09'),
(78, 10, '1 Real', 1.00, 3.00, 'R$ 1,00', '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 0, NULL, 0, 1, '2025-07-24 15:43:12', '2025-07-25 02:01:08'),
(79, 10, '4 Reais', 4.00, 1.00, 'R$ 4,00', '/prizes/SwcWlqfwHZSB9DATVIB61TcwCmECWwHerxGgyH1N.webp', 0, NULL, 0, 1, '2025-07-24 15:44:34', '2025-07-25 01:14:21'),
(80, 10, '5 Reais', 5.00, 1.00, 'R$ 5,00', '/prizes/rbasBzBRdefb88KQX0j5ExUjCU6HfzAbERRC8FOm.webp', 0, NULL, 0, 1, '2025-07-24 15:44:51', '2025-07-25 01:10:15'),
(81, 10, '10 Reais', 10.00, 0.70, 'R$ 10,00', '/prizes/IEZJdeg6UGDAQRnBHIFgTuJ23Uydb9nTyee1yOU8.webp', 0, NULL, 0, 1, '2025-07-24 15:45:05', '2025-07-24 22:26:38'),
(82, 10, '15 Reais', 15.00, 0.30, 'R$ 15,00', '/prizes/SL3iEV7DIC5DwrvJ2JkMOGCKQcgSRMBYQaWZYcDa.webp', 0, NULL, 0, 1, '2025-07-24 15:45:19', '2025-07-24 22:07:07'),
(83, 10, '20 Reais', 20.00, 0.20, 'R$ 20,00', '/prizes/nNpnv1RUhzprh2fjOAItzDHAIVcLeVkDBZw6TBcl.webp', 0, NULL, 0, 1, '2025-07-24 15:45:31', '2025-07-24 23:34:12'),
(84, 10, '50 Reais', 50.00, 0.10, 'R$ 50,00', '/prizes/Zj3FV41BoTFk3dIQFoc0YhEEuMuGrW1xvHwgDVxz.webp', 0, NULL, 0, 1, '2025-07-24 15:45:48', '2025-07-24 15:45:48'),
(85, 10, '100 Reais', 100.00, 0.00, 'R$ 100,00', '/prizes/WKmvks0qy0pZYquYjvGeUOlzhs8fgD7jzcAZ6FPZ.webp', 0, NULL, 0, 1, '2025-07-24 15:46:02', '2025-07-24 15:46:02'),
(86, 10, '100 Reais', 100.00, 0.00, 'R$ 100,00', '/prizes/C1CPYXy4xOO1xqov3tVGW2K95vXN2o1xemvoW98j.webp', 0, NULL, 0, 1, '2025-07-24 15:46:03', '2025-07-24 17:31:27'),
(87, 10, '200 Reais', 200.00, 0.00, 'R$ 200,00', '/prizes/yVWZEtxv8dfFidB83PDgpe4AUVXdQqAqoTeMvTfx.webp', 0, NULL, 0, 1, '2025-07-24 15:46:18', '2025-07-24 15:46:18'),
(88, 10, '200 Reais', 200.00, 0.00, 'R$ 200,00', '/prizes/IjxPZa6tN68onc9GUfpi1YPxAc7Vot5STjvXKRu4.webp', 0, NULL, 0, 1, '2025-07-24 15:46:18', '2025-07-24 15:46:18'),
(89, 10, '500 Reais', 500.00, 0.00, 'R$ 500,00', '/prizes/Br3TBnH0VPyjNjBoRyW1yIgfus7m9FuL3FJFmJG9.webp', 0, NULL, 0, 1, '2025-07-24 15:46:34', '2025-07-24 15:46:34'),
(90, 10, '700 Reais', 700.00, 0.00, 'R$ 700,00', '/prizes/6Hc5QJaIzSmo2AKWRkPkpeZxdbdPAf2dzHtAylbT.webp', 0, NULL, 0, 1, '2025-07-24 15:46:48', '2025-07-24 15:46:48'),
(91, 10, '1.000 Reais', 1000.00, 0.00, 'R$ 1000,00', '/prizes/L4L3fGA58IeQNQXwLcaryzs0ooqYKlDS6X4XHp2B.webp', 0, NULL, 0, 1, '2025-07-24 15:47:10', '2025-07-24 15:47:10'),
(92, 11, 'Nada', 0.00, 70.00, 'R$ 0,00', NULL, 0, NULL, 0, 1, '2025-07-24 15:53:32', '2025-07-25 02:09:01'),
(96, 11, 'Iphone 13', 2500.00, 0.00, 'R$ 2500,00', '/prizes/rw4haS3rt5Q734oOFfeyGf5jqKQXYHhsbAmnSxAn.webp', 1, 5, 0, 1, '2025-07-24 15:53:32', '2025-07-24 19:28:52'),
(97, 11, 'Caixa de Som JBL', 2500.00, 0.00, 'R$ 2500,00', '/prizes/oO4Ztj17oepttwKSUCC3W7oNfpXuzfbbz8bQHtwi.webp', 0, NULL, 0, 1, '2025-07-24 15:54:40', '2025-07-24 15:54:40'),
(98, 11, '1.000 Reais', 1000.00, 0.00, 'R$ 1000,00', '/prizes/OqMA2OxMOse02BnziCSrTjIOeUbq3Apq5PNSIVXg.webp', 0, NULL, 0, 1, '2025-07-24 15:55:33', '2025-07-24 15:55:33'),
(99, 11, 'Smartphone Motorola', 800.00, 0.00, 'R$ 800,00', '/prizes/q7z2pdSid3UOCFd7eLxNmMoPA7MKcXObiZWSXEM5.webp', 0, NULL, 0, 1, '2025-07-24 15:56:11', '2025-07-24 15:56:11'),
(100, 11, '700 Reais', 700.00, 0.00, 'R$ 700,00', '/prizes/8sGhs9JiGA2neBlNu0HFZS6NxwUC34iOFVhga3bh.webp', 0, NULL, 0, 1, '2025-07-24 15:56:28', '2025-07-24 15:56:28'),
(101, 11, 'Bola de Futebol', 500.00, 0.00, 'R$ 500,00', '/prizes/hCncyI1L5l6g0ItJhC5U53BiRJM1svU1oRgu2Ur0.webp', 0, NULL, 0, 1, '2025-07-24 15:56:50', '2025-07-24 15:56:50'),
(102, 11, 'Perfume 212 VIP', 399.00, 0.00, 'R$ 399,00', '/prizes/msXwvYhiLKrjdQMnCHdgiLsFNbXcexrUd7rEDObd.webp', 0, NULL, 0, 1, '2025-07-24 15:57:07', '2025-07-24 15:57:07'),
(103, 11, 'Camisa de Time', 350.00, 0.00, 'R$ 350,00', '/prizes/VmM0AhYXIYMK7xl1tYLV8woVGe1gVzw7JN37iMmT.webp', 0, NULL, 0, 1, '2025-07-24 15:57:29', '2025-07-24 15:57:29'),
(104, 11, 'Fone de ouvido', 220.00, 0.00, 'R$ 220,00', '/prizes/xooi6oOH6E6eq2EP2w1L51teadbyAcHMvpUopG3c.webp', 0, NULL, 0, 1, '2025-07-24 15:57:50', '2025-07-24 15:57:50'),
(105, 11, '200 Reais', 200.00, 0.00, 'R$ 200,00', '/prizes/CoagqoljyRGep7ImVHOwTqMIyV21Bj0UCVHFGRLC.webp', 0, NULL, 0, 1, '2025-07-24 15:58:03', '2025-07-24 15:58:03'),
(106, 11, '200 Reais', 200.00, 0.00, 'R$ 200,00', '/prizes/bWaO0qGaTldjgU9rPmgSE98cbWjdoXcABPIC1sMp.webp', 0, NULL, 0, 1, '2025-07-24 15:58:03', '2025-07-24 15:58:03'),
(107, 11, 'Copo Stanley', 165.00, 0.00, 'R$ 165,00', '/prizes/rCloFLiNAUtlTtgxGbLsHoVaFKrvMN9YVd4BCbwf.webp', 0, NULL, 0, 1, '2025-07-24 15:58:29', '2025-07-24 15:58:29'),
(108, 11, '100 Reais', 100.00, 0.00, 'R$ 100,00', '/prizes/phFcqWZtUOpmOturwR0BIZglRgc6T7Kcs9GLRvFi.webp', 0, NULL, 0, 1, '2025-07-24 15:58:41', '2025-07-24 15:58:41'),
(109, 11, '50 Reais', 50.00, 1.00, 'R$ 50,00', '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 0, NULL, 0, 1, '2025-07-24 15:58:51', '2025-07-24 22:25:26'),
(110, 11, 'Chinelo Havaianas', 35.00, 0.00, 'R$ 35,00', '/prizes/2R4KUqGMA2Z2oq3HCwMyP5hmYjuUaMgvydqvQ2IX.webp', 0, NULL, 0, 1, '2025-07-24 15:59:13', '2025-07-24 15:59:13'),
(111, 11, '10 Reais', 10.00, 1.00, 'R$ 10,00', '/prizes/ih4y7K7icleuchHg5MAkhXh28rHKGtH0ir0tnAuq.webp', 0, NULL, 0, 1, '2025-07-24 15:59:27', '2025-07-25 02:06:05'),
(112, 11, '5 Reais', 5.00, 3.00, 'R$ 5,00', '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 0, NULL, 0, 1, '2025-07-24 15:59:40', '2025-07-25 02:05:31'),
(113, 11, '3 Reais', 3.00, 2.00, 'R$ 3,00', '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 0, NULL, 0, 1, '2025-07-24 15:59:53', '2025-07-24 23:59:12'),
(114, 11, '2 Reais', 2.00, 8.00, 'R$ 2,00', '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 0, NULL, 0, 1, '2025-07-24 16:00:12', '2025-07-25 02:08:11'),
(115, 11, '1 Real', 1.00, 15.00, 'R$ 1,00', '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 0, NULL, 0, 1, '2025-07-24 16:00:26', '2025-07-25 02:08:41'),
(136, 12, 'Nada', 0.00, 76.20, 'R$ 0,00', NULL, 0, NULL, 0, 1, '2025-07-24 16:14:06', '2025-07-25 02:29:54'),
(141, 12, 'Churrasqueira a gás', 15000.00, 0.00, 'R$ 15000,00', '/prizes/HNH2mFEf2LwxnpTWGIw7ONwt4hXO5SXTDkDBXnxP.webp', 0, NULL, 0, 1, '2025-07-24 16:17:13', '2025-07-24 16:17:13'),
(142, 12, 'Moto honda Biz', 13000.00, 0.00, 'R$ 13000,00', '/prizes/iGwquFn5FvYk48s7o1Vc2PkJCvLtIhZY4kHVjKs1.webp', 0, NULL, 0, 1, '2025-07-24 16:17:35', '2025-07-24 16:17:35'),
(143, 12, 'Moto honda Pop', 11500.00, 0.00, 'R$ 11500,00', '/prizes/Qnfis0tcGyavtuPtn6AZDRfzeXn0W3lzSAhwRYVp.webp', 0, NULL, 0, 1, '2025-07-24 16:18:01', '2025-07-24 16:18:01'),
(144, 12, 'Iphone 15 pro', 11000.00, 0.00, 'R$ 11000,00', '/prizes/Fo2gdKluLgrPtdrW5sKsgqZecKSN9InHhrAQQAsI.webp', 0, NULL, 0, 1, '2025-07-24 16:18:29', '2025-07-24 18:16:38'),
(145, 12, '10.000 Reais', 10000.00, 0.00, 'R$ 10000,00', '/prizes/DSAUbqViIK0Hbwmawq0HxDpaAXjRFCmyQ8mBWTPs.webp', 0, NULL, 0, 1, '2025-07-24 16:19:03', '2025-07-24 18:16:48'),
(146, 12, 'Apple Watch', 9000.00, 0.00, 'R$ 9000,00', '/prizes/UNVEzeD37nTwYDsldwm9ZZOujdnvIX5AXyIO3Zwt.webp', 0, NULL, 0, 1, '2025-07-24 16:19:23', '2025-07-24 16:19:23'),
(147, 12, 'Geladeira Frost Free', 7500.00, 0.00, 'R$ 7500,00', '/prizes/ENU0rtnnvnEXhjC76DFSkecQxg5VDaFkhbYqrBwJ.webp', 0, NULL, 0, 1, '2025-07-24 16:19:51', '2025-07-24 18:16:54'),
(148, 12, 'Galaxy Z Flip 5', 6000.00, 0.00, 'R$ 6000,00', '/prizes/dOSrqTDmib2W1BBFsNglXSaO0dSD5W4AED9PbK7W.webp', 0, NULL, 0, 1, '2025-07-24 16:20:29', '2025-07-24 16:20:29'),
(149, 12, '5.000 Reais', 5000.00, 0.00, 'R$ 5000,00', '/prizes/6huGMqFqAyP6wJl0mjU3se7NiZk1nNwvs3omIlfz.webp', 0, NULL, 0, 1, '2025-07-24 16:21:07', '2025-07-24 18:17:00'),
(150, 12, 'Xbox Series X', 4500.00, 0.00, 'R$ 4500,00', '/prizes/Val6Q8a7lsr9D8ALcvYDBJ2eOijIfbXtyy03jlhx.webp', 0, NULL, 0, 1, '2025-07-24 16:21:32', '2025-07-24 19:08:52'),
(151, 12, 'Playstation 5', 4500.00, 0.00, 'R$ 4500,00', '/prizes/pLBwBfAwapcJkCUIAlTCePqwW6BEroUmK8HNc8k7.webp', 0, NULL, 0, 1, '2025-07-24 16:21:52', '2025-07-24 18:17:06'),
(152, 12, 'Lava Louças', 4000.00, 0.00, 'R$ 4000,00', '/prizes/ymUqG6VZyblQZIJlL9e9br8PDF4K7V0At0KT7iRC.webp', 0, NULL, 0, 1, '2025-07-24 16:22:55', '2025-07-24 16:22:55'),
(153, 12, '700 Reais', 700.00, 0.00, 'R$ 700,00', '/prizes/0dDeaNsSyDht4N8PNMyAi9DwoniNDuhPkQEZUMbw.webp', 0, NULL, 0, 1, '2025-07-24 16:23:13', '2025-07-24 23:06:33'),
(154, 12, '500 Reais', 500.00, 0.00, 'R$ 500,00', '/prizes/W7ZLi9PspJnh8nDC85ptfPYHktfuBR4OqCRVxutd.webp', 0, NULL, 0, 1, '2025-07-24 16:23:27', '2025-07-24 23:06:28'),
(155, 12, 'Controle Xbox', 500.00, 0.00, 'R$ 500,00', '/prizes/5sNqs7HdO4sRG320INIo7EH6z57J1DMP3A8yP6XV.webp', 0, NULL, 0, 1, '2025-07-24 16:23:51', '2025-07-24 16:23:51'),
(156, 12, 'Controle DualSense', 470.00, 0.00, 'R$ 470,00', '/prizes/m8KHQd6MyheHmeWNjtfbXDThucDkn3eSGWdJ4tG8.webp', 0, NULL, 0, 1, '2025-07-24 16:24:14', '2025-07-24 16:24:14'),
(157, 12, '200 Reais', 200.00, 0.00, 'R$ 200,00', '/prizes/YVqFBuzNqMI6jZKOyBgPKkuEEFid5ydg6eqauB0K.webp', 0, NULL, 0, 1, '2025-07-24 16:24:25', '2025-07-24 16:24:25'),
(158, 12, 'Fone de ouvido', 170.00, 0.00, 'R$ 170,00', '/prizes/nXZzHGwR5O4Srlztgq4P6dyyzbzLYzdcpwNL59pp.webp', 0, NULL, 0, 1, '2025-07-24 16:24:51', '2025-07-24 16:24:51'),
(159, 12, '100 Reais', 100.00, 1.80, 'R$ 100,00', '/prizes/wDfJumIfTc8ZICRAstczt4koBvzrLa8JQvYfN19E.webp', 0, NULL, 0, 1, '2025-07-24 16:25:03', '2025-07-24 19:25:13'),
(160, 12, '50 Reais', 50.00, 2.00, 'R$ 50,00', '/prizes/iDJN7gzhhNbZgKkb4zqOGmhMYqelhnN7ejMaTTxi.webp', 0, NULL, 0, 1, '2025-07-24 16:25:19', '2025-07-24 16:25:19'),
(161, 12, '15 Reais', 15.00, 2.00, 'R$ 15,00', '/prizes/W52ZJ9qoUmeVE8ZCRRIlZ3wrxc3Nx2lGeAu1sUWB.webp', 0, NULL, 0, 1, '2025-07-24 16:25:35', '2025-07-24 19:25:19'),
(162, 12, '10 Reais', 10.00, 5.00, 'R$ 10,00', '/prizes/QfZ67Bi8ZbC7eUK09RSw8pCVf1JwtUPxKZ4jcW1J.webp', 0, NULL, 0, 1, '2025-07-24 16:25:53', '2025-07-24 23:29:04'),
(163, 12, '5 Reais', 5.00, 11.00, 'R$ 5,00', '/prizes/ucW5NTt12gprXjDoBYXXzG2pEcib8zV0sfk5Se4t.webp', 0, NULL, 0, 1, '2025-07-24 16:26:16', '2025-07-24 22:24:15'),
(164, 12, '2 Reais', 2.00, 2.00, 'R$ 2,00', '/prizes/2Jn0F7xCqOhOl36dHcaxAzevkux0fUvHg02aRr4c.webp', 0, NULL, 0, 1, '2025-07-24 16:26:28', '2025-07-24 16:26:28'),
(165, 13, 'Nada', 0.00, 80.35, 'R$ 0,00', NULL, 0, NULL, 0, 1, '2025-07-24 20:36:51', '2025-07-25 02:09:36'),
(170, 13, '5.000 Reais', 5000.00, 0.00, 'R$ 5000,00', '/prizes/IzGwovAlSUXfn7F9yN6m5EKNF3FdL0PRXQEsvc3e.webp', 0, NULL, 0, 1, '2025-07-24 20:46:09', '2025-07-24 20:46:09'),
(171, 13, '5.000 Reais', 5000.00, 0.00, 'R$ 5000,00', '/prizes/GPe3U1aOTircVjfV8hhfrK08BDDFkZzej1oE3oK7.webp', 0, NULL, 0, 1, '2025-07-24 20:46:10', '2025-07-24 20:46:10'),
(172, 13, 'IPhone 15', 5000.00, 0.00, 'R$ 5000,00', '/prizes/I3iAys4pvznGN6nYoqzQRikkrMZuxjfCHGLKW4KL.webp', 0, NULL, 0, 1, '2025-07-24 20:46:32', '2025-07-24 20:46:32'),
(173, 13, 'Notebook Dell G15', 4500.00, 0.00, 'R$ 4500,00', '/prizes/tQMbyCpzJN7Q5lEqRhabicvVr9A77bSNPLMIEknr.webp', 0, NULL, 0, 1, '2025-07-24 20:47:05', '2025-07-24 20:47:05'),
(174, 13, 'PlayStation 5', 4500.00, 0.00, 'R$ 4500,00', '/prizes/GSQ79KLr2CtjHp6HEpEjo6Lb5Ju6hP9y2l8fI9A1.webp', 0, NULL, 0, 1, '2025-07-24 20:47:32', '2025-07-24 20:47:32'),
(175, 13, 'SmartTV 4K de 50 polegadas', 3000.00, 0.00, 'R$ 3000,00', '/prizes/kQpLNjp1MJJOrAX3HRryUqcEgTUyp8M9rjSl6k2i.webp', 0, NULL, 0, 1, '2025-07-24 20:48:09', '2025-07-24 20:48:17'),
(176, 13, 'iPad 10', 2800.00, 0.00, 'R$ 2800,00', '/prizes/Hd0VmgyAzjyDis4pfsWMOvT1RclYGnnommjXyX1O.webp', 0, NULL, 0, 1, '2025-07-24 20:48:42', '2025-07-24 20:48:42'),
(177, 13, 'Caixa de Som JBL', 2500.00, 0.00, 'R$ 2500,00', '/prizes/pFIWVm4usjAgpHHM2Naev9tXQGRZ3gWmaOdJ6L6i.webp', 0, NULL, 0, 1, '2025-07-24 20:49:02', '2025-07-24 20:49:02'),
(178, 13, 'Apple AirPods 3', 1900.00, 0.00, 'R$ 1900,00', '/prizes/hc3ZWEHtIMdNNG8z21LbX6mKx9G6cjj4Dte7Kzal.webp', 0, NULL, 0, 1, '2025-07-24 20:49:25', '2025-07-24 20:49:25'),
(179, 13, '1.000 Reais', 1000.00, 0.00, 'R$ 1000,00', '/prizes/xu6RBxOJZoNP32lPkmtdyzbOVt5aFfEOb7gfGBxr.webp', 0, NULL, 0, 1, '2025-07-24 20:50:00', '2025-07-24 20:50:00'),
(180, 13, 'Air Fryer', 850.00, 0.00, 'R$ 850,00', '/prizes/yrbIT2bsnDjTEc1lytSf7HppRD37OQY3QFqHBD64.webp', 0, NULL, 0, 1, '2025-07-24 20:50:21', '2025-07-24 20:50:21'),
(181, 13, '700 Reais', 700.00, 0.00, 'R$ 700,00', '/prizes/G56cHBOkmG7RlH1E16Mhp6yK59oWhlt2jXaR1oXf.webp', 0, NULL, 0, 1, '2025-07-24 20:50:43', '2025-07-24 20:50:43'),
(182, 13, '500 Reais', 500.00, 0.00, 'R$ 500,00', '/prizes/IluUirK5aTsUPXreBaxaVgOHyFjV3KT9hiXKaks0.webp', 0, NULL, 0, 1, '2025-07-24 20:51:08', '2025-07-24 20:51:08'),
(183, 13, 'Adaptador de energia', 220.00, 0.00, 'R$ 220,00', '/prizes/sOzvUDFq0I9ytCjwhk6Y7nN4rO5Q1IR0wwHBMXSF.webp', 0, NULL, 0, 1, '2025-07-24 20:51:42', '2025-07-24 20:51:42'),
(184, 13, 'Adaptador de energia', 220.00, 0.00, 'R$ 220,00', '/prizes/WHUaqAOeqwKkWqUzRb9uD8ay9UuJbA4bQXYWngXQ.webp', 0, NULL, 0, 1, '2025-07-24 20:51:42', '2025-07-24 20:51:42'),
(185, 13, '200 Reais', 200.00, 0.05, 'R$ 200,00', '/prizes/ND7nswPk6qWCNzH9kYGdkpmnnalGEQCdHN2bwsAW.webp', 0, NULL, 0, 1, '2025-07-24 20:52:00', '2025-07-24 20:58:55'),
(186, 13, 'Fone de ouvido', 170.00, 0.00, 'R$ 170,00', '/prizes/ZGzOAZd0agLyooeHCxvchtnpnc0bcAciPmoaNF3t.webp', 0, NULL, 0, 1, '2025-07-24 20:52:16', '2025-07-24 20:52:16'),
(187, 13, 'Copo Stanley', 165.00, 0.10, 'R$ 165,00', '/prizes/CQd0J9LwqcQOUqW0PbKeOebGefiVzd79LAevW2C5.webp', 0, NULL, 0, 1, '2025-07-24 20:52:33', '2025-07-24 20:58:44'),
(188, 13, 'Smartwatch D20', 150.00, 0.20, 'R$ 150,00', '/prizes/u4QBEJX2cVyojpgRAJ5J1mCUiqTEufWeAOT8U0gE.webp', 0, NULL, 0, 1, '2025-07-24 20:52:52', '2025-07-24 20:58:34'),
(189, 13, '100 Reais', 100.00, 0.50, 'R$ 100,00', '/prizes/gKNnva86ehT7dTceO933fGDLjPP6cIteqIqqtNys.webp', 0, NULL, 0, 1, '2025-07-24 20:53:37', '2025-07-24 22:25:47'),
(190, 13, 'PowerBank', 60.00, 0.80, 'R$ 60,00', '/prizes/sREposDNUpnv4ZoC6q7NXEP0lqLNMlCMqFnarSuT.webp', 0, NULL, 0, 1, '2025-07-24 20:54:04', '2025-07-24 23:27:08'),
(191, 13, '50 Reais', 50.00, 0.00, 'R$ 50,00', '/prizes/I7M6RAlcOzyJ3neI5A7fyApjX7wQ4koo50VvFiXI.webp', 0, NULL, 0, 1, '2025-07-24 20:54:23', '2025-07-24 20:54:23'),
(192, 13, '50 Reais', 50.00, 1.00, 'R$ 50,00', '/prizes/ZfFIvIEBrM6IlItXBo5F8PunKSH8irE2tE99Qikf.webp', 0, NULL, 0, 1, '2025-07-24 20:54:23', '2025-07-25 00:00:49'),
(193, 13, '20 Reais', 20.00, 2.00, 'R$ 20,00', '/prizes/VSAR1uchCVEBqDxymrdgIKDBJzWoQ2Qbzypz4TGe.webp', 0, NULL, 0, 1, '2025-07-24 20:54:33', '2025-07-25 01:44:37'),
(194, 13, '5 Reais', 5.00, 5.00, 'R$ 5,00', '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 0, NULL, 0, 1, '2025-07-24 20:54:45', '2025-07-25 01:36:15'),
(195, 13, '2 Reais', 2.00, 10.00, 'R$ 2,00', '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 0, NULL, 0, 1, '2025-07-24 20:54:57', '2025-07-25 02:09:16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ultimos_ganhos`
--

CREATE TABLE `ultimos_ganhos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `namewin` varchar(255) NOT NULL,
  `prizename` varchar(255) NOT NULL,
  `valueprize` decimal(10,2) NOT NULL,
  `imgprize` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `ultimos_ganhos`
--

INSERT INTO `ultimos_ganhos` (`id`, `namewin`, `prizename`, `valueprize`, `imgprize`, `active`, `created_at`, `updated_at`) VALUES
(28, 'Maria Cleide', 'Churrasqueira a gás GS Performance', 5000.00, '/prizes/YTsv2iX4WEyr3lwNzUz1uoDpLEHIlRYAaCgLSmkc.webp', 1, '2025-07-23 21:59:24', '2025-07-23 21:59:24'),
(29, 'Mariana Costa', '500 Reais', 500.00, '/prizes/197loyiByg96cuPMNVHmMODqyT25bWx1U2S17HCM.webp', 1, '2025-07-23 22:00:37', '2025-07-23 22:00:37'),
(30, 'Rafael Mendes', 'Moto Honda Biz 110i', 13000.00, '/prizes/6dpZopNLeuWO4KIBEX2YWgCLXugMDK6OGYQZoyUR.webp', 1, '2025-07-23 22:01:14', '2025-07-23 22:01:14'),
(31, 'Beatriz Ferreira', 'Capinha transparente para iPhone 15', 30.00, '/prizes/kpEgt91VNqq90CiwKcfYmcHaZwvWTUWPdMJwq8Rr.webp', 1, '2025-07-23 22:04:20', '2025-07-23 22:04:20'),
(32, 'Camila Silveira', '1 Real', 1.00, '/prizes/MYeYVv9g0czLzYaNMCt0miLHDQYYwT8sDyB3ETns.webp', 1, '2025-07-23 22:04:59', '2025-07-23 22:04:59'),
(33, 'Aline Martins', '1 Real', 1.00, '/prizes/MYeYVv9g0czLzYaNMCt0miLHDQYYwT8sDyB3ETns.webp', 1, '2025-07-23 22:05:23', '2025-07-23 22:05:23'),
(34, 'Bruno Cavalcante', '1 Real', 1.00, '/prizes/MYeYVv9g0czLzYaNMCt0miLHDQYYwT8sDyB3ETns.webp', 1, '2025-07-23 22:11:39', '2025-07-23 22:11:39'),
(44, 'Glaciane Ferreira', '10 Reais', 10.00, '/prizes/JK1XATH5nTDGdNGdONtGXlUTIkNb5S0gL7YToAgq.webp', 1, '2025-07-24 18:48:37', '2025-07-24 18:48:37'),
(45, 'Glaciane Ferreira', 'Capinha transparente para iPhone 15', 30.00, '/prizes/kpEgt91VNqq90CiwKcfYmcHaZwvWTUWPdMJwq8Rr.webp', 1, '2025-07-24 18:49:00', '2025-07-24 18:49:00'),
(46, 'Glaciane Ferreira', '10 Reais', 10.00, '/prizes/JK1XATH5nTDGdNGdONtGXlUTIkNb5S0gL7YToAgq.webp', 1, '2025-07-24 18:49:36', '2025-07-24 18:49:36'),
(47, 'Glaciane Ferreira', '10 Reais', 10.00, '/prizes/JK1XATH5nTDGdNGdONtGXlUTIkNb5S0gL7YToAgq.webp', 1, '2025-07-24 18:49:43', '2025-07-24 18:49:43'),
(48, 'Glaciane Ferreira', '500 Reais', 500.00, '/prizes/197loyiByg96cuPMNVHmMODqyT25bWx1U2S17HCM.webp', 1, '2025-07-24 18:50:00', '2025-07-24 18:50:00'),
(49, 'Glaciane Ferreira', '50 Reais', 50.00, '/prizes/OrmjV1IXaKVXvKjFHuQRtyljo3x8EieYpTn2eT6s.webp', 1, '2025-07-24 18:50:01', '2025-07-24 18:50:01'),
(50, 'Glaciane Ferreira', '10 Reais', 10.00, '/prizes/JK1XATH5nTDGdNGdONtGXlUTIkNb5S0gL7YToAgq.webp', 1, '2025-07-24 18:50:09', '2025-07-24 18:50:09'),
(51, 'Dayane Soares', 'Iphone 13', 2500.00, '/prizes/rw4haS3rt5Q734oOFfeyGf5jqKQXYHhsbAmnSxAn.webp', 1, '2025-07-24 19:26:51', '2025-07-24 19:26:51'),
(52, 'Dayane Soares', 'Iphone 13', 2500.00, '/prizes/rw4haS3rt5Q734oOFfeyGf5jqKQXYHhsbAmnSxAn.webp', 1, '2025-07-24 19:28:52', '2025-07-24 19:28:52'),
(53, 'Dayane Soares', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 19:35:33', '2025-07-24 19:35:33'),
(54, 'Glaciane Ferreira', '100 Reais', 100.00, '/prizes/36cMDSsNSv8xGK2y1jYObC2JXFus8wYne0qEfOko.webp', 1, '2025-07-24 20:13:43', '2025-07-24 20:13:43'),
(55, 'Glaciane Ferreira', '20 Reais', 20.00, '/prizes/VSAR1uchCVEBqDxymrdgIKDBJzWoQ2Qbzypz4TGe.webp', 1, '2025-07-24 21:15:20', '2025-07-24 21:15:20'),
(56, 'Glaciane Ferreira', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 21:15:38', '2025-07-24 21:15:38'),
(57, 'Glaciane Ferreira', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 21:16:15', '2025-07-24 21:16:15'),
(58, 'Daniela lopes guimaraes', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 21:59:53', '2025-07-24 21:59:53'),
(59, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:00:13', '2025-07-24 22:00:13'),
(60, 'Daniela lopes guimaraes', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:00:30', '2025-07-24 22:00:30'),
(61, 'Crislainy Galdino da Silva', '10 Reais', 10.00, '/prizes/ih4y7K7icleuchHg5MAkhXh28rHKGtH0ir0tnAuq.webp', 1, '2025-07-24 22:00:34', '2025-07-24 22:00:34'),
(62, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:00:55', '2025-07-24 22:00:55'),
(63, 'Crislainy Galdino da Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:01:32', '2025-07-24 22:01:32'),
(64, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:02:27', '2025-07-24 22:02:27'),
(65, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:03:13', '2025-07-24 22:03:13'),
(66, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:03:23', '2025-07-24 22:03:23'),
(67, 'Jenyffer Soares', '10 Reais', 10.00, '/prizes/IEZJdeg6UGDAQRnBHIFgTuJ23Uydb9nTyee1yOU8.webp', 1, '2025-07-24 22:03:27', '2025-07-24 22:03:27'),
(68, 'Tâmara Denisy', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:03:31', '2025-07-24 22:03:31'),
(69, 'Talita Silva', '1 Real', 1.00, '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 1, '2025-07-24 22:03:31', '2025-07-24 22:03:31'),
(70, 'Talita Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:03:52', '2025-07-24 22:03:52'),
(71, 'Jenyffer Soares', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:03:52', '2025-07-24 22:03:52'),
(72, 'ruty sara da Silva freitas', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:04:02', '2025-07-24 22:04:02'),
(73, 'Crislainy Galdino da Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:04:11', '2025-07-24 22:04:11'),
(74, 'Jenyffer Soares', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:04:19', '2025-07-24 22:04:19'),
(75, 'Jaqueline Monteiro', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:04:23', '2025-07-24 22:04:23'),
(76, 'ruty sara da Silva freitas', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:04:27', '2025-07-24 22:04:27'),
(77, 'Jenyffer Soares', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:04:29', '2025-07-24 22:04:29'),
(78, 'Talita Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:04:34', '2025-07-24 22:04:34'),
(79, 'Jaqueline Monteiro', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:04:38', '2025-07-24 22:04:38'),
(80, 'Radimila galdino pereira', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:04:39', '2025-07-24 22:04:39'),
(81, 'Renata de Araújo Alves dos Santos', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:04:56', '2025-07-24 22:04:56'),
(82, 'Talita Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:05:01', '2025-07-24 22:05:01'),
(83, 'Radimila galdino pereira', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:05:02', '2025-07-24 22:05:02'),
(84, 'Antonia Erica', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:05:03', '2025-07-24 22:05:03'),
(85, 'Jaqueline Monteiro', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:05:11', '2025-07-24 22:05:11'),
(86, 'ruty sara da Silva freitas', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:05:15', '2025-07-24 22:05:15'),
(87, 'Bruna Alves', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:05:17', '2025-07-24 22:05:17'),
(88, 'Antonia Erica', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:05:19', '2025-07-24 22:05:19'),
(89, 'Tâmara Denisy', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:05:21', '2025-07-24 22:05:21'),
(90, 'Talita Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:05:28', '2025-07-24 22:05:28'),
(91, 'Antonia Erica', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:05:28', '2025-07-24 22:05:28'),
(92, 'Anthony gabriel', '10 Reais', 10.00, '/prizes/ih4y7K7icleuchHg5MAkhXh28rHKGtH0ir0tnAuq.webp', 1, '2025-07-24 22:05:29', '2025-07-24 22:05:29'),
(93, 'Valdeneide galdino', '1 Real', 1.00, '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 1, '2025-07-24 22:05:46', '2025-07-24 22:05:46'),
(94, 'Talita Silva', '20 Reais', 20.00, '/prizes/VSAR1uchCVEBqDxymrdgIKDBJzWoQ2Qbzypz4TGe.webp', 1, '2025-07-24 22:05:47', '2025-07-24 22:05:47'),
(95, 'Enilma de França Costa Fideles', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:03', '2025-07-24 22:06:03'),
(96, 'Jenyffer Soares', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:06:03', '2025-07-24 22:06:03'),
(97, 'Jaqueline Monteiro', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:06:04', '2025-07-24 22:06:04'),
(98, 'Maria Priscila silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:06', '2025-07-24 22:06:06'),
(99, 'ruty sara da Silva freitas', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:06', '2025-07-24 22:06:06'),
(100, 'Nicolle da Silva santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:11', '2025-07-24 22:06:11'),
(101, 'Radimila galdino pereira', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:06:13', '2025-07-24 22:06:13'),
(102, 'ruty sara da Silva freitas', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:15', '2025-07-24 22:06:15'),
(103, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:17', '2025-07-24 22:06:17'),
(104, 'ruty sara da Silva freitas', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:06:18', '2025-07-24 22:06:18'),
(105, 'ruty sara da Silva freitas', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:06:19', '2025-07-24 22:06:19'),
(106, 'Bruna Alves', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:06:21', '2025-07-24 22:06:21'),
(107, 'Jaqueline Monteiro', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:24', '2025-07-24 22:06:24'),
(108, 'Enilma de França Costa Fideles', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:25', '2025-07-24 22:06:25'),
(109, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:26', '2025-07-24 22:06:26'),
(110, 'Rayonara Duarte de Sousa', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:06:27', '2025-07-24 22:06:27'),
(111, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:27', '2025-07-24 22:06:27'),
(112, 'Maria Priscila silva', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:06:32', '2025-07-24 22:06:32'),
(113, 'Antonia Erica', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:06:33', '2025-07-24 22:06:33'),
(114, 'Maria Priscila silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:35', '2025-07-24 22:06:35'),
(115, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:41', '2025-07-24 22:06:41'),
(116, 'Nicolle da Silva santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:42', '2025-07-24 22:06:42'),
(117, 'Enilma de França Costa Fideles', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:42', '2025-07-24 22:06:42'),
(118, 'Nicolle da Silva santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:06:48', '2025-07-24 22:06:48'),
(119, 'Crislainy Galdino da Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:06:55', '2025-07-24 22:06:55'),
(120, 'Josicarla Alves', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:06:57', '2025-07-24 22:06:57'),
(121, 'Maria Priscila silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:07:00', '2025-07-24 22:07:00'),
(122, 'Crislainy Galdino da Silva', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:07:01', '2025-07-24 22:07:01'),
(123, 'ruty sara da Silva freitas', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:07:03', '2025-07-24 22:07:03'),
(124, 'Tâmara Denisy', '15 Reais', 15.00, '/prizes/SL3iEV7DIC5DwrvJ2JkMOGCKQcgSRMBYQaWZYcDa.webp', 1, '2025-07-24 22:07:07', '2025-07-24 22:07:07'),
(125, 'Radimila galdino pereira', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:07:07', '2025-07-24 22:07:07'),
(126, 'ruty sara da Silva freitas', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:07:13', '2025-07-24 22:07:13'),
(127, 'Radimila galdino pereira', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:07:14', '2025-07-24 22:07:14'),
(128, 'Larissa dantas da Cruz Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:07:19', '2025-07-24 22:07:19'),
(129, 'Lucicleide saturnina lucena', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:07:28', '2025-07-24 22:07:28'),
(130, 'Nicolle da Silva santos', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:07:36', '2025-07-24 22:07:36'),
(131, 'Jenyffer Soares', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:07:36', '2025-07-24 22:07:36'),
(132, 'Nicolle da Silva santos', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:07:37', '2025-07-24 22:07:37'),
(133, 'Lucicleide saturnina lucena', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:07:39', '2025-07-24 22:07:39'),
(134, 'Jenyffer Soares', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:07:43', '2025-07-24 22:07:43'),
(135, 'Jenyffer Soares', '50 Reais', 50.00, '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 1, '2025-07-24 22:07:43', '2025-07-24 22:07:43'),
(136, 'Anthony gabriel', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:07:52', '2025-07-24 22:07:52'),
(137, 'Jaiane da Silveira Martins', '4 Reais', 4.00, '/prizes/SwcWlqfwHZSB9DATVIB61TcwCmECWwHerxGgyH1N.webp', 1, '2025-07-24 22:07:54', '2025-07-24 22:07:54'),
(138, 'Anthony gabriel', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:07:56', '2025-07-24 22:07:56'),
(139, 'Enilma de França Costa Fideles', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:07:56', '2025-07-24 22:07:56'),
(140, 'Crislainy Galdino da Silva', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:08:00', '2025-07-24 22:08:00'),
(141, 'Josicarla Alves', '1 Real', 1.00, '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 1, '2025-07-24 22:08:03', '2025-07-24 22:08:03'),
(142, 'Jaqueline Monteiro', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:08:06', '2025-07-24 22:08:06'),
(143, 'Glaciane Ferreira', '10 Reais', 10.00, '/prizes/JK1XATH5nTDGdNGdONtGXlUTIkNb5S0gL7YToAgq.webp', 1, '2025-07-24 22:08:15', '2025-07-24 22:08:15'),
(144, 'Glaciane Ferreira', '50 Reais', 50.00, '/prizes/OrmjV1IXaKVXvKjFHuQRtyljo3x8EieYpTn2eT6s.webp', 1, '2025-07-24 22:08:16', '2025-07-24 22:08:16'),
(145, 'Larissa dantas da Cruz Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:08:32', '2025-07-24 22:08:32'),
(146, 'Samara Pereira', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:08:33', '2025-07-24 22:08:33'),
(147, 'Antônio Rosendo Arrais', '3 Reais', 3.00, '/prizes/ADcvahhxcvNX3Hz8KtFKeX2LjlON3jaI3PQlGbqq.webp', 1, '2025-07-24 22:08:36', '2025-07-24 22:08:36'),
(148, 'Crislainy Galdino da Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:08:40', '2025-07-24 22:08:40'),
(149, 'Antônio Rosendo Arrais', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:08:46', '2025-07-24 22:08:46'),
(150, 'Antônio Rosendo Arrais', '5 Reais', 5.00, '/prizes/rbasBzBRdefb88KQX0j5ExUjCU6HfzAbERRC8FOm.webp', 1, '2025-07-24 22:08:48', '2025-07-24 22:08:48'),
(151, 'Glaciane Ferreira', '10 Reais', 10.00, '/prizes/JK1XATH5nTDGdNGdONtGXlUTIkNb5S0gL7YToAgq.webp', 1, '2025-07-24 22:08:51', '2025-07-24 22:08:51'),
(152, 'Antônio Rosendo Arrais', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:08:52', '2025-07-24 22:08:52'),
(153, 'Antônio Rosendo Arrais', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:08:52', '2025-07-24 22:08:52'),
(154, 'Antônio Rosendo Arrais', '1 Real', 1.00, '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 1, '2025-07-24 22:08:53', '2025-07-24 22:08:53'),
(155, 'Glaciane Ferreira', '50 Reais', 50.00, '/prizes/OrmjV1IXaKVXvKjFHuQRtyljo3x8EieYpTn2eT6s.webp', 1, '2025-07-24 22:08:56', '2025-07-24 22:08:56'),
(156, 'Keliane Santos', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:09:02', '2025-07-24 22:09:02'),
(157, 'Keliane Santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:09:04', '2025-07-24 22:09:04'),
(158, 'Valdeneide galdino', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:09:07', '2025-07-24 22:09:07'),
(159, 'Larissa dantas da Cruz Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:09:10', '2025-07-24 22:09:10'),
(160, 'Dayane Monteiro', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:09:26', '2025-07-24 22:09:26'),
(161, 'Dayane Monteiro', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:09:28', '2025-07-24 22:09:28'),
(162, 'Laisa Dantas dantas', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:09:36', '2025-07-24 22:09:36'),
(163, 'Bruna Alves', '100 Reais', 100.00, '/prizes/gKNnva86ehT7dTceO933fGDLjPP6cIteqIqqtNys.webp', 1, '2025-07-24 22:09:42', '2025-07-24 22:09:42'),
(164, 'Valdeneide galdino', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:09:45', '2025-07-24 22:09:45'),
(165, 'Radimila galdino pereira', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:09:50', '2025-07-24 22:09:50'),
(166, 'Nicolle da Silva santos', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:09:56', '2025-07-24 22:09:56'),
(167, 'Nicolle da Silva santos', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:09:59', '2025-07-24 22:09:59'),
(168, 'Nicolle da Silva santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:10:00', '2025-07-24 22:10:00'),
(169, 'Antônio Rosendo Arrais', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:10:01', '2025-07-24 22:10:01'),
(170, 'Nicolle da Silva santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:10:03', '2025-07-24 22:10:03'),
(171, 'Jocerlandia da silva leandro', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:10:27', '2025-07-24 22:10:27'),
(172, 'Valdeneide galdino', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:10:45', '2025-07-24 22:10:45'),
(173, 'Enilma de França Costa Fideles', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:10:53', '2025-07-24 22:10:53'),
(174, 'Leticia Diniz', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:11:07', '2025-07-24 22:11:07'),
(175, 'Antônio Rosendo Arrais', '4 Reais', 4.00, '/prizes/SwcWlqfwHZSB9DATVIB61TcwCmECWwHerxGgyH1N.webp', 1, '2025-07-24 22:11:12', '2025-07-24 22:11:12'),
(176, 'Antônio Rosendo Arrais', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:11:13', '2025-07-24 22:11:13'),
(177, 'Antônio Rosendo Arrais', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:11:15', '2025-07-24 22:11:15'),
(178, 'Laisa Dantas dantas', '50 Reais', 50.00, '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 1, '2025-07-24 22:11:17', '2025-07-24 22:11:17'),
(179, 'Keliane Santos', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:11:21', '2025-07-24 22:11:21'),
(180, 'Renata de Araújo Alves dos Santos', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:11:25', '2025-07-24 22:11:25'),
(181, 'Kamila Soares', '10 Reais', 10.00, '/prizes/ih4y7K7icleuchHg5MAkhXh28rHKGtH0ir0tnAuq.webp', 1, '2025-07-24 22:11:25', '2025-07-24 22:11:25'),
(182, 'Maria dos Milagres Alves Gomes', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:11:29', '2025-07-24 22:11:29'),
(183, 'Keliane Santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:11:32', '2025-07-24 22:11:32'),
(184, 'Valdeneide galdino', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:11:34', '2025-07-24 22:11:34'),
(185, 'Keliane Santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:11:35', '2025-07-24 22:11:35'),
(186, 'Keliane Santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:11:36', '2025-07-24 22:11:36'),
(187, 'Leticia Diniz', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:11:37', '2025-07-24 22:11:37'),
(188, 'Maria gadelha', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:12:01', '2025-07-24 22:12:01'),
(189, 'Karina Medeiros', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:12:14', '2025-07-24 22:12:14'),
(190, 'Maria dos Milagres Alves Gomes', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:12:24', '2025-07-24 22:12:24'),
(191, 'Leticia Diniz', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:12:25', '2025-07-24 22:12:25'),
(192, 'Laisa Dantas dantas', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:12:26', '2025-07-24 22:12:26'),
(193, 'Leticia Diniz', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:12:27', '2025-07-24 22:12:27'),
(194, 'Antônio Rosendo Arrais', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:12:28', '2025-07-24 22:12:28'),
(195, 'Jenyffer Soares', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:12:36', '2025-07-24 22:12:36'),
(196, 'Jenyffer Soares', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:12:36', '2025-07-24 22:12:36'),
(197, 'Jenyffer Soares', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:12:37', '2025-07-24 22:12:37'),
(198, 'Caliene Aucilene', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:12:38', '2025-07-24 22:12:38'),
(199, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:12:42', '2025-07-24 22:12:42'),
(200, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:12:43', '2025-07-24 22:12:43'),
(201, 'Keliane Santos', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:12:45', '2025-07-24 22:12:45'),
(202, 'Radimila galdino pereira', '1 Real', 1.00, '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 1, '2025-07-24 22:12:48', '2025-07-24 22:12:48'),
(203, 'Kamila Soares', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:12:55', '2025-07-24 22:12:55'),
(204, 'Antônio Rosendo Arrais', '3 Reais', 3.00, '/prizes/ADcvahhxcvNX3Hz8KtFKeX2LjlON3jaI3PQlGbqq.webp', 1, '2025-07-24 22:12:57', '2025-07-24 22:12:57'),
(205, 'Maria ilma Lúcio', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:13:00', '2025-07-24 22:13:00'),
(206, 'Antônio Rosendo Arrais', '1 Real', 1.00, '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 1, '2025-07-24 22:13:00', '2025-07-24 22:13:00'),
(207, 'Maria gadelha', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:13:01', '2025-07-24 22:13:01'),
(208, 'Jenyffer Soares', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:13:04', '2025-07-24 22:13:04'),
(209, 'Glaciane Ferreira', '10 Reais', 10.00, '/prizes/JK1XATH5nTDGdNGdONtGXlUTIkNb5S0gL7YToAgq.webp', 1, '2025-07-24 22:13:10', '2025-07-24 22:13:10'),
(210, 'Glaciane Ferreira', '50 Reais', 50.00, '/prizes/OrmjV1IXaKVXvKjFHuQRtyljo3x8EieYpTn2eT6s.webp', 1, '2025-07-24 22:13:13', '2025-07-24 22:13:13'),
(211, 'Samara Pereira', '1 Real', 1.00, '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 1, '2025-07-24 22:13:21', '2025-07-24 22:13:21'),
(212, 'Antônio Rosendo Arrais', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:13:31', '2025-07-24 22:13:31'),
(213, 'Maria dos Milagres Alves Gomes', '50 Reais', 50.00, '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 1, '2025-07-24 22:13:41', '2025-07-24 22:13:41'),
(214, 'Renata de Araújo Alves dos Santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:13:44', '2025-07-24 22:13:44'),
(215, 'Samara Pereira', '4 Reais', 4.00, '/prizes/SwcWlqfwHZSB9DATVIB61TcwCmECWwHerxGgyH1N.webp', 1, '2025-07-24 22:13:55', '2025-07-24 22:13:55'),
(216, 'Lucicleide saturnina lucena', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:13:56', '2025-07-24 22:13:56'),
(217, 'Camila Almeida da Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:14:02', '2025-07-24 22:14:02'),
(218, 'Leticia Diniz', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:14:03', '2025-07-24 22:14:03'),
(219, 'Antônio Rosendo Arrais', '3 Reais', 3.00, '/prizes/ADcvahhxcvNX3Hz8KtFKeX2LjlON3jaI3PQlGbqq.webp', 1, '2025-07-24 22:14:08', '2025-07-24 22:14:08'),
(220, 'Renata de Araújo Alves dos Santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:14:10', '2025-07-24 22:14:10'),
(221, 'Antônio Rosendo Arrais', '10 Reais', 10.00, '/prizes/IEZJdeg6UGDAQRnBHIFgTuJ23Uydb9nTyee1yOU8.webp', 1, '2025-07-24 22:14:13', '2025-07-24 22:14:13'),
(222, 'Antônio Rosendo Arrais', '3 Reais', 3.00, '/prizes/ADcvahhxcvNX3Hz8KtFKeX2LjlON3jaI3PQlGbqq.webp', 1, '2025-07-24 22:14:20', '2025-07-24 22:14:20'),
(223, 'Laisa Dantas dantas', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:14:32', '2025-07-24 22:14:32'),
(224, 'Camila Almeida da Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:14:32', '2025-07-24 22:14:32'),
(225, 'Iara Cristina Freitas dos Santos', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:14:35', '2025-07-24 22:14:35'),
(226, 'Jaqueline Monteiro', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:14:36', '2025-07-24 22:14:36'),
(227, 'Renata de Araújo Alves dos Santos', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:14:55', '2025-07-24 22:14:55'),
(228, 'Sara lins', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:15:01', '2025-07-24 22:15:01'),
(229, 'Keliane Santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:15:03', '2025-07-24 22:15:03'),
(230, 'Karina Medeiros', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:15:19', '2025-07-24 22:15:19'),
(231, 'Samara Pereira', '4 Reais', 4.00, '/prizes/SwcWlqfwHZSB9DATVIB61TcwCmECWwHerxGgyH1N.webp', 1, '2025-07-24 22:15:23', '2025-07-24 22:15:23'),
(232, 'Dayane Monteiro', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:15:29', '2025-07-24 22:15:29'),
(233, 'Maria gadelha', '10 Reais', 10.00, '/prizes/ih4y7K7icleuchHg5MAkhXh28rHKGtH0ir0tnAuq.webp', 1, '2025-07-24 22:15:37', '2025-07-24 22:15:37'),
(234, 'Radimila galdino pereira', '20 Reais', 20.00, '/prizes/VSAR1uchCVEBqDxymrdgIKDBJzWoQ2Qbzypz4TGe.webp', 1, '2025-07-24 22:15:44', '2025-07-24 22:15:44'),
(235, 'Maria Marta de Sousa Mota', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:15:54', '2025-07-24 22:15:54'),
(236, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:16:01', '2025-07-24 22:16:01'),
(237, 'Sara lins', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:16:03', '2025-07-24 22:16:03'),
(238, 'Amanda Pereira da Silva', 'PowerBank', 60.00, '/prizes/sREposDNUpnv4ZoC6q7NXEP0lqLNMlCMqFnarSuT.webp', 1, '2025-07-24 22:16:04', '2025-07-24 22:16:04'),
(239, 'Renata de Araújo Alves dos Santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:16:05', '2025-07-24 22:16:05'),
(240, 'Dayane Monteiro', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:16:10', '2025-07-24 22:16:10'),
(241, 'Antônia Monalisa de Lima', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:16:17', '2025-07-24 22:16:17'),
(242, 'Maria ilma Lúcio', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:16:26', '2025-07-24 22:16:26'),
(243, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:16:30', '2025-07-24 22:16:30'),
(244, 'Keliane Santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:16:37', '2025-07-24 22:16:37'),
(245, 'Dayane Monteiro', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:16:39', '2025-07-24 22:16:39'),
(246, 'Crislainy Galdino da Silva', '10 Reais', 10.00, '/prizes/IEZJdeg6UGDAQRnBHIFgTuJ23Uydb9nTyee1yOU8.webp', 1, '2025-07-24 22:16:43', '2025-07-24 22:16:43'),
(247, 'Dayane Monteiro', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:16:45', '2025-07-24 22:16:45'),
(248, 'Nicolle da Silva santos', '5 Reais', 5.00, '/prizes/rbasBzBRdefb88KQX0j5ExUjCU6HfzAbERRC8FOm.webp', 1, '2025-07-24 22:16:50', '2025-07-24 22:16:50'),
(249, 'Karina Medeiros', '50 Reais', 50.00, '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 1, '2025-07-24 22:16:58', '2025-07-24 22:16:58'),
(250, 'Karina Medeiros', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:17:00', '2025-07-24 22:17:00'),
(251, 'Maria Marta de Sousa Mota', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:17:01', '2025-07-24 22:17:01'),
(252, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:17:04', '2025-07-24 22:17:04'),
(253, 'Caliene Aucilene', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:17:05', '2025-07-24 22:17:05'),
(254, 'Samara Pereira', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:17:13', '2025-07-24 22:17:13'),
(255, 'Bruna Stefanie Souza de Araújo', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:17:13', '2025-07-24 22:17:13'),
(256, 'Keliane Santos', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:17:19', '2025-07-24 22:17:19'),
(257, 'Glaciane Ferreira', '10 Reais', 10.00, '/prizes/JK1XATH5nTDGdNGdONtGXlUTIkNb5S0gL7YToAgq.webp', 1, '2025-07-24 22:17:27', '2025-07-24 22:17:27'),
(258, 'Bruna Stefanie Souza de Araújo', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:17:32', '2025-07-24 22:17:32'),
(259, 'Dayane Monteiro', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:17:34', '2025-07-24 22:17:34'),
(260, 'Keliane Santos', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:17:38', '2025-07-24 22:17:38'),
(261, 'Glaciane Ferreira', '10 Reais', 10.00, '/prizes/JK1XATH5nTDGdNGdONtGXlUTIkNb5S0gL7YToAgq.webp', 1, '2025-07-24 22:17:41', '2025-07-24 22:17:41'),
(262, 'Kamila Soares', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:17:46', '2025-07-24 22:17:46'),
(263, 'Amanda Pereira da Silva', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:17:50', '2025-07-24 22:17:50'),
(264, 'Sara lins', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:17:55', '2025-07-24 22:17:55'),
(265, 'Bruna Stefanie Souza de Araújo', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:18:05', '2025-07-24 22:18:05'),
(266, 'Caliene Aucilene', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:18:07', '2025-07-24 22:18:07'),
(267, 'Tâmara Denisy', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:18:08', '2025-07-24 22:18:08'),
(268, 'Maria Marta de Sousa Mota', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:18:12', '2025-07-24 22:18:12'),
(269, 'Amanda Pereira da Silva', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:18:22', '2025-07-24 22:18:22'),
(270, 'Sara lins', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:18:22', '2025-07-24 22:18:22'),
(271, 'Dayane Monteiro', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:18:23', '2025-07-24 22:18:23'),
(272, 'Kamila Soares', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:18:25', '2025-07-24 22:18:25'),
(273, 'Keliane Santos', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:18:25', '2025-07-24 22:18:25'),
(274, 'Bruna Stefanie Souza de Araújo', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:18:28', '2025-07-24 22:18:28'),
(275, 'Tâmara Denisy', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:18:29', '2025-07-24 22:18:29'),
(276, 'Samara Pereira', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:18:33', '2025-07-24 22:18:33'),
(277, 'Maria ilma Lúcio', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:18:37', '2025-07-24 22:18:37'),
(278, 'Keliane Santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:18:39', '2025-07-24 22:18:39'),
(279, 'Bruna Stefanie Souza de Araújo', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:18:41', '2025-07-24 22:18:41'),
(280, 'Radimila galdino pereira', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 22:18:44', '2025-07-24 22:18:44'),
(281, 'Samara Pereira', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:18:50', '2025-07-24 22:18:50'),
(282, 'Bruna Stefanie Souza de Araújo', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:18:54', '2025-07-24 22:18:54'),
(283, 'ruty sara da Silva freitas', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:18:56', '2025-07-24 22:18:56'),
(284, 'Samara ferreira pereira', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:18:59', '2025-07-24 22:18:59'),
(285, 'Lucicleide saturnina lucena', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:19:03', '2025-07-24 22:19:03'),
(286, 'Sara lins', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:19:04', '2025-07-24 22:19:04'),
(287, 'Samara Pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:19:06', '2025-07-24 22:19:06'),
(288, 'MARIA ELIENE DE OLIVEIRA SILVA', '1 Real', 1.00, '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 1, '2025-07-24 22:19:10', '2025-07-24 22:19:10'),
(289, 'Karina Medeiros', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:19:13', '2025-07-24 22:19:13'),
(290, 'Juliana Cristina da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:19:13', '2025-07-24 22:19:13'),
(291, 'Bruna Stefanie Souza de Araújo', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:19:20', '2025-07-24 22:19:20'),
(292, 'Samara Pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:19:22', '2025-07-24 22:19:22'),
(293, 'Lucicleide saturnina lucena', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:19:23', '2025-07-24 22:19:23'),
(294, 'ruty sara da Silva freitas', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:19:26', '2025-07-24 22:19:26'),
(295, 'Samara ferreira pereira', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:19:27', '2025-07-24 22:19:27'),
(296, 'Joyce Sousa do Nascimento', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:19:29', '2025-07-24 22:19:29'),
(297, 'Dayane Monteiro', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 22:19:32', '2025-07-24 22:19:32'),
(298, 'Bruna Stefanie Souza de Araújo', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:19:35', '2025-07-24 22:19:35'),
(299, 'Sara lins', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:19:36', '2025-07-24 22:19:36'),
(300, 'Samara Pereira', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:19:39', '2025-07-24 22:19:39'),
(301, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:19:49', '2025-07-24 22:19:49'),
(302, 'Antônia Monalisa de Lima', '50 Reais', 50.00, '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 1, '2025-07-24 22:19:51', '2025-07-24 22:19:51'),
(303, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:19:51', '2025-07-24 22:19:51'),
(304, 'Bruna Stefanie Souza de Araújo', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:19:51', '2025-07-24 22:19:51'),
(305, 'Samara Pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:19:55', '2025-07-24 22:19:55'),
(306, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:20:00', '2025-07-24 22:20:00'),
(307, 'Karina Medeiros', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:20:01', '2025-07-24 22:20:01'),
(308, 'Juliana Cristina da Silva', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 22:20:09', '2025-07-24 22:20:09'),
(309, 'Samara Pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:20:10', '2025-07-24 22:20:10'),
(310, 'A Juliana Bezerra da Silva', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:20:13', '2025-07-24 22:20:13'),
(311, 'Camila Almeida da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:20:21', '2025-07-24 22:20:21'),
(312, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:20:26', '2025-07-24 22:20:26'),
(313, 'Amanda Pereira da Silva', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:20:30', '2025-07-24 22:20:30'),
(314, 'Iasmyn Dos Santos Alves', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:20:31', '2025-07-24 22:20:31'),
(315, 'Maria ilma Lúcio', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:20:45', '2025-07-24 22:20:45'),
(316, 'Caliene Aucilene', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:20:50', '2025-07-24 22:20:50'),
(317, 'Maria dos Milagres Alves Gomes', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:20:50', '2025-07-24 22:20:50'),
(318, 'Radimila galdino pereira', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 22:20:54', '2025-07-24 22:20:54'),
(319, 'Samara ferreira pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:20:55', '2025-07-24 22:20:55'),
(320, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:20:56', '2025-07-24 22:20:56'),
(321, 'Caliene Aucilene', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:20:58', '2025-07-24 22:20:58'),
(322, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:20:58', '2025-07-24 22:20:58'),
(323, 'Caliene Aucilene', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:20:58', '2025-07-24 22:20:58'),
(324, 'Paloma de Alencar França', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:21:02', '2025-07-24 22:21:02'),
(325, 'Maria ilma Lúcio', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:21:10', '2025-07-24 22:21:10'),
(326, 'Tâmara Denisy', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:21:13', '2025-07-24 22:21:13'),
(327, 'Anna Heloiza', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:21:14', '2025-07-24 22:21:14'),
(328, 'Camila Almeida da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:21:15', '2025-07-24 22:21:15'),
(329, 'Kamila Soares', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 22:21:17', '2025-07-24 22:21:17'),
(330, 'Anthony gabriel', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:21:18', '2025-07-24 22:21:18'),
(331, 'Radimila galdino pereira', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:21:25', '2025-07-24 22:21:25'),
(332, 'Lucicleide saturnina lucena', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:21:27', '2025-07-24 22:21:27'),
(333, 'Dayane Monteiro', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:21:28', '2025-07-24 22:21:28'),
(334, 'MARIA ELIENE DE OLIVEIRA SILVA', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:21:28', '2025-07-24 22:21:28'),
(335, 'MARIA ELIENE DE OLIVEIRA SILVA', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:21:30', '2025-07-24 22:21:30'),
(336, 'Camila Almeida da Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:21:32', '2025-07-24 22:21:32'),
(337, 'Iasmyn Dos Santos Alves', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:21:32', '2025-07-24 22:21:32'),
(338, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:21:37', '2025-07-24 22:21:37'),
(339, 'Antônio Rosendo Arrais', '1 Real', 1.00, '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 1, '2025-07-24 22:21:38', '2025-07-24 22:21:38'),
(340, 'Caliene Aucilene', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:21:47', '2025-07-24 22:21:47'),
(341, 'Anthony gabriel', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:21:47', '2025-07-24 22:21:47'),
(342, 'Samara Pereira', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:21:47', '2025-07-24 22:21:47'),
(343, 'A Juliana Bezerra da Silva', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:21:47', '2025-07-24 22:21:47'),
(344, 'Maria ilma Lúcio', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:21:48', '2025-07-24 22:21:48'),
(345, 'Samara ferreira pereira', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:21:51', '2025-07-24 22:21:51'),
(346, 'Tâmara Denisy', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:21:54', '2025-07-24 22:21:54'),
(347, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:21:56', '2025-07-24 22:21:56'),
(348, 'Amanda Pereira da Silva', '50 Reais', 50.00, '/prizes/ZfFIvIEBrM6IlItXBo5F8PunKSH8irE2tE99Qikf.webp', 1, '2025-07-24 22:22:12', '2025-07-24 22:22:12'),
(349, 'Maria Lucileide Da Silva Nascimento', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:22:22', '2025-07-24 22:22:22'),
(350, 'Maria Lucileide Da Silva Nascimento', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:22:24', '2025-07-24 22:22:24'),
(351, 'Samara ferreira pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:22:24', '2025-07-24 22:22:24'),
(352, 'Lucicleide saturnina lucena', '50 Reais', 50.00, '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 1, '2025-07-24 22:22:25', '2025-07-24 22:22:25'),
(353, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:22:33', '2025-07-24 22:22:33'),
(354, 'Samara ferreira pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:22:37', '2025-07-24 22:22:37'),
(355, 'Edivania tome da silva', '50 Reais', 50.00, '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 1, '2025-07-24 22:22:38', '2025-07-24 22:22:38'),
(356, 'Radimila galdino pereira', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:22:47', '2025-07-24 22:22:47'),
(357, 'Amanda Pereira da Silva', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 22:22:49', '2025-07-24 22:22:49'),
(358, 'Bruna Stefanie Souza de Araújo', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:22:52', '2025-07-24 22:22:52'),
(359, 'Crislainy Galdino da Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:22:53', '2025-07-24 22:22:53'),
(360, 'Reinaldo Santos de Freitas', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:22:55', '2025-07-24 22:22:55'),
(361, 'Natalia Mariano Silva', '50 Reais', 50.00, '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 1, '2025-07-24 22:22:58', '2025-07-24 22:22:58'),
(362, 'Lucicleide saturnina lucena', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:22:59', '2025-07-24 22:22:59'),
(363, 'Radimila galdino pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:23:00', '2025-07-24 22:23:00');
INSERT INTO `ultimos_ganhos` (`id`, `namewin`, `prizename`, `valueprize`, `imgprize`, `active`, `created_at`, `updated_at`) VALUES
(364, 'Karina Medeiros', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:23:00', '2025-07-24 22:23:00'),
(365, 'Maria ilma Lúcio', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:23:01', '2025-07-24 22:23:01'),
(366, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:23:01', '2025-07-24 22:23:01'),
(367, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:23:04', '2025-07-24 22:23:04'),
(368, 'Iasmyn Dos Santos Alves', '10 Reais', 10.00, '/prizes/ih4y7K7icleuchHg5MAkhXh28rHKGtH0ir0tnAuq.webp', 1, '2025-07-24 22:23:05', '2025-07-24 22:23:05'),
(369, 'Iasmyn Dos Santos Alves', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:23:06', '2025-07-24 22:23:06'),
(370, 'A Juliana Bezerra da Silva', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:23:15', '2025-07-24 22:23:15'),
(371, 'Natalia Mariano Silva', '10 Reais', 10.00, '/prizes/ih4y7K7icleuchHg5MAkhXh28rHKGtH0ir0tnAuq.webp', 1, '2025-07-24 22:23:15', '2025-07-24 22:23:15'),
(372, 'Lucicleide saturnina lucena', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:23:16', '2025-07-24 22:23:16'),
(373, 'Caliene Aucilene', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:23:18', '2025-07-24 22:23:18'),
(374, 'Camila Almeida da Silva', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:23:21', '2025-07-24 22:23:21'),
(375, 'Antonia Karol', '20 Reais', 20.00, '/prizes/nNpnv1RUhzprh2fjOAItzDHAIVcLeVkDBZw6TBcl.webp', 1, '2025-07-24 22:23:26', '2025-07-24 22:23:26'),
(376, 'Edivania tome da silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:23:26', '2025-07-24 22:23:26'),
(377, 'Samara ferreira pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:23:33', '2025-07-24 22:23:33'),
(378, 'Iasmyn Dos Santos Alves', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:23:35', '2025-07-24 22:23:35'),
(379, 'Crislainy Galdino da Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:23:38', '2025-07-24 22:23:38'),
(380, 'Karina Medeiros', '10 Reais', 10.00, '/prizes/ih4y7K7icleuchHg5MAkhXh28rHKGtH0ir0tnAuq.webp', 1, '2025-07-24 22:23:40', '2025-07-24 22:23:40'),
(381, 'Samara Pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:23:42', '2025-07-24 22:23:42'),
(382, 'Bruna Stefanie Souza de Araújo', '5 Reais', 5.00, '/prizes/ucW5NTt12gprXjDoBYXXzG2pEcib8zV0sfk5Se4t.webp', 1, '2025-07-24 22:23:46', '2025-07-24 22:23:46'),
(383, 'Antônio Rosendo Arrais', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:23:48', '2025-07-24 22:23:48'),
(384, 'Anthony gabriel', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:23:52', '2025-07-24 22:23:52'),
(385, 'Bruna Stefanie Souza de Araújo', '5 Reais', 5.00, '/prizes/ucW5NTt12gprXjDoBYXXzG2pEcib8zV0sfk5Se4t.webp', 1, '2025-07-24 22:23:56', '2025-07-24 22:23:56'),
(386, 'A Juliana Bezerra da Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:23:57', '2025-07-24 22:23:57'),
(387, 'Samara Pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:23:57', '2025-07-24 22:23:57'),
(388, 'Aline Santos', '10 Reais', 10.00, '/prizes/IEZJdeg6UGDAQRnBHIFgTuJ23Uydb9nTyee1yOU8.webp', 1, '2025-07-24 22:23:59', '2025-07-24 22:23:59'),
(389, 'Anthony gabriel', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:24:03', '2025-07-24 22:24:03'),
(390, 'Samara ferreira pereira', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:24:03', '2025-07-24 22:24:03'),
(391, 'Dayane Monteiro', '5 Reais', 5.00, '/prizes/ucW5NTt12gprXjDoBYXXzG2pEcib8zV0sfk5Se4t.webp', 1, '2025-07-24 22:24:15', '2025-07-24 22:24:15'),
(392, 'Samara Pereira', '50 Reais', 50.00, '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 1, '2025-07-24 22:24:15', '2025-07-24 22:24:15'),
(393, 'Edivania tome da silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:24:17', '2025-07-24 22:24:17'),
(394, 'Antonia Karol', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:24:21', '2025-07-24 22:24:21'),
(395, 'Priscila Vieira Gomes', '10 Reais', 10.00, '/prizes/ih4y7K7icleuchHg5MAkhXh28rHKGtH0ir0tnAuq.webp', 1, '2025-07-24 22:24:22', '2025-07-24 22:24:22'),
(396, 'Amanda Pereira da Silva', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 22:24:25', '2025-07-24 22:24:25'),
(397, 'A Juliana Bezerra da Silva', '10 Reais', 10.00, '/prizes/ih4y7K7icleuchHg5MAkhXh28rHKGtH0ir0tnAuq.webp', 1, '2025-07-24 22:24:26', '2025-07-24 22:24:26'),
(398, 'Rayonara Duarte de Sousa', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:24:28', '2025-07-24 22:24:28'),
(399, 'Samara Pereira', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:24:28', '2025-07-24 22:24:28'),
(400, 'Karina Medeiros', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:24:37', '2025-07-24 22:24:37'),
(401, 'Samara ferreira pereira', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:24:38', '2025-07-24 22:24:38'),
(402, 'Samara Pereira', '50 Reais', 50.00, '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 1, '2025-07-24 22:24:38', '2025-07-24 22:24:38'),
(403, 'Anthony gabriel', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:24:41', '2025-07-24 22:24:41'),
(404, 'Reinaldo Santos de Freitas', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:24:41', '2025-07-24 22:24:41'),
(405, 'Amanda Pereira da Silva', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 22:24:46', '2025-07-24 22:24:46'),
(406, 'Edivania tome da silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:24:49', '2025-07-24 22:24:49'),
(407, 'Priscila Vieira Gomes', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:24:54', '2025-07-24 22:24:54'),
(408, 'Edivania tome da silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:25:04', '2025-07-24 22:25:04'),
(409, 'Karina Medeiros', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:25:05', '2025-07-24 22:25:05'),
(410, 'Caliene Aucilene', '3 Reais', 3.00, '/prizes/ADcvahhxcvNX3Hz8KtFKeX2LjlON3jaI3PQlGbqq.webp', 1, '2025-07-24 22:25:07', '2025-07-24 22:25:07'),
(411, 'Amanda Pereira da Silva', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:25:07', '2025-07-24 22:25:07'),
(412, 'Camila Almeida da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:25:08', '2025-07-24 22:25:08'),
(413, 'Aline Santos', '20 Reais', 20.00, '/prizes/VSAR1uchCVEBqDxymrdgIKDBJzWoQ2Qbzypz4TGe.webp', 1, '2025-07-24 22:25:15', '2025-07-24 22:25:15'),
(414, 'Anthony gabriel', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:25:20', '2025-07-24 22:25:20'),
(415, 'Rayonara Duarte de Sousa', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:25:25', '2025-07-24 22:25:25'),
(416, 'Samara Pereira', '50 Reais', 50.00, '/prizes/H9YokTTFNFAwXfyt98vkzzhoHxhpEkPALsslV7gb.webp', 1, '2025-07-24 22:25:26', '2025-07-24 22:25:26'),
(417, 'Crislainy Galdino da Silva', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:25:30', '2025-07-24 22:25:30'),
(418, 'Ana carla Garcia dos santos', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:25:35', '2025-07-24 22:25:35'),
(419, 'Aline Santos', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:25:43', '2025-07-24 22:25:43'),
(420, 'Paloma de Alencar França', '0,50 Centavos', 0.50, '/prizes/bLyfaGKbep8Z7NzwSBY3GhRyyiJ5e0xtQ4xtOsRu.webp', 1, '2025-07-24 22:25:47', '2025-07-24 22:25:47'),
(421, 'Aline Santos', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 22:25:47', '2025-07-24 22:25:47'),
(422, 'Amanda Pereira da Silva', '100 Reais', 100.00, '/prizes/gKNnva86ehT7dTceO933fGDLjPP6cIteqIqqtNys.webp', 1, '2025-07-24 22:25:47', '2025-07-24 22:25:47'),
(423, 'Natalia Mariano Silva', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 22:25:56', '2025-07-24 22:25:56'),
(424, 'Caliene Aucilene', '20 Reais', 20.00, '/prizes/nNpnv1RUhzprh2fjOAItzDHAIVcLeVkDBZw6TBcl.webp', 1, '2025-07-24 22:25:57', '2025-07-24 22:25:57'),
(425, 'Samara Pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:25:58', '2025-07-24 22:25:58'),
(426, 'Rayonara Duarte de Sousa', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:26:00', '2025-07-24 22:26:00'),
(427, 'Amanda Pereira da Silva', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:26:02', '2025-07-24 22:26:02'),
(428, 'Crislainy Galdino da Silva', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:26:06', '2025-07-24 22:26:06'),
(429, 'Karyelle Lacerda', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:26:09', '2025-07-24 22:26:09'),
(430, 'Karina Medeiros', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:26:09', '2025-07-24 22:26:09'),
(431, 'Priscila Vieira Gomes', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:26:12', '2025-07-24 22:26:12'),
(432, 'Amanda Pereira da Silva', '5 Reais', 5.00, '/prizes/mRCddjmIH1MlsXLtDOKdvhrayAQfr46kXqnnmYWQ.webp', 1, '2025-07-24 22:26:13', '2025-07-24 22:26:13'),
(433, 'Karyelle Lacerda', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:26:20', '2025-07-24 22:26:20'),
(434, 'Crislainy Galdino da Silva', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:26:21', '2025-07-24 22:26:21'),
(435, 'Francisco da silva', '1 Real', 1.00, '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 1, '2025-07-24 22:26:21', '2025-07-24 22:26:21'),
(436, 'Aline Santos', '20 Reais', 20.00, '/prizes/VSAR1uchCVEBqDxymrdgIKDBJzWoQ2Qbzypz4TGe.webp', 1, '2025-07-24 22:26:24', '2025-07-24 22:26:24'),
(437, 'Iasmyn Dos Santos Alves', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:26:24', '2025-07-24 22:26:24'),
(438, 'Priscila Vieira Gomes', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:26:26', '2025-07-24 22:26:26'),
(439, 'Anthony gabriel', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:26:28', '2025-07-24 22:26:28'),
(440, 'Crislainy Galdino da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:26:35', '2025-07-24 22:26:35'),
(441, 'Rayonara Duarte de Sousa', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:26:35', '2025-07-24 22:26:35'),
(442, 'Ana carla Garcia dos santos', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:26:35', '2025-07-24 22:26:35'),
(443, 'Samara Pereira', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:26:36', '2025-07-24 22:26:36'),
(444, 'Francisco da silva', '10 Reais', 10.00, '/prizes/IEZJdeg6UGDAQRnBHIFgTuJ23Uydb9nTyee1yOU8.webp', 1, '2025-07-24 22:26:38', '2025-07-24 22:26:38'),
(445, 'Caliene Aucilene', '1 Real', 1.00, '/prizes/oDCCoiGC3hKv42K1368BV1i49EICK0gWxRmYWFdM.webp', 1, '2025-07-24 22:26:39', '2025-07-24 22:26:39'),
(446, 'Natalia Mariano Silva', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:26:42', '2025-07-24 22:26:42'),
(447, 'Karyelle Lacerda', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:26:45', '2025-07-24 22:26:45'),
(448, 'Leide jane ferreira Dutra', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:26:50', '2025-07-24 22:26:50'),
(449, 'Fernanda Luiza da Silva Dantas', '2 Reais', 2.00, '/prizes/gLcuQP2PpJefjfW94UcP2md2VfwbQcRhaU0lAnbH.webp', 1, '2025-07-24 22:26:57', '2025-07-24 22:26:57'),
(450, 'Karyelle Lacerda', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:26:59', '2025-07-24 22:26:59'),
(451, 'Karina Medeiros', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:27:03', '2025-07-24 22:27:03'),
(452, 'Juliana Cristina da Silva', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:27:06', '2025-07-24 22:27:06'),
(453, 'Edivania tome da silva', '5 Reais', 5.00, '/prizes/Z2DGPzS33YpevYZDYOo34FHeV6rsM6LTGaHQcDoS.webp', 1, '2025-07-24 22:27:15', '2025-07-24 22:27:15'),
(454, 'Dayane Monteiro', '50 Reais', 50.00, '/prizes/ZfFIvIEBrM6IlItXBo5F8PunKSH8irE2tE99Qikf.webp', 1, '2025-07-24 22:27:26', '2025-07-24 22:27:26'),
(455, 'Priscila Vieira Gomes', '2 Reais', 2.00, '/prizes/7DLPVDjrB3BJWXd3pEBSFnzlBHWdw9IM067UmykL.webp', 1, '2025-07-24 22:27:29', '2025-07-24 22:27:29'),
(456, 'Antônio Rosendo Arrais', 'R$ 2,00', 2.00, '/prizes/gAD8pXGfJWoZ9cF5P5ao78HNs5KOsvqKyQKSrOWO.webp', 1, '2025-07-24 22:27:32', '2025-07-24 22:27:32'),
(457, 'Ana carla Garcia dos santos', '10 Reais', 10.00, '/prizes/ih4y7K7icleuchHg5MAkhXh28rHKGtH0ir0tnAuq.webp', 1, '2025-07-24 22:27:33', '2025-07-24 22:27:33'),
(458, 'Raiane cota de Freitas', '50 Reais', 50.00, '/prizes/ZfFIvIEBrM6IlItXBo5F8PunKSH8irE2tE99Qikf.webp', 1, '2025-07-24 22:27:35', '2025-07-24 22:27:35'),
(459, 'Rayonara Duarte de Sousa', '3 Reais', 3.00, '/prizes/adC1RDmU0DCxIhlxFv8CNhcJdAi5SiYCiyn2laQD.webp', 1, '2025-07-24 22:27:36', '2025-07-24 22:27:36'),
(460, 'Lucicleide saturnina lucena', '1 Real', 1.00, '/prizes/j2m2oVJpN8ISp5xZdzyfP8eqjAAlx8gM1oS5wb7p.webp', 1, '2025-07-24 22:27:37', '2025-07-24 22:27:37'),
(461, 'MAILA BEZERRIL DE SOUZA', 'iPhone 15 Pro', 11000.00, '/prizes/XaTF5rpngpCrzqNTZECWNs051N67QRRsnRePLYx3.webp', 1, '2025-07-24 22:23:38', '2025-07-24 22:23:38'),
(462, 'MAILA BEZERRIL DE SOUZAKarina Almeida', 'iPhone 15', 5000.00, '/prizes/g9WMns7MjIhxU2jcw8MLKhTjPkEk7jxOdyHmBa5T.webp', 1, '2025-07-24 22:26:31', '2025-07-24 22:26:31'),
(463, 'João Moreira', 'Geladeira Frost Free', 7500.00, '/prizes/Z9DA9GWz8XA9axyIz94lRm9dLIF0Zv08ARaqqRdG.webp', 1, '2025-07-24 22:34:47', '2025-07-24 22:34:47'),
(464, 'Isabela Castro', 'Moto CG 160 Start', 16500.00, '/prizes/wcZx4lvOwVev3tlFmCV6npsCfOvFjK1dVjaGZngC.webp', 1, '2025-07-24 22:35:44', '2025-07-24 22:35:44'),
(465, 'Henrique Sousa', '1.000 Reais', 1000.00, '/prizes/JY4JWrmbDiWEO20UI3QNNtutBHLC0udFOBfCeq4d.webp', 1, '2025-07-24 22:36:42', '2025-07-24 22:36:42'),
(466, 'Gabriela Lima', 'iPhone 12', 2500.00, '/prizes/HH22Km9LbnFyGiw3yVzpG09TugMqQQuc1CDOkdb9.webp', 1, '2025-07-24 22:37:54', '2025-07-24 22:37:54'),
(467, 'Felipe Rocha', 'Apple AirPods 3ª geração', 1900.00, '/prizes/Hw0gQZTWpKmw94QqCtJnT7UhmH93T4Jq920y9cFg.webp', 1, '2025-07-24 22:38:56', '2025-07-24 22:38:56'),
(468, 'Bruno Silva', 'Air Jordan 1 Low Purple', 1100.00, '/prizes/6iPoORYHf22U2yDfopPRR0eriJhTtigJKkMKEayw.webp', 1, '2025-07-24 22:39:51', '2025-07-24 22:39:51'),
(469, 'Carla Fernandes', 'PlayStation 5', 4500.00, '/prizes/lgwq88W6y99W0v6Zei1q6889P9AYpDv9F3BhhCtH.webp', 1, '2025-07-24 22:42:02', '2025-07-24 22:42:02'),
(470, 'Diego Costa', '1.000 Reais', 1000.00, '/prizes/JY4JWrmbDiWEO20UI3QNNtutBHLC0udFOBfCeq4d.webp', 1, '2025-07-24 22:42:07', '2025-07-24 22:42:07'),
(471, 'Elisa Martins', '1.000 Reais', 1000.00, '/prizes/JY4JWrmbDiWEO20UI3QNNtutBHLC0udFOBfCeq4d.webp', 1, '2025-07-24 22:42:11', '2025-07-24 22:42:11'),
(472, 'Ana Oliveira', 'iPhone 12', 2500.00, '/prizes/HH22Km9LbnFyGiw3yVzpG09TugMqQQuc1CDOkdb9.webp', 1, '2025-07-24 22:42:16', '2025-07-24 22:42:16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomecompleto` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('USER','ADMIN') NOT NULL DEFAULT 'USER',
  `referral_code` varchar(255) DEFAULT NULL,
  `referral_level` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `referral_xp` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `referral_commission` decimal(5,2) NOT NULL DEFAULT 0.00,
  `referred_by` varchar(255) DEFAULT NULL,
  `commission_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_deposit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_withdraw` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_cashback` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `document` varchar(255) DEFAULT NULL,
  `bloqueado` tinyint(1) NOT NULL DEFAULT 0,
  `is_influencer` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `last_ip` varchar(255) DEFAULT NULL,
  `last_logout` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `nomecompleto`, `email`, `username`, `telefone`, `password`, `role`, `referral_code`, `referral_level`, `referral_xp`, `referral_commission`, `referred_by`, `commission_balance`, `total_deposit`, `total_withdraw`, `total_cashback`, `balance`, `document`, `bloqueado`, `is_influencer`, `last_login`, `last_ip`, `last_logout`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'adm', 'adm@gmail.com', 'user202806', '(87) 98723-2321', '$2a$12$5Mf.AcQeShYeSBSeCUDSB.svL2SbQYv3HM8wUaKR80AWpwZUz47Pq', 'ADMIN', 'alHz1lwq', 1, 200, 50.00, NULL, 50.00, 30.00, 0.00, 0.00, 105.00, NULL, 0, 0, '2025-07-22 23:43:57', '127.0.0.1', NULL, NULL, '2025-07-22 23:43:57', '2025-07-25 02:40:57'),
(2, 'Teste de indicação', 'testeindicate@gmail.com', 'Teste172', '(87) 9982-3211', '$2y$12$bWl2QudKVG25es8SqyJBTeDnO5UI2lk/Pt/FoPkkBdhqDVgzuQIJS', 'USER', NULL, 1, 0, 0.00, 'alHz1lwq', 0.00, 50.00, 0.00, 0.00, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, '2025-07-23 05:16:08', '2025-07-23 05:19:08'),
(3, 'TESTE INDICACAO', 'testeindicacao2@gmail.com', 'TESTE649', '(87) 98821-2323', '$2y$12$uTH1oRqBlG3q0mAMcB9Pa.DVXT4bH84lOjEuKh8hiJszBnLk0juCW', 'USER', NULL, 1, 0, 0.00, 'alHz1lwq', 0.00, 200.00, 0.00, 0.00, 122.00, NULL, 0, 0, NULL, NULL, NULL, NULL, '2025-07-23 19:28:52', '2025-07-23 19:35:57');

-- --------------------------------------------------------

--
-- Estrutura para tabela `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `pix_key` varchar(255) NOT NULL,
  `pix_key_type` enum('cpf','cnpj','email','phone','random') NOT NULL,
  `document` varchar(255) NOT NULL,
  `status` enum('pending','completed','cancelled','failed') NOT NULL DEFAULT 'pending',
  `reason` text DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deposits_payment_id_unique` (`payment_id`),
  ADD UNIQUE KEY `deposits_external_id_unique` (`external_id`),
  ADD KEY `deposits_user_id_status_index` (`user_id`,`status`),
  ADD KEY `deposits_status_created_at_index` (`status`,`created_at`),
  ADD KEY `deposits_gateway_status_index` (`gateway`,`status`);

--
-- Índices de tabela `gatewayskeys`
--
ALTER TABLE `gatewayskeys`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `jogos_historico`
--
ALTER TABLE `jogos_historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jogos_historico_raspadinha_id_foreign` (`raspadinha_id`),
  ADD KEY `jogos_historico_user_id_created_at_index` (`user_id`,`created_at`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `raspadinhas`
--
ALTER TABLE `raspadinhas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `raspadinha_prizes`
--
ALTER TABLE `raspadinha_prizes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ultimos_ganhos`
--
ALTER TABLE `ultimos_ganhos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  ADD KEY `users_email_username_index` (`email`,`username`),
  ADD KEY `users_role_index` (`role`),
  ADD KEY `users_bloqueado_index` (`bloqueado`);

--
-- Índices de tabela `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `withdrawals_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `config`
--
ALTER TABLE `config`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `gatewayskeys`
--
ALTER TABLE `gatewayskeys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `jogos_historico`
--
ALTER TABLE `jogos_historico`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `raspadinhas`
--
ALTER TABLE `raspadinhas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `raspadinha_prizes`
--
ALTER TABLE `raspadinha_prizes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT de tabela `ultimos_ganhos`
--
ALTER TABLE `ultimos_ganhos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=473;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `deposits`
--
ALTER TABLE `deposits`
  ADD CONSTRAINT `deposits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `jogos_historico`
--
ALTER TABLE `jogos_historico`
  ADD CONSTRAINT `jogos_historico_raspadinha_id_foreign` FOREIGN KEY (`raspadinha_id`) REFERENCES `raspadinhas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jogos_historico_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD CONSTRAINT `withdrawals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;