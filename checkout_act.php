<?php
session_start();
include 'koneksi.php';

if(isset($_POST['bayar'])){
    $userID = $_SESSION['userID'];
    $tipe   = $_SESSION['tipe_transaksi'];
    $metode = $_POST['metode_pembayaran'];
    $total  = $_POST['total_bayar'];
    $tgl    = date("Y-m-d");

    $info = isset($_SESSION['data_pesanan']) ? $_SESSION['data_pesanan'] : [];

    if(empty($info)){
        echo "<script>alert('Data pesanan hilang. Silakan ulangi pemesanan.'); location='index.php';</script>";
        exit();
    }

    $bookingDate = "NULL";
    $bookingTime = "NULL";
    $pax         = "NULL";
    $roomID      = "NULL";
    $duration    = "NULL";
    $delivAddr   = "NULL";
    $delivPhone  = "NULL";

    // reservation atau delivery
    if($tipe == 'reservation'){
      
        if(empty($info['bookingDate']) || empty($info['bookingTime'])){
            echo "<script>alert('Data tanggal/jam booking tidak lengkap!'); location='setup_pesanan.php';</script>";
            exit();
        }

        $bookingDate = "'" . mysqli_real_escape_string($koneksi, $info['bookingDate']) . "'";
        $bookingTime = "'" . mysqli_real_escape_string($koneksi, $info['bookingTime']) . "'";
        $pax         = "'" . mysqli_real_escape_string($koneksi, $info['pax']) . "'";
        $roomID      = "'" . mysqli_real_escape_string($koneksi, $info['roomID']) . "'";
        
        if(isset($info['duration'])){
            $duration = "'" . (int)$info['duration'] . "'";
        } else {
            $duration = "'120'"; 
        }
        
        $tipe_db = 'Reservation';

    } else {
        
        if(empty($info['deliveryAddress'])){
            echo "<script>alert('Alamat pengiriman belum diisi!'); location='setup_delivery.php';</script>";
            exit();
        }

        $delivAddr   = "'" . mysqli_real_escape_string($koneksi, $info['deliveryAddress']) . "'";
        $delivPhone  = "'" . mysqli_real_escape_string($koneksi, $info['deliveryPhone']) . "'";
        
        $tipe_db     = 'Delivery';
    }

    // insert tabel
    $query = "INSERT INTO transactions 
              (userID, type, transactionDate, totalAmount, paymentMethod, status, 
               bookingDate, bookingTime, pax, roomID, duration, deliveryAddress, deliveryPhone)
              VALUES 
              ('$userID', '$tipe_db', '$tgl', '$total', '$metode', 'pending',
               $bookingDate, $bookingTime, $pax, $roomID, $duration, $delivAddr, $delivPhone)";

    if(mysqli_query($koneksi, $query)){
        $id_transaksi = mysqli_insert_id($koneksi);

        // insert ke transactiondetail
        if(isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])){
            
            foreach($_SESSION['keranjang'] as $id_menu => $item){
                if(is_array($item)){
                    $jumlah_qty = $item['qty'];
                    $catatan    = mysqli_real_escape_string($koneksi, $item['catatan']);
                } else {
                    $jumlah_qty = $item;
                    $catatan    = "";
                }

                // Ambil harga
                $ambil = mysqli_query($koneksi, "SELECT price FROM menu WHERE menuID='$id_menu'");
                $dt    = mysqli_fetch_assoc($ambil);
                $harga = $dt['price'];
                
                $query_detail = "INSERT INTO transactionsdetail (transactionID, menuID, quantity, note, unitPrice) 
                                 VALUES ('$id_transaksi', '$id_menu', '$jumlah_qty', '$catatan', '$harga')";
                
                mysqli_query($koneksi, $query_detail);
            }
        }

        unset($_SESSION['keranjang']);
        unset($_SESSION['tipe_transaksi']);
        unset($_SESSION['data_pesanan']);

        header("location: sukses.php?id=$id_transaksi");
        
    } else {
        echo "Error Database: " . mysqli_error($koneksi);
        echo "<br>Query: " . $query;
    }
}
?>