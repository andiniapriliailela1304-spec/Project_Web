<?php
session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";

// Cek hak akses admin
if ($_SESSION['role'] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$id = (int) $_GET['id'];

$query = mysqli_query($conn, "SELECT * FROM ruangan WHERE id_ruangan='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data tidak ditemukan.");
}

// Proses Update
if (isset($_POST['update'])) {

    $nama       = mysqli_real_escape_string($conn, $_POST['nama_ruangan']);
    $gedung     = mysqli_real_escape_string($conn, $_POST['gedung']);
    $kapasitas  = mysqli_real_escape_string($conn, $_POST['kapasitas']);
    $status     = mysqli_real_escape_string($conn, $_POST['status']);

    $foto = $data['foto'];

    // Upload foto baru
    if (!empty($_FILES['foto']['name'])) {

        // Hapus foto lama
        if ($foto != "" && file_exists("../uploads/" . $foto)) {
            unlink("../uploads/" . $foto);
        }

        $foto = time() . "_" . basename($_FILES['foto']['name']);

        move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/" . $foto);
    }

    $update = mysqli_query($conn, "
        UPDATE ruangan SET
            nama_ruangan = '$nama',
            foto = '$foto',
            gedung = '$gedung',
            kapasitas = '$kapasitas',
            status = '$status'
        WHERE id_ruangan = '$id'
    ");

    if ($update) {
        header("Location: ruangan.php");
        exit;
    } else {
        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit Ruangan | StudyRoom</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/sidebar2.css">


</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h4 class="mb-0">
<i class="bi bi-pencil-square"></i>
Edit Ruangan
</h4>

</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">
Nama Ruangan
</label>

<input
type="text"
name="nama_ruangan"
class="form-control"
value="<?= htmlspecialchars($data['nama_ruangan']); ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">
Foto Ruangan
</label>

<input
type="file"
name="foto"
class="form-control">

</div>

<div class="mb-3">

<?php if($data['foto'] != ""){ ?>

<img src="../uploads/<?= $data['foto']; ?>"
width="200"
class="img-thumbnail">

<?php } ?>

</div>

<div class="mb-3">

<label class="form-label">
Gedung
</label>

<select name="gedung" class="form-select" required>

<option value="Gedung D" <?= $data['gedung']=="Gedung D" ? "selected" : ""; ?>>
Gedung D
</option>

<option value="Gedung E" <?= $data['gedung']=="Gedung E" ? "selected" : ""; ?>>
Gedung E
</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">
Kapasitas
</label>

<input
type="number"
name="kapasitas"
class="form-control"
value="<?= $data['kapasitas']; ?>"
required>

</div>

<div class="mb-4">

<label class="form-label">
Status
</label>

<select name="status" class="form-select">

<option value="Tersedia" <?= $data['status']=="Tersedia" ? "selected" : ""; ?>>
Tersedia
</option>

<option value="Digunakan" <?= $data['status']=="Digunakan" ? "selected" : ""; ?>>
Digunakan
</option>

</select>

</div>

<button
type="submit"
name="update"
class="btn btn-success">

<i class="bi bi-save"></i>
Simpan Perubahan

</button>

<a href="ruangan.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>
Kembali

</a>

</form>

</div>

</div>

</div>

</body>
</html>