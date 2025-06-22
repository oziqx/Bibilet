<?php
header('Content-Type: application/json');
try {
    require __DIR__ . '/../db/baglanti.php';
    if (!$conn) {
        throw new Exception("Veritabanı bağlantısı başarısız.");
    }
    if (isset($_GET['sehir_id'])) {
        $sehir_id = $_GET['sehir_id'];
        $stmt = $conn->prepare("
            SELECT o.id, o.sehir_id, o.otogar_adi, s.sehir_adi 
            FROM otogarlar o 
            JOIN sehirler s ON o.sehir_id = s.id 
            WHERE o.sehir_id = :sehir_id
        ");
        $stmt->execute(['sehir_id' => $sehir_id]);
        $otogarlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($otogarlar);
    } else {
        echo json_encode([]);
        error_log("sehir_id parametresi eksik.");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Veri çekme hatası: ' . $e->getMessage()]);
    error_log("Hata get_otogarlar.php: " . $e->getMessage());
}
?>