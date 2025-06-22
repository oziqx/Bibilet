<?php
session_start();
require '../db/baglanti.php';

if (isset($_SESSION['kullanici_id'])) {
    header("Location: ../panel/kullanici-paneli.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $sifre = trim($_POST['sifre']);

    if (empty($email) || empty($sifre)) {
        $hata = "Lütfen tüm alanları doldurun.";
    } else {
        try {
            // Kullanıcı kontrolü
            $stmt = $conn->prepare("SELECT id, email, sifre FROM kullanicilar WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($kullanici && password_verify($sifre, $kullanici['sifre'])) {
                // Eski oturumları temizle
                $oturum_id = session_id();
                $conn->prepare("DELETE FROM aktif_oturum WHERE kullanici_id = :kullanici_id")->execute(['kullanici_id' => $kullanici['id']]);
                $conn->prepare("INSERT INTO aktif_oturum (kullanici_id, oturum_id) VALUES (:kullanici_id, :oturum_id)")->execute(['kullanici_id' => $kullanici['id'], 'oturum_id' => $oturum_id]);

                $_SESSION['kullanici_id'] = $kullanici['id'];
                $_SESSION['user_type'] = 'kullanici';
                header("Location: ../panel/kullanici-paneli.php");
                exit();
            }

            // Firma kontrolü
            $stmt = $conn->prepare("SELECT id, email, sifre FROM firmalar WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $firma = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($firma && password_verify($sifre, $firma['sifre'])) {
                // Eski oturumları temizle
                $oturum_id = session_id();
                $conn->prepare("DELETE FROM aktif_oturum WHERE kullanici_id = :kullanici_id")->execute(['kullanici_id' => $firma['id']]);
                $conn->prepare("INSERT INTO aktif_oturum (kullanici_id, oturum_id) VALUES (:kullanici_id, :oturum_id)")->execute(['kullanici_id' => $firma['id'], 'oturum_id' => $oturum_id]);

                $_SESSION['kullanici_id'] = $firma['id'];
                $_SESSION['user_type'] = 'firma';
                header("Location: ../panel/firma-panel.php");
                exit();
            }

            $hata = "E-posta veya şifre yanlış.";
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
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="../css/uye.css">
</head>
<body>
    <div class="container">
        <h2>Giriş Yap</h2>
        <?php if (isset($hata)): ?>
            <p class="error"><?php echo htmlspecialchars($hata); ?></p>
        <?php endif; ?>
        <form action="giris-yap.php" method="POST">
            <div class="form-group">
                <label for="email">E-posta</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="sifre">Şifre</label>
                <input type="password" id="sifre" name="sifre" required>
            </div>
            <button type="submit" class="btn">Giriş Yap</button>
        </form>
        <p>Hesabınız yok mu? <a href="kayit-ol.php">Kayıt Ol</a></p>
    </div>
</body>
</html>