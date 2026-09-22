<?php
session_start();
include 'koneksi.php'; 

// Cek Login
if(!isset($_SESSION['status']) || $_SESSION['status'] != 'login'){
    header("location: login.php?pesan=belum_login");
    exit();
}

// Cek Mode
if(!isset($_SESSION['tipe_transaksi'])){
    header("location: index.php");
    exit();
}

// Jika memilih tipe pesanan delivery
if($_SESSION['tipe_transaksi'] == 'delivery'){
    header("location: setup_delivery.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Tahap 1 - Flavor Haven</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            padding-top: 100px;
        }
        a.logo { text-decoration: none; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="login-page">
        <div class="login-form" style="max-width: 500px;">
            
            <div style="font-size: 3rem; color: #6E2B2B; margin-bottom: 20px;">
                <i class="fas fa-calendar-alt"></i>
            </div>

            <h1>Cek Ketersediaan</h1>
            <p>Pilih tanggal dan jenis ruangan yang Anda inginkan.</p>
            
            <form action="setup_pesanan_2.php" method="POST" class="form">
                
                <div class="form-group">
                    <label>Tanggal Booking</label>
                    <input type="date" name="tanggal" required min="<?= date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label>Jenis Ruangan</label>
                    <select name="tipe_ruangan" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; background: #fafafa;">
                        <option value="Regular">Regular Area (Meja Biasa)</option>
                        <option value="VIP">VIP Room (Private)</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">
                    Cek Jadwal <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="register-link">
                <br>
                <a href="index.php" style="color: #888; text-decoration: none;">
                    <i class="fas fa-times"></i> Batal / Kembali
                </a>
            </div>

        </div>
    </main>

</body>
</html>