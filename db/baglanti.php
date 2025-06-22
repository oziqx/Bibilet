<?php
// DEBUG sabitini varsayılan olarak false yapalım (gerekirse manuel olarak true olarak tanımlanabilir)
if (!defined('DEBUG')) {
    define('DEBUG', false);
}

// Veritabanı bağlantı bilgileri
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "bibilet";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (DEBUG === true) {
        error_log("Bağlantı başarılı: " . date('Y-m-d H:i:s'));
    }
} catch (PDOException $e) {
    error_log("Bağlantı hatası: " . $e->getMessage());
    $conn = null;
    if (!defined('PRODUCTION')) {
        die("Bağlantı hatası oluştu. Lütfen sistem yöneticisine başvurun.");
    }
}

define('ENCRYPTION_KEY', 'your-secure-key-here-32byteslong!');
define('ENCRYPTION_METHOD', 'AES-256-CBC');

function encryptData($data) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(ENCRYPTION_METHOD));
    $encrypted = openssl_encrypt($data, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decryptData($data) {
    $decoded = base64_decode($data);
    if ($decoded === false) {
        return null; // Decode edilemezse null dön
    }
    $parts = explode('::', $decoded, 2);
    if (count($parts) !== 2) {
        return null; // Yanlış formatta ise null dön
    }
    list($encrypted_data, $iv) = $parts;
    $decrypted = openssl_decrypt($encrypted_data, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv);
    return $decrypted !== false ? $decrypted : null; // Çözme başarısızsa null dön
}

if (!isset($conn)) {
    die("Veritabanı bağlantısı kurulamadı.");
}
?>
