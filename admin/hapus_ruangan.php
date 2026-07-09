<?php
session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";

// Cek hak akses admin
if ($_SESSION['role'] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

// Pastikan id tersedia
if (!isset($_GET['id'])) {
    header("Location: ruangan.php");
    exit;
}

$id = (int) $_GET['id'];

// Ambil data ruangan
$query = mysqli_query($conn, "SELECT * FROM ruangan WHERE id_ruangan='$id'");

if (mysqli_num_rows($query) == 0) {
    header("Location: ruangan.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// Hapus foto jika ada
if (!empty($data['foto'])) {
    $path = "../uploads/" . $data['foto'];

    if (file_exists($path)) {
        unlink($path);
    }
}

// Hapus data dari database
$hapus = mysqli_query($conn, "DELETE FROM ruangan WHERE id_ruangan='$id'");

if ($hapus) {
    header("Location: ruangan.php");
    exit;
} else {
    echo "<h3>Gagal menghapus data!</h3>";
    echo mysqli_error($conn);
}

?>

