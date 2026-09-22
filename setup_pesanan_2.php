<?php
session_start();
include 'koneksi.php'; 

if(!isset($_SESSION['status']) || $_SESSION['status'] != 'login'){
    header("location: login.php");
    exit();
}

if(!isset($_POST['tanggal']) && !isset($_POST['simpan_final'])){
    header("location: setup_pesanan.php");
    exit();
}

if(isset($_POST['tanggal'])){
    $tanggal_pilih = $_POST['tanggal'];
    $tipe_pilih    = $_POST['tipe_ruangan'];
} 
else if(isset($_POST['simpan_final'])){
    $tanggal_pilih = $_POST['tanggal_final'];
    $tipe_pilih    = isset($_POST['tipe_ruangan']) ? $_POST['tipe_ruangan'] : 'Regular'; 
}

if(isset($_POST['simpan_final'])){
    
    $bookingDate  = $_POST['tanggal_final']; 
    $roomID       = $_POST['room_id'];
    $pax          = $_POST['pax']; 
    $jam_mulai    = $_POST['jam_mulai'];
    
    $durasi_menit = 120; // Default
    
    if($tipe_pilih == 'VIP'){
        $jam_selesai = $_POST['jam_selesai'];
        
        // Validasi Jam
        if(strtotime($jam_selesai) <= strtotime($jam_mulai)){
            echo "<script>alert('Jam Selesai harus lebih akhir dari Jam Mulai!'); window.history.back();</script>";
            exit();
        }
        
        // Hitung selisih menit
        $selisih = strtotime($jam_selesai) - strtotime($jam_mulai);
        $durasi_menit = $selisih / 60; 
    } else {
        $jam_selesai = date('H:i', strtotime($jam_mulai . ' + 120 minutes'));
    }

    // Cek Bentrok
    $cek_bentrok = mysqli_query($koneksi, "SELECT * FROM transactions 
        WHERE bookingDate = '$bookingDate' 
        AND roomID = '$roomID' 
        AND status != 'cancelled' 
        AND (
            ('$jam_mulai' < ADDTIME(bookingTime, SEC_TO_TIME(duration * 60))) AND 
            ('$jam_selesai' > bookingTime)
        )
    ");

    if (mysqli_num_rows($cek_bentrok) > 0) {
        echo "<script>
            alert('Maaf, Ruangan VIP sudah dibooking pada jam tersebut. Silakan pilih waktu atau ruangan lain.');
            window.location.href = 'setup_pesanan_2.php'; 
        </script>";
        exit(); 
    }

    $_SESSION['data_pesanan'] = [
        'bookingDate' => $bookingDate,
        'bookingTime' => $jam_mulai, 
        'pax'         => $pax,
        'roomID'      => $roomID,
        'duration'    => $durasi_menit 
    ];

    header("location: menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jam & Ruangan</title>
    <link rel="icon" href="img/logo_flavor_haven.png" type="image/x-icon">
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { padding-top: 100px; }
        a.logo { text-decoration: none; }
        
        .schedule-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; font-size: 0.9rem; }
        .schedule-table th, .schedule-table td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        .schedule-table th { background-color: #f8f9fa; color: #555; }
        .badge-booked { background-color: #ffebee; color: #c62828; padding: 5px 10px; border-radius: 5px; font-weight: bold; }
        
        .info-box { background: #e3f2fd; color: #0d47a1; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .info-box-regular { background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #c8e6c9; }
        
        .time-row { display: flex; gap: 20px; }
        .time-col { flex: 1; }
        
        .input-readonly {
            background-color: #e9ecef; color: #495057; cursor: not-allowed; border: 1px solid #ced4da;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="login-page">
        <div class="login-form" style="max-width: 600px;">
            
            <h2>Tipe Ruangan: <?= $tipe_pilih; ?></h2>
            <p style="margin-bottom: 10px;">Tanggal: <strong><?= date('d-m-Y', strtotime($tanggal_pilih)); ?></strong></p>

            <?php if($tipe_pilih == 'VIP'): ?>
                <div class="info-box"><i class="fas fa-info-circle"></i> Tabel di bawah adalah jadwal VIP yang <b>SUDAH DIBOOKING</b>.</div>
                
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Nama Ruangan</th>
                            <th>Jam Terisi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query_jadwal = mysqli_query($koneksi, "SELECT t.*, r.roomName 
                            FROM transactions t 
                            JOIN rooms r ON t.roomID = r.roomID 
                            WHERE t.bookingDate = '$tanggal_pilih' 
                            AND r.type = 'VIP' 
                            AND t.status != 'cancelled'
                            ORDER BY r.roomName, t.bookingTime ASC");

                        if(mysqli_num_rows($query_jadwal) > 0){
                            while($row = mysqli_fetch_assoc($query_jadwal)){
                                $jam_mulai = substr($row['bookingTime'], 0, 5);
                                $durasi_db = (!empty($row['duration']) && $row['duration'] > 0) ? $row['duration'] : 120;
                                $jam_selesai = date('H:i', strtotime($row['bookingTime'] . " + $durasi_db minutes"));
                                echo "<tr>
                                        <td>{$row['roomName']}</td>
                                        <td>$jam_mulai - $jam_selesai</td>
                                        <td><span class='badge-booked'>Booked</span></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' style='color:green;'>Jadwal Kosong</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="info-box-regular"><i class="fas fa-check-circle"></i> <b>Area Regular Tersedia!</b><br>Durasi makan standar 2 Jam.</div>
            <?php endif; ?>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

            <form action="" method="POST" class="form">
                <input type="hidden" name="tanggal_final" value="<?= $tanggal_pilih; ?>">
                <input type="hidden" name="tipe_ruangan" value="<?= $tipe_pilih; ?>">

                <div class="form-group">
                    <label>Pilih Ruangan / Meja</label>
                    <select name="room_id" id="roomSelect" required onchange="updatePax()" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; background: #fafafa;">
                        <?php 
                        $q_room = mysqli_query($koneksi, "SELECT * FROM rooms WHERE type='$tipe_pilih'");
                        while($r = mysqli_fetch_assoc($q_room)){
                            echo "<option value='{$r['roomID']}' data-capacity='{$r['capacity']}'>{$r['roomName']} (Kapasitas: {$r['capacity']})</option>";
                        }
                        ?>
                    </select>
                </div>

                <?php if($tipe_pilih == 'VIP'): ?>
                    <div class="time-row">
                        <div class="time-col">
                            <div class="form-group">
                                <label>Jam Mulai</label>
                                <input type="time" name="jam_mulai" required min="10:00" max="21:00">
                            </div>
                        </div>
                        <div class="time-col">
                            <div class="form-group">
                                <label>Jam Selesai</label>
                                <input type="time" name="jam_selesai" required min="11:00" max="23:00">
                            </div>
                        </div>
                    </div>
                    <small style="display:block; margin-top:-10px; margin-bottom:15px; color:#666;">*Bebas atur durasi.</small>
                <?php else: ?>
                    <div class="form-group">
                        <label>Jam Kedatangan</label>
                        <input type="time" name="jam_mulai" required min="10:00" max="21:00" step="600">
                        <small style="color:#666;">*Durasi otomatis 2 jam.</small>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Jumlah Orang</label>
                    <?php if($tipe_pilih == 'VIP'): ?>
                        <input type="text" id="paxDisplay" class="input-readonly" readonly>
                        <input type="hidden" name="pax" id="paxInput"> <small style="color:#666;">*Otomatis sesuai kapasitas ruangan.</small>
                    <?php else: ?>
                        <input type="number" name="pax" id="paxInput" min="1" max="20" required placeholder="Contoh: 4">
                    <?php endif; ?>
                </div>

                <button type="submit" name="simpan_final" class="btn-submit">
                    Lanjut Memilih Menu <i class="fas fa-arrow-right"></i>
                </button>
                
                <a href="setup_pesanan.php" style="display:block; text-align:center; margin-top:15px; text-decoration:none; color:#888;"><i class="fas fa-arrow-left"></i> Kembali Pilih Tanggal</a>
            </form>
        </div>
    </main>

    <script>
        function updatePax() {
            var select = document.getElementById('roomSelect');
            var selectedOption = select.options[select.selectedIndex];
            var capacity = selectedOption.getAttribute('data-capacity');
            
            var display = document.getElementById('paxDisplay');
            var hiddenInput = document.getElementById('paxInput');
            
            if(display) {
                display.value = capacity + " Orang (Full Room)";
                hiddenInput.value = capacity;
            } else {
                if(hiddenInput){
                    hiddenInput.setAttribute('max', capacity);
                    hiddenInput.setAttribute('placeholder', 'Max: ' + capacity + ' Orang');
                }
            }
        }
        
        window.onload = updatePax;
    </script>
</body>
</html>