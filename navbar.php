<?php
// kalau dah login
$is_logged = isset($_SESSION['status']) && $_SESSION['status'] == 'login';

$jml_item_keranjang = 0;
if(isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])){
    foreach($_SESSION['keranjang'] as $id => $val){
        if(is_array($val)){
            $jml_item_keranjang += $val['qty'];
        } else {
            $jml_item_keranjang += $val;
        }
    }
}
?>

<nav class="navbar">
    <div class="nav-wrapper">
        
        <a href="index.php" class="logo">
            <img src="img/logo_flavor_haven.png" alt="Flavor Haven">
            <span>Flavor Haven</span>
        </a>

        <div class="nav-links" id="navLinks">
            <a href="index.php">Home</a>
            <a href="menu.php">Menu</a>
            
            <?php if($is_logged): ?>
                <a href="riwayat.php">Riwayat</a>
            <?php endif; ?>

            <a href="keranjang.php" style="display:inline-flex; align-items:center; gap:5px;">
                Keranjang
                <span class="cart-badge" style="
                    background: #6E2B2B;
                    color: white;
                    font-size: 0.75rem;
                    font-weight: bold;
                    padding: 2px 8px;
                    border-radius: 10px;
                    min-width: 20px;
                    text-align: center;
                    display: <?= ($jml_item_keranjang > 0) ? 'inline-block' : 'none'; ?>;
                "><?= $jml_item_keranjang; ?></span>
            </a>

            <?php if($is_logged): ?>
                <a href="profile.php" style="font-weight:600; color:#6E2B2B;">
                    Halo, <?= $_SESSION['nama_user'] ?? 'User'; ?>
                </a>
                <a href="logout.php" class="btn-logout" onclick="return confirm('Logout dari Customer?')">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn-login-nav">Login</a>
            <?php endif; ?>
        </div>

        <div class="menu-toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>

    </div>
</nav>

<script>
    const menuToggle = document.getElementById('mobile-menu');
    const navLinks = document.getElementById('navLinks');

    if(menuToggle){
        menuToggle.addEventListener('click', function(){
            navLinks.classList.toggle('active');
            menuToggle.classList.toggle('is-active');
        });
    }
</script>   