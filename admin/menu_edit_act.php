<?php
session_start();
include '../koneksi.php';

$id         = $_POST['id'];
$nama       = $_POST['nama'];
$kategori   = $_POST['kategori'];
$harga      = $_POST['harga'];
$deskripsi  = $_POST['deskripsi'];
$status     = $_POST['status'];

// Cek apakah upload foto baru
if($_FILES['foto']['name'] == "") {
    // Tidak ganti foto
    $query = "UPDATE menu SET 
              categoryID='$kategori', 
              menuName='$nama', 
              description='$deskripsi', 
              price='$harga', 
              status='$status' 
              WHERE menuID='$id'";
} else {
    // Ganti foto baru
    $rand = rand();
    $ekstensi =  array('png','jpg','jpeg');
    $filename = $_FILES['foto']['name'];
    $ukuran = $_FILES['foto']['size'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if(!in_array($ext,$ekstensi) ) {
        echo "<script>alert('Format file tidak valid'); window.history.back();</script>";
        exit();
    }

    if($ukuran > 2097152){
        echo "<script>alert('Ukuran file terlalu besar'); window.history.back();</script>";
        exit();
    }

    $xx = $rand.'_'.$filename;
    move_uploaded_file($_FILES['foto']['tmp_name'], '../img/'.$xx);

    $query = "UPDATE menu SET 
              categoryID='$kategori', 
              menuName='$nama', 
              description='$deskripsi', 
              price='$harga', 
              status='$status',
              image='$xx'
              WHERE menuID='$id'";
}

if(mysqli_query($koneksi, $query)){
    header("location:menu.php?pesan=update_sukses");
} else {
    echo "Gagal update: " . mysqli_error($koneksi);
}
?>