<?php
session_start();
require '../db/baglanti.php';

header('Content-Type: application/json');

// Giriş kontrolü
if (!isset($_SESSION['kullanici_id']) || !isset($_SESSION['user_type'])) {
    echo json_encode(['success' => false, 'message' => 'Lütfen giriş yapın!']);
    exit;
}

$koltuk_no = filter_input(INPUT_POST, 'koltuk_no', FILTER_VALIDATE_INT);
$cinsiyet = filter_input(INPUT_POST, 'cinsiyet', FILTER_SANITIZE_STRING);
$sefer_id = filter_input(INPUT_POST, 'sefer_id', FILTER_VALIDATE_INT);

if (!$koltuk_no || !$cinsiyet || !$sefer_id) {
    echo json_encode(['success' => false, 'message' => 'Eksik veya geçersiz veri!']);
    exit;
}

// Sefer bilgilerini al
$stmt = $conn->prepare("SELECT firma_id, tarih, saat, fiyat FROM firma_seferler WHERE id = :sefer_id");
$stmt->execute(['sefer_id' => $sefer_id]);
$sefer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sefer) {
    echo json_encode(['success' => false, 'message' => 'Sefer bulunamadı!']);
    exit;
}

$firma_id = $sefer['firma_id'];
$tarih = $sefer['tarih'];
$saat = $sefer['saat'];
$odenen_tutar = $sefer['fiyat']; // Fiyatı seferden al
$kullanici_id = $_SESSION['kullanici_id'];

// Benzersiz PNR kodu üretimi
$pnr_kodu = substr(strtoupper(uniqid()), 0, 6) . rand(1000, 9999); // Örnek: ABC1234567
$pnr_check = $conn->prepare("SELECT COUNT(*) FROM bilet_kaydi WHERE pnr_kodu = :pnr_kodu");
$pnr_check->execute(['pnr_kodu' => $pnr_kodu]);
while ($pnr_check->fetchColumn() > 0) {
    $pnr_kodu = substr(strtoupper(uniqid()), 0, 6) . rand(1000, 9999);
}

try {
    $sql = "INSERT INTO bilet_kaydi (kullanici_id, firma_id, sefer_id, tarih, saat, koltuk_no, cinsiyet, pnr_kodu, odenen_tutar, odeme_durumu) 
            VALUES (:kullanici_id, :firma_id, :sefer_id, :tarih, :saat, :koltuk_no, :cinsiyet, :pnr_kodu, :odenen_tutar, 1)"; // Ödeme onaylı varsayımı
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        'kullanici_id' => $kullanici_id,
        'firma_id' => $firma_id,
        'sefer_id' => $sefer_id,
        'tarih' => $tarih,
        'saat' => $saat,
        'koltuk_no' => $koltuk_no,
        'cinsiyet' => $cinsiyet,
        'pnr_kodu' => $pnr_kodu,
        'odenen_tutar' => $odenen_tutar
    ]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Bilet kaydedildi!', 'pnr_kodu' => $pnr_kodu]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Kayıt başarısız!']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Hata: ' . $e->getMessage()]);
}
?>