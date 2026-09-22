<?php
session_start();
include 'koneksi.php';

$id_menu = $_GET['id'];

// Ambil data menu + nama kategori
$query = mysqli_query($koneksi, "SELECT * FROM menu 
    JOIN category ON menu.categoryID = category.categoryID 
    WHERE menuID='$id_menu'");

$data = mysqli_fetch_array($query);

if(!$data){
    header("location:menu.php");
    exit();
}

//  
$is_logged_in = isset($_SESSION['status']) && $_SESSION['status'] == 'login';
$has_type     = isset($_SESSION['tipe_transaksi']); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['menuName']; ?> - Detail</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/menu.css"> 
    <link rel="stylesheet" href="css/menu_detail.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container">
        
        <a href="menu.php" class="btn-back-detail">
            <i class="fas fa-arrow-left"></i> Kembali ke Menu
        </a>

        <div class="detail-wrapper">
            <div class="detail-img-box">
                <img src="img/<?= $data['image']; ?>" alt="<?= $data['menuName']; ?>" class="detail-img-full">
            </div>

            <div class="detail-content">
                <h1 class="dt-title"><?= $data['menuName']; ?></h1>
                
                <div class="dt-meta">
                    <span class="tag tag-cat">
                        <i class="fas fa-tag"></i> <?= $data['categoryName']; ?>
                    </span>
                    
                    <?php if($data['status'] == 'available'): ?>
                        <span class="tag tag-avail"><i class="fas fa-check-circle"></i> Tersedia</span>
                    <?php else: ?>
                        <span class="tag tag-empty"><i class="fas fa-times-circle"></i> Stok Habis</span>
                    <?php endif; ?>
                </div>

                <p class="dt-desc">
                    <?= $data['description']; ?>
                </p>

                <div class="dt-price">
                    Rp <?= number_format($data['price']); ?>
                </div>

                <?php if($data['status'] == 'available'): ?>
                    
                    <form action="keranjang_act.php" method="POST">
                        <input type="hidden" name="id_menu" value="<?= $data['menuID']; ?>">
                        
                        <div class="form-group-note">
                            <label for="catatan"><i class="fas fa-pen"></i> Catatan Pesanan (Opsional)</label>
                            <textarea name="catatan" id="catatan" class="note-input" placeholder="Contoh: Jangan pedas, tidak pakai bawang, saus dipisah..."></textarea>
                        </div>

                        <div class="action-form">
                            <input type="number" name="qty" class="qty-input-detail" value="1" min="1" max="50" required>
                            
                            <?php if(!$is_logged_in): ?>
                                <button type="button" class="btn-add-cart-detail" onclick="alertLogin()">
                                    <i class="fas fa-lock"></i> Login untuk Memesan
                                </button>

                            <?php elseif(!$has_type): ?>
                                <button type="button" class="btn-add-cart-detail" onclick="alertType()">
                                    <i class="fas fa-exclamation-circle"></i> Tambah Pesanan
                                </button>

                            <?php else: ?>
                                <button type="submit" class="btn-add-cart-detail">
                                    <i class="fas fa-shopping-bag"></i> Tambah Pesanan
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>

                <?php else: ?>
                    <div class="action-form" style="border-top:1px solid #eee; padding-top:25px;">
                        <button class="btn-add-cart-detail" style="background: #ccc; cursor: not-allowed;">
                            <i class="fas fa-times-circle"></i> Stok Habis
                        </button>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </main>

    <footer style="text-align:center; padding:30px; color:#999; margin-top:50px;">
        &copy; 2025 Flavor Haven
    </footer>

    <script>
        function alertLogin() {
            alert("Mohon Maaf\n\nAnda harus Login terlebih dahulu untuk melakukan pemesanan.\n\nKami akan mengarahkan Anda ke halaman Login.");
            window.location.href = 'login.php';
        }

        function alertType() {
            alert("⚠️ Mohon Maaf\n\nAnda harus memilih tipe pesanan (Reservasi Tempat / Delivery) terlebih dahulu sebelum memasukkan menu ke keranjang.\n\nKami akan mengarahkan Anda ke halaman utama.");
            window.location.href = 'index.php';
        }
    </script>

</body>
</html>