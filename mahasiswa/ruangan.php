<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";

// CEK LOGIN MAHASISWA
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "mahasiswa") {
    header("Location: ../auth/login.php");
    exit;
}

// ZONA WAKTU
date_default_timezone_set("Asia/Makassar");
$tanggal      = date("Y-m-d");
$jamSekarang  = date("H:i:s");

// ==========================================================
// SINKRONISASI STATUS RUANGAN BERDASARKAN BOOKING TERKINI
// ==========================================================
$syncQuery = mysqli_query($conn, "SELECT id_ruangan FROM ruangan");
if ($syncQuery) {
    while ($r = mysqli_fetch_assoc($syncQuery)) {
        $idRuangan = $r['id_ruangan'];

        $cekAktif = mysqli_query($conn, "
            SELECT id_booking
            FROM booking
            WHERE id_ruangan = '".mysqli_real_escape_string($conn, $idRuangan)."'
              AND tanggal = '$tanggal'
              AND status_booking = 'Disetujui'
              AND '$jamSekarang' BETWEEN jam_mulai AND jam_selesai
            LIMIT 1
        ");

        $adaBookingAktif = $cekAktif && mysqli_num_rows($cekAktif) > 0;

        if ($adaBookingAktif) {
            mysqli_query($conn, "
                UPDATE ruangan
                SET status = 'Booking'
                WHERE id_ruangan = '".mysqli_real_escape_string($conn, $idRuangan)."'
                  AND status != 'Booking'
            ");
        } else {
            mysqli_query($conn, "
                UPDATE ruangan
                SET status = 'Tersedia'
                WHERE id_ruangan = '".mysqli_real_escape_string($conn, $idRuangan)."'
                  AND status != 'Tersedia'
            ");
        }
    }
}

// AMBIL DATA RUANGAN (setelah sinkronisasi status di atas)
$query = mysqli_query($conn, "
    SELECT *
    FROM ruangan
    ORDER BY nama_ruangan ASC
");

if (!$query) {
    die("Query Error : " . mysqli_error($conn));
}

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

<?php include "../includes/sidebar_mahasiswa.php"; ?>

<div class="main-content">

    <?php include "../includes/navbar_mahasiswa.php"; ?>

    <div class="content">

        <div class="page-header">
            <h3>Data Ruangan</h3>
            <p class="text-muted">Lihat dan booking ruangan yang tersedia.</p>
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
                        $no = 1;

                        // Semua HTML modal ditampung di sini dulu, BARU dicetak
                        // setelah </table>. Modal TIDAK BOLEH ditaruh langsung di
                        // dalam <tbody>/<table> karena itu bukan struktur HTML yang
                        // valid (tbody hanya boleh berisi <tr>) -- kalau dipaksakan,
                        // browser akan "membetulkan" markup ini sendiri dengan cara
                        // memindahkan/merender div itu keluar dari alur normal,
                        // sehingga modal terlihat selalu terbuka & tabel jadi berantakan.
                        $modalsHtml = "";

                        while ($row = mysqli_fetch_assoc($query)) {

                            $statusRuangan = $row['status']; // 'Tersedia' atau 'Booking'

                            // Ambil booking aktif ATAU booking yang akan datang hari ini
                            $bookingQuery = mysqli_query($conn, "
                                SELECT
                                    booking.*,
                                    users.nama_lengkap,
                                    users.prodi
                                FROM booking
                                LEFT JOIN users ON booking.id_user = users.id_user
                                WHERE booking.id_ruangan='".$row['id_ruangan']."'
                                  AND booking.tanggal='$tanggal'
                                  AND booking.status_booking='Disetujui'
                                  AND booking.jam_selesai >= '$jamSekarang'
                                ORDER BY booking.jam_mulai ASC
                                LIMIT 1
                            ");

                            $dataBooking = null;
                            if ($bookingQuery) {
                                $dataBooking = mysqli_fetch_assoc($bookingQuery);
                            }

                            // Label & warna badge status
                            if ($statusRuangan === "Booking") {
                                $warna = "danger";
                                $icon  = "lock-fill";
                                $label = "Booking";
                            } else {
                                $warna = "success";
                                $icon  = "check-circle-fill";
                                $label = "Tersedia";
                            }

                            // Hitung sisa waktu (kalau ada booking aktif/akan datang)
                            $sisaWaktuJam   = null;
                            $sisaWaktuMenit = null;
                            $fase           = null; // "akan_mulai" | "sedang_berjalan"

                            if (!empty($dataBooking)) {
                                $jamSekarangObj = new DateTime();

                                if ($jamSekarang < $dataBooking['jam_mulai']) {
                                    $fase = "akan_mulai";
                                    $target = new DateTime($dataBooking['tanggal'] . " " . $dataBooking['jam_mulai']);
                                } else {
                                    $fase = "sedang_berjalan";
                                    $target = new DateTime($dataBooking['tanggal'] . " " . $dataBooking['jam_selesai']);
                                }

                                if ($jamSekarangObj < $target) {
                                    $selisih        = $jamSekarangObj->diff($target);
                                    $sisaWaktuJam   = $selisih->h;
                                    $sisaWaktuMenit = $selisih->i;
                                }
                            }
                            ?>

                            <tr>
                                <td><?= $no++; ?></td>

                                <td>
                                    <?php if (!empty($row['foto'])) { ?>
                                        <img
                                            src="../uploads/<?= htmlspecialchars($row['foto']); ?>"
                                            width="85" height="60"
                                            style="object-fit:cover;border-radius:10px;">
                                    <?php } else { ?>
                                        <span class="text-muted">Tidak ada foto</span>
                                    <?php } ?>
                                </td>

                                <td><strong><?= htmlspecialchars($row['nama_ruangan']); ?></strong></td>
                                <td><?= htmlspecialchars($row['gedung']); ?></td>

                                <td>
                                    <?= (int)$row['kapasitas']; ?> Orang
                                </td>

                                <td>
                                    <span class="badge bg-<?= $warna; ?>">
                                        <i class="bi bi-<?= $icon; ?>"></i>
                                        <?= $label; ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($statusRuangan === "Tersedia") { ?>
                                        <a href="booking.php?id=<?= $row['id_ruangan']; ?>" class="btn btn-success btn-sm">
                                            <i class="bi bi-calendar-plus"></i>
                                            Booking
                                        </a>
                                    <?php } else { ?>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="bi bi-lock-fill"></i>
                                            Sudah Dibooking
                                        </button>
                                    <?php } ?>

                                    <button
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detail<?= $row['id_ruangan']; ?>">
                                        <i class="bi bi-info-circle"></i>
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>

                            <?php
                            // ------------------------------------------------
                            // Modal ini TIDAK di-echo langsung di sini.
                            // Ditampung ke $modalsHtml dulu lewat output buffering,
                            // baru dicetak nanti setelah </table>.
                            // ------------------------------------------------
                            ob_start();
                            ?>

                            <div class="modal fade" id="detail<?= $row['id_ruangan']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-building"></i>
                                                Informasi Ruangan
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <h4 class="text-success"><?= htmlspecialchars($row['nama_ruangan']); ?></h4>
                                            <hr>

                                            <p><b>Gedung :</b> <?= htmlspecialchars($row['gedung']); ?></p>
                                            <p><b>Kapasitas :</b> <?= (int)$row['kapasitas']; ?> Orang</p>

                                            <p>
                                                <b>Status :</b>
                                                <span class="badge bg-<?= $warna; ?>">
                                                    <i class="bi bi-<?= $icon; ?>"></i>
                                                    <?= $label; ?>
                                                </span>
                                            </p>

                                            <?php if (!empty($dataBooking)) { ?>
                                                <hr>
                                                <h5 class="text-primary">Informasi Booking</h5>

                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th width="35%">Mahasiswa</th>
                                                        <td><?= htmlspecialchars($dataBooking['nama_lengkap']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Program Studi</th>
                                                        <td><?= htmlspecialchars($dataBooking['prodi']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tujuan</th>
                                                        <td><?= htmlspecialchars($dataBooking['tujuan']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <td><?= date('d-m-Y', strtotime($dataBooking['tanggal'])); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Jam</th>
                                                        <td><?= substr($dataBooking['jam_mulai'],0,5) . " - " . substr($dataBooking['jam_selesai'],0,5); ?></td>
                                                    </tr>
                                                </table>

                                                <div class="mt-3 alert alert-warning">
                                                    <i class="bi bi-clock-history"></i>
                                                    <b>Sisa Waktu</b>
                                                    <br><br>
                                                    <?php if ($fase === "akan_mulai") { ?>
                                                        Menuju jam mulai.
                                                    <?php } elseif ($fase === "sedang_berjalan") { ?>
                                                        Menuju jam selesai.
                                                    <?php } ?>
                                                    <br><br>
                                                    <b><?= (int)$sisaWaktuJam; ?> Jam</b>
                                                    <b><?= (int)$sisaWaktuMenit; ?> Menit</b>
                                                </div>
                                            <?php } else { ?>
                                                <div class="alert alert-success">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                    <b>Ruangan Sedang Kosong</b>
                                                    <br>
                                                    Mahasiswa dapat langsung melakukan booking.
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <?php if ($statusRuangan === "Tersedia") { ?>
                                                <a href="booking.php?id=<?= $row['id_ruangan']; ?>" class="btn btn-success">Booking Sekarang</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            $modalsHtml .= ob_get_clean();

                        } // end while
                        ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

<?php
// Semua modal detail dicetak DI SINI, di luar <table> sepenuhnya,
// supaya HTML valid dan modal Bootstrap benar-benar tersembunyi sampai
// tombol "Lihat Detail" diklik.
echo $modalsHtml;
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>