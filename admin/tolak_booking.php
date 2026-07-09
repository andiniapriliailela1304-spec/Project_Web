<?php
session_start();
require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";
require_once "../includes/ruangan_helper.php";

if ($_SESSION['role'] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: booking.php");
    exit;
}

$id_booking = intval($_GET['id']);
$id_admin   = intval($_SESSION['id_user']);

// ===============================
// AMBIL DATA BOOKING
// ===============================
$stmtData = mysqli_prepare($conn, "
    SELECT * FROM booking WHERE id_booking = ?
");
mysqli_stmt_bind_param($stmtData, "i", $id_booking);
mysqli_stmt_execute($stmtData);
$data = mysqli_stmt_get_result($stmtData);
$booking = mysqli_fetch_assoc($data);
mysqli_stmt_close($stmtData);

if (!$booking) {
    die("Booking tidak ditemukan.");
}

$id_ruangan = (int) $booking['id_ruangan'];

// ===============================
// UPDATE BOOKING -> DITOLAK
// ===============================
$stmtUpdate = mysqli_prepare($conn, "
    UPDATE booking
    SET status_booking = 'Ditolak'
    WHERE id_booking = ?
");
mysqli_stmt_bind_param($stmtUpdate, "i", $id_booking);
mysqli_stmt_execute($stmtUpdate);
mysqli_stmt_close($stmtUpdate);

// ===============================
// SINKRONISASI STATUS RUANGAN
// ===============================
// TIDAK langsung set 'Tersedia' begitu saja — kalau ternyata ruangan ini
// sedang dipakai oleh booking LAIN yang statusnya 'Disetujui' dan aktif
// saat ini, status ruangan akan tetap 'Booking', bukan tertimpa jadi
// 'Tersedia' secara keliru.
sinkronisasi_status_ruangan($conn, $id_ruangan);

// ===============================
// SIMPAN RIWAYAT VERIFIKASI
// ===============================
$stmtVerif = mysqli_prepare($conn, "
    INSERT INTO verifikasi (id_booking, id_admin, waktu_verifikasi, status_verifikasi)
    VALUES (?, ?, NOW(), 'Belum Diverifikasi')
");
mysqli_stmt_bind_param($stmtVerif, "ii", $id_booking, $id_admin);
mysqli_stmt_execute($stmtVerif);
mysqli_stmt_close($stmtVerif);

header("Location: booking.php");
exit;