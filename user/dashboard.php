<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['role'] != "user") {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard User | StudyRoom</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="bg-light">

<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-success">

    <div class="container">

        <a class="navbar-brand fw-bold" href="#">

            StudyRoom

        </a>

        <div class="dropdown">

            <button class="btn btn-success dropdown-toggle"
                data-bs-toggle="dropdown">

                <i class="bi bi-person-circle"></i>

                <?= $_SESSION['nama']; ?>

            </button>

            <ul class="dropdown-menu dropdown-menu-end">

                <li>

                    <a class="dropdown-item" href="profil.php">

                        <i class="bi bi-person"></i>

                        Profil

                    </a>

                </li>

                <li>

                    <a class="dropdown-item text-danger"
                        href="../auth/logout.php">

                        <i class="bi bi-box-arrow-right"></i>

                        Logout

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

<!-- Content -->

<div class="container mt-5">

    <h3>

        Halo, <?= $_SESSION['nama']; ?> 👋

    </h3>

    <p class="text-muted">

        Selamat datang di Sistem Reservasi Ruang Belajar

    </p>

    <!-- Card Statistik -->

    <div class="row mt-4">

        <div class="col-md-4 mb-3">

            <div class="card shadow border-0">

                <div class="card-body">

                    <h6 class="text-muted">

                        Total Booking

                    </h6>

                    <h2>

                        0

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card shadow border-0">

                <div class="card-body">

                    <h6 class="text-muted">

                        Booking Aktif

                    </h6>

                    <h2>

                        0

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card shadow border-0">

                <div class="card-body">

                    <h6 class="text-muted">

                        Status Akun

                    </h6>

                    <h2 class="text-success">

                        User

                    </h2>

                </div>

            </div>

        </div>

    </div>

    <!-- Menu -->

    <div class="row mt-4">

        <div class="col-md-3 mb-3">

            <a href="ruangan.php"
                class="btn btn-success w-100 p-3">

                <i class="bi bi-door-open fs-2"></i>

                <br>

                Cari Ruangan

            </a>

        </div>

        <div class="col-md-3 mb-3">

            <a href="booking.php"
                class="btn btn-primary w-100 p-3">

                <i class="bi bi-calendar-check fs-2"></i>

                <br>

                Booking Saya

            </a>

        </div>

        <div class="col-md-3 mb-3">

            <a href="riwayat.php"
                class="btn btn-warning w-100 p-3">

                <i class="bi bi-clock-history fs-2"></i>

                <br>

                Riwayat

            </a>

        </div>

        <div class="col-md-3 mb-3">

            <a href="../auth/logout.php"
                class="btn btn-danger w-100 p-3">

                <i class="bi bi-box-arrow-right fs-2"></i>

                <br>

                Logout

            </a>

        </div>

    </div>

    <!-- Informasi -->

    <div class="card shadow border-0 mt-4">

        <div class="card-header bg-success text-white">

            Informasi

        </div>

        <div class="card-body">

            Selamat datang di aplikasi <b>StudyRoom</b>.

            Gunakan menu di atas untuk melakukan reservasi ruang belajar,
            melihat riwayat booking, dan mengelola profil akun Anda.

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>