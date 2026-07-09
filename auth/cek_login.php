<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika belum login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../auth/login.php");
    exit;
}
?>