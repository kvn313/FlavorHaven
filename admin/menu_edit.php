<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("location:../login.php");
    exit();
}

$id = $_GET['id'];
$ambil = mysqli_query($koneksi, "SELECT * FROM menu WHERE menuID='$id'");
$data = mysqli_fetch_assoc($ambil);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu - Flavor Haven</title>
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
        .img-preview { width: 100px; border-radius: 8px; margin-top: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container">
        
        <div class="page-header" style="justify-content: flex-start; gap: 20px;">
            <a href="menu.php" class="btn btn-primary" style="background:#777;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="page-title"><i class="fas fa-edit"></i> Edit Menu</h1>
        </div>

        <div class="form-container">
            <form action="menu_edit_act.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $data['menuID']; ?>">
                
                <div class="form-group">
                    <label>Nama Menu</label>
                    <input type="text" name="nama" class="form-control" value="<?= $data['menuName']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" class="form-control" required>
                        <?php 
                        $kat = mysqli_query($koneksi, "SELECT * FROM category");
                        while($k = mysqli_fetch_array($kat)){
                            $selected = ($k['categoryID'] == $data['categoryID']) ? 'selected' : '';
                            echo "<option value='{$k['categoryID']}' $selected>{$k['categoryName']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" value="<?= $data['price']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"><?= $data['description']; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="available" <?= ($data['status']=='available')?'selected':''; ?>>Tersedia</option>
                        <option value="out of stock" <?= ($data['status']=='out of stock')?'selected':''; ?>>Habis</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Foto Menu (Biarkan kosong jika tidak diganti)</label>
                    <input type="file" name="foto" class="form-control">
                    <br>
                    <small>Foto saat ini:</small><br>
                    <img src="../img/<?= $data['image']; ?>" class="img-preview">
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>

            </form>
        </div>
    </main>

</body>
</html>