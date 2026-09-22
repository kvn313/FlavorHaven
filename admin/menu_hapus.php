<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['status']) || $_SESSION['role'] != 'admin'){
    header("location:../login.php");
    exit();
}

$id = $_GET['id'];

$query_gambar = mysqli_query($koneksi, "SELECT image FROM menu WHERE menuID='$id'");
$data = mysqli_fetch_assoc($query_gambar);
$foto_lama = $data['image'];

// Hapus foto
if(file_exists("../img/$foto_lama")){
    unlink("../img/$foto_lama");
}

mysqli_query($koneksi, "DELETE FROM menu WHERE menuID='$id'");

header("location:menu.php?pesan=hapus_sukses");
?>