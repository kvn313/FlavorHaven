<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['status']) || $_SESSION['status'] != 'login'){
    header("location: login.php");
    exit();
}

$id_trans = isset($_GET['id']) ? $_GET['id'] : '';
$id_user  = $_SESSION['userID'];

$q_header = mysqli_query($koneksi, "SELECT * FROM transactions WHERE transactionID = '$id_trans' AND userID = '$id_user'");
$d_header = mysqli_fetch_assoc($q_header);

if(mysqli_num_rows($q_header) == 0){
    header("location: riwayat.php");
    exit();
}

$q_detail = mysqli_query($koneksi, "
    SELECT t.*, m.menuName, m.image 
    FROM transactionsdetail t 
    JOIN menu m ON t.menuID = m.menuID 
    WHERE t.transactionID = '$id_trans'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/riwayat_detail.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container" style="margin-top: 30px;">
        
        <div class="detail-container">
            
            <a href="riwayat.php" class="btn-back-custom">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <h1 class="detail-title">Detail Order #<?= $d_header['transactionID']; ?></h1>
            <span class="date-info">
                <i class="far fa-calendar-alt"></i> <?= date('d F Y', strtotime($d_header['transactionDate'])); ?>
            </span>

            <div class="info-group">
                <strong>Status:</strong> 
                <?php
                    $s = $d_header['status'];
                    $color = 'text-pending';
                    if($s=='process') $color='text-process';
                    if($s=='completed') $color='text-completed';
                    if($s=='cancelled') $color='text-cancelled';
                ?>
                <span class="<?= $color; ?>"><?= strtoupper($s); ?></span>
            </div>
            
            <div class="info-group">
                <strong>Tipe Pesanan:</strong> <?= $d_header['type']; ?>
            </div>
            
            <div class="info-group">
                <strong>Pembayaran:</strong> <?= $d_header['paymentMethod']; ?>
            </div>

            <?php if($d_header['type'] == 'Delivery'): ?>
                <div class="info-group">
                    <strong>Alamat:</strong> <?= $d_header['deliveryAddress']; ?>
                </div>
            <?php elseif($d_header['type'] == 'Reservation'): ?>
                <div class="info-group">
                    <strong>Booking:</strong> <?= date('d/m/Y', strtotime($d_header['bookingDate'])); ?> (<?= $d_header['bookingTime']; ?>)
                </div>
            <?php endif; ?>

            <table class="table-simple">
                <thead>
                    <tr>
                        <th width="60">Foto</th>
                        <th>Menu</th>
                        <th>Catatan</th>
                        <th>Harga</th>
                        <th style="text-align:center;">Jml</th>
                        <th style="text-align:right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subtotal_produk = 0;
                    while($item = mysqli_fetch_assoc($q_detail)){ 
                        $total_per_item = $item['quantity'] * $item['unitPrice'];
                        $subtotal_produk += $total_per_item;
                    ?>
                    <tr>
                        <td><img src="img/<?= $item['image']; ?>" class="img-thumb"></td>
                        <td>
                            <div style="font-weight:600; color:#333;"><?= $item['menuName']; ?></div>
                        </td>
                        <td>
                            <?php if(!empty($item['note'])): ?>
                                <small style="font-style:italic; color:#6E2B2B;">
                                    <?= $item['note']; ?>
                                </small>
                            <?php else: ?>
                                <span style="color:#ccc;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>Rp <?= number_format($item['unitPrice']); ?></td>
                        <td style="text-align:center;"><?= $item['quantity']; ?></td>
                        <td style="text-align:right; font-weight:bold;">
                            Rp <?= number_format($total_per_item); ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php 
                $pajak = $subtotal_produk * 0.10;
                $grand_total = $d_header['totalAmount']; 
            ?>

            <div class="total-area">
                <div class="sub-row">
                    Subtotal: Rp <?= number_format($subtotal_produk); ?>
                </div>
                <div class="sub-row">
                    Pajak (10%): Rp <?= number_format($pajak); ?>
                </div>
                
                <div class="sub-divider"></div>

                <span class="total-label">Total Pembayaran:</span><br>
                <span class="total-amount">Rp <?= number_format($grand_total); ?></span>
            </div>

        </div>

    </div>

    <footer style="text-align:center; padding:30px; color:#999;">
        &copy; 2025 Flavor Haven
    </footer>

</body>
</html>