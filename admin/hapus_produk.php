<?php
include '../../config/database.php';

if(!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "SELECT gambar FROM produk WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    
    if($product) {
        unlink("../../assets/images/".$product['gambar']);
        
        $delete = "DELETE FROM produk WHERE id = $id";
        mysqli_query($conn, $delete);
    }
}

header("Location: produk.php");
exit();
?>