<?php
session_start();
require '../db/baglanti.php';

header('Content-Type: application/json');

if (!isset($_SESSION['kullanici_id'])) {
    echo json_encode(['success' => false, 'message' => 'Oturum geçersiz, lütfen giriş yapın!']);
    exit;
}

$sefer_id = filter_input(INPUT_POST, 'sefer_id', FILTER_VALIDATE_INT);
$koltuk_no = filter_input(INPUT_POST, 'koltuk_no', FILTER_VALIDATE_INT);
$cinsiyet = filter_input(INPUT_POST, 'cinsiyet', FILTER_SANITIZE_STRING);
$firma_id = filter_input(INPUT_POST, 'firma_id', FILTER_VALIDATE_INT);
$tarih = filter_input(INPUT_POST, 'tarih', FILTER_SANITIZE_STRING);
$saat = filter_input(INPUT_POST, 'saat', FILTER_SANITIZE_STRING);
$odenen_tutar = filter_input(INPUT_POST, 'odenen_tutar', FILTER_VALIDATE_FLOAT);
$kullanici_id = $_SESSION['kullanici_id'];
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$ad = filter_input(INPUT_POST, 'ad', FILTER_SANITIZE_STRING);
$soyad = filter_input(INPUT_POST, 'soyad', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$telefon = filter_input(INPUT_POST, 'telefon', FILTER_SANITIZE_STRING);
$tc_kimlik_no = filter_input(INPUT_POST, 'tc_kimlik_no', FILTER_SANITIZE_STRING);
$dogum_tarihi = filter_input(INPUT_POST, 'dogum_tarihi', FILTER_SANITIZE_STRING);
$card_id = filter_input(INPUT_POST, 'card_id', FILTER_VALIDATE_INT);
$kart_adi = filter_input(INPUT_POST, 'kart_adi', FILTER_SANITIZE_STRING);
$kart_numarasi = filter_input(INPUT_POST, 'kart_numarasi', FILTER_SANITIZE_STRING);
$son_kullanma = filter_input(INPUT_POST, 'son_kullanma', FILTER_SANITIZE_STRING);
$cvc2 = filter_input(INPUT_POST, 'cvc2', FILTER_SANITIZE_STRING);

if (!$sefer_id || !$koltuk_no || !$cinsiyet || !$firma_id || !$tarih || !$saat || !$odenen_tutar || !$kullanici_id ||
    (!$user_id && (!$ad || !$soyad)) || (!$card_id && (!$kart_adi || !$kart_numarasi || !$son_kullanma || !$cvc2))) {
    echo json_encode(['success' => false, 'message' => 'Eksik veri!']);
    exit;
}

// Yeni kullanıcıyı kaydet
if ($ad && $soyad && !$user_id) {
    $tc_kimlik_no_enc = encryptData($tc_kimlik_no);
    $stmt = $conn->prepare("INSERT INTO kayitli_yolcular (kullanici_id, ad, soyad, email, telefon, tc_kimlik_no, dogum_tarihi, cinsiyet) 
                           VALUES (:kullanici_id, :ad, :soyad, :email, :telefon, :tc_kimlik_no, :dogum_tarihi, :cinsiyet)");
    $stmt->execute([
        'kullanici_id' => $kullanici_id,
        'ad' => encryptData($ad),
        'soyad' => encryptData($soyad),
        'email' => encryptData($email),
        'telefon' => encryptData($telefon),
        'tc_kimlik_no' => $tc_kimlik_no_enc,
        'dogum_tarihi' => encryptData($dogum_tarihi),
        'cinsiyet' => encryptData($cinsiyet)
    ]);
    $user_id = $conn->lastInsertId();
}

// Yeni kartı kaydet
if ($kart_adi && $kart_numarasi && $son_kullanma && $cvc2 && !$card_id) {
    $kart_numarasi = preg_replace('/\s+/', '', $kart_numarasi);
    $son_kullanma_full = '20' . substr($son_kullanma, -2);
    $stmt = $conn->prepare("INSERT INTO kartlar (kullanici_id, kart_adi, kart_numarasi, son_kullanma_tarihi, cvc2) 
                           VALUES (:kullanici_id, :kart_adi, :kart_numarasi, :son_kullanma_tarihi, :cvc2)");
    $stmt->execute([
        'kullanici_id' => $kullanici_id,
        'kart_adi' => encryptData($kart_adi),
        'kart_numarasi' => encryptData($kart_numarasi),
        'son_kullanma_tarihi' => encryptData($son_kullanma_full),
        'cvc2' => encryptData($cvc2)
    ]);
    $card_id = $conn->lastInsertId();
}

// Bilet kaydını bilet_kaydi tablosuna ekle
$pnr_kodu = substr(strtoupper(uniqid()), 0, 6) . rand(1000, 9999);
$stmt = $conn->prepare("SELECT COUNT(*) FROM bilet_kaydi WHERE pnr_kodu = :pnr_kodu");
$stmt->execute(['pnr_kodu' => $pnr_kodu]);
while ($stmt->fetchColumn() > 0) {
    $pnr_kodu = substr(strtoupper(uniqid()), 0, 6) . rand(1000, 9999);
}

try {
    $stmt = $conn->prepare("INSERT INTO bilet_kaydi (kullanici_id, firma_id, sefer_id, tarih, saat, koltuk_no, cinsiyet, pnr_kodu, odenen_tutar, odeme_durumu) 
                           VALUES (:kullanici_id, :firma_id, :sefer_id, :tarih, :saat, :koltuk_no, :cinsiyet, :pnr_kodu, :odenen_tutar, 1)");
    $stmt->execute([
        'kullanici_id' => $user_id ?: $kullanici_id,
        'firma_id' => $firma_id,
        'sefer_id' => $sefer_id,
        'tarih' => $tarih,
        'saat' => $saat,
        'koltuk_no' => $koltuk_no,
        'cinsiyet' => $cinsiyet,
        'pnr_kodu' => $pnr_kodu,
        'odenen_tutar' => $odenen_tutar
    ]);

    echo json_encode(['success' => true, 'message' => 'Ödeme başarılı! PNR Kodunuz: ' . $pnr_kodu, 'redirect' => '../panel/kullanici-paneli.php?sayfa=seyahatlerim']);
} catch (PDOException $e) {
    error_log("Bilet kaydı hatası: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Ödeme başarısız: ' . $e->getMessage()]);
}