<?php
include 'koneksi.php';

$nama = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$role = 'pelanggan';

// Cek Password 6 karakter
if(strlen($password) < 6){
    header("location:register.php?pesan=password_pendek");
    exit();
}

// Cek pass word = konfirmasi password
if($password != $confirm_password){
    header("location:register.php?pesan=password_beda");
    exit();
}

// Cek apakah Email sudah pernah terdaftar
$cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
if(mysqli_num_rows($cek_email) > 0){
    header("location:register.php?pesan=email_ada");
    exit();
}

// Simpan
$query_insert = "INSERT INTO users VALUES('', '$nama', '$email', '$password', '$gender', '$dob', '$role')";
$simpan = mysqli_query($koneksi, $query_insert);

if($simpan){
    header("location:login.php?pesan=sukses_daftar");
} else {
    header("location:register.php?pesan=gagal");
}
?>