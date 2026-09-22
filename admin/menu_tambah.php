<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("location:../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu - Flavor Haven</title>
    <link rel="icon" href="../img/logo_flavor_haven.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-container {
            background: white; padding: 30px; border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        .form-control {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;
            font-size: 1rem; font-family: inherit;
        }
        .form-control:focus { border-color: var(--primary); outline: none; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container">
        
        <div class="page-header" style="justify-content: flex-start; gap: 20px;">
            <a href="menu.php" class="btn btn-primary" style="background:#777;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="page-title"><i class="fas fa-plus-circle"></i> Tambah Menu Baru</h1>
        </div>

        <div class="form-container">
            <form action="menu_tambah_act.php" method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label>Nama Menu</label>
                    <input type="text" name="nama" class="form-control" required placeholder="Contoh: Nasi Goreng Spesial">
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php 
                        $kat = mysqli_query($koneksi, "SELECT * FROM category");
                        while($k = mysqli_fetch_array($kat)){
                            echo "<option value='{$k['categoryID']}'>{$k['categoryName']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" required placeholder="Contoh: 25000">
                </div>

                <div class="form-group">
                    <label>Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Penjelasan singkat menu..."></textarea>
                </div>

                <div class="form-group">
                    <label>Foto Menu</label>
                    <input type="file" name="foto" class="form-control" required>
                    <small style="color:#999;">Format: JPG, PNG. Maks 2MB.</small>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px;">
                    <i class="fas fa-save"></i> Simpan Menu
                </button>

            </form>
        </div>
    </main>

</body>
</html>