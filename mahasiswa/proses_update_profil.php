<?php

session_start();

require_once "../config/koneksi.php";
require_once "../auth/cek_login.php";


// Cek role mahasiswa

if($_SESSION['role'] != "mahasiswa"){

    header("Location: ../auth/login.php");
    exit;

}



if($_SERVER['REQUEST_METHOD']=="POST"){


    $id_user = $_SESSION['id_user'];


    $nama_lengkap = mysqli_real_escape_string(
        $conn,
        trim($_POST['nama_lengkap'])
    );


    $email = mysqli_real_escape_string(
        $conn,
        trim($_POST['email'])
    );


    $password = $_POST['password'];

    $konfirmasi = $_POST['konfirmasi'];



    // Ambil data lama

    $data = mysqli_query($conn,"
        SELECT foto 
        FROM users 
        WHERE id_user='$id_user'
    ");

    $user = mysqli_fetch_assoc($data);



    $foto = $user['foto'];



    // ============================
    // UPLOAD FOTO PROFIL
    // ============================


    if(!empty($_FILES['foto']['name'])){


        $folder = "../uploads/profile/";


        // buat folder jika belum ada

        if(!is_dir($folder)){

            mkdir($folder,0777,true);

        }



        $namaFile = time()."_".
        basename($_FILES['foto']['name']);



        $target = $folder.$namaFile;



        $tipe = $_FILES['foto']['type'];



        $allowed = [
            "image/jpeg",
            "image/png",
            "image/jpg"
        ];



        if(in_array($tipe,$allowed)){



            if(move_uploaded_file(
                $_FILES['foto']['tmp_name'],
                $target
            )){


                // hapus foto lama

                if(
                    $foto != "" &&
                    $foto != "default.png" &&
                    file_exists($folder.$foto)
                ){

                    unlink($folder.$foto);

                }



                $foto = $namaFile;


            }


        }else{


            echo "<script>
            alert('Format foto harus JPG atau PNG');
            history.back();
            </script>";

            exit;

        }


    }





    // ============================
    // UPDATE PASSWORD
    // ============================


    if(!empty($password)){


        if($password != $konfirmasi){


            echo "<script>

            alert('Konfirmasi password tidak sesuai');

            history.back();

            </script>";

            exit;


        }



        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );



        $query = mysqli_query($conn,"

            UPDATE users SET

            nama_lengkap='$nama_lengkap',

            email='$email',

            password='$passwordHash',

            foto='$foto'


            WHERE id_user='$id_user'

        ");



    }else{


        $query = mysqli_query($conn,"

            UPDATE users SET

            nama_lengkap='$nama_lengkap',

            email='$email',

            foto='$foto'


            WHERE id_user='$id_user'

        ");


    }





    if($query){


        echo "<script>

        alert('Profil berhasil diperbarui');

        window.location='profil.php';

        </script>";


    }else{


        echo "<script>

        alert('Profil gagal diperbarui');

        history.back();

        </script>";

    }



}else{


    header("Location: profil.php");

    exit;

}


?>