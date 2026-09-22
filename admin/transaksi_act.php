<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['status']) || $_SESSION['role'] != 'admin'){
    header("location:../login.php");
    exit();
}

$id = $_POST['id'];
$status = $_POST['status'];

// Update Status di db
$query = "UPDATE transactions SET status='$status' WHERE transactionID='$id'";
mysqli_query($koneksi, $query);

header("location:transaksi_detail.php?id=$id");
?>