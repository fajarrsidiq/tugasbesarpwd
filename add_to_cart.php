<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/config/database.php';

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /toko_online/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $produk_id = (int)$_POST['produk_id'];
    $qty = (int)$_POST['qty'];
    
    if ($qty < 1) {
        $_SESSION['error'] = "Jumlah tidak valid";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    $check = "SELECT * FROM keranjang WHERE user_id = $user_id AND produk_id = $produk_id";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) > 0) {
        $update = "UPDATE keranjang SET qty = qty + $qty WHERE user_id = $user_id AND produk_id = $produk_id";
        mysqli_query($conn, $update);
    } else {
        $insert = "INSERT INTO keranjang (user_id, produk_id, qty) VALUES ($user_id, $produk_id, $qty)";
        mysqli_query($conn, $insert);
    }
    
    $_SESSION['success'] = "Produk berhasil ditambahkan ke keranjang";
    header("Location: /toko_online/keranjang.php");
    exit();
}

header("Location: /toko_online/katalog.php");
exit();
?>