<?php
session_start();
include 'koneksi.php';

$email = $_POST['email'];
$password = $_POST['password'];

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
$cek = mysqli_num_rows($query);

if($cek > 0){
    $data = mysqli_fetch_assoc($query);

  
    if($password == $data['password']){
        
        $_SESSION['userID'] = $data['userID'];
        $_SESSION['nama']   = $data['nama'];
        $_SESSION['role']   = $data['role'];
        $_SESSION['status'] = "login";

        if($data['role'] == "admin"){
            header("location:admin/index.php");
        } else {
            // Kalau login karena mau Reservasi/Delivery
            if(isset($_SESSION['tipe_transaksi'])){
                header("location:setup_pesanan.php");
            } 
            // Kalau login biasa
            else {
                header("location:index.php"); 
            }
        }

    } else {
        header("location:login.php?pesan=gagal");
    }

} else {
    header("location:login.php?pesan=gagal");
}
?>