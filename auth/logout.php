<?php

session_start();

session_destroy();

// Hapus Cookie Remember Me
setcookie(

    "remember_user",

    "",

    time()-3600,

    "/"

);

header("Location: login.php");

exit;