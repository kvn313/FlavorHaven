<?php
session_start();
include '../koneksi.php';

// Cek Admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("location:../login.php");
    exit();
}

$tgl_hari_ini = date('Y-m-d');
$bulan_ini    = date('m');
$tahun_ini    = date('Y');

// Omset Hari Ini
$q_day = mysqli_query($koneksi, "
    SELECT SUM(totalAmount) as total 
    FROM transactions 
    WHERE status = 'completed' AND transactionDate = '$tgl_hari_ini'
");
$d_day = mysqli_fetch_assoc($q_day);
$omset_harian = $d_day['total'] ?: 0;

// Omset Bulan Ini
$q_month = mysqli_query($koneksi, "
    SELECT SUM(totalAmount) as total 
    FROM transactions 
    WHERE status = 'completed' AND MONTH(transactionDate) = '$bulan_ini' AND YEAR(transactionDate) = '$tahun_ini'
");
$d_month = mysqli_fetch_assoc($q_month);
$omset_bulanan = $d_month['total'] ?: 0;

// Jumlah Transaksi Hari Ini
$q_tx_day = mysqli_query($koneksi, "
    SELECT COUNT(*) as jumlah 
    FROM transactions 
    WHERE transactionDate = '$tgl_hari_ini'
");
$d_tx_day = mysqli_fetch_assoc($q_tx_day);
$transaksi_harian = $d_tx_day['jumlah'] ?: 0;

// Transaksi yang butuh tindakan (warning)
$q_pending = mysqli_query($koneksi, "
    SELECT COUNT(*) as jumlah FROM transactions WHERE status = 'pending'
");
$d_pending = mysqli_fetch_assoc($q_pending);
$total_pending = $d_pending['jumlah'] ?: 0;

// 5 Transaksi TERBARU
$q_recent = mysqli_query($koneksi, "
    SELECT * FROM transactions ORDER BY transactionID DESC LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Flavor Haven</title>
    <link rel="icon" href="../img/logo_flavor_haven.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container">
        
        <div class="page-header">
            <div>
                <h1 class="page-title">Dashboard Overview</h1>
                <p style="color:#777; margin-top:5px;">Performa restoran hari ini</p>
            </div>
            <div style="text-align:right;">
                <span style="font-weight:600; color:#555;">
                    <i class="far fa-calendar-alt"></i> <?= date('d M Y'); ?>
                </span>
            </div>
        </div>

        <div class="dashboard-grid">
            
            <a href="laporan.php" class="card-omset">
                <div class="info">
                    <p>Pendapatan Bulan Ini</p>
                    <h3>Rp <?= number_format($omset_bulanan); ?></h3>
                    <span style="font-size:0.85rem; opacity:0.8;">*Akumulasi <?= date('F Y'); ?></span>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </a>

            <div class="card-mini border-green">
                <div class="info">
                    <h3>Rp <?= number_format($omset_harian); ?></h3>
                    <p>Omset Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-coins" style="color:#28a745;"></i>
                </div>
            </div>

            <div class="card-mini border-blue">
                <div class="info">
                    <h3><?= $transaksi_harian; ?></h3>
                    <p>Transaksi Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-receipt" style="color:#007bff;"></i>
                </div>
            </div>

            </div>

        <div class="page-header" style="margin-top:40px; margin-bottom:15px; border:none; padding:0; background:transparent;">
            <h2 style="font-size:1.3rem; color:#333;">
                <i class="fas fa-history"></i> Pesanan Terbaru
            </h2>
            <?php if($total_pending > 0): ?>
                <a href="transaksi.php?status=pending" class="badge bg-pending" style="font-size:0.9rem; text-decoration:none;">
                    <i class="fas fa-exclamation-circle"></i> <?= $total_pending; ?> Menunggu Konfirmasi
                </a>
            <?php endif; ?>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Tipe</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($q_recent) == 0){
                        echo "<tr><td colspan='7' style='text-align:center; padding:20px; color:#999;'>Belum ada transaksi.</td></tr>";
                    }
                    while($d = mysqli_fetch_assoc($q_recent)){ 
                        // Cek nama user
                        $nama_cust = "Guest";
                        if($d['userID']){
                            $u = mysqli_query($koneksi, "SELECT nama FROM users WHERE userID='".$d['userID']."'");
                            $du = mysqli_fetch_assoc($u);
                            $nama_cust = $du['nama'] ?? 'User Hapus';
                        }
                    ?>
                    <tr>
                        <td>#<?= $d['transactionID']; ?></td>
                        <td><?= date('d/m/Y', strtotime($d['transactionDate'])); ?></td>
                        <td style="font-weight:600;"><?= $nama_cust; ?></td>
                        <td>
                            <?php if($d['type'] == 'Delivery'): ?>
                                <span style="color:#007bff;">Delivery</span>
                            <?php elseif($d['type'] == 'Reservation'): ?>
                                <span style="color:#6f42c1;">Reservation</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight:bold;">Rp <?= number_format($d['totalAmount']); ?></td>
                        <td>
                            <?php 
                                if($d['status']=='pending') echo '<span class="badge bg-pending">PENDING</span>';
                                elseif($d['status']=='process') echo '<span class="badge bg-process">PROCESS</span>';
                                elseif($d['status']=='completed') echo '<span class="badge bg-completed">COMPLETED</span>';
                                else echo '<span class="badge bg-cancelled">CANCELLED</span>';
                            ?>
                        </td>
                        <td>
                            <a href="transaksi_detail.php?id=<?= $d['transactionID']; ?>" class="btn btn-sm btn-primary">Lihat</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div style="text-align:center; margin-top:15px;">
                <a href="transaksi.php" style="text-decoration:none; font-weight:600; color:var(--primary);">
                    Lihat Semua Transaksi <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

    </main>

</body>
</html>