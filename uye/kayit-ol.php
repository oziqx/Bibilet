<?php
session_start();
require '../db/baglanti.php';

if (isset($_SESSION['kullanici_id'])) {
    header("Location: ../panel/kullanici-paneli.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ad = trim($_POST['ad']);
    $soyad = trim($_POST['soyad']);
    $email = trim($_POST['email']);
    $sifre = trim($_POST['sifre']);
    $sifre_tekrar = trim($_POST['sifre_tekrar']);

    if (empty($ad) || empty($soyad) || empty($email) || empty($sifre) || empty($sifre_tekrar)) {
        $hata = "Lütfen tüm alanları doldurun.";
    } elseif ($sifre !== $sifre_tekrar) {
        $hata = "Şifreler eşleşmiyor.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM kullanicilar WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $hata = "Bu e-posta adresi zaten kayıtlı.";
            } else {
                $stmt = $conn->prepare("INSERT INTO kullanicilar (ad, soyad, email, sifre) VALUES (:ad, :soyad, :email, :sifre)");
                $stmt->execute([
                    'ad' => $ad,
                    'soyad' => $soyad,
                    'email' => $email,
                    'sifre' => password_hash($sifre, PASSWORD_DEFAULT)
                ]);
                header("Location: giris-yap.php");
                exit();
            }
        } catch (PDOException $e) {
            $hata = "Bir hata oluştu: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="../css/uye.css">
</head>
<body>
    <div class="container">
        <h2>Kayıt Ol</h2>
        <?php if (isset($hata)): ?>
            <p class="error"><?php echo htmlspecialchars($hata); ?></p>
        <?php endif; ?>
        <form action="kayit-ol.php" method="POST">
            <div class="form-group">
                <label for="ad">Ad</label>
                <input type="text" id="ad" name="ad" required>
            </div>
            <div class="form-group">
                <label for="soyad">Soyad</label>
                <input type="text" id="soyad" name="soyad" required>
            </div>
            <div class="form-group">
                <label for="email">E-posta</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="sifre">Şifre</label>
                <input type="password" id="sifre" name="sifre" required>
            </div>
            <div class="form-group">
                <label for="sifre_tekrar">Şifre Tekrar</label>
                <input type="password" id="sifre_tekrar" name="sifre_tekrar" required>
            </div>
            <button type="submit" class="btn">Kayıt Ol</button>
        </form>
        <p>Zaten üye misiniz? <a href="giris-yap.php">Giriş Yap</a> | <a href="firma-kayit.php">Firma Kayıt</a></p>
    </div>
</body>
</html>