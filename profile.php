<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['status']) || $_SESSION['status'] != 'login'){
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['userID'];

if(isset($_POST['update_profile'])){
    $nama   = $_POST['nama'];
    $gender = $_POST['gender'];
    $dob    = $_POST['dob'];
    
    $password_baru = $_POST['password'];
    
    if(!empty($password_baru)){
        $query = "UPDATE users SET nama='$nama', password='$password_baru', gender='$gender', DOB='$dob' WHERE userID='$id_user'";
    } else {
        $query = "UPDATE users SET nama='$nama', gender='$gender', DOB='$dob' WHERE userID='$id_user'";
    }

    if(mysqli_query($koneksi, $query)){
        $_SESSION['nama'] = $nama;
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($koneksi) . "');</script>";
    }
}

$query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE userID='$id_user'");
$data = mysqli_fetch_assoc($query_user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Flavor Haven</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            padding-top: 100px;
        }
        
        .input-readonly {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
            border-color: #ced4da;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="login-page">
        <div class="login-form">
            
            <h2>Edit Profil</h2>
            <p>Perbarui informasi akun Anda di sini.</p>

            <form action="" method="POST">
                
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= $data['nama']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Email <small style="font-weight:normal; color:#888;">(Tidak dapat diubah)</small></label>
                    <input type="email" value="<?= $data['email']; ?>" class="input-readonly" readonly>
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="gender" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 10px; background: white;">
                        <option value="">-- Pilih Gender --</option>
                        <option value="Male" <?= ($data['gender'] == 'Male') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Female" <?= ($data['gender'] == 'Female') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="dob" value="<?= $data['DOB']; ?>" required>
                </div>

                <div class="form-group" style="margin-top:20px; border-top:1px dashed #ddd; padding-top:15px;">
                    <label>Ganti Password <small style="color:#999; font-weight:400;">(Kosongkan jika tidak ganti)</small></label>
                    <input type="password" name="password" placeholder="Password baru...">
                </div>

                <button type="submit" name="update_profile" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>

                <div class="register-link">
                    <a href="index.php"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
                </div>

            </form>
        </div>
    </main>

    <footer style="text-align:center; padding:30px; color:#999; margin-top:50px;">
        &copy; 2025 Flavor Haven
    </footer>

</body>
</html>