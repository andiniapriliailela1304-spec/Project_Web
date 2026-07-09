<?php

session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";


// ==============================
// CEK LOGIN MAHASISWA
// ==============================

if($_SESSION['role'] != "mahasiswa"){

    header("Location: ../auth/login.php");
    exit;

}


// ==============================
// AMBIL DATA BOOKING
// ==============================

$id_user = $_SESSION['id_user'];

$query = mysqli_query($conn, "

SELECT

booking.*,

ruangan.nama_ruangan,

ruangan.gedung

FROM booking

JOIN ruangan

ON booking.id_ruangan = ruangan.id_ruangan

WHERE booking.id_user='$id_user'

ORDER BY booking.id_booking DESC

");

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Riwayat Booking | StudyRoom</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="../assets/css/style.css">

<link rel="stylesheet"
href="../assets/css/dashboard.css">
<link rel="stylesheet" href="../assets/css/sidebar2.css">
</head>

<body>

<?php include "../includes/sidebar_mahasiswa.php"; ?>

<div class="main-content">

<?php include "../includes/navbar_mahasiswa.php"; ?>

<div class="content">

<div class="page-header">

<h3>

Riwayat Booking

</h3>

<p class="text-muted">

Daftar seluruh riwayat peminjaman ruangan Anda.

</p>

</div>

<div class="card shadow-sm">

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>No</th>

<th>Ruangan</th>

<th>Tanggal</th>

<th>Waktu</th>

<th>Tujuan</th>

<th>Status</th>

<th>Kode Booking</th>

</tr>

</thead>

<tbody>

<?php

$no = 1;

while($row = mysqli_fetch_assoc($query)){

?>
<tr>

<td>

<?= $no++; ?>

</td>



<td>

<b>

<?= htmlspecialchars($row['nama_ruangan']); ?>

</b>

<br>

<small class="text-muted">

<?= htmlspecialchars($row['gedung']); ?>

</small>

</td>



<td>

<?= date('d-m-Y', strtotime($row['tanggal'])); ?>

</td>



<td>

<?= substr($row['jam_mulai'],0,5); ?>

-

<?= substr($row['jam_selesai'],0,5); ?>

</td>



<td>

<?= htmlspecialchars($row['tujuan']); ?>

</td>



<td>

<?php

if($row['status_booking']=="Disetujui"){

?>

<span class="badge bg-success">

<i class="bi bi-check-circle-fill"></i>

Disetujui

</span>

<?php

}elseif($row['status_booking']=="Ditolak"){

?>

<span class="badge bg-danger">

<i class="bi bi-x-circle-fill"></i>

Ditolak

</span>

<?php

}else{

?>

<span class="badge bg-warning text-dark">

<i class="bi bi-hourglass-split"></i>

Menunggu

</span>

<?php

}

?>

</td>



<td>
    <?php

if($row['status_booking']=="Disetujui"){

    if(!empty($row['kode_booking'])){

?>

<span class="badge bg-primary fs-6">

    <i class="bi bi-ticket-perforated-fill"></i>

    <?= htmlspecialchars($row['kode_booking']); ?>

</span>

<?php

    }else{

?>

<span class="text-muted">

Belum dibuat

</span>

<?php

    }

}elseif($row['status_booking']=="Menunggu"){

?>

<span class="text-warning">

<i class="bi bi-hourglass-split"></i>

Menunggu Persetujuan

</span>

<?php

}else{

?>

<span class="text-danger">

<i class="bi bi-x-circle"></i>

Tidak tersedia

</span>

<?php

}

?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>

</body>

</html>