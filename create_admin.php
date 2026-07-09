<?php
require_once "config/koneksi.php";

// ===============================
// DATA ADMIN DEFAULT
// ===============================

$username = "admin";
$email    = "admin@studyroom.com";
$password = "admin123";
$role     = "admin";

// ===============================
// CEK ADMIN SUDAH ADA
// ===============================

$cek = mysqli_query($conn,
    "SELECT * FROM users
    WHERE username='$username'
    OR email='$email'"
);

if (mysqli_num_rows($cek) > 0) {

    echo "

    <div style='font-family:Poppins;text-align:center;margin-top:50px;'>

        <h2>Admin sudah tersedia</h2>

        <hr>

        <p>
        <b>Username :</b> $username
        </p>

        <p>
        <b>Email :</b> $email
        </p>

        <br>

        <a href='auth/login.php'>
            <button>
                Login Sekarang
            </button>
        </a>

    </div>

    ";

    exit;
}

// ===============================
// HASH PASSWORD
// ===============================

$password_hash = password_hash($password, PASSWORD_DEFAULT);

// ===============================
// INSERT ADMIN
// ===============================

$query = mysqli_query($conn,

"INSERT INTO users
(
    username,
    email,
    password,
    role
)

VALUES
(
    '$username',
    '$email',
    '$password_hash',
    '$role'
)"

);

// ===============================
// HASIL
// ===============================

if ($query) {

    echo "

    <div style='font-family:Poppins;text-align:center;margin-top:50px;'>

        <h2 style='color:green;'>
            Admin berhasil dibuat
        </h2>

        <hr>

        <p>
            <b>Username :</b> $username
        </p>

        <p>
            <b>Email :</b> $email
        </p>

        <p>
            <b>Password :</b> $password
        </p>

        <p>
            <b>Role :</b> $role
        </p>

        <br>

        <a href='auth/login.php'>
            <button>
                Login Sekarang
            </button>
        </a>

    </div>

    ";

} else {

    echo "

    <div style='font-family:Poppins;text-align:center;margin-top:50px;'>

        <h2 style='color:red;'>
            Gagal membuat akun admin
        </h2>

        <hr>

        <p>" . mysqli_error($conn) . "</p>

    </div>

    ";

}
?>