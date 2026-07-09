<?php

session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";


if ($_SESSION['role'] !== 'admin') {

    header("Location: ../auth/login.php");
    exit;

}


$query = mysqli_query($conn,"

SELECT 

booking.*,

users.nama_lengkap,

ruangan.nama_ruangan,

ruangan.gedung


FROM booking


JOIN users

ON booking.id_user = users.id_user


JOIN ruangan

ON booking.id_ruangan = ruangan.id_ruangan


ORDER BY booking.id_booking DESC


");


?>


<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">


<title>Data Booking</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">


<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet" href="../assets/css/dashboard.css">
<link rel="stylesheet" href="../assets/css/sidebar2.css">

</head>


<body>


<?php include "../includes/sidebar_admin.php"; ?>


<div class="main-content">


<?php include "../includes/navbar_admin.php"; ?>


<div class="content">


<div class="page-header">


<h3>
Data Booking
</h3>


<p class="text-muted">
Kelola persetujuan peminjaman ruangan.
</p>


</div>



<div class="card shadow-sm">


<div class="card-body">


<div class="table-responsive">


<table class="table table-hover">


<thead>

<tr>

<th>No</th>

<th>Mahasiswa</th>

<th>Ruangan</th>

<th>Tanggal</th>

<th>Waktu</th>

<th>Status</th>

<th>Aksi</th>

</tr>

</thead>


<tbody>


<?php

$no=1;


while($row=mysqli_fetch_assoc($query)){


?>


<tr>


<td>
<?= $no++; ?>
</td>


<td>

<?= htmlspecialchars($row['nama_lengkap']); ?>

</td>


<td>

<b>
<?= htmlspecialchars($row['nama_ruangan']); ?>
</b>

<br>

<small>
<?= htmlspecialchars($row['gedung']); ?>
</small>

</td>


<td>

<?= date('d-m-Y',strtotime($row['tanggal'])); ?>

</td>


<td>

<?= substr($row['jam_mulai'],0,5); ?>

-

<?= substr($row['jam_selesai'],0,5); ?>

</td>


<td>


<?php if($row['status_booking']=="Menunggu"){ ?>


<span class="badge bg-warning text-dark">

Menunggu

</span>


<?php }elseif($row['status_booking']=="Disetujui"){ ?>


<span class="badge bg-success">

Disetujui

</span>


<?php }else{ ?>


<span class="badge bg-danger">

Ditolak

</span>


<?php } ?>


</td>



<td>


<?php if($row['status_booking']=="Menunggu"){ ?>


<a href="setujui_booking.php?id=<?= $row['id_booking']; ?>"
class="btn btn-success btn-sm">

<i class="bi bi-check-circle"></i>

</a>



<a href="tolak_booking.php?id=<?= $row['id_booking']; ?>"
class="btn btn-danger btn-sm">

<i class="bi bi-x-circle"></i>

</a>


<?php }else{ ?>


<span class="text-muted">

Selesai

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