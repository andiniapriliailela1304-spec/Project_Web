<?php

session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";


if ($_SESSION['role'] !== 'admin') {
    header("location:../login.php");
    exit;
}


// ambil data ruangan
$query = mysqli_query($conn,"
    SELECT * FROM ruangan
    ORDER BY id_ruangan DESC
");


?>


<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">


<title>Data Ruangan | StudyRoom</title>


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



<div class="page-header d-flex justify-content-between align-items-center">


<div>

<h3>
Data Ruangan
</h3>

<p class="text-muted">
Kelola data ruangan StudyRoom
</p>

</div>



<button 
class="btn btn-success"
data-bs-toggle="modal"
data-bs-target="#modalTambah">

<i class="bi bi-plus-circle"></i>
Tambah Ruangan

</button>


</div>




<div class="card shadow-sm">


<div class="card-body">


<div class="table-responsive">


<table class="table table-hover align-middle">


<thead>

<tr>

<th>No</th>

<th>Foto</th>

<th>Nama Ruangan</th>

<th>Gedung</th>

<th>Kapasitas</th>

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


<?php if($row['foto'] != ""){ ?>


<img src="../uploads/<?= $row['foto']; ?>"
width="70"
height="50"
style="object-fit:cover;border-radius:8px;">


<?php }else{ ?>


<span class="text-muted">
Tidak ada
</span>


<?php } ?>


</td>




<td>

<b>
<?= $row['nama_ruangan']; ?>
</b>

</td>




<td>

<?= $row['gedung']; ?>

</td>




<td>

<?= $row['kapasitas']; ?> Orang

</td>

<td>


<?php if($row['status']=="Tersedia"){ ?>


<span class="badge bg-success">

<i class="bi bi-check-circle"></i>
Tersedia

</span>


<?php }else{ ?>


<span class="badge bg-danger">

<i class="bi bi-door-open"></i>
Digunakan

</span>


<?php } ?>


</td>





<td>


<a href="edit_ruangan.php?id=<?= $row['id_ruangan']; ?>"
class="btn btn-warning btn-sm">

<i class="bi bi-pencil"></i>

</a>



<a href="hapus_ruangan.php?id=<?= $row['id_ruangan']; ?>"
onclick="return confirm('Hapus ruangan ini?')"
class="btn btn-danger btn-sm">

<i class="bi bi-trash"></i>

</a>


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





<!-- MODAL TAMBAH RUANGAN -->


<div class="modal fade" id="modalTambah">


<div class="modal-dialog">


<div class="modal-content">


<form action="proses_tambah_ruangan.php" 
method="POST"
enctype="multipart/form-data">



<div class="modal-header">


<h5 class="modal-title">

Tambah Ruangan

</h5>


<button type="button"
class="btn-close"
data-bs-dismiss="modal">

</button>


</div>





<div class="modal-body">



<div class="mb-3">


<label>
Nama Ruangan
</label>


<input 
type="text"
name="nama_ruangan"
class="form-control"
required>


</div>




<div class="mb-3">


<label>
Foto Ruangan
</label>


<input 
type="file"
name="foto"
class="form-control">


</div>





<div class="mb-3">


<label>
Gedung
</label>


<select 
name="gedung"
class="form-select"
required>


<option value="">
-- Pilih Gedung --
</option>


<option value="Gedung D">
Gedung D
</option>


<option value="Gedung E">
Gedung E
</option>


</select>


</div>





<div class="mb-3">


<label>
Kapasitas
</label>


<input 
type="number"
name="kapasitas"
class="form-control"
required>


</div>
</div>





<div class="modal-footer">


<button 
type="submit"
name="tambah"
class="btn btn-success">

<i class="bi bi-save"></i>

Simpan

</button>


</div>



</form>


</div>


</div>


</div>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>


</body>

</html>