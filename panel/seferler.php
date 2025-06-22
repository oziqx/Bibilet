<?php
session_start();
require '../db/baglanti.php'; // Bir üst dizine çıkıp db klasörüne eriş

try {
    $kalkis_id = isset($_GET['kalkis_id']) ? (int)$_GET['kalkis_id'] : null;
    $varis_id = isset($_GET['varis_id']) ? (int)$_GET['varis_id'] : null;
    $tarih = isset($_GET['tarih']) ? $_GET['tarih'] : null;

    $sql = "SELECT fs.*, s1.sehir_adi AS kalkis_sehir_adi, s2.sehir_adi AS varis_sehir_adi, 
            o1.otogar_adi AS kalkis_otogar_adi, o2.otogar_adi AS varis_otogar_adi,
            f.firma_adi AS firma_adi
            FROM firma_seferler fs
            JOIN sehirler s1 ON fs.kalkis_sehir = s1.id
            JOIN sehirler s2 ON fs.varis_sehir = s2.id
            JOIN otogarlar o1 ON fs.kalkis_otogar = o1.id
            JOIN otogarlar o2 ON fs.varis_otogar = o2.id
            LEFT JOIN firmalar f ON fs.firma_id = f.id
            WHERE (:kalkis_id IS NULL OR fs.kalkis_otogar = :kalkis_id)
            AND (:varis_id IS NULL OR fs.varis_otogar = :varis_id)
            AND (:tarih IS NULL OR fs.tarih = :tarih)
            ORDER BY fs.saat ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'kalkis_id' => $kalkis_id,
        'varis_id' => $varis_id,
        'tarih' => $tarih
    ]);
    $seferler = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>alert('Veritabanı hatası oluştu. Lütfen daha sonra tekrar deneyin.');</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Seferler - Bi Bilet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/seferler.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script>
        // PHP oturum durumunu JavaScript'e aktar
        const isLoggedIn = <?php echo isset($_SESSION['user_type']) ? 'true' : 'false'; ?>;
    </script>
</head>
<body>
    <header>
        <h1>Bi Bilet</h1>
        <nav>
            <?php if (isset($_SESSION['user_type'])): ?>
                <?php if ($_SESSION['user_type'] === 'musteri'): ?>
                    <a href="kullanici-paneli.php"><i class="fas fa-user"></i> Hesabım</a>
                <?php elseif ($_SESSION['user_type'] === 'firma'): ?>
                    <a href="firma-panel.php"><i class="fas fa-user"></i> Hesabım</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="../uye/giris-yap.php"><i class="fas fa-sign-in-alt"></i> Giriş Yap</a>
                <a href="../uye/kayit-ol.php"><i class="fas fa-user-plus"></i> Üye Ol</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <h2>Sefer Listesi</h2>
        <div class="loading" id="loading">Seferler yükleniyor...</div>
        <?php if (empty($seferler)): ?>
            <p class="no-sefer">Bu kriterlere uygun sefer bulunamadı.</p>
        <?php else: ?>
            <div class="sefer-container" id="seferContainer">
                <?php foreach ($seferler as $sefer): ?>
                    <div class="sefer-card" data-sefer-id="<?php echo $sefer['id']; ?>">
                        <div class="sefer-header">
                            <div class="firma-box">
                                <span class="firma-adi"><?php echo htmlspecialchars($sefer['firma_adi'] ?? 'Bilinmeyen Firma'); ?></span>
                            </div>
                            <button class="koltuk-sec-btn" data-sefer-id="<?php echo $sefer['id']; ?>">Koltuk Seç</button>
                        </div>
                        <div class="sefer-details">
                            <div class="detail-item">
                                <span class="label">Kalkış Şehir:</span>
                                <span class="value"><?php echo htmlspecialchars($sefer['kalkis_sehir_adi']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Kalkış Otogar:</span>
                                <span class="value"><?php echo htmlspecialchars($sefer['kalkis_otogar_adi']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Varış Şehir:</span>
                                <span class="value"><?php echo htmlspecialchars($sefer['varis_sehir_adi']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Varış Otogar:</span>
                                <span class="value"><?php echo htmlspecialchars($sefer['varis_otogar_adi']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Tarih:</span>
                                <span class="value"><?php echo htmlspecialchars($sefer['tarih']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Saat:</span>
                                <span class="value"><?php echo htmlspecialchars($sefer['saat']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Fiyat:</span>
                                <span class="value"><?php echo htmlspecialchars($sefer['fiyat']); ?> TL</span>
                            </div>
                        </div>
                        <div class="sefer-koltuk-panel" id="koltuk-panel-<?php echo $sefer['id']; ?>" style="display: none;">
                            <div class="otobus-yapi">
                                <div class="koltuk-alani">
                                    <!-- Koltuklar JS ile doldurulacak -->
                                </div>
                                <div class="koltuk-secenekleri">
                                    <button class="kapat-btn">Kapat</button>
                                    <div class="cinsiyet-secim">
                                        <label><input type="radio" name="cinsiyet-<?php echo $sefer['id']; ?>" value="erkek"> Erkek</label>
                                        <label><input type="radio" name="cinsiyet-<?php echo $sefer['id']; ?>" value="kadin"> Kadın</label>
                                    </div>
                                    <button class="onayla-btn" disabled>Devam Et</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script src="../js/seferler.js"></script>
</body>
</html>