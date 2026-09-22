<nav class="admin-navbar">
    <div class="nav-container">
        <a href="index.php" class="admin-logo">
            <img src="../img/logo_flavor_haven.png" alt="Admin Logo"> Admin Panel
        </a>
        
        <div class="admin-toggle" id="admin-menu-toggle">
            <i class="fas fa-bars"></i>
        </div>

        <div class="admin-links" id="admin-nav-links">
            <a href="index.php">Dashboard</a>
            <a href="menu.php"></i>Kelola Menu</a>
            <a href="transaksi.php">Transaksi</a>
            <a href="laporan.php">Laporan</a>
            
            <a href="../logout.php" class="btn-logout" onclick="return confirm('Logout dari Admin?')">Logout</a>
        </div>
    </div>
</nav>

<script>
    const adminToggle = document.getElementById('admin-menu-toggle');
    const adminNav = document.getElementById('admin-nav-links');

    adminToggle.addEventListener('click', () => {
        adminNav.classList.toggle('active');
        // Ganti ikon bars ke X
        const icon = adminToggle.querySelector('i');
        if(adminNav.classList.contains('active')){
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
    });
</script>