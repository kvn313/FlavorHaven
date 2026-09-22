<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['status']) || $_SESSION['status'] != 'login'){
    echo "<script>alert('Silahkan login terlebih dahulu!'); location='login.php';</script>";
    exit();
}

if(empty($_SESSION['keranjang'])){
    echo "<script>alert('Keranjang belanja Anda masih kosong. Silahkan pilih menu.'); location='menu.php';</script>";
    exit();
}

if(!isset($_SESSION['tipe_transaksi']) || !isset($_SESSION['data_pesanan'])){
    echo "<script>alert('Mohon lengkapi data pemesanan (Reservasi/Delivery) terlebih dahulu di Halaman Utama.'); location='index.php';</script>";
    exit();
}

$tipe_transaksi = $_SESSION['tipe_transaksi'];
$info_pesanan   = $_SESSION['data_pesanan'];

// Hitung Subtotal
$subtotal_belanja = 0;
foreach($_SESSION['keranjang'] as $id_menu => $item){

    if(is_array($item)){
        $jumlah_beli = $item['qty'];
    } else {
        $jumlah_beli = $item;
    }

    $ambil = mysqli_query($koneksi, "SELECT price FROM menu WHERE menuID='$id_menu'");
    $pecah = mysqli_fetch_assoc($ambil);
    
    $subtotal_belanja += $pecah['price'] * $jumlah_beli;
}

// Hitung Pajak
$pajak = $subtotal_belanja * 0.10;

// Hitung Total
$total_bayar = $subtotal_belanja + $pajak;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Flavor Haven</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    
    <link rel="stylesheet" href="css/landing.css">
    
    <link rel="stylesheet" href="css/checkout.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container">
        
        <a href="keranjang.php" class="btn-back-cart">
            <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
        </a>

        <h2 style="text-align:center; margin-bottom:30px; color:#333;">Konfirmasi Pembayaran</h2>

        <form action="checkout_act.php" method="POST" class="checkout-grid">
            
            <div class="box">
                <h3><i class="fas fa-wallet"></i> Metode Pembayaran</h3>
                <p style="margin-bottom:20px; color:#666;">Silahkan pilih metode pembayaran Anda.</p>
                
                <label class="payment-option">
                    <input type="radio" name="metode_pembayaran" value="QRIS" required checked>
                    <span><i class="fas fa-qrcode"></i> QRIS</span>
                </label>

                <label class="payment-option">
                    <input type="radio" name="metode_pembayaran" value="Debit/Credit Card">
                    <span><i class="fas fa-credit-card"></i> Debit / Credit Card</span>
                </label>

                <label class="payment-option">
                    <input type="radio" name="metode_pembayaran" value="Virtual Account">
                    <span><i class="fas fa-university"></i> Transfer Virtual Account</span>
                </label>
            </div>

            <div class="box">
                <h3><i class="fas fa-receipt"></i> Ringkasan Order</h3>
                
                <div style="background:#fcfcfc; padding:15px; border-radius:8px; border:1px solid #eee; margin-bottom:20px;">
                    <?php if($tipe_transaksi == 'delivery'): ?>
                        <strong style="color:#0d47a1;"><i class="fas fa-motorcycle"></i> DELIVERY ORDER</strong>
                        <hr style="margin:10px 0; border:0; border-top:1px solid #ddd;">
                        <p style="font-size:0.9rem; color:#555;">
                            <strong>Alamat:</strong><br>
                            <?= $info_pesanan['deliveryAddress']; ?>
                        </p>
                        <p style="font-size:0.9rem; color:#555; margin-top:5px;">
                            <strong>Telepon:</strong> <?= $info_pesanan['deliveryPhone']; ?>
                        </p>

                    <?php elseif($tipe_transaksi == 'reservation'): ?>
                        <strong style="color:#7b1fa2;"><i class="fas fa-chair"></i> RESERVASI TEMPAT</strong>
                        <hr style="margin:10px 0; border:0; border-top:1px solid #ddd;">
                        <p style="font-size:0.9rem; color:#555;">
                            <strong>Tanggal:</strong> <?= date('d M Y', strtotime($info_pesanan['bookingDate'])); ?><br>
                            <strong>Jam:</strong> <?= $info_pesanan['bookingTime']; ?><br>
                            <strong>Pax:</strong> <?= $info_pesanan['pax']; ?> Orang<br>
                            <?php if(isset($info_pesanan['duration']) && $info_pesanan['duration'] > 120): ?>
                                <strong>Durasi:</strong> <?= round($info_pesanan['duration']/60, 1); ?> Jam
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="summary-row">
                    <span>Subtotal Produk</span>
                    <span>Rp <?= number_format($subtotal_belanja); ?></span>
                </div>
                
                <div class="summary-row">
                    <span>Pajak (10%)</span>
                    <span>Rp <?= number_format($pajak); ?></span>
                </div>

                <div class="summary-total">
                    <span>Total Bayar</span>
                    <span>Rp <?= number_format($total_bayar); ?></span>
                </div>

                <input type="hidden" name="total_bayar" value="<?= $total_bayar; ?>">

                <button type="submit" name="bayar" class="btn-pay">
                    Bayar Sekarang <i class="fas fa-chevron-right"></i>
                </button>
            </div>

        </form>
    </main>

    <footer style="text-align:center; padding:30px; color:#999; margin-top:50px;">
        &copy; 2025 Flavor Haven
    </footer>

</body>
</html>