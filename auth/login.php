<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | StudyRoom</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="login-page">

<div class="container">

    <div class="row justify-content-center align-items-center vh-100">

        <div class="col-lg-5 col-md-7">

            <div class="login-card">

                <div class="text-center mb-4">

                    <div class="logo-circle">
                        <i class="bi bi-book-half"></i>
                    </div>

                    <h2 class="mt-3 fw-bold text-navy">
                        StudyRoom
                    </h2>

                    <p class="text-muted">
                        Login untuk melanjutkan
                    </p>

                </div>

                <form action="login_process.php" method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            placeholder="Masukkan email"
                            required>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Password
                        </label>

                        <div class="input-group">

                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                required>

                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                onclick="togglePassword()">

                                <i class="bi bi-eye" id="iconPassword"></i>

                            </button>

                        </div>

                    </div>

                    <button class="btn btn-login w-100">

                        Login

                    </button>

                </form>

                <div class="text-center mt-4">

                    Belum punya akun?

                    <a href="register.php" class="text-decoration-none fw-semibold text-navy">

                        Register

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

function togglePassword(){

    let password=document.getElementById("password");
    let icon=document.getElementById("iconPassword");

    if(password.type==="password"){

        password.type="text";

        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");

    }else{

        password.type="password";

        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");

    }

}

</script>

</body>
</html>