<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("location:../login.php");
    exit();
}

$id = $_GET['id'];
$ambil = mysqli_query($koneksi, "SELECT * FROM transactions WHERE transactionID='$id'");
$detail = mysqli_fetch_assoc($ambil);

$items = mysqli_query($koneksi, "SELECT transactionsdetail.*, menu.menuName, menu.image 
                                 FROM transactionsdetail 
                                 JOIN menu ON transactionsdetail.menuID = menu.menuID 
                                 WHERE transactionID='$id'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi - Flavor Haven</title>
    <link rel="icon" href="../img/logo_flavor_haven.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .detail-card { 
            background: white; 
            padding: 25px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            margin-bottom: 20px; 
        }

        .info-row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 10px; 
            border-bottom: 1px dashed #eee; 
            padding-bottom: 10px; 
        }

        .info-label { 
            font-weight: 600; 
            color: #777; 
        }

        .info-val { 
            font-weight: bold; 
            color: #333; 
            text-align: right; 
        }

        .status-select { 
            padding: 10px; 
            border-radius: 5px; 
            border: 1px solid #ccc; 
            width: 100%; 
            margin-top: 10px; 
        }
        
        .btn-back-top {
            display: inline-flex; 
            align-items: center; 
            gap: 8px;
            background: #777; 
            color: white; 
            padding: 10px 20px;
            border-radius: 8px; 
            text-decoration: none; 
            font-weight: 600;
            margin-bottom: 20px; 
            transition: 0.3s;
        }

        .btn-back-top:hover { 
            background: #555; 
            transform: translateX(-5px); 
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container">
        
        <a href="transaksi.php" class="btn-back-top">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <div class="page-header" style="justify-content: flex-start;">
            <h1 class="page-title">Detail Pesanan #<?= $detail['transactionID']; ?></h1>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
            
            <div class="detail-card">
                <h3 style="margin-bottom:20px; border-bottom:2px solid var(--primary); padding-bottom:10px;">Item Pesanan</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Catatan</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $subtotal_murni = 0; 
                        while($i = mysqli_fetch_assoc($items)){ 
                            $subtotal_item = $i['unitPrice'] * $i['quantity'];
                            $subtotal_murni += $subtotal_item;
                        ?>
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <img src="../img/<?= $i['image']; ?>" style="width:50px; height:50px; border-radius:5px;">
                                    <?= $i['menuName']; ?>
                                </div>
                            </td>
                            
                            <td>
                                <?php if(!empty($i['note'])): ?>
                                    <small style="color:#d35400; font-style:italic; font-weight:600;">
                                        <?= $i['note']; ?>
                                    </small>
                                <?php else: ?>
                                    <span style="color:#ccc;">-</span>
                                <?php endif; ?>
                            </td>

                            <td>Rp <?= number_format($i['unitPrice']); ?></td>
                            <td><?= $i['quantity']; ?></td>
                            <td style="font-weight:bold;">Rp <?= number_format($subtotal_item); ?></td>
                        </tr>
                        <?php } ?>
                        
                        <?php 
                            // Hitung Pajak
                            $pajak = $subtotal_murni * 0.10;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align:right;">Subtotal</td>
                            <td style="font-weight:bold;">Rp <?= number_format($subtotal_murni); ?></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right;">Pajak (10%)</td>
                            <td style="font-weight:bold;">Rp <?= number_format($pajak); ?></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right; font-size:1.2rem; font-weight:bold; color:var(--primary);">TOTAL BAYAR</td>
                            <td style="font-size:1.2rem; font-weight:bold; color:var(--primary);">Rp <?= number_format($detail['totalAmount']); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div>
                <div class="detail-card">
                    <h3 style="margin-bottom:15px; color:var(--primary);">Info Pesanan</h3>
                    
                    <div class="info-row">
                        <span class="info-label">Tipe</span>
                        <span class="info-val"><?= $detail['type']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Metode Bayar</span>
                        <span class="info-val"><?= $detail['paymentMethod']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span class="info-val"><?= date('d M Y', strtotime($detail['transactionDate'])); ?></span>
                    </div>

                    <?php if($detail['type'] == 'Delivery'): ?>
                        <div style="margin-top:15px; background:#f9f9f9; padding:10px; border-radius:8px;">
                            <strong>Alamat:</strong><br>
                            <?= $detail['deliveryAddress']; ?><br><br>
                            <strong>Telepon:</strong><br>
                            <?= $detail['deliveryPhone']; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($detail['type'] == 'Reservation'): ?>
                        <div style="margin-top:15px; background:#f9f9f9; padding:10px; border-radius:8px;">
                            <strong>Booking:</strong> <?= $detail['bookingDate']; ?><br>
                            <strong>Jam: </strong><?= $detail['bookingTime']; ?><br>
                            <strong>Pax:</strong> <?= $detail['pax']; ?> Orang
                        </div>
                    <?php endif; ?>
                </div>

                <div class="detail-card">
                    <h3 style="margin-bottom:15px; color:var(--primary);">Update Status</h3>
                    <form action="transaksi_act.php" method="POST">
                        <input type="hidden" name="id" value="<?= $detail['transactionID']; ?>">
                        
                        <label style="font-weight:600;">Status Sekarang:</label>
                        <select name="status" class="status-select">
                            <option value="pending" <?= ($detail['status']=='pending')?'selected':''; ?>>Pending (Menunggu)</option>
                            <option value="process" <?= ($detail['status']=='process')?'selected':''; ?>>Process (Diproses)</option>
                            <option value="completed" <?= ($detail['status']=='completed')?'selected':''; ?>>Completed (Selesai)</option>
                        </select>

                        <button type="submit" name="update_status" class="btn btn-primary" style="width:100%; margin-top:15px;">
                            <i class="fas fa-sync-alt"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>

</body>
</html>