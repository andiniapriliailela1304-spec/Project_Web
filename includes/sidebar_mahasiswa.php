<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">

    <div class="logo-section">

        <img src="../assets/img/logo.png" class="logo">

        <div>

            <h4>StudyRoom</h4>

            <small>Mahasiswa</small>

        </div>

    </div>

    <ul class="menu">

        <li class="<?= ($current=="dashboard.php") ? "active" : "" ?>">

            <a href="../mahasiswa/dashboard.php">

                <i class="bi bi-grid-fill"></i>

                Dashboard

            </a>

        </li>

        <li class="<?= ($current=="ruangan.php") ? "active" : "" ?>">

            <a href="../mahasiswa/ruangan.php">

                <i class="bi bi-building"></i>

                Ruangan

            </a>

        </li>

        <li class="<?= ($current=="riwayat.php") ? "active" : "" ?>">

            <a href="../mahasiswa/riwayat.php">

                <i class="bi bi-calendar-check"></i>

                Riwayat Booking

            </a>

        </li>

        <li class="<?= ($current=="profil.php") ? "active" : "" ?>">

            <a href="../mahasiswa/profil.php">

                <i class="bi bi-person-circle"></i>

                Profil

            </a>

        </li>

        <li class="logout">

            <a href="../auth/logout.php"
               onclick="return confirm('Yakin ingin logout?')">

                <i class="bi bi-box-arrow-right"></i>

                Logout

            </a>

        </li>

    </ul>

</div>