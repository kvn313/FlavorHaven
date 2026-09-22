<?php
session_start();
include 'koneksi.php';

if(!isset($_GET['id'])){
    header("location: index.php");
    exit();
}

$id_transaksi = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Flavor Haven</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f9f9f9;
            padding-top: 100px; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        a.logo { text-decoration: none; }

       
        .success-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .success-card {
            background: white;
            padding: 50px 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            max-width: 500px;
            width: 100%;
            border: 1px solid #eee;
        }

        
        .icon-circle {
            width: 80px; height: 80px;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 3rem;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .order-id {
            background: #fafafa;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: bold;
            color: #6E2B2B;
            display: inline-block;
            margin-bottom: 30px;
            border: 1px dashed #ccc;
        }

       
        .btn-home {
            display: block;
            width: 100%;
            padding: 15px;
            background: #6E2B2B;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: 0.3s;
        }
        .btn-home:hover {
            background: #8e3b3b;
            transform: translateY(-2px);
        }

        .btn-history {
            display: block;
            margin-top: 15px;
            color: #888;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .btn-history:hover { color: #333; }

    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="success-container">
        <div class="success-card">
            <div class="icon-circle">
                <i class="fas fa-check"></i>
            </div>
            
            <h1>Pesanan Berhasil!</h1>
            <p>Terima kasih telah memesan di Flavor Haven. <br>Pesanan Anda sedang kami proses.</p>
            
            <div class="order-id">
                ID Transaksi: #<?= $id_transaksi; ?>
            </div>

            <a href="index.php" class="btn-home">
                <i class="fas fa-home"></i> Kembali ke Home
            </a>

            <a href="riwayat.php" class="btn-history">
                Lihat Status Pesanan di Riwayat
            </a>
        </div>
    </div>

    <footer>
        <div class="container" style="text-align: center; padding: 20px; color: #666;">
            <p>© 2025 Flavor Haven. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>