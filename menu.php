<?php 
session_start();
include 'koneksi.php'; 

// Cek Tipe Pesanan
$sudah_pilih_mode = isset($_SESSION['tipe_transaksi']);

if(isset($_POST['mode_ajax']) && isset($_POST['id']) && isset($_POST['aksi'])){
    
    $id = $_POST['id'];
    $aksi = $_POST['aksi'];

    if(!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];

    if($aksi == 'tambah'){
        if(isset($_SESSION['keranjang'][$id])){
            if(is_array($_SESSION['keranjang'][$id])) $_SESSION['keranjang'][$id]['qty']++;
            else $_SESSION['keranjang'][$id]++;
        } else {
            $_SESSION['keranjang'][$id] = 1;
        }
    } 
    elseif($aksi == 'kurang'){
        if(isset($_SESSION['keranjang'][$id])){
            if(is_array($_SESSION['keranjang'][$id])){
                $_SESSION['keranjang'][$id]['qty']--;
                if($_SESSION['keranjang'][$id]['qty'] <= 0) unset($_SESSION['keranjang'][$id]);
            } else {
                $_SESSION['keranjang'][$id]--;
                if($_SESSION['keranjang'][$id] <= 0) unset($_SESSION['keranjang'][$id]);
            }
        }
    }

    // untuk badge keranjang
    $total_item = 0;
    foreach($_SESSION['keranjang'] as $item){
        $total_item += (is_array($item) ? $item['qty'] : $item);
    }

    $qty_sekarang = 0;
    if(isset($_SESSION['keranjang'][$id])){
        $qty_sekarang = is_array($_SESSION['keranjang'][$id]) ? $_SESSION['keranjang'][$id]['qty'] : $_SESSION['keranjang'][$id];
    }
    echo $total_item . "#"; 

    if($qty_sekarang > 0){
        echo '<div class="qty-control">
                <button type="button" onclick="ubahPesanan('.$id.', \'kurang\')" class="btn-qty" style="border:none; cursor:pointer;">-</button>
                <div class="qty-val">'.$qty_sekarang.'</div>
                <button type="button" onclick="ubahPesanan('.$id.', \'tambah\')" class="btn-qty" style="border:none; cursor:pointer;">+</button>
              </div>';
    } else {
        echo '<button type="button" onclick="ubahPesanan('.$id.', \'tambah\')" class="btn-add">
                + Tambah
              </button>';
    }
    
    exit(); 
}


if(isset($_GET['cari'])){
    $cari = $_GET['cari'];
    $query_menu = mysqli_query($koneksi, "SELECT * FROM menu WHERE menuName LIKE '%$cari%' ORDER BY categoryID ASC");
}
elseif(isset($_GET['kategori'])){
    $kategoriID = $_GET['kategori'];
    $query_menu = mysqli_query($koneksi, "SELECT * FROM menu WHERE categoryID = '$kategoriID'");
}
else {
    $query_menu = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY categoryID ASC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu - Flavor Haven</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <section class="menu-hero">
        <h2 style="color:#333; margin-bottom:15px;">Mau makan apa hari ini?</h2>
        
        <?php if(!$sudah_pilih_mode): ?>
            <div style="background: #fff3cd; color: #856404; padding: 10px 20px; border-radius: 50px; font-size: 0.95rem; margin-bottom: 15px; display:inline-block; border: 1px solid #ffeeba;">
                <i class="fas fa-exclamation-circle"></i> 
                <strong>Perhatian:</strong> Silakan pilih tipe pesanan (Reservasi/Delivery) dulu sebelum belanja.
            </div>
        <?php endif; ?>

        <form action="menu.php" method="GET" class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="cari" placeholder="Cari menu..." value="<?= isset($_GET['cari']) ? $_GET['cari'] : '' ?>">
        </form>
    </section>

    <section class="category-scroll">
        <a href="menu.php" class="cat-pill <?= !isset($_GET['kategori']) ? 'active' : ''; ?>">
            Semua
        </a>
        <?php
        $q_kat = mysqli_query($koneksi, "SELECT * FROM category");
        while($k = mysqli_fetch_array($q_kat)){
            $isActive = (isset($_GET['kategori']) && $_GET['kategori'] == $k['categoryID']) ? 'active' : '';
            echo "<a href='menu.php?kategori={$k['categoryID']}' class='cat-pill $isActive'>
                    {$k['categoryName']}
                  </a>";
        }
        ?>
    </section>

    <main class="container-menu">
        <div class="menu-grid">
            <?php 
            if(mysqli_num_rows($query_menu) == 0){
                echo "<div style='grid-column:1/-1; text-align:center; padding:50px; color:#888;'>Menu tidak ditemukan.</div>";
            }

            while($d = mysqli_fetch_array($query_menu)){
                $id = $d['menuID'];
                
                $qty = 0;
                if(isset($_SESSION['keranjang'][$id])){
                    $qty = is_array($_SESSION['keranjang'][$id]) ? $_SESSION['keranjang'][$id]['qty'] : $_SESSION['keranjang'][$id];
                }
                
                $isAvailable = ($d['status'] == 'available');
                $classHabis = !$isAvailable ? 'habis' : '';
            ?>
            <article class="menu-card <?= $classHabis; ?>">
                <div class="card-img-wrapper">
                    <?php if(!$isAvailable): ?>
                        <div class="badge-habis">STOK HABIS</div>
                    <?php endif; ?>
                    
                    <a href="menu_detail.php?id=<?= $id; ?>">
                        <img src="img/<?= $d['image']; ?>" alt="<?= $d['menuName']; ?>" class="menu-img">
                    </a>
                </div>
                <div class="card-body">
                    <a href="menu_detail.php?id=<?= $id; ?>" class="card-title"><?= $d['menuName']; ?></a>
                    <p class="card-desc"><?= substr($d['description'], 0, 60) . '...'; ?></p>

                    <div class="card-footer">
                        <span class="price">Rp <?= number_format($d['price']); ?></span>
                        
                        <div id="tombol-menu-<?= $id; ?>">
                            <?php if ($isAvailable): ?>
                                <?php if ($sudah_pilih_mode): ?>
                                    
                                    <?php if($qty > 0): ?>
                                        <div class="qty-control">
                                            <button type="button" onclick="ubahPesanan(<?= $id; ?>, 'kurang')" class="btn-qty" style="border:none; cursor:pointer;">-</button>
                                            <div class="qty-val"><?= $qty; ?></div>
                                            <button type="button" onclick="ubahPesanan(<?= $id; ?>, 'tambah')" class="btn-qty" style="border:none; cursor:pointer;">+</button>
                                        </div>
                                    <?php else: ?>
                                        <button type="button" onclick="ubahPesanan(<?= $id; ?>, 'tambah')" class="btn-add">
                                            + Tambah
                                        </button>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <button onclick="alert('Silakan pilih tipe pesanan dulu di Home!'); window.location='index.php';" class="btn-add" style="opacity: 0.6;">
                                        + Tambah
                                    </button>
                                <?php endif; ?>

                            <?php else: ?>
                                <button class="btn-habis" disabled>Habis</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </article>
            <?php } ?>
        </div>
    </main>
    
    <footer style="text-align:center; padding:30px; color:#999; margin-top:50px;">
        &copy; 2025 Flavor Haven
    </footer>

    <script>
    function ubahPesanan(idMenu, aksi) {
        let formData = new FormData();
        formData.append('mode_ajax', 'yes');
        formData.append('id', idMenu);
        formData.append('aksi', aksi);

        fetch('menu.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(hasil => {
            let data = hasil.split("#");
            let totalKeranjang = data[0]; 
            let htmlTombolBaru = data[1];

            document.getElementById('tombol-menu-' + idMenu).innerHTML = htmlTombolBaru;

            let badges = document.querySelectorAll('.cart-badge');
            badges.forEach(el => {
                if(totalKeranjang > 0){
                    el.style.display = 'inline-block';
                    el.innerText = totalKeranjang;
                } else {
                    el.style.display = 'none';
                }
            });
        });
    }
    </script>

</body>
</html>