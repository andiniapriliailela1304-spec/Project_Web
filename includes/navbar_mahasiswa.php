<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/koneksi.php";

date_default_timezone_set("Asia/Makassar");

$id_user = $_SESSION['id_user'];

// PENTING: pakai nama variabel unik ($query_navbar_user), BUKAN $query.
// Sebelumnya variabel ini bernama $query dan menimpa variabel $query
// milik halaman pemanggil (mis. mahasiswa/ruangan.php & mahasiswa/riwayat.php)
// yang sedang menampung hasil SELECT ruangan/riwayat untuk while-loop,
// karena file ini di-include SETELAH query tsb dijalankan tapi SEBELUM
// while-loop-nya berjalan. Akibatnya data ruangan/riwayat jadi tidak
// tampil karena $query sudah "ketiban" oleh query user di sini.
$query_navbar_user = mysqli_query($conn,"
SELECT *
FROM users
WHERE id_user='$id_user'
");

$user = mysqli_fetch_assoc($query_navbar_user);

// Foto default
$foto = "../uploads/profile/default.png";

if(!empty($user['foto']) && file_exists("../uploads/profile/".$user['foto'])){

    $foto = "../uploads/profile/".$user['foto'];

}

?>

<nav class="top-navbar">

    <div class="navbar-left">

        <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Buka menu">
            <i class="bi bi-list"></i>
        </button>

        <div>

            <h4>Dashboard Mahasiswa</h4>

            <span>

                <?= date('l, d F Y'); ?>

            </span>

        </div>

    </div>

    <div class="navbar-right">

        <div class="profile">

            <img src="<?= $foto; ?>" alt="Mahasiswa">

            <div>

                <h6>

                    <?= htmlspecialchars($user['nama_lengkap']); ?>

                </h6>

                <small>

                    Mahasiswa

                </small>

            </div>

        </div>

    </div>

</nav>
<script src="../assets/js/sidebar.js"></script>