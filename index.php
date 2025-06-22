<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db/baglanti.php';

try {
    $stmt = $conn->prepare("
        SELECT o.id, o.otogar_adi, s.sehir_adi
        FROM otogarlar o
        JOIN sehirler s ON o.sehir_id = s.id
        ORDER BY s.sehir_adi, o.otogar_adi
    ");
    $stmt->execute();
    $otogarlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>alert('Veritabanı hatası oluştu. Lütfen daha sonra tekrar deneyin.');</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Bi Bilet</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="js/main.js" defer></script>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <img src="bibilet-logo.png" alt="Bi Bilet Logo" class="logo">
            </div>
            <nav>
                <?php if (isset($_SESSION['kullanici_id'])): ?>
                    <a href="panel/kullanici-paneli.php" class="nav-button"><i class="fas fa-user"></i> Hesabım</a>
                <?php else: ?>
                    <a href="uye/giris-yap.php" class="nav-button"><i class="fas fa-sign-in-alt"></i> Giriş Yap</a>
                    <a href="uye/kayit-ol.php" class="nav-button"><i class="fas fa-user-plus"></i> Üye Ol</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main>
        <div class="search-container">
            <form id="search-form" action="bibilet2/panel/seferler.php" method="GET">
                <div class="search-row">
                    <div class="form-group">
                        <label for="nereden">Nereden</label>
                        <select id="nereden" name="nereden" required>
                            <option value="" disabled selected>Seçiniz</option>
                            <?php foreach ($otogarlar as $otogar): ?>
                                <option value="<?php echo $otogar['id']; ?>">
                                    <?php echo htmlspecialchars($otogar['otogar_adi'] . ' (' . $otogar['sehir_adi'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="swap-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="form-group">
                        <label for="nereye">Nereye</label>
                        <select id="nereye" name="nereye" required>
                            <option value="" disabled selected>Seçiniz</option>
                            <?php foreach ($otogarlar as $otogar): ?>
                                <option value="<?php echo $otogar['id']; ?>">
                                    <?php echo htmlspecialchars($otogar['otogar_adi'] . ' (' . $otogar['sehir_adi'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gidis_tarihi">Gidiş Tarihi</label>
                        <input type="date" id="gidis_tarihi" name="gidis_tarihi" required>
                    </div>
                    <div class="form-group tarih-secim">
                        <label>Tarih Seçimi</label>
                        <div class="tarih-options">
                            <label><input type="radio" name="tarih_secim" value="bugun" onclick="setToday()"> Bugün</label>
                            <label><input type="radio" name="tarih_secim" value="yarin" onclick="setTomorrow()"> Yarın</label>
                        </div>
                    </div>
                    <button type="submit" class="search-button">Otobüs Ara</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>