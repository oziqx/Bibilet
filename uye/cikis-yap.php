<?php
session_start();

// Oturumu tamamen temizle
session_unset();
session_destroy();

// Ana sayfaya yönlendir
header("Location: ../index.php");
exit();
?>