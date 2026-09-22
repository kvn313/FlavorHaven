<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['status']) || $_SESSION['status'] != 'login'){
    header("location: login.php?pesan=belum_login");
    exit();
}

$id_user = $_SESSION['userID'];

// Filtering
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$where_status = "";

$allowed_status = ['pending', 'process', 'completed', 'cancelled'];
if(in_array($status_filter, $allowed_status)){
    $where_status = "AND status = '$status_filter'";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Flavor Haven</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/riwayat.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .filter-tabs {
            display: flex; gap: 10px; margin-bottom: 25px;
            overflow-x: auto; padding-bottom: 5px;
        }
        .filter-btn {
            padding: 8px 20px; border-radius: 50px;
            text-decoration: none; font-size: 0.9rem; font-weight: 600;
            border: 1px solid #ddd; color: #555; background: white;
            transition: 0.3s; white-space: nowrap;
        }
        .filter-btn:hover { border-color: var(--primary); color: var(--primary); }
        .filter-btn.active {
            background-color: var(--primary); color: white;
            border-color: var(--primary); box-shadow: 0 4px 10px rgba(110, 43, 43, 0.2);
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <section class="section-history">
        <div class="container">
            
            <div class="page-title">
                <h2>Riwayat Pesanan</h2>
                <p>Pantau status pesanan Anda di sini</p>
            </div>

            <div class="filter-tabs">
                <a href="riwayat.php" class="filter-btn <?= ($status_filter == '') ? 'active' : ''; ?>">Semua</a>
                <a href="riwayat.php?status=pending" class="filter-btn <?= ($status_filter == 'pending') ? 'active' : ''; ?>">Pending</a>
                <a href="riwayat.php?status=process" class="filter-btn <?= ($status_filter == 'process') ? 'active' : ''; ?>">Diproses</a>
                <a href="riwayat.php?status=completed" class="filter-btn <?= ($status_filter == 'completed') ? 'active' : ''; ?>">Selesai</a>
            </div>

            <div class="history-list">
                <?php
                $query = mysqli_query($koneksi, "SELECT * FROM transactions WHERE userID='$id_user' $where_status ORDER BY transactionID DESC");
                
                if(mysqli_num_rows($query) == 0){
                    echo '<div class="empty-state">
                            <i class="fas fa-search" style="font-size: 3rem; color: #ddd; margin-bottom: 15px;"></i>
                            <p>Tidak ada pesanan dengan status ini.</p>
                            <a href="riwayat.php" style="color:var(--primary); font-weight:600; font-size:0.9rem;">Lihat Semua Pesanan</a>
                          </div>';
                }

                while($row = mysqli_fetch_assoc($query)){
                    $st_class = 'st-pending';
                    $icon = 'fa-clock';
                    if($row['status'] == 'process') { $st_class = 'st-process'; $icon = 'fa-fire'; }
                    if($row['status'] == 'completed') { $st_class = 'st-completed'; $icon = 'fa-check'; }
                    if($row['status'] == 'cancelled') { $st_class = 'st-cancelled'; $icon = 'fa-times'; }

                    // Format Tanggal (Tanpa Jam)
                    $tgl_fix = date('d M Y', strtotime($row['transactionDate']));
                ?>
                
                <div class="history-row">
                    <div class="hr-left">
                        <div class="hr-header">
                            <span class="hr-id">Order #<?= $row['transactionID']; ?></span>
                            
                            <?php if($row['type'] == 'Delivery'): ?>
                                <span class="badge type-deliv"><i class="fas fa-motorcycle"></i> Delivery</span>
                            <?php elseif($row['type'] == 'Reservation'): ?>
                                <span class="badge type-res"><i class="fas fa-chair"></i> Reservasi</span>
                            <?php else: ?>
                                <span class="badge type-dine"><i class="fas fa-utensils"></i> Dine In</span>
                            <?php endif; ?>
                        </div>

                        <div class="hr-meta">
                            <span class="hr-date">
                                <i class="far fa-calendar-alt"></i> <?= $tgl_fix; ?>
                            </span>
                            
                            <span class="badge <?= $st_class; ?>">
                                <i class="fas <?= $icon; ?>"></i> <?= ucfirst($row['status']); ?>
                            </span>
                        </div>
                    </div>

                    <div class="hr-right">
                        <div class="hr-price">Rp <?= number_format($row['totalAmount']); ?></div>
                        <a href="riwayat_detail.php?id=<?= $row['transactionID']; ?>" class="btn-detail-row">
                            Detail <i class="fas fa-chevron-right" style="font-size: 0.8rem; margin-left:5px;"></i>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>

        </div>
    </section>

    <footer>
        <div class="container">
            <p>© 2025 Flavor Haven. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>