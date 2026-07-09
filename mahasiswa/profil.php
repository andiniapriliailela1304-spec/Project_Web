<?php

session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";


// ================================
// CEK LOGIN
// ================================

if ($_SESSION['role'] != "mahasiswa") {

    header("Location: ../auth/login.php");
    exit;

}


// ================================
// AMBIL DATA MAHASISWA
// ================================

$id_user = $_SESSION['id_user'];

$query = mysqli_query($conn, "
SELECT *
FROM users
WHERE id_user='$id_user'
");

$user = mysqli_fetch_assoc($query);


// ================================
// FOTO PROFIL
// ================================

$foto = "../uploads/profile/default.png";

if (!empty($user['foto']) && file_exists("../uploads/profile/" . $user['foto'])) {

    $foto = "../uploads/profile/" . $user['foto'];

}

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Profil Mahasiswa | StudyRoom</title>


<!-- Bootstrap -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
rel="stylesheet">


<!-- CSS -->

<link rel="stylesheet"
href="../assets/css/style.css">

<link rel="stylesheet"
href="../assets/css/dashboard.css">

<link rel="stylesheet"
href="../assets/css/profil.css">
<link rel="stylesheet" href="../assets/css/sidebar2.css">
</head>

<body>


<?php include "../includes/sidebar_mahasiswa.php"; ?>


<div class="main-content">


<?php include "../includes/navbar_mahasiswa.php"; ?>


<div class="content">


<div class="page-header">

<h3>

Profil Mahasiswa

</h3>

<p class="text-muted">

Kelola informasi akun Anda.

</p>

</div>




<div class="card shadow border-0">

<div class="card-body p-3 p-md-5">

<div class="row">


<!-- FOTO PROFIL -->

<div class="col-lg-4 text-center">


<img

src="<?= $foto; ?>"

class="rounded-circle shadow"

style="

width:180px;
height:180px;
object-fit:cover;
border:5px solid #0d6efd;

">


<h4 class="mt-4 fw-bold">

<?= htmlspecialchars($user['nama_lengkap']); ?>

</h4>

<p class="text-muted">

Mahasiswa

</p>


<button

type="button"

class="btn btn-primary mt-2"

data-bs-toggle="modal"

data-bs-target="#modalEdit">

<i class="bi bi-pencil-square"></i>

Edit Profil

</button>

</div>



<!-- BIODATA -->

<div class="col-lg-8">


<div class="card border-0 shadow-sm">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="bi bi-person-vcard"></i>

Biodata Mahasiswa

</h5>

</div>

<div class="card-body">

<div class="table-responsive">
<table class="table table-borderless align-middle">
    <tr>

    <th width="230">

        <i class="bi bi-person-fill text-primary"></i>

        Nama Lengkap

    </th>

    <td>

        <?= htmlspecialchars($user['nama_lengkap']); ?>

    </td>

</tr>



<tr>

    <th>

        <i class="bi bi-person-badge-fill text-primary"></i>

        Username (NIM)

    </th>

    <td>

        <?= htmlspecialchars($user['username']); ?>

    </td>

</tr>



<tr>

    <th>

        <i class="bi bi-envelope-fill text-primary"></i>

        Email

    </th>

    <td>

        <?= htmlspecialchars($user['email']); ?>

    </td>

</tr>



<tr>

    <th>

        <i class="bi bi-mortarboard-fill text-primary"></i>

        Program Studi

    </th>

    <td>

        <?= htmlspecialchars($user['prodi']); ?>

    </td>

</tr>



<tr>

    <th>

        <i class="bi bi-shield-check text-primary"></i>

        Role

    </th>

    <td>

        <span class="badge bg-success">

            <?= ucfirst($user['role']); ?>

        </span>

    </td>

</tr>



<tr>

    <th>

        <i class="bi bi-check-circle-fill text-primary"></i>

        Status Akun

    </th>

    <td>

        <span class="badge bg-primary">

            Aktif

        </span>

    </td>

</tr>

</table>
</div>


</div>

</div>

</div>

</div>

</div>

</div>
<!-- ========================================= -->
<!-- MODAL EDIT PROFIL -->
<!-- ========================================= -->

<div class="modal fade" id="modalEdit" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form
            action="proses_update_profil.php"
            method="POST"
            enctype="multipart/form-data">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        <i class="bi bi-pencil-square"></i>

                        Edit Profil

                    </h5>

                    <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"></button>

                </div>



                <div class="modal-body">

                    <div class="row">


                        <!-- FOTO -->

                        <div class="col-md-4 text-center">

                            <img

                            id="preview"

                            src="<?= $foto; ?>"

                            class="rounded-circle shadow"

                            style="
                            width:180px;
                            height:180px;
                            object-fit:cover;
                            border:4px solid #0d6efd;
                            ">

                            <div class="mt-3">

                                <label class="form-label fw-semibold">

                                    Foto Profil

                                </label>

                                <input

                                type="file"

                                name="foto"

                                id="foto"

                                class="form-control"

                                accept="image/*"

                                onchange="previewFoto(event)">

                            </div>

                        </div>



                        <!-- FORM -->

                        <div class="col-md-8">


                            <div class="mb-3">

                                <label class="form-label">

                                    Nama Lengkap

                                </label>

                                <input

                                type="text"

                                name="nama_lengkap"

                                class="form-control"

                                value="<?= htmlspecialchars($user['nama_lengkap']); ?>"

                                required>

                            </div>



                            <div class="mb-3">

                                <label class="form-label">

                                    Username (NIM)

                                </label>

                                <input

                                type="text"

                                class="form-control"

                                value="<?= htmlspecialchars($user['username']); ?>"

                                readonly>

                            </div>



                            <div class="mb-3">

                                <label class="form-label">

                                    Email

                                </label>

                                <input

                                type="email"

                                name="email"

                                class="form-control"

                                value="<?= htmlspecialchars($user['email']); ?>"

                                required>

                            </div>



                            <div class="row">

                                <div class="col-md-6">

                                    <label class="form-label">

                                        Password Baru

                                    </label>

                                    <div class="input-group">

                                        <input

                                        type="password"

                                        name="password"

                                        id="password"

                                        class="form-control"

                                        placeholder="Kosongkan jika tidak diganti">

                                        <button

                                        type="button"

                                        class="btn btn-outline-secondary"

                                        onclick="showPassword('password',this)">

                                            <i class="bi bi-eye"></i>

                                        </button>

                                    </div>

                                </div>



                                <div class="col-md-6">

                                    <label class="form-label">

                                        Konfirmasi Password

                                    </label>

                                    <div class="input-group">

                                        <input

                                        type="password"

                                        name="konfirmasi"

                                        id="konfirmasi"

                                        class="form-control"

                                        placeholder="Ulangi password">

                                        <button

                                        type="button"

                                        class="btn btn-outline-secondary"

                                        onclick="showPassword('konfirmasi',this)">

                                            <i class="bi bi-eye"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <div class="modal-footer">

                    <button

                    type="button"

                    class="btn btn-secondary"

                    data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button

                    type="submit"

                    class="btn btn-primary">

                        <i class="bi bi-save"></i>

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>

<script>

// =====================================
// PREVIEW FOTO
// =====================================

function previewFoto(event){

    const preview = document.getElementById("preview");

    preview.src = URL.createObjectURL(event.target.files[0]);

}



// =====================================
// SHOW / HIDE PASSWORD
// =====================================

function showPassword(id,button){

    let input = document.getElementById(id);

    let icon = button.querySelector("i");

    if(input.type === "password"){

        input.type = "text";

        icon.classList.remove("bi-eye");

        icon.classList.add("bi-eye-slash");

    }else{

        input.type = "password";

        icon.classList.remove("bi-eye-slash");

        icon.classList.add("bi-eye");

    }

}

</script>

</body>

</html>