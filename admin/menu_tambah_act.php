<?php
session_start();
include '../koneksi.php';

$nama       = $_POST['nama'];
$kategori   = $_POST['kategori'];
$harga      = $_POST['harga'];
$deskripsi  = $_POST['deskripsi'];

$status     = 'available';

// Upload Foto
$ekstensi =  array('png','jpg','jpeg');
$filename = $_FILES['foto']['name'];
$ukuran   = $_FILES['foto']['size'];
$ext      = pathinfo($filename, PATHINFO_EXTENSION);

// Cek format foto
if(!in_array($ext, $ekstensi)) {
    echo "<script>alert('Format file tidak diperbolehkan (Harus JPG/PNG)'); window.history.back();</script>";
    exit();
}
if($ukuran > 2097152){
    echo "<script>alert('Ukuran file terlalu besar (Maks 2MB)'); window.history.back();</script>";
    exit();
}

$xx = $filename; 
move_uploaded_file($_FILES['foto']['tmp_name'], '../img/'.$xx);

$query = "INSERT INTO menu (categoryID, menuName, description, price, status, image) 
          VALUES ('$kategori', '$nama', '$deskripsi', '$harga', '$status', '$xx')";

if(mysqli_query($koneksi, $query)){
    header("location:menu.php?pesan=sukses");
} else {
    echo "Gagal menyimpan: " . mysqli_error($koneksi);
}
?>