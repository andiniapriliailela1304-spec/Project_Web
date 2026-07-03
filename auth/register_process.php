<?php
include '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data dari form
    $nama     = htmlspecialchars(trim($_POST['nama']));
    $nim      = htmlspecialchars(trim($_POST['nim']));
    $email    = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Validasi password
    if ($password != $confirm) {
        echo "<script>
                alert('Konfirmasi password tidak sama!');
                window.location='register.php';
              </script>";
        exit;
    }

    // Cek email sudah terdaftar
    $cekEmail = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($cekEmail) > 0) {
        echo "<script>
                alert('Email sudah terdaftar!');
                window.location='register.php';
              </script>";
        exit;
    }

    // Cek NIM sudah terdaftar
    $cekNim = mysqli_query($conn, "SELECT * FROM users WHERE nim='$nim'");

    if (mysqli_num_rows($cekNim) > 0) {
        echo "<script>
                alert('NIM sudah terdaftar!');
                window.location='register.php';
              </script>";
        exit;
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Default role = user
    $role = "user";

    // Simpan ke database
    $query = mysqli_query($conn, "INSERT INTO users (nama, nim, email, password, role)
                                  VALUES ('$nama', '$nim', '$email', '$passwordHash', '$role')");

    if ($query) {

        header("Location: login.php?success=register");
        exit;

    } else {

        echo "<script>
                alert('Registrasi gagal!');
                window.location='register.php';
              </script>";

    }

}
?>