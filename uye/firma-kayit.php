<?php
session_start();
require '../db/baglanti.php';

if (isset($_SESSION['kullanici_id'])) {
    header("Location: ../panel/firma-paneli.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firma_adi = trim($_POST['firma_adi']);
    $email = trim($_POST['email']);
    $sifre = trim($_POST['sifre']);
    $sifre_tekrar = trim($_POST['sifre_tekrar']);

    if (empty($firma_adi) || empty($email) || empty($sifre) || empty($sifre_tekrar)) {
        $hata = "Lütfen tüm alanları doldurun.";
    } elseif ($sifre !== $sifre_tekrar) {
        $hata = "Şifreler eşleşmiyor.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM firmalar WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $hata = "Bu e-posta adresi zaten kayıtlı.";
            } else {
                $stmt = $conn->prepare("INSERT INTO firmalar (firma_adi, email, sifre) VALUES (:firma_adi, :email, :sifre)");
                $stmt->execute([
                    'firma_adi' => $firma_adi,
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
    <title>Firma Kayıt</title>
    <link rel="stylesheet" href="../css/firma.css">
</head>
<body>
    <div class="container">
        <h2>Firma Kayıt</h2>
        <?php if (isset($hata)): ?>
            <p class="error"><?php echo htmlspecialchars($hata); ?></p>
        <?php endif; ?>
        <form action="firma-kayit.php" method="POST">
            <div class="form-group">
                <label for="firma_adi">Firma Adı</label>
                <input type="text" id="firma_adi" name="firma_adi" required>
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
            <button type="submit" class="btn">Firma Kayıt</button>
        </form>
        <p>Zaten üye misiniz? <a href="giris-yap.php">Giriş Yap</a></p>
    </div>
</body>
</html>