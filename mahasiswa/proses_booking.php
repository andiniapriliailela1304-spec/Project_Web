<?php

session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";

// ==============================
// CEK LOGIN
// ==============================

if ($_SESSION['role'] != "mahasiswa") {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ruangan.php");
    exit;
}

$id_user     = $_SESSION['id_user'];
$id_ruangan  = intval($_POST['id_ruangan']);
$tanggal     = $_POST['tanggal'];
$jam_mulai   = $_POST['jam_mulai'];
$jam_selesai = $_POST['jam_selesai'];
$tujuan      = trim($_POST['tujuan']);

// ==============================
// VALIDASI FORMAT INPUT
// ==============================

// Tanggal harus format Y-m-d yang valid
$tglObj = DateTime::createFromFormat('Y-m-d', $tanggal);
if (!$tglObj || $tglObj->format('Y-m-d') !== $tanggal) {
    echo "<script>
    alert('Format tanggal tidak valid.');
    window.history.back();
    </script>";
    exit;
}

// Jam harus format H:i yang valid
if (!preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $jam_mulai) ||
    !preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $jam_selesai)) {
    echo "<script>
    alert('Format jam tidak valid.');
    window.history.back();
    </script>";
    exit;
}

// Normalisasi ke H:i:s supaya konsisten dengan kolom TIME di DB
$jam_mulai   = $jam_mulai . ":00";
$jam_selesai = $jam_selesai . ":00";

if (empty($tujuan)) {
    echo "<script>
    alert('Tujuan penggunaan wajib diisi.');
    window.history.back();
    </script>";
    exit;
}

// ==============================
// VALIDASI TANGGAL TIDAK BOLEH DI MASA LALU
// ==============================

if (strtotime($tanggal) < strtotime(date("Y-m-d"))) {
    echo "<script>
    alert('Tanggal booking tidak boleh sebelum hari ini.');
    window.history.back();
    </script>";
    exit;
}

// ==============================
// VALIDASI JAM MULAI < JAM SELESAI
// ==============================

if ($jam_mulai >= $jam_selesai) {
    echo "<script>
    alert('Jam selesai harus lebih besar dari jam mulai.');
    window.history.back();
    </script>";
    exit;
}

// Kalau tanggal booking = hari ini, jam mulai tidak boleh sudah lewat
if ($tanggal === date("Y-m-d") && $jam_mulai < date("H:i:s")) {
    echo "<script>
    alert('Jam mulai sudah lewat untuk hari ini.');
    window.history.back();
    </script>";
    exit;
}

// ==============================
// PASTIKAN RUANGAN ADA
// ==============================

$stmtRuangan = mysqli_prepare($conn, "SELECT id_ruangan FROM ruangan WHERE id_ruangan = ?");
mysqli_stmt_bind_param($stmtRuangan, "i", $id_ruangan);
mysqli_stmt_execute($stmtRuangan);
$hasilRuangan = mysqli_stmt_get_result($stmtRuangan);

if (mysqli_num_rows($hasilRuangan) === 0) {
    echo "<script>
    alert('Ruangan tidak ditemukan.');
    window.location='ruangan.php';
    </script>";
    exit;
}

// ==============================
// CEK BENTROK JADWAL
// ==============================
// Bentrok terjadi jika ada booking lain (Menunggu / Disetujui) di ruangan
// & tanggal yang sama, dengan rentang jam yang tumpang tindih.
// Rumus overlap: existing.jam_mulai < baru.jam_selesai
//            DAN existing.jam_selesai > baru.jam_mulai

$stmtBentrok = mysqli_prepare($conn, "
    SELECT id_booking
    FROM booking
    WHERE id_ruangan = ?
      AND tanggal = ?
      AND status_booking IN ('Menunggu', 'Disetujui')
      AND jam_mulai < ?
      AND jam_selesai > ?
    LIMIT 1
");
mysqli_stmt_bind_param(
    $stmtBentrok,
    "isss",
    $id_ruangan,
    $tanggal,
    $jam_selesai,
    $jam_mulai
);
mysqli_stmt_execute($stmtBentrok);
$hasilBentrok = mysqli_stmt_get_result($stmtBentrok);

if (mysqli_num_rows($hasilBentrok) > 0) {
    echo "<script>
    alert('Ruangan sudah dibooking pada tanggal dan jam tersebut. Silakan pilih jam lain.');
    window.history.back();
    </script>";
    exit;
}

// ==============================
// SIMPAN BOOKING
// ==============================

$stmtInsert = mysqli_prepare($conn, "
    INSERT INTO booking
        (id_user, id_ruangan, tanggal, jam_mulai, jam_selesai, tujuan, status_booking)
    VALUES
        (?, ?, ?, ?, ?, ?, 'Menunggu')
");
mysqli_stmt_bind_param(
    $stmtInsert,
    "iissss",
    $id_user,
    $id_ruangan,
    $tanggal,
    $jam_mulai,
    $jam_selesai,
    $tujuan
);

if (mysqli_stmt_execute($stmtInsert)) {
    echo "<script>
    alert('Booking berhasil dikirim. Menunggu persetujuan admin.');
    window.location='riwayat.php';
    </script>";
} else {
    echo "<script>
    alert('Gagal menyimpan booking. Silakan coba lagi.');
    window.history.back();
    </script>";
}