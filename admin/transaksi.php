<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("location:../login.php");
    exit();
}

// Filtering
$where_clauses = [];

// Filter Tanggal
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';

if(!empty($tgl_mulai) && !empty($tgl_selesai)){
    $where_clauses[] = "transactionDate BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}

// Filter Tipe Pesanan
$filter_tipe = isset($_GET['tipe']) ? $_GET['tipe'] : '';
if(!empty($filter_tipe)){
    $where_clauses[] = "type = '$filter_tipe'";
}

// Filter Status
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
if(!empty($filter_status)){
    $where_clauses[] = "status = '$filter_status'";
}


$sql = "SELECT * FROM transactions";
if(count($where_clauses) > 0){
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}
$sql .= " ORDER BY transactionID DESC";

$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi - Flavor Haven</title>
    <link rel="icon" href="../img/logo_flavor_haven.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container">
        <div class="page-header">
            <h1 class="page-title"><i class="fas fa-receipt"></i> Data Transaksi</h1>
        </div>

        <div class="filter-card">
            <form action="" method="GET" class="filter-form">
                
                <div class="form-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai; ?>">
                </div>

                <div class="form-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai; ?>">
                </div>

                <div class="form-group">
                    <label>Tipe Pesanan</label>
                    <select name="tipe" class="form-control">
                        <option value="">-- Semua Tipe --</option>
                        <option value="Delivery" <?= ($filter_tipe == 'Delivery') ? 'selected' : ''; ?>>Delivery (Antar)</option>
                        <option value="Reservation" <?= ($filter_tipe == 'Reservation') ? 'selected' : ''; ?>>Reservation (Reservasi)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" <?= ($filter_status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="process" <?= ($filter_status == 'process') ? 'selected' : ''; ?>>Process</option>
                        <option value="completed" <?= ($filter_status == 'completed') ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>

                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
                    <a href="transaksi.php" class="btn-reset"><i class="fas fa-sync-alt"></i> Reset</a>
                </div>

            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl</th>
                        <th>Pelanggan</th>
                        <th>Tipe</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($query) == 0){
                        echo "<tr><td colspan='7' style='text-align:center; padding:30px; color:#888;'>Tidak ada data transaksi yang cocok dengan filter.</td></tr>";
                    }

                    while($d = mysqli_fetch_array($query)){
                        $statusClass = 'bg-pending';
                        if($d['status'] == 'process') $statusClass = 'bg-process';
                        if($d['status'] == 'completed') $statusClass = 'bg-completed';
                        if($d['status'] == 'cancelled') $statusClass = 'bg-cancelled';
                        
                        $pelanggan = "Guest";
                        if($d['userID']){
                            $u = mysqli_query($koneksi, "SELECT nama FROM users WHERE userID='{$d['userID']}'");
                            $du = mysqli_fetch_assoc($u);
                            $pelanggan = $du['nama'] ?? 'User Terhapus';
                        } elseif($d['guestName']) {
                            $pelanggan = $d['guestName'] . " (Guest)";
                        }
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= date('d/m/Y', strtotime($d['transactionDate'])); ?></td>
                        <td style="font-weight:600;"><?= $pelanggan; ?></td>
                        <td>
                            <?php if($d['type'] == 'Delivery'): ?>
                                <span style="color:#007bff;">Delivery</span>
                            <?php elseif($d['type'] == 'Reservation'): ?>
                                <span style="color:#6f42c1;">Reservation</span>
                            <?php else: ?>
                                <?= $d['type']; ?> <?php endif; ?>
                        </td>
                        <td style="font-weight:bold;">Rp <?= number_format($d['totalAmount']); ?></td>
                        <td>
                            <span class="badge <?= $statusClass; ?>"><?= strtoupper($d['status']); ?></span>
                        </td>
                        <td>
                            <a href="transaksi_detail.php?id=<?= $d['transactionID']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>