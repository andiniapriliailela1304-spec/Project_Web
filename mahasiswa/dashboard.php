<?php
session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";


// Cek role mahasiswa
if ($_SESSION['role'] !== 'mahasiswa') {

    header("Location: ../auth/login.php");
    exit;

}


$id_user = $_SESSION['id_user'];


// Total booking mahasiswa
$total_booking = mysqli_query($conn,"
    SELECT COUNT(*) AS total 
    FROM booking
    WHERE id_user='$id_user'
");

$total = mysqli_fetch_assoc($total_booking)['total'];


// Booking menunggu
$menunggu = mysqli_query($conn,"
    SELECT COUNT(*) AS jumlah
    FROM booking
    WHERE id_user='$id_user'
    AND status_booking='Menunggu'
");

$jml_menunggu = mysqli_fetch_assoc($menunggu)['jumlah'];


// Booking disetujui
$disetujui = mysqli_query($conn,"
    SELECT COUNT(*) AS jumlah
    FROM booking
    WHERE id_user='$id_user'
    AND status_booking='Disetujui'
");

$jml_disetujui = mysqli_fetch_assoc($disetujui)['jumlah'];


// Booking ditolak
$ditolak = mysqli_query($conn,"
    SELECT COUNT(*) AS jumlah
    FROM booking
    WHERE id_user='$id_user'
    AND status_booking='Ditolak'
");

$jml_ditolak = mysqli_fetch_assoc($ditolak)['jumlah'];


// Booking terbaru
$booking = mysqli_query($conn,"
SELECT 
booking.*,
ruangan.nama_ruangan,
ruangan.gedung

FROM booking

JOIN ruangan
ON booking.id_ruangan = ruangan.id_ruangan

WHERE booking.id_user='$id_user'

ORDER BY id_booking DESC

LIMIT 5
");

?>



<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard Mahasiswa | StudyRoom</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">


<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet" href="../assets/css/dashboard.css">
<link rel="stylesheet" href="../assets/css/sidebar2.css">

</head>


<body>


<?php include "../includes/sidebar_mahasiswa.php"; ?>


<div class="main-content">


<?php include "../includes/navbar_mahasiswa.php"; ?>


<div class="content">


<div class="page-header">


<h3>
Dashboard Mahasiswa
</h3>


<p class="text-muted">
Selamat datang, <?= $_SESSION['username']; ?>
</p>


</div>




<div class="row g-4">



<div class="col-md-3">


<div class="card shadow-sm border-0">


<div class="card-body">


<div class="d-flex justify-content-between">


<div>

<h6>
Total Booking
</h6>

<h3>
<?= $total; ?>
</h3>

</div>


<i class="bi bi-calendar-check fs-1 text-primary"></i>


</div>


</div>


</div>


</div>





<div class="col-md-3">


<div class="card shadow-sm border-0">


<div class="card-body">


<div class="d-flex justify-content-between">


<div>

<h6>
Menunggu
</h6>

<h3>
<?= $jml_menunggu; ?>
</h3>

</div>


<i class="bi bi-hourglass-split fs-1 text-warning"></i>


</div>


</div>


</div>


</div>





<div class="col-md-3">


<div class="card shadow-sm border-0">


<div class="card-body">


<div class="d-flex justify-content-between">


<div>

<h6>
Disetujui
</h6>

<h3>
<?= $jml_disetujui; ?>
</h3>

</div>


<i class="bi bi-check-circle fs-1 text-success"></i>


</div>


</div>


</div>


</div>





<div class="col-md-3">


<div class="card shadow-sm border-0">


<div class="card-body">


<div class="d-flex justify-content-between">


<div>

<h6>
Ditolak
</h6>

<h3>
<?= $jml_ditolak; ?>
</h3>

</div>


<i class="bi bi-x-circle fs-1 text-danger"></i>


</div>


</div>


</div>


</div>


</div>





<br>





<div class="card shadow-sm">


<div class="card-header">


<h5>
Booking Terbaru
</h5>


</div>


<div class="card-body">


<div class="table-responsive">


<table class="table table-hover">


<thead>

<tr>

<th>No</th>

<th>Ruangan</th>

<th>Tanggal</th>

<th>Jam</th>

<th>Status</th>

</tr>

</thead>


<tbody>


<?php


$no=1;


while($row=mysqli_fetch_assoc($booking)){


?>


<tr>


<td>
<?= $no++; ?>
</td>


<td>

<?= $row['nama_ruangan']; ?>

<br>

<small class="text-muted">

<?= $row['gedung']; ?>

</small>

</td>


<td>

<?= $row['tanggal']; ?>

</td>


<td>

<?= $row['jam_mulai']; ?>

-

<?= $row['jam_selesai']; ?>

</td>


<td>


<?php if($row['status_booking']=="Disetujui"){ ?>


<span class="badge bg-success">
Disetujui
</span>


<?php }elseif($row['status_booking']=="Ditolak"){ ?>


<span class="badge bg-danger">
Ditolak
</span>


<?php }else{ ?>


<span class="badge bg-warning">
Menunggu
</span>


<?php } ?>


</td>


</tr>


<?php } ?>


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