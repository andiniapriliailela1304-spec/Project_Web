<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login | StudyRoom</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="container-fluid">

    <div class="row vh-100">

        <!-- BAGIAN KIRI -->

        <div class="col-lg-6 d-none d-lg-flex left-side justify-content-center align-items-center">

            <div class="text-center">

                <img src="../assets/img/studyroom.png"
                     class="logo mb-4"
                     alt="Logo">

                <h2 class="fw-bold">

                    StudyRoom

                </h2>

                <p>

                    Smart Room Reservation System

                </p>

            </div>

        </div>

        <!-- BAGIAN KANAN -->

        <div class="col-lg-6 d-flex justify-content-center align-items-center">

            <div class="login-card">
            <?php

if(isset($_GET['error'])){

    if($_GET['error']=="email"){

        echo '

        <div class="alert alert-danger">

            Email tidak ditemukan.

        </div>

        ';

    }

    if($_GET['error']=="password"){

        echo '

        <div class="alert alert-danger">

            Password salah.

        </div>

        ';

    }

}

if(isset($_GET['success'])){

    if($_GET['success']=="register"){

        echo '

        <div class="alert alert-success">

            Registrasi berhasil.
            Silakan login.

        </div>

        ';

    }

}

?>

                <div class="text-center mb-4">

                    <img src="../assets/img/studyroom.png"
                         width="80"
                         class="mb-3">

                    <h3 class="fw-bold">

                        Selamat Datang

                    </h3>

                    <p class="text-muted">

                        Silakan login terlebih dahulu

                    </p>

                </div>

                <form action="login_process.php" method="POST">
                    <!-- Email -->

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

                    <!-- Password -->

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
                                onclick="togglePassword()">

                                <i class="bi bi-eye" id="eyeIcon"></i>

                            </button>

                        </div>

                    </div>

                    <div class="mb-4 text-end">

                        <a href="#"
                           class="text-decoration-none">

                            Lupa Password?

                        </a>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-login w-100">

                        LOGIN

                    </button>

                </form>

                <div class="text-center mt-4">

                    Belum punya akun?

                    <a href="register.php">

                        Register

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- Javascript -->

<script src="../assets/js/script.js"></script>

</body>

</html>