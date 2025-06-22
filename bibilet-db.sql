-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 22 Haz 2025, 12:03:52
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `bibilet`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aktif_oturum`
--

CREATE TABLE `aktif_oturum` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `oturum_id` varchar(255) NOT NULL,
  `olusturma_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bilet_kaydi`
--

CREATE TABLE `bilet_kaydi` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `firma_id` int(11) NOT NULL,
  `sefer_id` int(11) NOT NULL,
  `tarih` date NOT NULL,
  `saat` time NOT NULL,
  `koltuk_no` int(11) NOT NULL,
  `cinsiyet` enum('erkek','kadin') NOT NULL,
  `pnr_kodu` varchar(10) NOT NULL,
  `odenen_tutar` decimal(10,2) NOT NULL,
  `odeme_durumu` tinyint(1) DEFAULT 0,
  `olusturma_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `firmalar`
--

CREATE TABLE `firmalar` (
  `id` int(11) NOT NULL,
  `firma_adi` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `firmalar`
--

INSERT INTO `firmalar` (`id`, `firma_adi`, `email`, `sifre`, `created_at`) VALUES
(1, 'buzlu', 'buzlu@gmail.com', '$2y$10$xKCIoBLlWg6MALItXXjhY.qtJEz7PPudSInehX9sSzjeqWmMUmNbu', '2025-05-19 12:10:35'),
(2, 'Kütahyalılar', 'qth@gmail.com', '$2y$10$lSiCZyGjPnrYisVq2bw2X.tRIt2J0gA5posJwqBPM6FgQxR43ube.', '2025-06-10 14:39:38');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `firma_seferler`
--

CREATE TABLE `firma_seferler` (
  `id` int(11) NOT NULL,
  `firma_id` int(11) NOT NULL,
  `kalkis_sehir` int(11) NOT NULL,
  `kalkis_otogar` int(11) NOT NULL,
  `varis_sehir` int(11) NOT NULL,
  `varis_otogar` int(11) NOT NULL,
  `tarih` date NOT NULL,
  `saat` time NOT NULL,
  `fiyat` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `firma_seferler`
--

INSERT INTO `firma_seferler` (`id`, `firma_id`, `kalkis_sehir`, `kalkis_otogar`, `varis_sehir`, `varis_otogar`, `tarih`, `saat`, `fiyat`) VALUES
(1, 2, 1, 1, 4, 12, '2025-06-22', '16:19:00', 500.00),
(2, 2, 1, 1, 3, 4, '2025-06-13', '12:00:00', 500.00),
(3, 2, 3, 6, 3, 7, '2025-06-15', '12:00:00', 100.00),
(5, 2, 8, 47, 8, 48, '2025-06-15', '18:00:00', 100000.00),
(6, 1, 43, 315, 1, 1, '2025-06-15', '00:52:00', 500.00),
(7, 1, 3, 6, 3, 7, '2025-06-15', '12:00:00', 123214.00),
(8, 2, 4, 10, 4, 11, '2025-06-18', '15:00:00', 500.00),
(9, 2, 1, 1, 4, 10, '2000-02-02', '00:00:00', 5000.00),
(10, 2, 2, 2, 1, 1, '2025-06-18', '10:00:00', 500.00),
(11, 2, 1, 1, 4, 10, '2025-06-18', '12:00:00', 1000.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kartlar`
--

CREATE TABLE `kartlar` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `kart_adi` varchar(50) NOT NULL,
  `kart_numarasi` varchar(255) NOT NULL,
  `son_kullanma_tarihi` varchar(5) NOT NULL,
  `cvc2` varchar(255) NOT NULL,
  `banka_adi` varchar(50) NOT NULL,
  `telefon` varchar(15) NOT NULL,
  `eklenme_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kayitli_yolcular`
--

CREATE TABLE `kayitli_yolcular` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `ad` varchar(50) NOT NULL,
  `soyad` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefon` varchar(15) NOT NULL,
  `tc_kimlik_no` varchar(255) NOT NULL,
  `dogum_tarihi` date NOT NULL,
  `cinsiyet` enum('Erkek','Kadın') NOT NULL,
  `eklenme_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `ad` varchar(50) NOT NULL,
  `soyad` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefon` varchar(15) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `otogarlar`
--

CREATE TABLE `otogarlar` (
  `id` int(11) NOT NULL,
  `sehir_id` int(11) NOT NULL,
  `otogar_adi` varchar(100) NOT NULL,
  `sehir_adi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `otogarlar`
--

INSERT INTO `otogarlar` (`id`, `sehir_id`, `otogar_adi`, `sehir_adi`) VALUES
(1, 1, 'Adana Otogarı', 'Adana'),
(2, 2, 'Esenler Otogarı', 'İstanbul'),
(3, 2, 'Basın Ekspres Otogarı', 'İstanbul'),
(4, 3, 'Adıyaman Otogarı', 'Adıyaman'),
(5, 3, 'Besni Otogarı', 'Adıyaman'),
(6, 3, 'Gölbaşı (Adıyaman) Otogarı', 'Adıyaman'),
(7, 3, 'Kahta Otogarı', 'Adıyaman'),
(8, 3, 'Sincik Otogarı', 'Adıyaman'),
(9, 3, 'Tut Otogarı', 'Adıyaman'),
(10, 4, 'Afyon Otogarı', 'Afyonkarahisar'),
(11, 4, 'Bolvadin Otogarı', 'Afyonkarahisar'),
(12, 4, 'Çay Otogarı', 'Afyonkarahisar'),
(13, 4, 'Dazkırı Otogarı', 'Afyonkarahisar'),
(14, 4, 'Dinar Otogarı', 'Afyonkarahisar'),
(15, 4, 'Emirdağ Otogarı', 'Afyonkarahisar'),
(16, 4, 'Hocalar Otogarı', 'Afyonkarahisar'),
(17, 4, 'İhsaniye Otogarı', 'Afyonkarahisar'),
(18, 4, 'Sandıklı Otogarı', 'Afyonkarahisar'),
(19, 4, 'Sultandağı Otogarı', 'Afyonkarahisar'),
(20, 5, 'Ağrı Otogarı', 'Ağrı'),
(21, 5, 'Doğubeyazıt Otogarı', 'Ağrı'),
(22, 5, 'Patnos Otogarı', 'Ağrı'),
(23, 5, 'Patnos Otogar', 'Ağrı'),
(24, 6, 'Amasya Otogarı', 'Amasya'),
(25, 6, 'Amasya Yeni Otogar', 'Amasya'),
(26, 6, 'Göynücek Otogarı', 'Amasya'),
(27, 6, 'Gümüşhacıköy Otogarı', 'Amasya'),
(28, 6, 'Merzifon Otogarı', 'Amasya'),
(29, 6, 'Suluova Otogarı', 'Amasya'),
(30, 6, 'Taşova Otogarı', 'Amasya'),
(31, 7, 'Ankara (Aşti) Otogarı', 'Ankara'),
(32, 7, 'Söğütözü (Ankara) Otogarı', 'Ankara'),
(33, 7, 'Akyurt Otogarı', 'Ankara'),
(34, 7, 'Bala Otogarı', 'Ankara'),
(35, 7, 'Beypazarı Otogarı', 'Ankara'),
(36, 7, 'Çubuk Otogarı', 'Ankara'),
(37, 7, 'Güdül Otogarı', 'Ankara'),
(38, 7, 'Haymana Otogarı', 'Ankara'),
(39, 7, 'Kalecik Otogarı', 'Ankara'),
(40, 7, 'Kazan Otogarı', 'Ankara'),
(41, 7, 'Kızılcahamam Otogarı', 'Ankara'),
(42, 7, 'Nallıhan Otogarı', 'Ankara'),
(43, 7, 'Polatlı Otogarı', 'Ankara'),
(44, 7, 'Şereflikoçhisar Otogarı', 'Ankara'),
(45, 8, 'Antalya Otogarı', 'Antalya'),
(46, 8, 'Alanya Otogarı', 'Antalya'),
(47, 8, 'Alanya Otogar', 'Antalya'),
(48, 8, 'Anamur Otogarı', 'Antalya'),
(49, 8, 'Elmalı Otogarı', 'Antalya'),
(50, 8, 'Finike Otogarı', 'Antalya'),
(51, 8, 'Gazipaşa Otogarı', 'Antalya'),
(52, 8, 'Kaş Otogarı', 'Antalya'),
(53, 8, 'Kaş Eski Otogarı', 'Antalya'),
(54, 8, 'Kemer (Antalya) Otogarı', 'Antalya'),
(55, 8, 'Korkuteli Otogarı', 'Antalya'),
(56, 8, 'Kumluca Otogarı', 'Antalya'),
(57, 8, 'Manavgat Otogarı', 'Antalya'),
(58, 8, 'Serik Otogarı', 'Antalya'),
(59, 9, 'Artvin Otogarı', 'Artvin'),
(60, 9, 'Ardanuç Otogarı', 'Artvin'),
(61, 9, 'Arhavi Otogarı', 'Artvin'),
(62, 9, 'Borçka Otogarı', 'Artvin'),
(63, 9, 'Hopa Otogarı', 'Artvin'),
(64, 9, 'Şavşat Otogarı', 'Artvin'),
(65, 9, 'Yusufeli Otogarı', 'Artvin'),
(66, 10, 'Aydın Otogarı', 'Aydın'),
(67, 10, 'Çine Otogarı', 'Aydın'),
(68, 10, 'Didim Otogarı', 'Aydın'),
(69, 10, 'Germencik Otogarı', 'Aydın'),
(70, 10, 'Kuşadası Otogarı', 'Aydın'),
(71, 10, 'Nazilli Otogarı', 'Aydın'),
(72, 10, 'Söke Otogarı', 'Aydın'),
(73, 11, 'Balıkesir Otogarı', 'Balıkesir'),
(74, 11, 'Ayvalık Otogarı', 'Balıkesir'),
(75, 11, 'Bandırma Otogarı', 'Balıkesir'),
(76, 11, 'Bigadiç Otogarı', 'Balıkesir'),
(77, 11, 'Burhaniye Otogarı', 'Balıkesir'),
(78, 11, 'Dursunbey Otogarı', 'Balıkesir'),
(79, 11, 'Edremit Otogarı', 'Balıkesir'),
(80, 11, 'Erdek Otogarı', 'Balıkesir'),
(81, 11, 'Gönen Otogarı', 'Balıkesir'),
(82, 11, 'Havran Otogarı', 'Balıkesir'),
(83, 11, 'Kepsut Otogarı', 'Balıkesir'),
(84, 11, 'Manyas Otogarı', 'Balıkesir'),
(85, 11, 'Savaştepe Otogarı', 'Balıkesir'),
(86, 11, 'Sındırgı Otogarı', 'Balıkesir'),
(87, 12, 'Bilecik Otogarı', 'Bilecik'),
(88, 12, 'Bozüyük Otogarı', 'Bilecik'),
(89, 12, 'Gölpazarı Otogarı', 'Bilecik'),
(90, 12, 'Osmaneli Otogarı', 'Bilecik'),
(91, 12, 'Pazaryeri Otogarı', 'Bilecik'),
(92, 12, 'Söğüt Otogarı', 'Bilecik'),
(93, 13, 'Bingöl Otogarı', 'Bingöl'),
(94, 14, 'Bitlis Otogarı', 'Bitlis'),
(95, 14, 'Adilcevaz Otogarı', 'Bitlis'),
(96, 14, 'Ahlat Otogarı', 'Bitlis'),
(97, 14, 'Tatvan Otogarı', 'Bitlis'),
(98, 15, 'Bolu Otogarı', 'Bolu'),
(99, 15, 'Dörtdivan Otogarı', 'Bolu'),
(100, 15, 'Gerede Otogarı', 'Bolu'),
(101, 15, 'Göynük Otogarı', 'Bolu'),
(102, 15, 'Kıbrıscık Otogarı', 'Bolu'),
(103, 15, 'Mengen Otogarı', 'Bolu'),
(104, 15, 'Mudurnu Otogarı', 'Bolu'),
(105, 16, 'Burdur Otogarı', 'Burdur'),
(106, 16, 'Bucak Otogarı', 'Burdur'),
(107, 16, 'Çavdır Otogarı', 'Burdur'),
(108, 16, 'Gölhisar Otogarı', 'Burdur'),
(109, 16, 'Tefenni Otogarı', 'Burdur'),
(110, 16, 'Yeşilova Otogarı', 'Burdur'),
(111, 17, 'Bursa Otogarı', 'Bursa'),
(112, 17, 'Gemlik Otogarı', 'Bursa'),
(113, 17, 'İnegöl Otogarı', 'Bursa'),
(114, 17, 'Karacabey Otogarı', 'Bursa'),
(115, 17, 'Mustafakemalpaşa Otogarı', 'Bursa'),
(116, 17, 'Orhaneli Otogarı', 'Bursa'),
(117, 17, 'Orhangazi Otogarı', 'Bursa'),
(118, 18, 'Çanakkale Otogarı', 'Çanakkale'),
(119, 18, 'Ayvacık (Çanakkale) Otogarı', 'Çanakkale'),
(120, 18, 'Biga Otogarı', 'Çanakkale'),
(121, 18, 'Çan Otogarı', 'Çanakkale'),
(122, 18, 'Eceabat Otogarı', 'Çanakkale'),
(123, 18, 'Ezine Otogarı', 'Çanakkale'),
(124, 18, 'Gelibolu Otogarı', 'Çanakkale'),
(125, 18, 'Geyikli Otogarı', 'Çanakkale'),
(126, 18, 'Lapseki Otogarı', 'Çanakkale'),
(127, 18, 'Yenice (Çanakkale) Otogarı', 'Çanakkale'),
(128, 19, 'Çankırı Otogarı', 'Çankırı'),
(129, 19, 'Eldivan Otogarı', 'Çankırı'),
(130, 19, 'Ilgaz Otogarı', 'Çankırı'),
(131, 19, 'Kurşunlu Otogarı', 'Çankırı'),
(132, 19, 'Orta Otogarı', 'Çankırı'),
(133, 19, 'Şabanözü Otogarı', 'Çankırı'),
(134, 20, 'Çorum Otogarı', 'Çorum'),
(135, 20, 'Çorum İlçe Terminali', 'Çorum'),
(136, 20, 'Alaca Otogarı', 'Çorum'),
(137, 20, 'Bayat (Çorum) Otogarı', 'Çorum'),
(138, 20, 'İskilip Otogarı', 'Çorum'),
(139, 20, 'Kargı Otogarı', 'Çorum'),
(140, 20, 'Mecitözü Otogarı', 'Çorum'),
(141, 20, 'Osmancık Otogarı', 'Çorum'),
(142, 20, 'Sungurlu Otogarı', 'Çorum'),
(143, 21, 'Denizli Otogarı', 'Denizli'),
(144, 21, 'Acıpayam Otogarı', 'Denizli'),
(145, 21, 'Bekilli Otogarı', 'Denizli'),
(146, 21, 'Buldan Otogarı', 'Denizli'),
(147, 21, 'Çal Otogarı', 'Denizli'),
(148, 21, 'Çivril Otogarı', 'Denizli'),
(149, 21, 'Honaz Otogarı', 'Denizli'),
(150, 21, 'Kale (Denizli) Otogarı', 'Denizli'),
(151, 21, 'Sarayköy Otogarı', 'Denizli'),
(152, 21, 'Serinhisar Otogarı', 'Denizli'),
(153, 21, 'Tavas Otogarı', 'Denizli'),
(154, 22, 'Diyarbakır Otogarı', 'Diyarbakır'),
(155, 22, 'Bismil Otogarı', 'Diyarbakır'),
(156, 22, 'Çermik Otogarı', 'Diyarbakır'),
(157, 22, 'Çınar Otogarı', 'Diyarbakır'),
(158, 22, 'Dargeçit Otogarı', 'Diyarbakır'),
(159, 22, 'Ergani Otogarı', 'Diyarbakır'),
(160, 22, 'Silvan Otogarı', 'Diyarbakır'),
(161, 23, 'Edirne Otogarı', 'Edirne'),
(162, 23, 'Enez Otogarı', 'Edirne'),
(163, 23, 'Havsa Otogarı', 'Edirne'),
(164, 23, 'İpsala Otogarı', 'Edirne'),
(165, 23, 'Keşan Otogarı', 'Edirne'),
(166, 23, 'Meriç Otogarı', 'Edirne'),
(167, 23, 'Uzunköprü Otogarı', 'Edirne'),
(168, 24, 'Elazığ Otogarı', 'Elazığ'),
(169, 24, 'Arapgir Otogarı', 'Elazığ'),
(170, 24, 'Karakoçan Otogarı', 'Elazığ'),
(171, 24, 'Kovancılar Otogarı', 'Elazığ'),
(172, 24, 'Maden Otogarı', 'Elazığ'),
(173, 24, 'Palu Otogarı', 'Elazığ'),
(174, 25, 'Erzincan Otogarı', 'Erzincan'),
(175, 25, 'Çayırlı Otogarı', 'Erzincan'),
(176, 25, 'Kemah Otogarı', 'Erzincan'),
(177, 25, 'Kemaliye Otogarı', 'Erzincan'),
(178, 25, 'Refahiye Otogarı', 'Erzincan'),
(179, 25, 'Tercan Otogarı', 'Erzincan'),
(180, 26, 'Erzurum Otogarı', 'Erzurum'),
(181, 26, 'Aşkale Otogarı', 'Erzurum'),
(182, 26, 'Horasan Otogarı', 'Erzurum'),
(183, 26, 'İspir Otogarı', 'Erzurum'),
(184, 26, 'Oltu Otogarı', 'Erzurum'),
(185, 26, 'Pasinler Otogarı', 'Erzurum'),
(186, 27, 'Eskişehir Otogarı', 'Eskişehir'),
(187, 27, 'Alpu Otogarı', 'Eskişehir'),
(188, 27, 'Beylikova Otogarı', 'Eskişehir'),
(189, 27, 'Çifteler Otogarı', 'Eskişehir'),
(190, 27, 'Mahmudiye Otogarı', 'Eskişehir'),
(191, 27, 'Mihalıççık Otogarı', 'Eskişehir'),
(192, 27, 'Seyitgazi Otogarı', 'Eskişehir'),
(193, 28, 'Gaziantep Otogarı', 'Gaziantep'),
(194, 28, 'Araban Otogarı', 'Gaziantep'),
(195, 28, 'Birecik Otogarı', 'Gaziantep'),
(196, 28, 'İslahiye Otogarı', 'Gaziantep'),
(197, 28, 'Nizip Otogarı', 'Gaziantep'),
(198, 29, 'Giresun Otogarı', 'Giresun'),
(199, 29, 'Bulancak Otogarı', 'Giresun'),
(200, 29, 'Espiye Otogarı', 'Giresun'),
(201, 29, 'Görele Otogarı', 'Giresun'),
(202, 29, 'Keşap Otogarı', 'Giresun'),
(203, 29, 'Tirebolu Otogarı', 'Giresun'),
(204, 30, 'Gümüşhane Otogarı', 'Gümüşhane'),
(205, 30, 'Kelkit Otogarı', 'Gümüşhane'),
(206, 30, 'Köse Otogarı', 'Gümüşhane'),
(207, 30, 'Şiran Otogarı', 'Gümüşhane'),
(208, 30, 'Torul Otogarı', 'Gümüşhane'),
(209, 31, 'Hakkari Otogarı', 'Hakkari'),
(210, 31, 'Çukurca Otogarı', 'Hakkari'),
(211, 31, 'Şemdinli Otogarı', 'Hakkari'),
(212, 31, 'Yüksekova Otogarı', 'Hakkari'),
(213, 32, 'Hatay Otogarı', 'Hatay'),
(214, 32, 'Hatay Köy Otogarı', 'Hatay'),
(215, 32, 'Antakya Otogarı', 'Hatay'),
(216, 32, 'Dörtyol Otogarı', 'Hatay'),
(217, 32, 'İskenderun Otogarı', 'Hatay'),
(218, 32, 'Kırıkhan Otogarı', 'Hatay'),
(219, 32, 'Reyhanlı Otogarı', 'Hatay'),
(220, 33, 'Isparta Otogarı', 'Isparta'),
(221, 33, 'Eğirdir Otogarı', 'Isparta'),
(222, 33, 'Gelendost Otogarı', 'Isparta'),
(223, 33, 'Gönen (Isparta) Otogarı', 'Isparta'),
(224, 33, 'Keçiborlu Otogarı', 'Isparta'),
(225, 33, 'Senirkent Otogarı', 'Isparta'),
(226, 33, 'Sütçüler Otogarı', 'Isparta'),
(227, 33, 'Şarkikaraağaç Otogarı', 'Isparta'),
(228, 33, 'Uluborlu Otogarı', 'Isparta'),
(229, 33, 'Yalvaç Otogarı', 'Isparta'),
(230, 34, 'Mersin Otogarı', 'Mersin'),
(231, 34, 'Mersin Eski Otogar', 'Mersin'),
(232, 34, 'Anamur Otogarı', 'Mersin'),
(233, 34, 'Aydıncık Otogarı', 'Mersin'),
(234, 34, 'Bozyazı Otogarı', 'Mersin'),
(235, 34, 'Çamlıyayla Otogarı', 'Mersin'),
(236, 34, 'Erdemli Otogarı', 'Mersin'),
(237, 34, 'Gülnar Otogarı', 'Mersin'),
(238, 34, 'Mut Otogarı', 'Mersin'),
(239, 34, 'Silifke Otogarı', 'Mersin'),
(240, 34, 'Tarsus Otogarı', 'Mersin'),
(241, 35, 'İstanbul Otogarı', 'İstanbul'),
(242, 35, 'Alibeyköy Otogarı', 'İstanbul'),
(243, 35, 'Harem Otogarı', 'İstanbul'),
(244, 36, 'İzmir Otogarı', 'İzmir'),
(245, 36, 'İzmir Otogar Tesis Otobüs Kalkış-Varış Noktası', 'İzmir'),
(246, 36, 'Aliağa Otogarı', 'İzmir'),
(247, 36, 'Bayındır Otogarı', 'İzmir'),
(248, 36, 'Bergama Otogarı', 'İzmir'),
(249, 36, 'Bornova Otogarı', 'İzmir'),
(250, 36, 'Çandarlı Otogarı', 'İzmir'),
(251, 36, 'Çeşme Otogarı', 'İzmir'),
(252, 36, 'Dikili Otogarı', 'İzmir'),
(253, 36, 'Foça Otogarı', 'İzmir'),
(254, 36, 'Karaburun Otogarı', 'İzmir'),
(255, 36, 'Kemalpaşa Otogarı', 'İzmir'),
(256, 36, 'Kiraz Otogarı', 'İzmir'),
(257, 36, 'Konak Otogarı', 'İzmir'),
(258, 36, 'Menemen Otogarı', 'İzmir'),
(259, 36, 'Ödemiş Otogarı', 'İzmir'),
(260, 36, 'Seferihisar Otogarı', 'İzmir'),
(261, 36, 'Selçuk (İzmir) Otogarı', 'İzmir'),
(262, 36, 'Tire Otogarı', 'İzmir'),
(263, 36, 'Torbalı Otogarı', 'İzmir'),
(264, 36, 'Urla Otogarı', 'İzmir'),
(265, 37, 'Kars Otogarı', 'Kars'),
(266, 37, 'Kars Eski Otogarı', 'Kars'),
(267, 37, 'Kars Turgutreis Otogarı', 'Kars'),
(268, 37, 'Akyaka Otogarı', 'Kars'),
(269, 37, 'Kağızman Otogarı', 'Kars'),
(270, 37, 'Sarıkamış Otogarı', 'Kars'),
(271, 37, 'Selim Otogarı', 'Kars'),
(272, 38, 'Kastamonu Otogarı', 'Kastamonu'),
(273, 38, 'Abana Otogarı', 'Kastamonu'),
(274, 38, 'Araç Otogarı', 'Kastamonu'),
(275, 38, 'Bozkurt Otogarı', 'Kastamonu'),
(276, 38, 'Cide Otogarı', 'Kastamonu'),
(277, 38, 'Çatalzeytin Otogarı', 'Kastamonu'),
(278, 38, 'Daday Otogarı', 'Kastamonu'),
(279, 38, 'Doğanyurt Otogarı', 'Kastamonu'),
(280, 38, 'İnebolu Otogarı', 'Kastamonu'),
(281, 38, 'Küre Otogarı', 'Kastamonu'),
(282, 38, 'Pınarbaşı (Kastamonu) Otogarı', 'Kastamonu'),
(283, 38, 'Taşköprü Otogarı', 'Kastamonu'),
(284, 38, 'Tosya Otogarı', 'Kastamonu'),
(285, 39, 'Kayseri Otogarı', 'Kayseri'),
(286, 39, 'Akkışla Otogarı', 'Kayseri'),
(287, 39, 'Bünyan Otogarı', 'Kayseri'),
(288, 39, 'Develi Otogarı', 'Kayseri'),
(289, 39, 'Felahiye Otogarı', 'Kayseri'),
(290, 39, 'İncesu Otogarı', 'Kayseri'),
(291, 39, 'Pınarbaşı Otogarı', 'Kayseri'),
(292, 39, 'Sarıoğlan Otogarı', 'Kayseri'),
(293, 39, 'Tomarza Otogarı', 'Kayseri'),
(294, 39, 'Yahyalı Otogarı', 'Kayseri'),
(295, 39, 'Yeşilhisar Otogarı', 'Kayseri'),
(296, 40, 'Kırklareli Otogarı', 'Kırklareli'),
(297, 40, 'Babaeski Otogarı', 'Kırklareli'),
(298, 40, 'Demirköy Otogarı', 'Kırklareli'),
(299, 40, 'Lüleburgaz Otogarı', 'Kırklareli'),
(300, 40, 'Pehlivanköy Otogarı', 'Kırklareli'),
(301, 40, 'Pınarhisar Otogarı', 'Kırklareli'),
(302, 40, 'Vize Otogarı', 'Kırklareli'),
(303, 41, 'Kırşehir Otogarı', 'Kırşehir'),
(304, 41, 'Akçakent Otogarı', 'Kırşehir'),
(305, 41, 'Akpınar Otogarı', 'Kırşehir'),
(306, 41, 'Kaman Otogarı', 'Kırşehir'),
(307, 41, 'Mucur Otogarı', 'Kırşehir'),
(308, 42, 'Kocaeli Otogarı', 'Kocaeli'),
(309, 42, 'Gebze Otogarı', 'Kocaeli'),
(310, 42, 'Gölcük Otogarı', 'Kocaeli'),
(311, 42, 'İzmit Otogarı', 'Kocaeli'),
(312, 42, 'Kandıra Otogarı', 'Kocaeli'),
(313, 42, 'Kartepe Otogarı', 'Kocaeli'),
(314, 43, 'Konya Otogarı', 'Konya'),
(315, 43, 'Akşehir Otogarı', 'Konya'),
(316, 43, 'Beyşehir Otogarı', 'Konya'),
(317, 43, 'Bozkır Otogarı', 'Konya'),
(318, 43, 'Cihanbeyli Otogarı', 'Konya'),
(319, 43, 'Çumra Otogarı', 'Konya'),
(320, 43, 'Ereğli (Konya) Otogarı', 'Konya'),
(321, 43, 'Hadim Otogarı', 'Konya'),
(322, 43, 'Ilgın Otogarı', 'Konya'),
(323, 43, 'Kadınhanı Otogarı', 'Konya'),
(324, 43, 'Karapınar Otogarı', 'Konya'),
(325, 43, 'Karatay Otogarı', 'Konya'),
(326, 43, 'Kulu Otogarı', 'Konya'),
(327, 43, 'Sarayönü Otogarı', 'Konya'),
(328, 43, 'Seydişehir Otogarı', 'Konya'),
(329, 43, 'Yunak Otogarı', 'Konya'),
(330, 44, 'Kütahya Otogarı', 'Kütahya'),
(331, 44, 'Altıntaş Otogarı', 'Kütahya'),
(332, 44, 'Domaniç Otogarı', 'Kütahya'),
(333, 44, 'Emet Otogarı', 'Kütahya'),
(334, 44, 'Gediz Otogarı', 'Kütahya'),
(335, 44, 'Simav Otogarı', 'Kütahya'),
(336, 44, 'Tavşanlı Otogarı', 'Kütahya'),
(337, 45, 'Malatya (Maşti) Otogarı', 'Malatya'),
(338, 45, 'Akçadağ Otogarı', 'Malatya'),
(339, 45, 'Arapgir Otogarı', 'Malatya'),
(340, 45, 'Darende Otogarı', 'Malatya'),
(341, 45, 'Doğanşehir Otogarı', 'Malatya'),
(342, 45, 'Hekimhan Otogarı', 'Malatya'),
(343, 45, 'Pütürge Otogarı', 'Malatya'),
(344, 46, 'Manisa Otogarı', 'Manisa'),
(345, 46, 'Akhisar Otogarı', 'Manisa'),
(346, 46, 'Alaşehir Otogarı', 'Manisa'),
(347, 46, 'Demirci Otogarı', 'Manisa'),
(348, 46, 'Gördes Otogarı', 'Manisa'),
(349, 46, 'Kırkağaç Otogarı', 'Manisa'),
(350, 46, 'Kula Otogarı', 'Manisa'),
(351, 46, 'Salihli Otogarı', 'Manisa'),
(352, 46, 'Sarıgöl Otogarı', 'Manisa'),
(353, 46, 'Saruhanlı Otogarı', 'Manisa'),
(354, 46, 'Selendi Otogarı', 'Manisa'),
(355, 46, 'Soma Otogarı', 'Manisa'),
(356, 46, 'Turgutlu Otogarı', 'Manisa'),
(357, 47, 'Maraş (Kahramanmaraş) Otogarı', 'Kahramanmaraş'),
(358, 47, 'Afşin Otogarı', 'Kahramanmaraş'),
(359, 47, 'Andırın Otogarı', 'Kahramanmaraş'),
(360, 47, 'Elbistan Otogarı', 'Kahramanmaraş'),
(361, 47, 'Göksun Otogarı', 'Kahramanmaraş'),
(362, 47, 'Pazarcık Otogarı', 'Kahramanmaraş'),
(363, 47, 'Türkoğlu Otogarı', 'Kahramanmaraş'),
(364, 48, 'Mardin Otogarı', 'Mardin'),
(365, 48, 'Dargeçit Otogarı', 'Mardin'),
(366, 48, 'Midyat Otogarı', 'Mardin'),
(367, 48, 'Ömerli Otogarı', 'Mardin'),
(368, 49, 'Muğla Otogarı', 'Muğla'),
(369, 49, 'Bodrum Yeni (Torba) Otogarı', 'Muğla'),
(370, 49, 'Datça Otogarı', 'Muğla'),
(371, 49, 'Fethiye Otogarı', 'Muğla'),
(372, 49, 'Gündoğan Otogarı', 'Muğla'),
(373, 49, 'Köyceğiz Otogarı', 'Muğla'),
(374, 49, 'Marmaris Otogarı', 'Muğla'),
(375, 49, 'Milas Otogarı', 'Muğla'),
(376, 49, 'Ortaca Otobüs Terminali', 'Muğla'),
(377, 49, 'Ortaca Bus Station (Otogar)', 'Muğla'),
(378, 49, 'Yatağan Otogarı', 'Muğla'),
(379, 50, 'Muş Otogarı', 'Muş'),
(380, 50, 'Bulanık Otogarı', 'Muş'),
(381, 50, 'Malazgirt Otogarı', 'Muş'),
(382, 51, 'Nevşehir Otogarı', 'Nevşehir'),
(383, 51, 'Avanos Otogarı', 'Nevşehir'),
(384, 51, 'Derinkuyu Otogarı', 'Nevşehir'),
(385, 51, 'Göreme Otogarı', 'Nevşehir'),
(386, 51, 'Gülşehir Otogarı', 'Nevşehir'),
(387, 51, 'Hacıbektaş Otogarı', 'Nevşehir'),
(388, 51, 'Ürgüp Otogarı', 'Nevşehir'),
(389, 52, 'Niğde Otogarı', 'Niğde'),
(390, 52, 'Bor Otogarı', 'Niğde'),
(391, 52, 'Ulukışla Otogarı', 'Niğde'),
(392, 52, 'Altunhisar Otogarı', 'Niğde'),
(393, 53, 'Ordu Otogarı', 'Ordu'),
(394, 53, 'Akkuş Otogarı', 'Ordu'),
(395, 53, 'Çatalpınar Otogarı', 'Ordu'),
(396, 53, 'Fatsa Otogarı', 'Ordu'),
(397, 53, 'Gürgentepe Otogarı', 'Ordu'),
(398, 53, 'Kabataş Otogarı', 'Ordu'),
(399, 53, 'Korgan Otogarı', 'Ordu'),
(400, 53, 'Kumru Otogarı', 'Ordu'),
(401, 53, 'Mesudiye Otogarı', 'Ordu'),
(402, 53, 'Ulubey Otogarı', 'Ordu'),
(403, 53, 'Ünye Otogarı', 'Ordu'),
(404, 54, 'Rize Otogarı', 'Rize'),
(405, 54, 'Ardeşen Otogarı', 'Rize'),
(406, 54, 'Çayeli Otogarı', 'Rize'),
(407, 54, 'Fındıklı Otogarı', 'Rize'),
(408, 55, 'Adapazarı Otogarı', 'Sakarya'),
(409, 55, 'Akyazı Otogarı', 'Sakarya'),
(410, 55, 'Ferizli Otogarı', 'Sakarya'),
(411, 55, 'Hendek Otogarı', 'Sakarya'),
(412, 55, 'Karasu Otogarı', 'Sakarya'),
(413, 55, 'Sapanca Otogarı', 'Sakarya'),
(414, 56, 'Samsun Otogarı', 'Samsun'),
(415, 56, 'Bafra Otogarı', 'Samsun'),
(416, 56, 'Çarşamba Otogarı', 'Samsun'),
(417, 56, 'Havza Otogarı', 'Samsun'),
(418, 56, 'Terme Otogarı', 'Samsun'),
(419, 56, 'Vezirköprü Otogarı', 'Samsun'),
(420, 56, 'Yakakent Otogarı', 'Samsun'),
(421, 57, 'Siirt Otogarı', 'Siirt'),
(422, 57, 'Baykan Otogarı', 'Siirt'),
(423, 57, 'Kurtalan Otogarı', 'Siirt'),
(424, 58, 'Sinop Otogarı', 'Sinop'),
(425, 58, 'Ayancık Otogarı', 'Sinop'),
(426, 58, 'Boyabat Otogarı', 'Sinop'),
(427, 58, 'Dikmen Otogarı', 'Sinop'),
(428, 58, 'Durağan Otogarı', 'Sinop'),
(429, 58, 'Gerze Otogarı', 'Sinop'),
(430, 59, 'Sivas Otogarı', 'Sivas'),
(431, 59, 'Divriği Otogarı', 'Sivas'),
(432, 59, 'Gürün Otogarı', 'Sivas'),
(433, 59, 'Kangal Otogarı', 'Sivas'),
(434, 59, 'Suşehri Otogarı', 'Sivas'),
(435, 59, 'Şarkışla Otogarı', 'Sivas'),
(436, 60, 'Tekirdağ Otogarı', 'Tekirdağ'),
(437, 60, 'Çerkezköy Otogarı', 'Tekirdağ'),
(438, 60, 'Çorlu Otogarı', 'Tekirdağ'),
(439, 60, 'Hayrabolu Otogarı', 'Tekirdağ'),
(440, 60, 'Malkara Otogarı', 'Tekirdağ'),
(441, 60, 'Saray Otogarı', 'Tekirdağ'),
(442, 61, 'Tokat Otogarı', 'Tokat'),
(443, 61, 'Almus Otogarı', 'Tokat'),
(444, 61, 'Erbaa Otogarı', 'Tokat'),
(445, 61, 'Niksar Otogarı', 'Tokat'),
(446, 61, 'Reşadiye Otogarı', 'Tokat'),
(447, 61, 'Turhal Otogarı', 'Tokat'),
(448, 61, 'Zile Otogarı', 'Tokat'),
(449, 62, 'Trabzon Otogarı', 'Trabzon'),
(450, 62, 'Akçaabat Otogarı', 'Trabzon'),
(451, 62, 'Araklı Otogarı', 'Trabzon'),
(452, 62, 'Beşikdüzü Otogarı', 'Trabzon'),
(453, 62, 'Sürmene Otogarı', 'Trabzon'),
(454, 62, 'Vakfıkebir Otogarı', 'Trabzon'),
(455, 63, 'Tunceli Otogarı', 'Tunceli'),
(456, 64, 'Şanlıurfa Otogarı', 'Şanlıurfa'),
(457, 64, 'Siverek Otogarı', 'Şanlıurfa'),
(458, 64, 'Suruç Otogarı', 'Şanlıurfa'),
(459, 64, 'Viranşehir Otogarı', 'Şanlıurfa'),
(460, 65, 'Uşak Otogarı', 'Uşak'),
(461, 65, 'Banaz Otogarı', 'Uşak'),
(462, 65, 'Eşme Otogarı', 'Uşak'),
(463, 65, 'Ulubey Otogarı', 'Uşak'),
(464, 66, 'Van Otogarı', 'Van'),
(465, 66, 'Çaldıran Otogarı', 'Van'),
(466, 66, 'Erciş Otogarı', 'Van'),
(467, 66, 'Muradiye Otogarı', 'Van'),
(468, 67, 'Yozgat Otogarı', 'Yozgat'),
(469, 67, 'Boğazlıyan Otogarı', 'Yozgat'),
(470, 67, 'Çayıralan Otogarı', 'Yozgat'),
(471, 67, 'Sarıkaya Otogarı', 'Yozgat'),
(472, 67, 'Sorgun Otogarı', 'Yozgat'),
(473, 67, 'Şefaatli Otogarı', 'Yozgat'),
(474, 68, 'Zonguldak Otogarı', 'Zonguldak'),
(475, 68, 'Çaycuma Otogarı', 'Zonguldak'),
(476, 68, 'Devrek Otogarı', 'Zonguldak'),
(477, 68, 'Kozlu Otogarı', 'Zonguldak'),
(478, 69, 'Aksaray Otogarı', 'Aksaray'),
(479, 69, 'Ortaköy Otogarı', 'Aksaray'),
(480, 69, 'Sultanhanı Otogarı', 'Aksaray'),
(481, 70, 'Bayburt Otogarı', 'Bayburt'),
(482, 71, 'Karaman Otogarı', 'Karaman'),
(483, 71, 'Ermenek Otogarı', 'Karaman'),
(484, 71, 'Sarıveliler Otogarı', 'Karaman'),
(485, 72, 'Kırıkkale Otogarı', 'Kırıkkale'),
(486, 72, 'Delice Otogarı', 'Kırıkkale'),
(487, 73, 'Batman Otogarı', 'Batman'),
(488, 74, 'Bartın Otogarı', 'Bartın'),
(489, 74, 'Kozcağız Otogarı', 'Bartın'),
(490, 75, 'Ardahan Otogarı', 'Ardahan'),
(491, 75, 'Göle Otogarı', 'Ardahan'),
(492, 75, 'Hanak Otogarı', 'Ardahan'),
(493, 75, 'Posof Otogarı', 'Ardahan'),
(494, 76, 'Iğdır Otogarı', 'Iğdır'),
(495, 77, 'Yalova Otogarı', 'Yalova'),
(496, 77, 'Çınarcık Otogarı', 'Yalova'),
(497, 78, 'Karabük Otogarı', 'Karabük'),
(498, 78, 'Safranbolu Otogarı', 'Karabük'),
(499, 78, 'Yenice Otogarı', 'Karabük'),
(500, 79, 'Kilis Otogarı', 'Kilis'),
(501, 80, 'Osmaniye Otogarı', 'Osmaniye'),
(502, 80, 'Düziçi Otogarı', 'Osmaniye'),
(503, 80, 'Kadirli Otogarı', 'Osmaniye'),
(504, 81, 'Düzce Otogarı', 'Düzce'),
(505, 81, 'Akçakoca Otogarı', 'Düzce');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `seferler`
--

CREATE TABLE `seferler` (
  `id` int(11) NOT NULL,
  `firma_id` int(11) NOT NULL,
  `kalkis_otogar_id` int(11) NOT NULL,
  `varis_otogar_id` int(11) NOT NULL,
  `ucret` decimal(10,2) NOT NULL,
  `kalkis_saati` datetime NOT NULL,
  `varis_saati` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sehirler`
--

CREATE TABLE `sehirler` (
  `id` int(11) NOT NULL,
  `sehir_adi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `sehirler`
--

INSERT INTO `sehirler` (`id`, `sehir_adi`) VALUES
(1, 'Adana'),
(2, 'İstanbul'),
(3, 'Adıyaman'),
(4, 'Afyon'),
(5, 'Ağrı'),
(6, 'Amasya'),
(7, 'Ankara'),
(8, 'Antalya'),
(9, 'Artvin'),
(10, 'Aydın'),
(11, 'Balıkesir'),
(12, 'Bilecik'),
(13, 'Bingöl'),
(14, 'Bitlis'),
(15, 'Bolu'),
(16, 'Burdur'),
(17, 'Bursa'),
(18, 'Çanakkale'),
(19, 'Çankırı'),
(20, 'Çorum'),
(21, 'Denizli'),
(22, 'Diyarbakır'),
(23, 'Edirne'),
(24, 'Elazığ'),
(25, 'Erzincan'),
(26, 'Erzurum'),
(27, 'Eskişehir'),
(28, 'Gaziantep'),
(29, 'Giresun'),
(30, 'Gümüşhane'),
(31, 'Hakkari'),
(32, 'Hatay'),
(33, 'Isparta'),
(34, 'Mersin'),
(35, 'İzmir'),
(36, 'Kars'),
(37, 'Kastamonu'),
(38, 'Kayseri'),
(39, 'Kırklareli'),
(40, 'Kırşehir'),
(41, 'Kocaeli'),
(42, 'Konya'),
(43, 'Kütahya'),
(44, 'Malatya'),
(45, 'Manisa'),
(46, 'Kahramanmaraş'),
(47, 'Mardin'),
(48, 'Muğla'),
(49, 'Muş'),
(50, 'Nevşehir'),
(51, 'Niğde'),
(52, 'Ordu'),
(53, 'Rize'),
(54, 'Sakarya'),
(55, 'Samsun'),
(56, 'Siirt'),
(57, 'Sinop'),
(58, 'Sivas'),
(59, 'Tekirdağ'),
(60, 'Tokat'),
(61, 'Trabzon'),
(62, 'Tunceli'),
(63, 'Şanlıurfa'),
(64, 'Uşak'),
(65, 'Van'),
(66, 'Yozgat'),
(67, 'Zonguldak'),
(68, 'Aksaray'),
(69, 'Bayburt'),
(70, 'Karaman'),
(71, 'Kırıkkale'),
(72, 'Batman'),
(73, 'Bartın'),
(74, 'Ardahan'),
(75, 'Iğdır'),
(76, 'Yalova'),
(77, 'Karabük'),
(78, 'Kilis'),
(79, 'Osmaniye'),
(80, 'Düzce');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `aktif_oturum`
--
ALTER TABLE `aktif_oturum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `bilet_kaydi`
--
ALTER TABLE `bilet_kaydi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pnr_kodu` (`pnr_kodu`),
  ADD KEY `kullanici_id` (`kullanici_id`),
  ADD KEY `firma_id` (`firma_id`),
  ADD KEY `sefer_id` (`sefer_id`);

--
-- Tablo için indeksler `firmalar`
--
ALTER TABLE `firmalar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `firma_seferler`
--
ALTER TABLE `firma_seferler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firma_id` (`firma_id`),
  ADD KEY `kalkis_sehir` (`kalkis_sehir`),
  ADD KEY `kalkis_otogar` (`kalkis_otogar`),
  ADD KEY `varis_sehir` (`varis_sehir`),
  ADD KEY `varis_otogar` (`varis_otogar`);

--
-- Tablo için indeksler `kartlar`
--
ALTER TABLE `kartlar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `kayitli_yolcular`
--
ALTER TABLE `kayitli_yolcular`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `otogarlar`
--
ALTER TABLE `otogarlar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `seferler`
--
ALTER TABLE `seferler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kalkis_otogar_id` (`kalkis_otogar_id`),
  ADD KEY `varis_otogar_id` (`varis_otogar_id`);

--
-- Tablo için indeksler `sehirler`
--
ALTER TABLE `sehirler`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `aktif_oturum`
--
ALTER TABLE `aktif_oturum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `bilet_kaydi`
--
ALTER TABLE `bilet_kaydi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `firmalar`
--
ALTER TABLE `firmalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `firma_seferler`
--
ALTER TABLE `firma_seferler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `kartlar`
--
ALTER TABLE `kartlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `kayitli_yolcular`
--
ALTER TABLE `kayitli_yolcular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `otogarlar`
--
ALTER TABLE `otogarlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=506;

--
-- Tablo için AUTO_INCREMENT değeri `seferler`
--
ALTER TABLE `seferler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `sehirler`
--
ALTER TABLE `sehirler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `aktif_oturum`
--
ALTER TABLE `aktif_oturum`
  ADD CONSTRAINT `aktif_oturum_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `bilet_kaydi`
--
ALTER TABLE `bilet_kaydi`
  ADD CONSTRAINT `bilet_kaydi_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kayitli_yolcular` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bilet_kaydi_ibfk_2` FOREIGN KEY (`firma_id`) REFERENCES `firmalar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bilet_kaydi_ibfk_3` FOREIGN KEY (`sefer_id`) REFERENCES `firma_seferler` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `firma_seferler`
--
ALTER TABLE `firma_seferler`
  ADD CONSTRAINT `firma_seferler_ibfk_1` FOREIGN KEY (`firma_id`) REFERENCES `firmalar` (`id`),
  ADD CONSTRAINT `firma_seferler_ibfk_2` FOREIGN KEY (`kalkis_sehir`) REFERENCES `sehirler` (`id`),
  ADD CONSTRAINT `firma_seferler_ibfk_3` FOREIGN KEY (`kalkis_otogar`) REFERENCES `otogarlar` (`id`),
  ADD CONSTRAINT `firma_seferler_ibfk_4` FOREIGN KEY (`varis_sehir`) REFERENCES `sehirler` (`id`),
  ADD CONSTRAINT `firma_seferler_ibfk_5` FOREIGN KEY (`varis_otogar`) REFERENCES `otogarlar` (`id`);

--
-- Tablo kısıtlamaları `kartlar`
--
ALTER TABLE `kartlar`
  ADD CONSTRAINT `kartlar_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `kayitli_yolcular`
--
ALTER TABLE `kayitli_yolcular`
  ADD CONSTRAINT `kayitli_yolcular_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `seferler`
--
ALTER TABLE `seferler`
  ADD CONSTRAINT `seferler_ibfk_1` FOREIGN KEY (`kalkis_otogar_id`) REFERENCES `otogarlar` (`id`),
  ADD CONSTRAINT `seferler_ibfk_2` FOREIGN KEY (`varis_otogar_id`) REFERENCES `otogarlar` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
