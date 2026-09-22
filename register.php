<?php
session_start();
include 'koneksi.php';

if(isset($_SESSION['status']) && $_SESSION['status'] == 'login'){
    header("location: index.php");
    exit();
}

if(isset($_POST['register'])){
    $nama   = $_POST['nama'];
    $email  = $_POST['email'];
    $pass   = $_POST['password'];
    $conf_pass = $_POST['confirm_password']; // Ambil data konfirmasi password
    $gender = $_POST['gender'];
    $dob    = $_POST['dob'];

    // validasi password
    if(strlen($pass) < 6){
        $error = "Password minimal harus 6 karakter!";
    }
    elseif($pass != $conf_pass){
        $error = "Konfirmasi password tidak sesuai!";
    }
    else {
        // cek email
        $cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
        if(mysqli_num_rows($cek_email) > 0){
            $error = "Email sudah terdaftar! Gunakan email lain.";
        } else {
            // insert
            $query = "INSERT INTO users (nama, email, password, gender, DOB) VALUES ('$nama', '$email', '$pass', '$gender', '$dob')";
            
            if(mysqli_query($koneksi, $query)){
                echo "<script>alert('Pendaftaran Berhasil! Silahkan Login.'); location='login.php';</script>";
            } else {
                $error = "Gagal mendaftar: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member - Flavor Haven</title>
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
        <div class="login-form" style="max-width: 500px;"> 
            <h1>Buat Akun Baru</h1>
            <p>Gabung member untuk nikmati kemudahannya.</p>

            <?php if(isset($error)): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                    <?= $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="form">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Nama Anda" value="<?= isset($_POST['nama']) ? $_POST['nama'] : '' ?>">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="Contoh: user@email.com" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter">
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="confirm_password" required placeholder="Ulangi password di atas">
                </div>
                
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <div class="radio-group" style="display:flex; gap:15px; margin-top:5px;">
                        <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                            <input type="radio" name="gender" value="Male" required checked> Laki-laki
                        </label>
                        <label style="display:flex; align-items:center; gap:5px; cursor:pointer;">
                            <input type="radio" name="gender" value="Female"> Perempuan
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="dob" required value="<?= isset($_POST['dob']) ? $_POST['dob'] : '' ?>">
                </div>

                <button type="submit" name="register" class="btn-submit">
                    Daftar Sekarang <i class="fas fa-user-plus"></i>
                </button>
            </form>

            <div class="register-link">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </div>
    </main>

</body>
</html>