<?php
session_start();
if (isset($_POST['aksi']) && $_POST['aksi'] == 'update_note') {
    $id_menu = $_POST['id_menu'];
    $catatan = htmlspecialchars($_POST['catatan']);

    if (isset($_SESSION['keranjang'][$id_menu])) {
        
        if (!is_array($_SESSION['keranjang'][$id_menu])) {
            $qty_lama = $_SESSION['keranjang'][$id_menu];
            $_SESSION['keranjang'][$id_menu] = [
                'qty' => $qty_lama,
                'catatan' => ''
            ];
        }

        $_SESSION['keranjang'][$id_menu]['catatan'] = $catatan;
    }
    
    header("location: keranjang.php");
    exit();
}

elseif (isset($_POST['id_menu'])) {
    $id_menu = $_POST['id_menu'];
    $qty     = (int) $_POST['qty'];
    $catatan = isset($_POST['catatan']) ? htmlspecialchars($_POST['catatan']) : '';

    if (isset($_SESSION['keranjang'][$id_menu])) {
        if (is_array($_SESSION['keranjang'][$id_menu])) {
            $_SESSION['keranjang'][$id_menu]['qty'] += $qty;
            $_SESSION['keranjang'][$id_menu]['catatan'] = $catatan; 
        } else {
            // Konversi jika masih angka    
            $qty_lama = $_SESSION['keranjang'][$id_menu];
            $_SESSION['keranjang'][$id_menu] = [
                'qty' => $qty_lama + $qty,
                'catatan' => $catatan
            ];
        }
    } else {
        $_SESSION['keranjang'][$id_menu] = [
            'qty' => $qty,
            'catatan' => $catatan
        ];
    }

    echo "<script>alert('Menu berhasil ditambahkan!'); location='menu.php';</script>";
} 

elseif (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $aksi = $_GET['aksi'];

    if (isset($_SESSION['keranjang'][$id])) {
        
        if (!is_array($_SESSION['keranjang'][$id])) {
            $qty_lama = $_SESSION['keranjang'][$id];
            $_SESSION['keranjang'][$id] = [
                'qty' => $qty_lama,
                'catatan' => '' 
            ];
        }

        if ($aksi == 'tambah') {
            $_SESSION['keranjang'][$id]['qty'] += 1;
        } elseif ($aksi == 'kurang') {
            $_SESSION['keranjang'][$id]['qty'] -= 1;
            if ($_SESSION['keranjang'][$id]['qty'] <= 0) {
                unset($_SESSION['keranjang'][$id]);
            }
        } elseif ($aksi == 'hapus') {
            unset($_SESSION['keranjang'][$id]);
        }
    }
    header("location: keranjang.php");
} else {
    header("location: menu.php");
}
?>