<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['status']) || $_SESSION['status'] != 'login'){
    header("location: login.php?pesan=belum_login");
    exit();
}

if(!isset($_SESSION['tipe_transaksi']) || $_SESSION['tipe_transaksi'] != 'delivery'){
    header("location: index.php");
    exit();
}

if(isset($_POST['simpan_delivery'])){
    $telepon = $_POST['telepon'];
    $alamat  = $_POST['alamat'];

    // No Telp
    if(!is_numeric($telepon)){
        echo "<script>alert('Nomor telepon harus berupa angka!');</script>";
    }
    elseif(strlen($telepon) < 10 || strlen($telepon) > 15){
        echo "<script>alert('Nomor telepon tidak valid! (Minimal 10 digit, Maksimal 15 digit)');</script>";
    }
    else {
        $_SESSION['data_pesanan'] = [
            'deliveryAddress' => $alamat,
            'deliveryPhone'   => $telepon
        ];
        header("location: menu.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengiriman - Flavor Haven</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { padding-top: 100px; }
        
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="login-page">
        <div class="login-form" style="max-width: 500px;">
            
            <div style="font-size: 3rem; color: #6E2B2B; margin-bottom: 20px;">
                <i class="fas fa-motorcycle"></i>
            </div>

            <h1>Detail Pengiriman</h1>
            <p>Mohon lengkapi alamat tujuan Anda.</p>
            
            <form action="" method="POST" class="form">
                <div class="form-group">
                    <label>Nomor WhatsApp / Telepon</label>
                    <input type="number" name="telepon" placeholder="Contoh: 081234567890" required>
                    <small style="color:#888; font-size:0.8rem;">*Wajib angka, min 10 digit.</small>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" rows="4" required placeholder="Nama Jalan, Nomor Rumah, RT/RW, Patokan..." 
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; background: #fafafa; font-family: inherit; resize: vertical;"></textarea>
                </div>

                <button type="submit" name="simpan_delivery" class="btn-submit">
                    Lanjut Pilih Menu <i class="fas fa-arrow-right"></i>
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