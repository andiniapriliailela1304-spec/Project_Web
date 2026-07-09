<?php

session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";

if($_SESSION['role']!="admin"){

    header("Location: ../auth/login.php");
    exit;

}

$id_booking = intval($_GET['id']);

$id_admin = $_SESSION['id_user'];

$data = mysqli_query($conn,"
SELECT *
FROM booking
WHERE id_booking='$id_booking'
");

$booking = mysqli_fetch_assoc($data);

if(!$booking){

    die("Booking tidak ditemukan.");

}

$id_ruangan = $booking['id_ruangan'];


// Booking ditolak

mysqli_query($conn,"
UPDATE booking
SET status_booking='Ditolak'
WHERE id_booking='$id_booking'
");


// Ruangan tetap tersedia

mysqli_query($conn,"
UPDATE ruangan
SET status='Tersedia'
WHERE id_ruangan='$id_ruangan'
");


// Simpan riwayat verifikasi

mysqli_query($conn,"
INSERT INTO verifikasi
(

id_booking,

id_admin,

waktu_verifikasi,

status_verifikasi

)

VALUES
(

'$id_booking',

'$id_admin',

NOW(),

'Belum Diverifikasi'

)
");

header("Location: booking.php");
exit;

?>