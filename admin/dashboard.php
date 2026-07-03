<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['role'] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2>
        Selamat Datang Admin,
        <?= $_SESSION['nama']; ?>
    </h2>

    <hr>

    <a href="../auth/logout.php" class="btn btn-danger">
        Logout
    </a>

</div>

</body>
</html>