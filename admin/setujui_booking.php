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
    die("Data booking tidak ditemukan.");
}

// Cuma booking yang masih "Menunggu" yang boleh diapprove.
// Mencegah approve dobel / approve booking yang sudah Ditolak.
if ($booking['status_booking'] !== 'Menunggu') {
    echo "<script>
    alert('Booking ini sudah diproses sebelumnya (status: {$booking['status_booking']}).');
    window.location='booking.php';
    </script>";
    exit;
}

$id_ruangan  = (int) $booking['id_ruangan'];
$tanggal     = $booking['tanggal'];
$jam_mulai   = $booking['jam_mulai'];
$jam_selesai = $booking['jam_selesai'];

// ===============================
// CEK BENTROK DENGAN BOOKING LAIN YANG SUDAH DISETUJUI
// ===============================
// Ini menutup celah: dua booking "Menunggu" di jam sama tidak bisa
// dua-duanya di-approve. Begitu satu disetujui, yang kedua akan
// ditolak otomatis pada tahap ini kalau jamnya tumpang tindih.
$bentrok = cek_bentrok_disetujui(
    $conn,
    $id_ruangan,
    $tanggal,
    $jam_mulai,
    $jam_selesai,
    $id_booking
);

if ($bentrok) {
    echo "<script>
    alert('Tidak bisa disetujui: ruangan sudah punya booking disetujui lain (ID #{$bentrok['id_booking']}) di jam yang sama. Tolak booking ini atau pilih ruangan/jam lain.');
    window.location='booking.php';
    </script>";
    exit;
}

// ===============================
// GENERATE KODE BOOKING
// ===============================
$kode_booking = "BK" . date("Ymd") . sprintf("%03d", $id_booking);

// ===============================
// UPDATE BOOKING -> DISETUJUI
// ===============================
$stmtUpdate = mysqli_prepare($conn, "
    UPDATE booking
    SET status_booking = 'Disetujui', kode_booking = ?
    WHERE id_booking = ?
");
mysqli_stmt_bind_param($stmtUpdate, "si", $kode_booking, $id_booking);
mysqli_stmt_execute($stmtUpdate);
mysqli_stmt_close($stmtUpdate);

// ===============================
// SINKRONISASI STATUS RUANGAN
// ===============================
// TIDAK langsung set 'Booking' begitu saja — status ruangan dihitung
// berdasarkan apakah jam booking ini mencakup waktu SEKARANG.
// Kalau booking untuk besok/nanti, ruangan akan tetap 'Tersedia'
// sampai jamnya benar-benar tiba.
sinkronisasi_status_ruangan($conn, $id_ruangan);

// ===============================
// SIMPAN VERIFIKASI
// ===============================
$stmtVerif = mysqli_prepare($conn, "
    INSERT INTO verifikasi (id_booking, id_admin, waktu_verifikasi, status_verifikasi)
    VALUES (?, ?, NOW(), 'Terverifikasi')
");
mysqli_stmt_bind_param($stmtVerif, "ii", $id_booking, $id_admin);
mysqli_stmt_execute($stmtVerif);
mysqli_stmt_close($stmtVerif);

header("Location: booking.php");
exit;