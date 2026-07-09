<?php

session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";


// =======================================
// CEK LOGIN ADMIN
// =======================================

if ($_SESSION['role'] !== 'admin') {

    header("Location: ../auth/login.php");
    exit;

}



// =======================================
// TOTAL MAHASISWA
// =======================================

$total_mahasiswa = mysqli_fetch_assoc(

    mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM users
        WHERE role='mahasiswa'
    ")

)['total'];



// =======================================
// TOTAL RUANGAN
// =======================================

$total_ruangan = mysqli_fetch_assoc(

    mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM ruangan
    ")

)['total'];



// =======================================
// TOTAL BOOKING
// =======================================

$total_booking = mysqli_fetch_assoc(

    mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM booking
    ")

)['total'];



// =======================================
// BOOKING MENUNGGU
// =======================================

$booking_menunggu = mysqli_fetch_assoc(

    mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM booking
        WHERE status_booking='Menunggu'
    ")

)['total'];



// =======================================
// BOOKING DISETUJUI
// =======================================

$booking_disetujui = mysqli_fetch_assoc(

    mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM booking
        WHERE status_booking='Disetujui'
    ")

)['total'];



// =======================================
// BOOKING DITOLAK
// =======================================

$booking_ditolak = mysqli_fetch_assoc(

    mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM booking
        WHERE status_booking='Ditolak'
    ")

)['total'];



// =======================================
// RUANGAN TERSEDIA
// =======================================

$ruangan_tersedia = mysqli_fetch_assoc(

    mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM ruangan
        WHERE status='Tersedia'
    ")

)['total'];



// =======================================
// RUANGAN DIBOOKING
// =======================================

$ruangan_booking = mysqli_fetch_assoc(

    mysqli_query($conn,"
        SELECT COUNT(*) AS total
        FROM ruangan
        WHERE status='Booking'
    ")

)['total'];

?>
<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Dashboard Admin | StudyRoom</title>

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

<?php include "../includes/sidebar_admin.php"; ?>

<div class="main-content">

<?php include "../includes/navbar_admin.php"; ?>

<div class="container-fluid">
    <div class="row g-4">

    <!-- Total Mahasiswa -->
    <div class="col-xl-4 col-md-6">

        <div class="dashboard-card">

            <div>

                <h6>Total Mahasiswa</h6>

                <h2><?= $total_mahasiswa; ?></h2>

            </div>

            <div class="icon blue">

                <i class="bi bi-people-fill"></i>

            </div>

        </div>

    </div>


    <!-- Total Ruangan -->
    <div class="col-xl-4 col-md-6">

        <div class="dashboard-card">

            <div>

                <h6>Total Ruangan</h6>

                <h2><?= $total_ruangan; ?></h2>

            </div>

            <div class="icon green">

                <i class="bi bi-building"></i>

            </div>

        </div>

    </div>


    <!-- Total Booking -->
    <div class="col-xl-4 col-md-6">

        <div class="dashboard-card">

            <div>

                <h6>Total Booking</h6>

                <h2><?= $total_booking; ?></h2>

            </div>

            <div class="icon orange">

                <i class="bi bi-calendar-check-fill"></i>

            </div>

        </div>

    </div>


    <!-- Booking Menunggu -->
    <div class="col-xl-4 col-md-6">

        <div class="dashboard-card">

            <div>

                <h6>Booking Menunggu</h6>

                <h2><?= $booking_menunggu; ?></h2>

            </div>

            <div class="icon red">

                <i class="bi bi-hourglass-split"></i>

            </div>

        </div>

    </div>


    <!-- Booking Disetujui -->
    <div class="col-xl-4 col-md-6">

        <div class="dashboard-card">

            <div>

                <h6>Booking Disetujui</h6>

                <h2><?= $booking_disetujui; ?></h2>

            </div>

            <div class="icon green">

                <i class="bi bi-check-circle-fill"></i>

            </div>

        </div>

    </div>


    <!-- Booking Ditolak -->
    <div class="col-xl-4 col-md-6">

        <div class="dashboard-card">

            <div>

                <h6>Booking Ditolak</h6>

                <h2><?= $booking_ditolak; ?></h2>

            </div>

            <div class="icon red">

                <i class="bi bi-x-circle-fill"></i>

            </div>

        </div>

    </div>

</div>
<!-- ===================================== -->
<!-- BOOKING TERBARU -->
<!-- ===================================== -->

<div class="card shadow-sm mt-4 border-0 rounded-4">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            <i class="bi bi-clock-history"></i>

            Booking Terbaru

        </h5>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Mahasiswa</th>

                        <th>Ruangan</th>

                        <th>Tanggal</th>

                        <th>Jam</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                $no = 1;

                $data = mysqli_query($conn, "

                    SELECT

                    booking.*,

                    users.nama_lengkap,

                    users.username,

                    ruangan.nama_ruangan

                    FROM booking

                    JOIN users

                    ON booking.id_user = users.id_user

                    JOIN ruangan

                    ON booking.id_ruangan = ruangan.id_ruangan

                    ORDER BY booking.id_booking DESC

                    LIMIT 5

                ");

                if(mysqli_num_rows($data) > 0){

                    while($d = mysqli_fetch_assoc($data)){

                ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td>

                            <strong>

                                <?= htmlspecialchars($d['nama_lengkap']); ?>

                            </strong>

                            <br>

                            <small class="text-muted">

                                <?= htmlspecialchars($d['username']); ?>

                            </small>

                        </td>

                        <td>

                            <?= htmlspecialchars($d['nama_ruangan']); ?>

                        </td>

                        <td>

                            <?= date('d-m-Y', strtotime($d['tanggal'])); ?>

                        </td>

                        <td>

                            <?= substr($d['jam_mulai'],0,5); ?>

                            -

                            <?= substr($d['jam_selesai'],0,5); ?>

                        </td>

                        <td>

                        <?php

                        if($d['status_booking']=="Menunggu"){

                            echo '<span class="badge bg-warning text-dark">
                                    Menunggu
                                  </span>';

                        }elseif($d['status_booking']=="Disetujui"){

                            echo '<span class="badge bg-success">
                                    Disetujui
                                  </span>';

                        }elseif($d['status_booking']=="Ditolak"){

                            echo '<span class="badge bg-danger">
                                    Ditolak
                                  </span>';

                        }else{

                            echo '<span class="badge bg-secondary">
                                    '.$d['status_booking'].'
                                  </span>';

                        }

                        ?>

                        </td>

                    </tr>

                <?php

                    }

                }else{

                ?>

                    <tr>

                        <td colspan="6" class="text-center text-muted">

                            Belum ada data booking.

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