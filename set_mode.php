<?php
session_start();

if(isset($_GET['mode'])){
    // Simpan pilihan user (reservation / delivery) 
    $_SESSION['tipe_transaksi'] = $_GET['mode'];
}


if(isset($_SESSION['status']) && $_SESSION['status'] == 'login'){
    header("location: setup_pesanan.php");
} else {

    header("location: login.php?pesan=belum_login");
}
?>