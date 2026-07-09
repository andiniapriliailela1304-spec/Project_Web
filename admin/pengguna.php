<?php
session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";

if($_SESSION['role']!="admin"){
    header("Location: ../auth/login.php");
    exit;
}

$query = mysqli_query($conn,"
    SELECT *
    FROM users
    WHERE role='mahasiswa'
    ORDER BY id_user DESC
");

if(!$query){
    die("Query Error : ".mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data Pengguna | StudyRoom</title>

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
                <h3>Data Pengguna</h3>
                <p class="text-muted">Daftar seluruh mahasiswa yang telah melakukan registrasi.</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Program Studi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                        $no = 1;
                        while($row = mysqli_fetch_assoc($query)){
                            $fotoPath = "../uploads/profile/".$row['foto'];
                            $hasFoto = !empty($row['foto']) && file_exists($fotoPath);
                            $modalId = "detail".$row['id_user'];
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>

                                <td>
                                    <img
                                        src="<?= $hasFoto ? $fotoPath : '../uploads/profile/default.png'; ?>"
                                        width="55"
                                        height="55"
                                        style="border-radius:50%;object-fit:cover;">
                                </td>

                                <td><?= htmlspecialchars($row['username']); ?></td>

                                <td><b><?= htmlspecialchars($row['nama_lengkap']); ?></b></td>

                                <td><?= htmlspecialchars($row['email']); ?></td>

                                <td><?= htmlspecialchars($row['prodi']); ?></td>

                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-info btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#<?= $modalId; ?>">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <a
                                        href="hapus_pengguna.php?id=<?= $row['id_user']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus pengguna ini?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal detail dibuat sekali per user (di luar tbody supaya tabel tetap rapi) -->
        <?php
        // ulang query untuk modal (agar tidak mengganggu iterator tbody)
        $queryModal = mysqli_query($conn,"
            SELECT *
            FROM users
            WHERE role='mahasiswa'
            ORDER BY id_user DESC
        ");

        if($queryModal){
            while($row = mysqli_fetch_assoc($queryModal)){
                $fotoPath = "../uploads/profile/".$row['foto'];
                $hasFoto = !empty($row['foto']) && file_exists($fotoPath);
                $modalId = "detail".$row['id_user'];
        ?>

        <div class="modal fade" id="<?= $modalId; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Detail Pengguna</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">
                        <img
                            src="<?= $hasFoto ? $fotoPath : '../uploads/profile/default.png'; ?>"
                            width="120"
                            height="120"
                            style="border-radius:50%;object-fit:cover;">

                        <hr>

                        <table class="table">
                            <tr>
                                <th>Username</th>
                                <td><?= htmlspecialchars($row['username']); ?></td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                            </tr>
                            <tr>
                                <th>Program Studi</th>
                                <td><?= htmlspecialchars($row['prodi']); ?></td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td><?= ucfirst($row['role']); ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
            }
        }
        ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>

