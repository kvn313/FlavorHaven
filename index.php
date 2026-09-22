<?php 
session_start(); 
include 'koneksi.php';

unset($_SESSION['tipe_transaksi']);
unset($_SESSION['data_pesanan']); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flavor Haven - Restoran & Delivery</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <section class="hero">
        <div class="container hero-content">
            <h1>Selamat Datang di <br><span>Flavor Haven</span></h1>
            
            <p>
                Nikmati pengalaman kuliner terbaik. Silahkan pilih layanan yang Anda inginkan hari ini.
            </p>
            
            <div class="mode-selection">
                <a href="set_mode.php?mode=reservation" class="mode-card">
                    <div class="mode-icon"><i class="fas fa-chair"></i></div>
                    <h3>Reservasi Tempat</h3>
                    <p>Booking meja untuk makan di tempat (Dine In).</p>
                    <span class="btn-arrow">Pesan Sekarang <i class="fas fa-arrow-right"></i></span>
                </a>

                <a href="set_mode.php?mode=delivery" class="mode-card">
                    <div class="mode-icon"><i class="fas fa-motorcycle"></i></div>
                    <h3>Pesan Antar (Delivery)</h3>
                    <p>Makanan lezat diantar langsung ke rumah Anda.</p>
                    <span class="btn-arrow">Pesan Sekarang <i class="fas fa-arrow-right"></i></span>
                </a>
            </div>
        </div>
    </section>

    <section id="about" class="section-white">
        <div class="container">
            <h2 class="section-title">Tentang Flavor Haven</h2>
            <div class="about-grid">
                <div class="about-img">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80" alt="Interior Restoran">
                </div>
                <div class="about-text">
                    <p>Flavor Haven didirikan pada tahun 2020 dengan satu tujuan: menyajikan kehangatan rasa rumahan dalam balutan kemewahan modern. Kami menggunakan bahan-bahan organik terbaik yang diambil langsung dari petani lokal setiap pagi.</p>
                    <p>Koki kami berpengalaman lebih dari 10 tahun dalam masakan Nusantara dan Western, menjamin setiap suapan adalah pengalaman yang tak terlupakan.</p>
                    
                    <div class="features-icon">
                        <div><i class="fas fa-certificate"></i> Halal 100%</div>
                        <div><i class="fas fa-wifi"></i> Free Wifi</div>
                        <div><i class="fas fa-parking"></i> Parkir Luas</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="menu" class="section-promo">
        <div class="container">
            <h2 class="section-title" style="color:white;">Rekomendasi Chef</h2>
            <div class="promo-grid">
                <?php
                
                $query_promo = mysqli_query($koneksi, "
                    SELECT * FROM menu 
                    WHERE menuName IN ('Sop Buntut', 'Nasi Goreng', 'Mie Goreng Ayam')
                ");

                if(mysqli_num_rows($query_promo) > 0) {
                    while($m = mysqli_fetch_assoc($query_promo)){ 
                ?>
                    <div class="promo-card">
                        <img src="img/<?= $m['image']; ?>" alt="<?= $m['menuName']; ?>">
                        
                        <h3><?= $m['menuName']; ?></h3>
                        
                        <p style="font-size: 0.9rem; color: #555; margin-top: 5px;">
                            <i class="fas fa-thumbs-up" style="color:var(--primary);"></i> Pilihan Terbaik
                        </p>
                    </div>
                <?php 
                    } 
                } else {
                    echo "<p style='color:white; text-align:center; grid-column:1/-1;'>Menu rekomendasi belum tersedia di database.</p>";
                }
                ?>
            </div>
            
            <div style="text-align:center; margin-top:40px;">
                <a href="menu.php" class="btn-login-nav" style="background: white; color: var(--primary) !important; text-decoration: none;">
                    Lihat Semua Menu
                </a>
            </div>
        </div>
    </section>

    <section id="location" class="section-white">
        <div class="container">
            <h2 class="section-title">Lokasi & Kontak</h2>
            <div class="contact-wrapper">
                <div class="contact-info">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Alamat</h4>
                            <p>Jl. Anggrek No. 10, Jakarta Barat, Indonesia 11480</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <h4>Telepon / WA</h4>
                            <p>+62 812-3456-7890</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>Jam Buka</h4>
                            <p>Setiap Hari: 10.00 - 22.00 WIB</p>
                        </div>
                    </div>
                </div>
                <div class="map-box">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.458800931215!2d106.7804473749902!3d-6.203046993784742!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f6c4a8d0555d%3A0x6a0a099f131109a0!2sBinus%20University%20Anggrek%20Campus!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
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