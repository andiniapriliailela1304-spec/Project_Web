<?php

session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";


if($_SESSION['role'] != "mahasiswa"){

    header("Location: ../auth/login.php");
    exit;

}


// cek id ruangan

if(!isset($_GET['id'])){

    header("Location: ruangan.php");
    exit;

}


$id_ruangan = $_GET['id'];


// ambil data ruangan

$query = mysqli_query($conn,"
    SELECT * FROM ruangan
    WHERE id_ruangan='$id_ruangan'
");


$ruangan = mysqli_fetch_assoc($query);


if(!$ruangan){

    die("Data ruangan tidak ditemukan.");

}

?>


<!DOCTYPE html>
<html lang="id">


<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Booking Ruangan | StudyRoom</title>


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
Booking Ruangan
</h3>

<p class="text-muted">
Ajukan peminjaman ruangan StudyRoom
</p>

</div>




<div class="card shadow-sm">


<div class="card-body">



<form action="proses_booking.php" method="POST">



<input type="hidden"
name="id_ruangan"
value="<?= $ruangan['id_ruangan']; ?>">





<div class="row">



<div class="col-md-5">


<?php if($ruangan['foto']!=""){ ?>


<img src="../uploads/<?= $ruangan['foto']; ?>"
class="img-fluid rounded"
style="height:250px;width:100%;object-fit:cover;">


<?php }else{ ?>


<img src="../assets/img/no-image.png"
class="img-fluid rounded">


<?php } ?>


</div>





<div class="col-md-7">



<h4>

<?= $ruangan['nama_ruangan']; ?>

</h4>


<p>

<i class="bi bi-building"></i>

<?= $ruangan['gedung']; ?>

</p>


<p>

<i class="bi bi-people"></i>

Kapasitas :
<?= $ruangan['kapasitas']; ?> Orang

</p>



<hr>





<div class="mb-3">


<label class="form-label">

Tanggal Booking

</label>


<input type="date"
name="tanggal"
class="form-control"
required>


</div>





<div class="row">



<div class="col-md-6">


<label class="form-label">

Jam Mulai

</label>


<input type="time"
name="jam_mulai"
class="form-control"
required>


</div>





<div class="col-md-6">


<label class="form-label">

Jam Selesai

</label>


<input type="time"
name="jam_selesai"
class="form-control"
required>


</div>


</div>






<div class="mb-3 mt-3">


<label class="form-label">

Tujuan Penggunaan

</label>


<textarea
name="tujuan"
class="form-control"
rows="3"
placeholder="Contoh: Diskusi tugas kelompok"
required></textarea>


</div>





<button type="submit"
class="btn btn-success">


<i class="bi bi-calendar-check"></i>

Ajukan Booking


</button>



<a href="ruangan.php"
class="btn btn-secondary">

Kembali

</a>



</div>


</div>


</form>


</div>


</div>


</div>


</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>



</body>


</html>