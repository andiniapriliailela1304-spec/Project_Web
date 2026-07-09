<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">

    <div class="logo-section">
        <img src="../assets/img/logo.png" class="logo">

        <div>
            <h4>StudyRoom</h4>
            <small>Administrator</small>
        </div>
    </div>

    <ul class="menu">

        <li class="<?= ($current=="dashboard.php") ? "active":"" ?>">
            <a href="../admin/dashboard.php">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>
        </li>

        <li class="<?= ($current=="ruangan.php") ? "active":"" ?>">
            <a href="../admin/ruangan.php">
                <i class="bi bi-building"></i>
                Ruangan
            </a>
        </li>

        <li>
            <a href="../admin/booking.php">
                <i class="bi bi-calendar-check"></i>
                Booking
            </a>
        </li>

       <li class="<?= ($current=="pengguna.php") ? "active" : "" ?>">

         <a href="../admin/pengguna.php">
            <i class="bi bi-people-fill"></i>
            Data Pengguna
        </a>
    </li>

        <li class="logout">

            <a href="../auth/logout.php">

                <i class="bi bi-box-arrow-right"></i>

                Logout

            </a>

        </li>

    </ul>

</div>