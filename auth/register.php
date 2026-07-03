<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | StudyRoom</title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- CSS -->

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="container-fluid login-page">

    <div class="row vh-100">

        <!-- LEFT -->

        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center left-side">

            <div class="text-center">

                <img src="../assets/images/logo.png"
                     class="logo mb-4"
                     alt="Logo">

                <h2 class="fw-bold">
                    StudyRoom
                </h2>

                <p class="mt-3">

                    Smart Room Reservation System

                </p>

                <p class="px-5">

                    Daftarkan akunmu dan mulai melakukan
                    reservasi ruang belajar dengan mudah.

                </p>

            </div>

        </div>

        <!-- RIGHT -->

        <div class="col-lg-6 d-flex align-items-center justify-content-center">

            <div class="login-card">

                <div class="text-center mb-4">

                    <img src="../assets/images/logo.png"
                         width="80"
                         class="mb-3">

                    <h3 class="fw-bold">

                        Daftar Akun

                    </h3>

                    <p class="text-muted">

                        Lengkapi data berikut

                    </p>

                </div>

                <form action="register_process.php" method="POST">

                    <div class="mb-3">

                        <label class="form-label">

                            Nama Lengkap

                        </label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            NIM

                        </label>

                        <input
                            type="text"
                            name="nim"
                            class="form-control"
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
                                required>

                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                onclick="togglePassword()">

                                <i
                                    class="bi bi-eye"
                                    id="eyeIcon"></i>

                            </button>

                        </div>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">

                            Konfirmasi Password

                        </label>

                        <input
                            type="password"
                            name="confirm_password"
                            class="form-control"
                            required>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-login w-100">

                        DAFTAR

                    </button>

                </form>

                <div class="text-center mt-4">

                    Sudah punya akun?

                    <a href="login.php">

                        Login

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script src="../assets/js/script.js"></script>

</body>

</html>