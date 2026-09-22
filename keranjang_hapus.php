<?php
session_start();

$id_menu = $_GET['id'];


unset($_SESSION['keranjang'][$id_menu]);


header("location: keranjang.php");
?>