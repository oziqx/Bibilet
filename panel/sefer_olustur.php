<?php
session_start();
require '../db/baglanti.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['kullanici_id'])) {
    $firma_id = $_SESSION['kullanici_id'];
    $kalkis_sehir = $_POST['kalkis_sehir'];
    $kalkis_otogar = $_POST['kalkis_otogar'];
    $varis_sehir = $_POST['varis_sehir'];
    $varis_otogar = $_POST['varis_otogar'];
    $saat = $_POST['saat'];
    $tarih = $_POST['tarih'];
    $fiyat = $_POST['fiyat'];

    try {
        $sql = "INSERT INTO firma_seferler (firma_id, kalkis_sehir, kalkis_otogar, varis_sehir, varis_otogar, tarih, saat, fiyat) 
                VALUES (:firma_id, :kalkis_sehir, :kalkis_otogar, :varis_sehir, :varis_otogar, :tarih, :saat, :fiyat)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'firma_id' => $firma_id,
            'kalkis_sehir' => $kalkis_sehir,
            'kalkis_otogar' => $kalkis_otogar,
            'varis_sehir' => $varis_sehir,
            'varis_otogar' => $varis_otogar,
            'tarih' => $tarih,
            'saat' => $saat,
            'fiyat' => $fiyat
        ]);
        echo "Sefer başarıyla oluşturuldu!";
    } catch (PDOException $e) {
        error_log("Sefer oluşturma hatası: " . $e->getMessage());
        echo "Sefer oluşturma başarısız: " . $e->getMessage();
    }
} else {
    echo "Yetkisiz erişim veya eksik veri!";
}
?>