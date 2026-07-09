<?php
session_start();
require_once "../config/koneksi.php";

// Jika sudah login
if (isset($_SESSION['login'])) {

    if ($_SESSION['role'] == "admin") {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../mahasiswa/dashboard.php");
    }
    exit;
}

$success = "";
$error = "";

if (isset($_POST['register'])) {

    $nama_lengkap = mysqli_real_escape_string($conn, trim($_POST['nama_lengkap']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($password != $konfirmasi) {

        $error = "Konfirmasi password tidak sesuai.";

    } else {

        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");

        if (mysqli_num_rows($cek) > 0) {

            $error = "Username (NIM) atau Email sudah digunakan.";

        } else {

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $simpan = mysqli_query($conn, "INSERT INTO users
            (nama_lengkap, username,email,password,prodi, foto, role)
            VALUES
            ('$nama_lengkap','$username','$email','$passwordHash','Sistem Informadi', 'dafault.png','mahasiswa')");

            if ($simpan) {

                $success = "Registrasi berhasil. Silakan login.";

            } else {

                $error = "Registrasi gagal : " . mysqli_error($conn);

            }

        }

    }

}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Register | StudyRoom</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet" href="../assets/css/auth.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


</head>

<body>

<div class="auth-container">

    <!-- LEFT -->

    <div class="auth-left">

        <img src="../assets/img/logo.png" class="logo">

        <h1>StudyRoom</h1>

        <p>
            Buat akun terlebih dahulu untuk mulai melakukan booking
            ruang belajar dengan mudah dan cepat.
        </p>

        <img src="../assets/img/kampus.png">

    </div>

    <!-- RIGHT -->

    <div class="auth-right">

        <div class="auth-box">

            <div class="brand">

                <h3>Registrasi</h3>

            </div>

            <p>Lengkapi data di bawah ini.</p>

            <?php if($error!=""){ ?>

                <div class="alert alert-danger">

                    <?= $error ?>

                </div>

            <?php } ?>

            <?php if($success!=""){ ?>

                <div class="alert alert-success">

                    <?= $success ?>

                </div>

            <?php } ?>

            <form method="POST">

                <div class="mb-3">

                 <label class="form-label">
                    Nama Lengkap    
                </label>
                <input
                type="text"
                name="nama_lengkap"
                class="form-control"
                placeholder="Masukkan Nama Lengkap"
                required>
                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Username (NIM)

                    </label>

                    <input
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan NIM"
                    required>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Email

                    </label>

                    <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Masukkan Email"
                    required>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Password

                    </label>

                    <div class="input-group">

                        <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan Password"
                        required>

                        <button
                        class="btn btn-outline-secondary"
                        type="button"
                        onclick="showPassword('password', this)">

                            <i class="bi bi-eye"></i>

                        </button>

                    </div>

                </div>

                <div class="mb-4">

                    <label class="form-label">

                        Konfirmasi Password

                    </label>

                    <div class="input-group">

                        <input
                        type="password"
                        id="konfirmasi"
                        name="konfirmasi"
                        class="form-control"
                        placeholder="Konfirmasi Password"
                        required>

                        <button
                        class="btn btn-outline-secondary"
                        type="button"
                        onclick="showPassword('konfirmasi', this)">

                            <i class="bi bi-eye"></i>

                        </button>

                    </div>

                </div>

                <button
                type="submit"
                name="register"
                class="btn-login">

                    Daftar

                </button>

            </form>

            <div class="auth-link">

                Sudah memiliki akun?

                <a href="login.php">

                    Login

                </a>

            </div>

        </div>

    </div>

</div>

<script>

function showPassword(id, button){

    let input = document.getElementById(id);
    let icon = button.querySelector("i");

    if(input.type === "password"){

        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");

    }else{

        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");

    }

}

</script>

</body>

</html>