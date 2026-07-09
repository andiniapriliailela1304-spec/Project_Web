<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set("Asia/Makassar");
?>

<nav class="top-navbar">

    <div class="navbar-left">

        <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Buka menu">
            <i class="bi bi-list"></i>
        </button>

        <div>

            <h4>Dashboard Admin</h4>

            <span>

                <?= date('l, d F Y'); ?>

            </span>

        </div>

    </div>

    <div class="navbar-right">

        <div class="profile">

            <img src="../assets/img/admin.jpeg"
                 alt="Administrator">

            <div>

                <h6>Administrator</h6>

                <small>Administrator</small>

            </div>

        </div>

    </div>

</nav>
<script src="../assets/js/sidebar.js"></script>