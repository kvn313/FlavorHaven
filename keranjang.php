<?php
session_start();
include 'koneksi.php';

if(isset($_GET['aksi']) && isset($_GET['id'])){
    header("location: keranjang_act.php?aksi=".$_GET['aksi']."&id=".$_GET['id']);
    exit();
}

if(empty($_SESSION['keranjang']) || !isset($_SESSION['keranjang'])){
    echo "<script>
            alert('Keranjang masih kosong. Silakan pilih tipe pesanan terlebih dahulu.'); 
            location='index.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja - Flavor Haven</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/keranjang.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .btn-back-menu {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #f0f0f0;
            color: #333;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .btn-back-menu:hover {
            background-color: #e0e0e0;
            color: #000;
            transform: translateX(-3px);
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container">
        <div class="cart-container">
            
            <a href="menu.php" class="btn-back-menu">
                <i class="fas fa-arrow-left"></i> Kembali ke Menu
            </a>

            <h1 class="cart-title"><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h1>
            
            <div class="table-scroll">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th style="text-align:center;">Jumlah</th>
                            <th style="text-align:right;">Subtotal</th>
                            <th style="text-align:center;">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $subtotal_belanja = 0;
                        foreach($_SESSION['keranjang'] as $id_menu => $item): 
                            
                            if(is_array($item)){
                                $jumlah = $item['qty'];
                                $catatan = $item['catatan'];
                            } else {
                                $jumlah = $item;
                                $catatan = "";
                            }

                            $ambil = mysqli_query($koneksi, "SELECT menu.*, category.categoryName 
                                                            FROM menu 
                                                            LEFT JOIN category ON menu.categoryID = category.categoryID 
                                                            WHERE menu.menuID='$id_menu'");
                            $pecah = mysqli_fetch_assoc($ambil);
                            
                            $total_harga_item = $pecah['price'] * $jumlah;
                            $subtotal_belanja += $total_harga_item;
                        ?>
                        <tr>
                            <td>
                                <div class="item-flex">
                                    <img src="img/<?= $pecah['image']; ?>" class="item-img" alt="Menu">
                                    <div>
                                        <div class="item-name"><?= $pecah['menuName']; ?></div>
                                        <div class="item-cat"><?= $pecah['categoryName']; ?></div>
                                        
                                        <form action="keranjang_act.php" method="POST" class="note-edit-form">
                                            <input type="hidden" name="aksi" value="update_note">
                                            <input type="hidden" name="id_menu" value="<?= $id_menu; ?>">
                                            
                                            <input type="text" name="catatan" class="input-note-small" 
                                                value="<?= $catatan; ?>" placeholder="Tambah catatan...">
                                            
                                            <button type="submit" class="btn-save-note" title="Simpan Catatan">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </td>

                            <td>Rp <?= number_format($pecah['price']); ?></td>
                            
                            <td style="text-align:center;">
                                <div class="qty-control">
                                    <a href="keranjang_act.php?aksi=kurang&id=<?= $id_menu; ?>" class="btn-qty">-</a>
                                    <span class="qty-display"><?= $jumlah; ?></span>
                                    <a href="keranjang_act.php?aksi=tambah&id=<?= $id_menu; ?>" class="btn-qty">+</a>
                                </div>
                            </td>
                            
                            <td style="text-align:right; font-weight:bold;">
                                Rp <?= number_format($total_harga_item); ?>
                            </td>

                            <td style="text-align:center;">
                                <a href="keranjang_act.php?aksi=hapus&id=<?= $id_menu; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus menu ini?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div> <?php 
                $pajak = $subtotal_belanja * 0.10;
                $grand_total = $subtotal_belanja + $pajak;
            ?>

            <div class="total-area">
                <div class="sub-info">Subtotal: Rp <?= number_format($subtotal_belanja); ?></div>
                <div class="sub-info">Pajak (10%): Rp <?= number_format($pajak); ?></div>
                <hr style="width: 200px; margin-left: auto; margin-bottom: 10px; border: 0; border-top: 1px dashed #ccc;">
                
                <span class="total-label">Total Bayar:</span><br>
                <span class="total-amount">Rp <?= number_format($grand_total); ?></span>
                <br><br>
                <a href="checkout.php" class="btn-checkout">
                    Checkout Sekarang <i class="fas fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </main>

    <footer style="text-align:center; padding:30px; color:#999; margin-top:50px;">
        &copy; 2025 Flavor Haven
    </footer>

</body>
</html>