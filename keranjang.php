<?php 
include 'includes/header.php'; 
include 'includes/navbar.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config/database.php';
?>

<section class="cart">
    <h1>Keranjang Belanja</h1>
    
    <?php
    $user_id = $_SESSION['user_id'];
    $query = "SELECT k.*, p.nama_produk, p.harga, p.gambar 
              FROM keranjang k 
              JOIN produk p ON k.produk_id = p.id 
              WHERE k.user_id = $user_id";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $total = 0;
        echo '<table>';
        echo '<tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th>Aksi</th></tr>';
        
        while($row = mysqli_fetch_assoc($result)) {
            $subtotal = $row['harga'] * $row['qty'];
            $total += $subtotal;
            
            echo '<tr>';
            echo '<td>';
            echo '<img src="assets/images/'.$row['gambar'].'" width="50"> ';
            echo $row['nama_produk'];
            echo '</td>';
            echo '<td>Rp '.number_format($row['harga'], 0, ',', '.').'</td>';
            echo '<td>'.$row['qty'].'</td>';
            echo '<td>Rp '.number_format($subtotal, 0, ',', '.').'</td>';
            echo '<td><a href="remove_from_cart.php?id='.$row['id'].'">Hapus</a></td>';
            echo '</tr>';
        }
        
        echo '<tr><td colspan="3">Total</td><td>Rp '.number_format($total, 0, ',', '.').'</td><td></td></tr>';
        echo '</table>';
        
        echo '<a href="checkout.php" class="btn">Checkout</a>';
    } else {
        echo '<p>Keranjang belanja Anda kosong.</p>';
    }
    ?>
</section>

<?php include 'includes/footer.php'; ?>