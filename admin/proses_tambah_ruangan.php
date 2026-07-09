<?php
session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";

// Cek apakah admin sudah login
if ($_SESSION['role'] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $nama_ruangan = mysqli_real_escape_string($conn, $_POST['nama_ruangan']);
    $gedung       = mysqli_real_escape_string($conn, $_POST['gedung']);
    $kapasitas    = mysqli_real_escape_string($conn, $_POST['kapasitas']);

    // Status default
    $status = "Tersedia";

    // Upload Foto
    $foto = "";

    if (!empty($_FILES['foto']['name'])) {

        $folder = "../uploads/";

        // Buat folder uploads jika belum ada
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $namaFile = time() . "_" . basename($_FILES['foto']['name']);

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $namaFile)) {
            $foto = $namaFile;
        }
    }

    // Simpan data ke database
    $query = mysqli_query($conn, "
        INSERT INTO ruangan
        (
            nama_ruangan,
            foto,
            gedung,
            kapasitas,
            status
        )
        VALUES
        (
            '$nama_ruangan',
            '$foto',
            '$gedung',
            '$kapasitas',
            '$status'
        )
    ");

    if ($query) {

        header("Location: ruangan.php");
        exit;

    } else {

        echo "<h3>Gagal menambahkan data!</h3>";
        echo mysqli_error($conn);

    }
}
?>