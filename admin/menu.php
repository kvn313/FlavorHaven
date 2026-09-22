<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("location:../login.php");
    exit();
}

// Filtering
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$sql = "SELECT menu.*, category.categoryName 
        FROM menu 
        LEFT JOIN category ON menu.categoryID = category.categoryID";

if(!empty($filter_kategori)){
    $sql .= " WHERE menu.categoryID = '$filter_kategori'";
}

$sql .= " ORDER BY menu.menuID DESC";
$query = mysqli_query($koneksi, $sql);

// Ambil Data Kategori untuk Dropdown
$data_kategori = mysqli_query($koneksi, "SELECT * FROM category");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Flavor Haven</title>
    <link rel="icon" href="../img/logo_flavor_haven.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container">
        
        <div class="page-header">
            <h1 class="page-title">Daftar Menu</h1>
            <a href="menu_tambah.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Menu Baru
            </a>
        </div>

        <div class="filter-box">
            <span style="font-weight: 600; color: #555;"><i class="fas fa-filter"></i> Filter Kategori:</span>
            
            <form action="" method="GET" style="display:flex; gap:10px; flex-wrap:wrap;">
                <select name="kategori" class="filter-select" onchange="this.form.submit()">
                    <option value="">-- Tampilkan Semua --</option>
                    <?php 
                    while($kat = mysqli_fetch_array($data_kategori)){
                        $selected = ($filter_kategori == $kat['categoryID']) ? 'selected' : '';
                        echo "<option value='{$kat['categoryID']}' $selected>{$kat['categoryName']}</option>";
                    }
                    ?>
                </select>
                <?php if(!empty($filter_kategori)): ?>
                    <a href="menu.php" class="btn-delete" style="padding: 10px 15px; background: #999; text-decoration:none; border-radius:8px;">
                        <i class="fas fa-times"></i> Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Foto</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    
                    if(mysqli_num_rows($query) == 0){
                        echo "<tr><td colspan='7' style='text-align:center; padding:40px; color:#888;'>Menu tidak ditemukan pada kategori ini.</td></tr>";
                    }

                    while($d = mysqli_fetch_array($query)){
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <img src="../img/<?= $d['image']; ?>" alt="Menu Img">
                        </td>
                        <td>
                            <strong><?= $d['menuName']; ?></strong><br>
                            <small style="color:#999;"><?= substr($d['description'], 0, 30); ?>...</small>
                        </td>
                        <td>
                            <span style="background:#eee; padding:3px 8px; border-radius:4px; font-size:0.8rem; font-weight:600; color:#555;">
                                <?= $d['categoryName']; ?>
                            </span>
                        </td>
                        <td style="font-weight:bold; color:var(--primary);">
                            Rp <?= number_format($d['price']); ?>
                        </td>
                        <td>
                            <?php if($d['status'] == 'available'): ?>
                                <span style="color:green; font-weight:bold;"><i class="fas fa-check-circle"></i> Tersedia</span>
                            <?php else: ?>
                                <span style="color:red; font-weight:bold;"><i class="fas fa-times-circle"></i> Habis</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="menu_edit.php?id=<?= $d['menuID']; ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="menu_hapus.php?id=<?= $d['menuID']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Hapus menu ini?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>