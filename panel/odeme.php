<?php
session_start();
require '../db/baglanti.php';

if (!isset($_SESSION['kullanici_id']) || !isset($_SESSION['user_type'])) {
    header("Location: ../uye/giris-yap.php");
    exit;
}

$sefer_id = $_GET['sefer_id'] ?? null;
$koltuk_no = $_GET['koltuk_no'] ?? null;
$cinsiyet = $_GET['cinsiyet'] ?? null;

if (!$sefer_id || !$koltuk_no || !$cinsiyet) {
    die("Eksik parametreler!");
}

// Sefer bilgilerini al
$stmt = $conn->prepare("SELECT firma_id, tarih, saat, fiyat FROM firma_seferler WHERE id = :sefer_id");
$stmt->execute(['sefer_id' => $sefer_id]);
$sefer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sefer) {
    die("Sefer bulunamadı!");
}

$firma_id = $sefer['firma_id'];
$tarih = $sefer['tarih'];
$saat = $sefer['saat'];
$odenen_tutar = $sefer['fiyat'];

// Kullanıcı ve kart bilgilerini al
$kullanici_id = $_SESSION['kullanici_id'];
if (!isset($kullanici_id)) {
    die("Kullanıcı ID oturumda tanımlı değil!");
}

$stmt = $conn->prepare("SELECT id, ad, soyad, email, telefon, tc_kimlik_no, dogum_tarihi, cinsiyet FROM kayitli_yolcular WHERE kullanici_id = :kullanici_id");
$stmt->execute(['kullanici_id' => $kullanici_id]);
$kullanicilar = $stmt->fetchAll(PDO::FETCH_ASSOC);
error_log("Kullanıcılar çekildi, sayı: " . count($kullanicilar));

$stmt = $conn->prepare("SELECT id, kart_adi, kart_numarasi, son_kullanma_tarihi, cvc2 FROM kartlar WHERE kullanici_id = :kullanici_id");
$stmt->execute(['kullanici_id' => $kullanici_id]);
$kartlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
error_log("Kartlar çekildi, sayı: " . count($kartlar));

// Şifreli verileri çöz
foreach ($kullanicilar as &$kullanici) {
    $kullanici['ad'] = decryptData($kullanici['ad']) ?: 'Ad Bulunamadı';
    $kullanici['soyad'] = decryptData($kullanici['soyad']) ?: 'Soyad Bulunamadı';
    $kullanici['email'] = decryptData($kullanici['email']) ?: 'Email Bulunamadı';
    $kullanici['telefon'] = decryptData($kullanici['telefon']) ?: 'Telefon Bulunamadı';
    $kullanici['tc_kimlik_no'] = decryptData($kullanici['tc_kimlik_no']) ?: 'TC Bulunamadı';
    $kullanici['dogum_tarihi'] = decryptData($kullanici['dogum_tarihi']) ?: 'Doğum Tarihi Bulunamadı';
    $kullanici['cinsiyet'] = decryptData($kullanici['cinsiyet']) ?: 'Cinsiyet Bulunamadı';
}
unset($kullanici);

foreach ($kartlar as &$kart) {
    $kart['kart_adi'] = decryptData($kart['kart_adi']) ?: 'Kart Adı Bulunamadı';
    $kart['kart_numarasi'] = decryptData($kart['kart_numarasi']) ?: 'Kart Numarası Bulunamadı';
    $kart['son_kullanma_tarihi'] = decryptData($kart['son_kullanma_tarihi']) ?: 'Son Kullanma Bulunamadı';
    $kart['cvc2'] = decryptData($kart['cvc2']) ?: 'CVC2 Bulunamadı';
}
unset($kart);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ödeme - Bi Bilet</title>
    <link rel="stylesheet" href="../css/odeme.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="payment-container">
        <h1>Ödeme İşlemi</h1>
        <form id="paymentForm" action="odeme_islem.php" method="POST">
            <input type="hidden" name="sefer_id" value="<?php echo htmlspecialchars($sefer_id); ?>">
            <input type="hidden" name="koltuk_no" value="<?php echo htmlspecialchars($koltuk_no); ?>">
            <input type="hidden" name="cinsiyet" value="<?php echo htmlspecialchars($cinsiyet); ?>">
            <input type="hidden" name="firma_id" value="<?php echo htmlspecialchars($firma_id); ?>">
            <input type="hidden" name="tarih" value="<?php echo htmlspecialchars($tarih); ?>">
            <input type="hidden" name="saat" value="<?php echo htmlspecialchars($saat); ?>">
            <input type="hidden" name="odenen_tutar" value="<?php echo htmlspecialchars($odenen_tutar); ?>">

            <!-- Kullanıcı Bilgileri -->
            <div class="section">
                <h2>Kullanıcı Bilgileri</h2>
                <div class="field">
                    <label for="userSelect">Kayıtlı Kullanıcı:</label>
                    <select id="userSelect" name="user_id" class="select">
                        <option value="">Seçiniz...</option>
                        <?php foreach ($kullanicilar as $user): ?>
                            <option value="<?php echo $user['id']; ?>"
                                    data-ad="<?php echo htmlspecialchars($user['ad']); ?>"
                                    data-soyad="<?php echo htmlspecialchars($user['soyad']); ?>"
                                    data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                    data-telefon="<?php echo htmlspecialchars($user['telefon']); ?>"
                                    data-tc="<?php echo htmlspecialchars($user['tc_kimlik_no']); ?>"
                                    data-dogum="<?php echo htmlspecialchars($user['dogum_tarihi']); ?>"
                                    data-cinsiyet="<?php echo htmlspecialchars($user['cinsiyet']); ?>">
                                <?php echo htmlspecialchars($user['ad'] . ' ' . $user['soyad']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="ad">Ad:*</label>
                    <input type="text" id="ad" name="ad" class="input" required>
                </div>
                <div class="field">
                    <label for="soyad">Soyad:*</label>
                    <input type="text" id="soyad" name="soyad" class="input" required>
                </div>
                <div class="field">
                    <label for="email">E-posta:*</label>
                    <input type="email" id="email" name="email" class="input" required>
                </div>
                <div class="field">
                    <label for="telefon">Telefon:*</label>
                    <input type="text" id="telefon" name="telefon" class="input" required pattern="\d{10}" title="10 haneli numara">
                </div>
                <div class="field">
                    <label for="tc">TC Kimlik No:*</label>
                    <input type="text" id="tc" name="tc_kimlik_no" class="input" required pattern="\d{11}" title="11 haneli TC">
                </div>
                <div class="field">
                    <label for="dogum">Doğum Tarihi:*</label>
                    <input type="date" id="dogum" name="dogum_tarihi" class="input" required>
                </div>
                <div class="field">
                    <label for="cinsiyet">Cinsiyet:*</label>
                    <select id="cinsiyet" name="cinsiyet" class="select" required>
                        <option value="">Seçiniz...</option>
                        <option value="Erkek">Erkek</option>
                        <option value="Kadın">Kadın</option>
                    </select>
                </div>
            </div>

            <!-- Kart Bilgileri -->
            <div class="section">
                <h2>Kart Bilgileri</h2>
                <div class="field">
                    <label for="cardSelect">Kayıtlı Kart:</label>
                    <select id="cardSelect" name="card_id" class="select">
                        <option value="">Seçiniz...</option>
                        <?php foreach ($kartlar as $card): ?>
                            <option value="<?php echo $card['id']; ?>"
                                    data-kart-adi="<?php echo htmlspecialchars($card['kart_adi']); ?>"
                                    data-kart-numara="<?php echo htmlspecialchars($card['kart_numarasi']); ?>"
                                    data-son-kullanma="<?php echo htmlspecialchars($card['son_kullanma_tarihi']); ?>"
                                    data-cvc2="<?php echo htmlspecialchars($card['cvc2']); ?>">
                                <?php echo htmlspecialchars($card['kart_adi'] . ' (****-****-****-' . substr($card['kart_numarasi'], -4)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="kart_adi">Kart Üzerindeki İsim:*</label>
                    <input type="text" id="kart_adi" name="kart_adi" class="input" required>
                </div>
                <div class="field">
                    <label for="kart_numarasi">Kart Numarası:*</label>
                    <input type="text" id="kart_numarasi" name="kart_numarasi" class="input" required placeholder="1234 5678 9012 3456" pattern="\d{16}|\d{4}\s\d{4}\s\d{4}\s\d{4}">
                </div>
                <div class="field">
                    <label for="son_kullanma">Son Kullanma (MM/YY):*</label>
                    <input type="text" id="son_kullanma" name="son_kullanma" class="input" required placeholder="12/29" pattern="\d{2}/\d{2}">
                </div>
                <div class="field">
                    <label for="cvc2">CVC2:*</label>
                    <input type="text" id="cvc2" name="cvc2" class="input" required pattern="\d{3}">
                </div>
                <div class="field">
                    <label>Toplam Tutar:</label>
                    <span class="total"><?php echo htmlspecialchars($odenen_tutar); ?> TL</span>
                </div>
            </div>

            <button type="submit" class="submit-btn">Ödemeyi Onayla</button>
        </form>
    </div>
    <script src="../js/odeme.js"></script>
</body>
</html>