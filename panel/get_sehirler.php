<?php
header('Content-Type: application/json');
try {
    require __DIR__ . '/../db/baglanti.php';
    if (!$conn) {
        throw new Exception("Veritabanı bağlantısı başarısız.");
    }
    $stmt = $conn->prepare("SELECT id, sehir_adi FROM sehirler");
    $stmt->execute();
    $sehirler = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($sehirler);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Veri çekme hatası: ' . $e->getMessage()]);
    error_log("Hata get_sehirler.php: " . $e->getMessage());
}
?>