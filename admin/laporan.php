<?php
session_start();
include '../koneksi.php';

// Cek Admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("location:../login.php");
    exit();
}

// Filter
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : date('Y-m-01');
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : date('Y-m-d');

// Data-Data Laporan
$q_utama = mysqli_query($koneksi, "
    SELECT 
        COUNT(*) as total_transaksi,
        SUM(totalAmount) as omset_kotor
    FROM transactions 
    WHERE status = 'completed' 
    AND transactionDate BETWEEN '$tgl_mulai' AND '$tgl_selesai'
");
$d_utama = mysqli_fetch_assoc($q_utama);

$total_transaksi = $d_utama['total_transaksi'] ?: 0;
$omset_kotor     = $d_utama['omset_kotor'] ?: 0;

$omset_bersih = $omset_kotor / 1.1;
$total_pajak  = $omset_kotor - $omset_bersih;
$rata_rata    = ($total_transaksi > 0) ? ($omset_kotor / $total_transaksi) : 0;


// 5 Menu Terlaris
$q_top_menu = mysqli_query($koneksi, "
    SELECT menu.menuName, SUM(transactionsdetail.quantity) as terjual
    FROM transactionsdetail
    JOIN transactions ON transactionsdetail.transactionID = transactions.transactionID
    JOIN menu ON transactionsdetail.menuID = menu.menuID
    WHERE transactions.status = 'completed' 
    AND transactions.transactionDate BETWEEN '$tgl_mulai' AND '$tgl_selesai'
    GROUP BY menu.menuID
    ORDER BY terjual DESC
    LIMIT 5
");


// Penjualan per kategori
$q_kategori = mysqli_query($koneksi, "
    SELECT category.categoryName, SUM(transactionsdetail.quantity) as qty_cat
    FROM transactionsdetail
    JOIN transactions ON transactionsdetail.transactionID = transactions.transactionID
    JOIN menu ON transactionsdetail.menuID = menu.menuID
    JOIN category ON menu.categoryID = category.categoryID
    WHERE transactions.status = 'completed' 
    AND transactions.transactionDate BETWEEN '$tgl_mulai' AND '$tgl_selesai'
    GROUP BY category.categoryID
");


// Metode Pembayaran yang dipakai
$q_metode = mysqli_query($koneksi, "
    SELECT paymentMethod, COUNT(*) as jumlah
    FROM transactions
    WHERE status = 'completed'
    AND transactionDate BETWEEN '$tgl_mulai' AND '$tgl_selesai'
    GROUP BY paymentMethod
    ORDER BY jumlah DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <link rel="icon" href="../img/logo_flavor_haven.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container">
        
        <div class="page-header">
            <h1 class="page-title">Laporan Penjualan</h1>
        </div>

        <form class="report-filter" method="GET">
            <div class="form-group" style="flex:1;">
                <label>Dari Tanggal</label>
                <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai; ?>">
            </div>
            <div class="form-group" style="flex:1;">
                <label>Sampai Tanggal</label>
                <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai; ?>">
            </div>
            <button type="submit" class="btn btn-primary" style="height:45px; margin-bottom:1px;">
                Tampilkan
            </button>
        </form>

        <div class="stats-container">
            <div class="stat-card stat-primary">
                <h4>TOTAL OMSET</h4>
                <div class="value">Rp <?= number_format($omset_kotor); ?></div>
                <div class="desc">Termasuk Pajak</div>
            </div>

            <div class="stat-card stat-success">
                <h4>ESTIMASI PAJAK (10%)</h4>
                <div class="value" style="color:#28a745;">Rp <?= number_format($total_pajak); ?></div>
                <div class="desc">Pendapatan Bersih: Rp <?= number_format($omset_bersih); ?></div>
            </div>

            <div class="stat-card stat-warning">
                <h4>TOTAL TRANSAKSI</h4>
                <div class="value" style="color:#ffc107;"><?= $total_transaksi; ?></div>
                <div class="desc">Pesanan Selesai</div>
            </div>

            <div class="stat-card stat-info">
                <h4>RATA-RATA</h4>
                <div class="value" style="color:#17a2b8;">Rp <?= number_format($rata_rata); ?></div>
                <div class="desc">Per Transaksi</div>
            </div>
        </div>

        <div class="report-grid">
            
            <div class="report-box">
                <div class="report-title">Menu Terlaris (Top 5)</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Menu</th>
                            <th style="text-align:right;">Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if(mysqli_num_rows($q_top_menu) == 0) {
                            echo "<tr><td colspan='3' align='center' style='padding:20px; color:#999;'>Belum ada data.</td></tr>";
                        }
                        while($m = mysqli_fetch_assoc($q_top_menu)){ ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td style="font-weight:600;"><?= $m['menuName']; ?></td>
                            <td style="text-align:right; font-weight:bold;">
                                <?= $m['terjual']; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="report-box">
                <div class="report-title">Per Kategori</div>
                <table class="table">
                    <?php 
                    if(mysqli_num_rows($q_kategori) == 0) echo "<p style='color:#999; text-align:center;'>Tidak ada data.</p>";
                    while($c = mysqli_fetch_assoc($q_kategori)){ ?>
                    <tr>
                        <td style="border:none; padding: 8px 0; color:#555;"><?= $c['categoryName']; ?></td>
                        <td style="border:none; padding: 8px 0; text-align:right; font-weight:bold;">
                            <?= $c['qty_cat']; ?> Item
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="report-box">
                <div class="report-title">Metode Pembayaran</div>
                <table class="table">
                    <?php 
                    if(mysqli_num_rows($q_metode) == 0) echo "<p style='color:#999; text-align:center;'>Tidak ada data.</p>";
                    while($p = mysqli_fetch_assoc($q_metode)){ ?>
                    <tr>
                        <td style="border:none; padding: 8px 0; color:#555;"><?= $p['paymentMethod']; ?></td>
                        <td style="border:none; padding: 8px 0; text-align:right; font-weight:bold;">
                            <?= $p['jumlah']; ?> x
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>

        </div>

    </main>

</body>
</html>