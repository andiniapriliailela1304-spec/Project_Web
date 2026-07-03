<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['email'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($query) == 1) {

        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {

            // Simpan session terlebih dahulu
            $_SESSION['id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect sesuai role
            if ($user['role'] == "admin") {

                header("Location: ../admin/dashboard.php");

            } else {

                header("Location: ../user/dashboard.php");

            }

            exit;

        } else {

            header("Location: login.php?error=password");
            exit;

        }

    } else {

        header("Location: login.php?error=email");
        exit;

    }

}
?>