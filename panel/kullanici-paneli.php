<?php
session_start();
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: ../uye/giris-yap.php");
    exit();
}

require '../db/baglanti.php';

// Kullanıcı bilgilerini çek
$kullanici_id = $_SESSION['kullanici_id'];
$stmt = $conn->prepare("SELECT ad, soyad, email, telefon FROM kullanicilar WHERE id = ?");
$stmt->execute([$kullanici_id]);
$kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kullanici) {
    header("Location: ../uye/cikis-yap.php");
    exit();
}

// Kullanıcı Bilgilerim Güncelleme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['sayfa']) && $_GET['sayfa'] == 'kullanici-bilgilerim') {
    $ad = trim($_POST['ad']);
    $soyad = trim($_POST['soyad']);
    $email = trim($_POST['email']);
    $telefon = trim($_POST['telefon']);
    $sifre = trim($_POST['sifre']);

    $stmt = $conn->prepare("SELECT id FROM kullanicilar WHERE email = ? AND id != ?");
    $stmt->execute([$email, $kullanici_id]);
    if ($stmt->rowCount() > 0) {
        $error = "Bu e-posta zaten başka bir kullanıcı tarafından kullanılıyor!";
    } else {
        try {
            if (!empty($sifre)) {
                $sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE kullanicilar SET ad = ?, soyad = ?, email = ?, telefon = ?, sifre = ? WHERE id = ?");
                $stmt->execute([$ad, $soyad, $email, $telefon, $sifre_hash, $kullanici_id]);
            } else {
                $stmt = $conn->prepare("UPDATE kullanicilar SET ad = ?, soyad = ?, email = ?, telefon = ? WHERE id = ?");
                $stmt->execute([$ad, $soyad, $email, $telefon, $kullanici_id]);
            }

            $_SESSION['kullanici_ad'] = $ad;
            $success = "Bilgileriniz başarıyla güncellendi!";
            
            $stmt = $conn->prepare("SELECT ad, soyad, email, telefon FROM kullanicilar WHERE id = ?");
            $stmt->execute([$kullanici_id]);
            $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = "Hata: " . $e->getMessage();
        }
    }
}

// Kart Ekleme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['sayfa']) && $_GET['sayfa'] == 'odeme-bilgilerim' && isset($_POST['kart_ekle'])) {
    $telefon = trim($_POST['telefon']);
    $kart_numarasi = trim($_POST['kart_numarasi']);
    $son_kullanma_tarihi = trim($_POST['son_kullanma_tarihi']);
    $cvc2 = trim($_POST['cvc2']);
    $kart_adi = trim($_POST['kart_adi']);
    $banka_adi = trim($_POST['banka_adi']);

    if (empty($telefon) || empty($kart_numarasi) || empty($son_kullanma_tarihi) || empty($cvc2) || empty($kart_adi) || empty($banka_adi)) {
        $error = "Tüm alanları doldurunuz!";
    } else {
        try {
            $kart_numarasi_enc = encryptData($kart_numarasi);
            $cvc2_enc = encryptData($cvc2);

            $stmt = $conn->prepare("INSERT INTO kartlar (kullanici_id, kart_adi, kart_numarasi, son_kullanma_tarihi, cvc2, banka_adi, telefon) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$kullanici_id, $kart_adi, $kart_numarasi_enc, $son_kullanma_tarihi, $cvc2_enc, $banka_adi, $telefon]);
            $success = "Kart başarıyla eklendi!";
        } catch (PDOException $e) {
            $error = "Hata: " . $e->getMessage();
        }
    }
}

// Kart Silme
if (isset($_GET['sayfa']) && $_GET['sayfa'] == 'odeme-bilgilerim' && isset($_GET['action']) && $_GET['action'] == 'sil' && isset($_GET['kart_id'])) {
    $kart_id = $_GET['kart_id'];
    try {
        $stmt = $conn->prepare("DELETE FROM kartlar WHERE id = ? AND kullanici_id = ?");
        $stmt->execute([$kart_id, $kullanici_id]);
        $success = "Kart başarıyla silindi!";
    } catch (PDOException $e) {
        $error = "Hata: " . $e->getMessage();
    }
}

// Yolcu Ekleme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['sayfa']) && $_GET['sayfa'] == 'kayitli-yolcularim' && isset($_POST['yolcu_ekle'])) {
    $ad = trim($_POST['ad']);
    $soyad = trim($_POST['soyad']);
    $email = trim($_POST['email']);
    $telefon = trim($_POST['telefon']);
    $tc_kimlik_no = trim($_POST['tc_kimlik_no']);
    $dogum_tarihi = trim($_POST['dogum_tarihi']);
    $cinsiyet = trim($_POST['cinsiyet']);

    if (empty($ad) || empty($soyad) || empty($email) || empty($telefon) || empty($tc_kimlik_no) || empty($dogum_tarihi) || empty($cinsiyet)) {
        $error = "Tüm alanları doldurunuz!";
    } else {
        try {
            // TC Kimlik No’yu şifrele
            $tc_kimlik_no_enc = encryptData($tc_kimlik_no);

            $stmt = $conn->prepare("INSERT INTO kayitli_yolcular (kullanici_id, ad, soyad, email, telefon, tc_kimlik_no, dogum_tarihi, cinsiyet) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$kullanici_id, $ad, $soyad, $email, $telefon, $tc_kimlik_no_enc, $dogum_tarihi, $cinsiyet]);
            $success = "Yolcu başarıyla eklendi!";
        } catch (PDOException $e) {
            $error = "Hata: " . $e->getMessage();
        }
    }
}

// Yolcu Silme
if (isset($_GET['sayfa']) && $_GET['sayfa'] == 'kayitli-yolcularim' && isset($_GET['action']) && $_GET['action'] == 'sil' && isset($_GET['yolcu_id'])) {
    $yolcu_id = $_GET['yolcu_id'];
    try {
        $stmt = $conn->prepare("DELETE FROM kayitli_yolcular WHERE id = ? AND kullanici_id = ?");
        $stmt->execute([$yolcu_id, $kullanici_id]);
        $success = "Yolcu başarıyla silindi!";
    } catch (PDOException $e) {
        $error = "Hata: " . $e->getMessage();
    }
}

// Kayıtlı kartları çek
$kartlar = [];
if (isset($_GET['sayfa']) && $_GET['sayfa'] == 'odeme-bilgilerim') {
    try {
        $stmt = $conn->prepare("SELECT id, kart_adi, kart_numarasi, banka_adi FROM kartlar WHERE kullanici_id = ?");
        $stmt->execute([$kullanici_id]);
        $kartlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Kartlar çekilirken hata: " . $e->getMessage();
    }
}

// Kayıtlı yolcuları çek
$yolcular = [];
if (isset($_GET['sayfa']) && $_GET['sayfa'] == 'kayitli-yolcularim') {
    try {
        $stmt = $conn->prepare("SELECT id, ad, soyad, email, telefon, tc_kimlik_no, dogum_tarihi, cinsiyet FROM kayitli_yolcular WHERE kullanici_id = ?");
        $stmt->execute([$kullanici_id]);
        $yolcular = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Yolcular çekilirken hata: " . $e->getMessage();
    }
}

// Hangi sayfanın gösterileceğini belirle
$sayfa = isset($_GET['sayfa']) ? $_GET['sayfa'] : 'anasayfa';

// AJAX isteği mi?
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    switch ($sayfa) {
        case 'seyahatlerim':
            echo '<h2>Seyahatlerim</h2><p>Geçmiş ve yaklaşan seyahatlerin burada listelenecek.</p>';
            break;
        case 'kullanici-bilgilerim':
            ob_start();
            ?>
            <h2>Kullanıcı Bilgilerim</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <form action="kullanici-paneli.php?sayfa=kullanici-bilgilerim" method="POST" id="user-info-form">
                <div class="form-group">
                    <label for="ad">Ad</label>
                    <input type="text" id="ad" name="ad" value="<?php echo htmlspecialchars($kullanici['ad']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="soyad">Soyad</label>
                    <input type="text" id="soyad" name="soyad" value="<?php echo htmlspecialchars($kullanici['soyad']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($kullanici['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefon">Telefon</label>
                    <input type="tel" id="telefon" name="telefon" value="<?php echo htmlspecialchars($kullanici['telefon']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="sifre">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                    <input type="password" id="sifre" name="sifre" placeholder="Yeni şifrenizi girin">
                </div>
                <button type="submit" class="save-button">Bilgileri Kaydet</button>
            </form>
            <?php
            echo ob_get_clean();
            break;
        case 'odeme-bilgilerim':
            ob_start();
            ?>
            <h2>Ödeme Bilgilerim</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <div class="kartlar-container">
                <?php if (empty($kartlar)): ?>
                    <p>Kayıtlı kart bulunmamaktadır.</p>
                <?php else: ?>
                    <?php foreach ($kartlar as $kart): ?>
                        <?php
                        $kart_numarasi = decryptData($kart['kart_numarasi']);
                        $kart_numarasi_goster = substr($kart_numarasi, 0, 4) . '**** ****' . substr($kart_numarasi, -4);
                        ?>
                        <div class="kart">
                            <div class="kart-header">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="MasterCard" class="kart-logo">
                                <span class="kart-numara"><?php echo htmlspecialchars($kart_numarasi_goster); ?></span>
                                <span class="banka-adi"><?php echo htmlspecialchars($kart['banka_adi']); ?></span>
                                <a href="kullanici-paneli.php?sayfa=odeme-bilgilerim&action=sil&kart_id=<?php echo $kart['id']; ?>" class="sil-buton" onclick="return confirm('Bu kartı silmek istediğinizden emin misiniz?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                            <div class="kart-body">
                                <p><?php echo htmlspecialchars($kart['kart_adi']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button class="add-kart-button" onclick="openAddKartModal()">Başka Kart Ekle</button>
            <?php
            echo ob_get_clean();
            break;
        case 'kayitli-yolcularim':
            ob_start();
            ?>
            <h2>Kayıtlı Yolcularım</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <div class="yolcular-container">
                <?php if (empty($yolcular)): ?>
                    <p>Kayıtlı yolcu bulunmamaktadır.</p>
                <?php else: ?>
                    <?php foreach ($yolcular as $yolcu): ?>
                        <?php
                        $tc_kimlik_no = decryptData($yolcu['tc_kimlik_no']);
                        $tc_kimlik_no_goster = substr($tc_kimlik_no, 0, 2) . '****' . substr($tc_kimlik_no, -2);
                        ?>
                        <div class="yolcu">
                            <div class="yolcu-header">
                                <span class="yolcu-ad-soyad"><?php echo htmlspecialchars($yolcu['ad'] . ' ' . $yolcu['soyad']); ?></span>
                                <span class="yolcu-cinsiyet"><?php echo htmlspecialchars($yolcu['cinsiyet']); ?></span>
                                <a href="kullanici-paneli.php?sayfa=kayitli-yolcularim&action=sil&yolcu_id=<?php echo $yolcu['id']; ?>" class="sil-buton" onclick="return confirm('Bu yolcuyu silmek istediğinizden emin misiniz?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                            <div class="yolcu-body">
                                <p><strong>E-posta:</strong> <?php echo htmlspecialchars($yolcu['email']); ?></p>
                                <p><strong>Telefon:</strong> <?php echo htmlspecialchars($yolcu['telefon']); ?></p>
                                <p><strong>TC Kimlik No:</strong> <?php echo htmlspecialchars($tc_kimlik_no_goster); ?></p>
                                <p><strong>Doğum Tarihi:</strong> <?php echo htmlspecialchars($yolcu['dogum_tarihi']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button class="add-yolcu-button" onclick="openAddYolcuModal()">Yeni Yolcu Ekle</button>
            <?php
            echo ob_get_clean();
            break;
        case 'fatura-bilgilerim':
            echo '<h2>Fatura Bilgilerim</h2><p>Fatura bilgilerini burada düzenleyebilirsin.</p>';
            break;
        default:
            echo '<h2>Kullanıcı Paneli</h2><p>Buradan seyahatlerini yönetebilir, bilgilerini güncelleyebilirsin.</p>';
            break;
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Paneli</title>
    <link rel="stylesheet" href="../css/panel.css">
    <!-- FontAwesome ikonları için -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="../js/panel.js" defer></script>
</head>
<body>
    <div class="panel-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Hesabım</h3>
            </div>
            <a href="../index.php" class="home-button"><i class="fas fa-home"></i> Ana Sayfaya Dön</a>
            <ul>
                <li>
                    <a href="kullanici-paneli.php?sayfa=seyahatlerim" <?php echo $sayfa == 'seyahatlerim' ? 'class="active"' : ''; ?>>
                        <i class="fas fa-bus"></i> Seyahatlerim
                    </a>
                </li>
                <li>
                    <a href="kullanici-paneli.php?sayfa=kullanici-bilgilerim" <?php echo $sayfa == 'kullanici-bilgilerim' ? 'class="active"' : ''; ?>>
                        <i class="fas fa-user"></i> Kullanıcı Bilgilerim
                    </a>
                </li>
                <li>
                    <a href="kullanici-paneli.php?sayfa=odeme-bilgilerim" <?php echo $sayfa == 'odeme-bilgilerim' ? 'class="active"' : ''; ?>>
                        <i class="fas fa-credit-card"></i> Ödeme Bilgilerim
                    </a>
                </li>
                <li>
                    <a href="kullanici-paneli.php?sayfa=kayitli-yolcularim" <?php echo $sayfa == 'kayitli-yolcularim' ? 'class="active"' : ''; ?>>
                        <i class="fas fa-users"></i> Kayıtlı Yolcularım
                    </a>
                </li>
                <li>
                    <a href="kullanici-paneli.php?sayfa=fatura-bilgilerim" <?php echo $sayfa == 'fatura-bilgilerim' ? 'class="active"' : ''; ?>>
                        <i class="fas fa-file-invoice"></i> Fatura Bilgilerim
                    </a>
                </li>
                <li>
                    <a href="../uye/cikis-yap.php">
                        <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                    </a>
                </li>
            </ul>
        </aside>
        <main class="content">
            <h2>
                <?php
                switch ($sayfa) {
                    case 'seyahatlerim':
                        echo 'Seyahatlerim';
                        break;
                    case 'kullanici-bilgilerim':
                        echo 'Kullanıcı Bilgilerim';
                        break;
                    case 'odeme-bilgilerim':
                        echo 'Ödeme Bilgilerim';
                        break;
                    case 'kayitli-yolcularim':
                        echo 'Kayıtlı Yolcularım';
                        break;
                    case 'fatura-bilgilerim':
                        echo 'Fatura Bilgilerim';
                        break;
                    default:
                        echo 'Kullanıcı Paneli';
                        break;
                }
                ?>
            </h2>
            <?php if ($sayfa == 'kullanici-bilgilerim'): ?>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <p class="success"><?php echo $success; ?></p>
                <?php endif; ?>
                <form action="kullanici-paneli.php?sayfa=kullanici-bilgilerim" method="POST" id="user-info-form">
                    <div class="form-group">
                        <label for="ad">Ad</label>
                        <input type="text" id="ad" name="ad" value="<?php echo htmlspecialchars($kullanici['ad']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="soyad">Soyad</label>
                        <input type="text" id="soyad" name="soyad" value="<?php echo htmlspecialchars($kullanici['soyad']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-posta</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($kullanici['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="telefon">Telefon</label>
                        <input type="tel" id="telefon" name="telefon" value="<?php echo htmlspecialchars($kullanici['telefon']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="sifre">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                        <input type="password" id="sifre" name="sifre" placeholder="Yeni şifrenizi girin">
                    </div>
                    <button type="submit" class="save-button">Bilgileri Kaydet</button>
                </form>
            <?php elseif ($sayfa == 'odeme-bilgilerim'): ?>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <p class="success"><?php echo $success; ?></p>
                <?php endif; ?>
                <div class="kartlar-container">
                    <?php if (empty($kartlar)): ?>
                        <p>Kayıtlı kart bulunmamaktadır.</p>
                    <?php else: ?>
                        <?php foreach ($kartlar as $kart): ?>
                            <?php
                            $kart_numarasi = decryptData($kart['kart_numarasi']);
                            $kart_numarasi_goster = substr($kart_numarasi, 0, 4) . '**** ****' . substr($kart_numarasi, -4);
                            ?>
                            <div class="kart">
                                <div class="kart-header">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="MasterCard" class="kart-logo">
                                    <span class="kart-numara"><?php echo htmlspecialchars($kart_numarasi_goster); ?></span>
                                    <span class="banka-adi"><?php echo htmlspecialchars($kart['banka_adi']); ?></span>
                                    <a href="kullanici-paneli.php?sayfa=odeme-bilgilerim&action=sil&kart_id=<?php echo $kart['id']; ?>" class="sil-buton" onclick="return confirm('Bu kartı silmek istediğinizden emin misiniz?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                                <div class="kart-body">
                                    <p><?php echo htmlspecialchars($kart['kart_adi']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button class="add-kart-button" onclick="openAddKartModal()">Başka Kart Ekle</button>
            <?php elseif ($sayfa == 'kayitli-yolcularim'): ?>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <p class="success"><?php echo $success; ?></p>
                <?php endif; ?>
                <div class="yolcular-container">
                    <?php if (empty($yolcular)): ?>
                        <p>Kayıtlı yolcu bulunmamaktadır.</p>
                    <?php else: ?>
                        <?php foreach ($yolcular as $yolcu): ?>
                            <?php
                            $tc_kimlik_no = decryptData($yolcu['tc_kimlik_no']);
                            $tc_kimlik_no_goster = substr($tc_kimlik_no, 0, 2) . '****' . substr($tc_kimlik_no, -2);
                            ?>
                            <div class="yolcu">
                                <div class="yolcu-header">
                                    <span class="yolcu-ad-soyad"><?php echo htmlspecialchars($yolcu['ad'] . ' ' . $yolcu['soyad']); ?></span>
                                    <span class="yolcu-cinsiyet"><?php echo htmlspecialchars($yolcu['cinsiyet']); ?></span>
                                    <a href="kullanici-paneli.php?sayfa=kayitli-yolcularim&action=sil&yolcu_id=<?php echo $yolcu['id']; ?>" class="sil-buton" onclick="return confirm('Bu yolcuyu silmek istediğinizden emin misiniz?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                                <div class="yolcu-body">
                                    <p><strong>E-posta:</strong> <?php echo htmlspecialchars($yolcu['email']); ?></p>
                                    <p><strong>Telefon:</strong> <?php echo htmlspecialchars($yolcu['telefon']); ?></p>
                                    <p><strong>TC Kimlik No:</strong> <?php echo htmlspecialchars($tc_kimlik_no_goster); ?></p>
                                    <p><strong>Doğum Tarihi:</strong> <?php echo htmlspecialchars($yolcu['dogum_tarihi']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button class="add-yolcu-button" onclick="openAddYolcuModal()">Yeni Yolcu Ekle</button>
            <?php else: ?>
                <p>
                    <?php
                    switch ($sayfa) {
                        case 'seyahatlerim':
                            echo 'Geçmiş ve yaklaşan seyahatlerin burada listelenecek.';
                            break;
                        case 'kullanici-bilgilerim':
                            echo 'Kullanıcı bilgilerini burada güncelleyebilirsin.';
                            break;
                        case 'odeme-bilgilerim':
                            echo 'Kayıtlı ödeme yöntemlerini burada yönetebilirsin.';
                            break;
                        case 'kayitli-yolcularim':
                            echo 'Kayıtlı yolcuları burada yönetebilirsin.';
                            break;
                        case 'fatura-bilgilerim':
                            echo 'Fatura bilgilerini burada düzenleyebilirsin.';
                            break;
                        default:
                            echo 'Buradan seyahatlerini yönetebilir, bilgilerini güncelleyebilirsin.';
                            break;
                    }
                    ?>
                </p>
            <?php endif; ?>
            <!-- Kart Ekleme Modalı -->
            <div id="add-kart-modal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeAddKartModal()">×</span>
                    <h3>Yeni Kart Ekle</h3>
                    <form action="kullanici-paneli.php?sayfa=odeme-bilgilerim" method="POST" id="add-kart-form">
                        <input type="hidden" name="kart_ekle" value="1">
                        <div class="form-group">
                            <label for="telefon">Cep Telefonu</label>
                            <div class="telefon-input">
                                <select name="telefon_kod" required>
                                    <option value="+90">TR (+90)</option>
                                </select>
                                <input type="tel" id="telefon" name="telefon" placeholder="5540164344" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kart_numarasi">Kart Numarası</label>
                            <input type="text" id="kart_numarasi" name="kart_numarasi" placeholder="1234 5678 9012 3456" required>
                        </div>
                        <div class="form-group form-row">
                            <div>
                                <label for="son_kullanma_tarihi">Son Kullanma Tarihi (AA/YY)</label>
                                <input type="text" id="son_kullanma_tarihi" name="son_kullanma_tarihi" placeholder="MM/YY" required>
                            </div>
                            <div>
                                <label for="cvc2">CVC2</label>
                                <input type="text" id="cvc2" name="cvc2" placeholder="123" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kart_adi">Kart Adı</label>
                            <input type="text" id="kart_adi" name="kart_adi" placeholder="Örn: Neo MasterCard" required>
                        </div>
                        <div class="form-group">
                            <label for="banka_adi">Banka Adı</label>
                            <input type="text" id="banka_adi" name="banka_adi" placeholder="Örn: AKBANK" required>
                        </div>
                        <button type="submit" class="save-button">Kartı Kaydet</button>
                    </form>
                </div>
            </div>
            <!-- Yolcu Ekleme Modalı -->
            <div id="add-yolcu-modal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeAddYolcuModal()">×</span>
                    <h3>Yeni Yolcu Ekle</h3>
                    <form action="kullanici-paneli.php?sayfa=kayitli-yolcularim" method="POST" id="add-yolcu-form">
                        <input type="hidden" name="yolcu_ekle" value="1">
                        <div class="form-group form-row">
                            <div>
                                <label for="ad">Ad</label>
                                <input type="text" id="ad" name="ad" placeholder="Ad" required>
                            </div>
                            <div>
                                <label for="soyad">Soyad</label>
                                <input type="text" id="soyad" name="soyad" placeholder="Soyad" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">E-posta</label>
                            <input type="email" id="email" name="email" placeholder="E-posta" required>
                        </div>
                        <div class="form-group">
                            <label for="telefon">Telefon</label>
                            <div class="telefon-input">
                                <select name="telefon_kod" required>
                                    <option value="+90">TR (+90)</option>
                                </select>
                                <input type="tel" id="telefon" name="telefon" placeholder="5540164344" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tc_kimlik_no">TC Kimlik No</label>
                            <input type="text" id="tc_kimlik_no" name="tc_kimlik_no" placeholder="TC Kimlik No" required>
                        </div>
                        <div class="form-group form-row">
                            <div>
                                <label for="dogum_tarihi">Doğum Tarihi (YYYY-AA-GG)</label>
                                <input type="date" id="dogum_tarihi" name="dogum_tarihi" required>
                            </div>
                            <div>
                                <label for="cinsiyet">Cinsiyet</label>
                                <div class="cinsiyet-secim">
                                    <label><input type="radio" name="cinsiyet" value="Erkek" required> Erkek</label>
                                    <label><input type="radio" name="cinsiyet" value="Kadın"> Kadın</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="save-button">Yolcuyu Kaydet</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>