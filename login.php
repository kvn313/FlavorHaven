<?php
session_start();
include 'koneksi.php';

// admin atau pelanggan
if(isset($_SESSION['status']) && $_SESSION['status'] == 'login'){
    if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){
        header("location: admin/index.php");
    } else {
        header("location: index.php");
    }
    exit();
}

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($cek) > 0){
        $data = mysqli_fetch_assoc($cek);
        
        // Cek Password
        if($password == $data['password']){
            $_SESSION['userID'] = $data['userID'];
            $_SESSION['nama']   = $data['nama'];
            
            // --- TAMBAHAN KECIL SUPAYA NAVBAR NONGOL NAMANYA ---
            $_SESSION['nama_user'] = $data['nama']; 
            // ---------------------------------------------------

            $_SESSION['role']   = $data['role'];
            $_SESSION['status'] = "login";

            
            // admin
            if($data['role'] == "admin"){
                header("location: admin/index.php");
            } 
            // pelanggan
            else {
                if(isset($_SESSION['tipe_transaksi'])){
                    if($_SESSION['tipe_transaksi'] == 'delivery'){
                        header("location: setup_delivery.php"); // Balik ke form delivery
                    } else {
                        header("location: setup_pesanan.php"); // Balik ke form reservasi
                    }
                } 
                else {
                    header("location: index.php"); 
                }
            }
            exit();

        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak terdaftar!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Member - Flavor Haven</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { padding-top: 100px; }
        a.logo { text-decoration: none; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="login-page">
        <div class="login-form">
            <h1>Selamat Datang Kembali</h1>
            <p>Silahkan login untuk melanjutkan.</p>

            <?php if(isset($error)): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                    <?= $error; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'belum_login'): ?>
                <div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                    Silahkan login untuk melanjutkan pesanan Anda.
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="form">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="Masukkan email Anda">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password">
                </div>

                <button type="submit" name="login" class="btn-submit">
                    Masuk Sekarang <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="register-link">
                Belum punya akun? <a href="register.php">Daftar Sekarang</a>
            </div>
        </div>
    </main>

</body>
</html>