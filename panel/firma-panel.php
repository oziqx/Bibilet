<?php
session_start();
if (!isset($_SESSION['kullanici_id']) || $_SESSION['user_type'] != 'firma') {
    header("Location: ../index.php");
    exit();
}

$firma_adi = "Örnek Firma";
if (isset($_SESSION['kullanici_id'])) {
    require_once __DIR__ . '/../db/baglanti.php';
    $stmt = $conn->prepare("SELECT firma_adi FROM firmalar WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['kullanici_id']]);
    $firma = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($firma && !empty($firma['firma_adi'])) {
        $firma_adi = htmlspecialchars($firma['firma_adi']);
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Firma Paneli</title>
    <link rel="stylesheet" href="../css/firma-panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="panel-container">
        <aside class="sidebar">
            <ul>
                <li><a href="#anasayfa"><i class="fas fa-home"></i> Anasayfa</a></li>
                <li><a href="#sefer-olustur"><i class="fas fa-plus"></i> Sefer Oluştur</a></li>
                <li><a href="#seferler"><i class="fas fa-list"></i> Seferler</a></li>
                <li><a href="#bilgileri-guncelle"><i class="fas fa-edit"></i> Bilgileri Güncelle</a></li>
                <li><a href="#kullanici-bilgisi"><i class="fas fa-users"></i> Kullanıcı Bilgisi</a></li>
                <li><a href="../uye/cikis-yap.php"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a></li>
            </ul>
        </aside>
        <main class="main-content">
            <h2>Hoş geldiniz, <?php echo $firma_adi; ?>!</h2>
            <div id="content-area"></div>
        </main>
    </div>
    <script src="../js/firma-panel.js"></script>
</body>
</html>