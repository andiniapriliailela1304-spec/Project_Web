<?php
// ob_start() di paling atas sebagai "pengaman": walaupun ada warning/whitespace
// tak sengaja tercetak sebelum header()/setcookie() dipanggil nanti, outputnya
// akan ditahan di buffer dulu (bukan langsung dikirim ke browser), sehingga
// header()/setcookie() tetap bisa jalan tanpa error "headers already sent".
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ========================================
// COOKIE REMEMBER ME
// ========================================

$username_cookie = "";

if (isset($_COOKIE['remember_user'])) {
    $username_cookie = $_COOKIE['remember_user'];
}

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

$error = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' LIMIT 1");

    if (mysqli_num_rows($query) == 1) {

        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {

            $_SESSION['login']    = true;
            $_SESSION['id_user']  = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email']    = $user['email'];
            $_SESSION['role']     = $user['role'];

            if (isset($_POST['remember'])) {
                // setcookie dengan parameter lengkap (path, domain, secure, httponly)
                // supaya perilakunya konsisten di semua browser.
                setcookie(
                    "remember_user",
                    $user['username'],
                    [
                        'expires'  => time() + (86400 * 7), // 7 hari
                        'path'     => '/',
                        'samesite' => 'Lax',
                    ]
                );
            } else {
                // Kalau user login TANPA centang "Ingat Saya" tapi cookie lama
                // masih ada dari sesi sebelumnya, hapus supaya tidak nyangkut.
                if (isset($_COOKIE['remember_user'])) {
                    setcookie("remember_user", "", time() - 3600, "/");
                }
            }

            if ($user['role'] == "admin") {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../mahasiswa/dashboard.php");
            }

            exit;

        } else {
            $error = "Password yang Anda masukkan salah.";
        }

    } else {
        $error = "Username (NIM) belum terdaftar. Silakan daftar terlebih dahulu.";
    }

}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login | StudyRoom</title>

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

            Sistem peminjaman ruang belajar yang memudahkan mahasiswa
            menemukan ruang kosong, melakukan booking, dan memperoleh
            QR Code sebagai bukti peminjaman.

        </p>

        <img src="../assets/img/kampus.png">

    </div>

    <!-- RIGHT -->

    <div class="auth-right">

        <div class="auth-box">

            <div class="brand">


                <h3>Login</h3>

            </div>

            <p>Silakan masuk menggunakan akun Anda.</p>

            <?php if ($error != "") { ?>

                <div class="alert alert-danger">

                    <?= $error ?>

                </div>

            <?php } ?>

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">

                        Username (NIM)

                    </label>

                    <input
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan NIM Anda"
                    value="<?= htmlspecialchars($username_cookie); ?>"
                    required>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Password

                    </label>

                    <div class="input-group">

                        <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Masukkan Password Anda"
                        required>

                        <button
                        class="btn btn-outline-secondary"
                        type="button"
                        onclick="showPassword()">

                        <i class="bi bi-eye"></i>

                        </button>

                    </div>

                </div>

                <div class="d-flex justify-content-between mb-4">

                    <div class="form-check">

                        <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        id="remember"
                        <?= $username_cookie ? "checked" : ""; ?>>

                        <label class="form-check-label"
                        for="remember">

                            Ingat Saya

                        </label>

                    </div>

                    <a href="#" class="text-decoration-none">

                        Lupa Password?

                    </a>

                </div>

                <button
                type="submit"
                name="login"
                class="btn-login">

                    Login

                </button>

            </form>

            <div class="auth-link">

                Belum memiliki akun?

                <a href="register.php">

                    Daftar Sekarang

                </a>

            </div>

        </div>

    </div>

</div>

<script>

function showPassword(){

    let password=document.getElementById("password");

    if(password.type=="password"){

        password.type="text";

    }else{

        password.type="password";

    }

}

</script>

</body>
</html>