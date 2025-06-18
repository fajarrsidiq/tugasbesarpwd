<?php 
include 'includes/header.php'; 
include 'includes/navbar.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config/database.php';

// Proses checkout
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    
    // Ambil data keranjang
    $query = "SELECT k.*, p.harga 
              FROM keranjang k 
              JOIN produk p ON k.produk_id = p.id 
              WHERE k.user_id = $user_id";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) > 0) {
        // Buat pesanan
        $total_harga = 0;
        while($row = mysqli_fetch_assoc($result)) {
            $total_harga += $row['harga'] * $row['qty'];
        }
        
        $insert_order = "INSERT INTO pesanan (user_id, total_harga, status) 
                         VALUES ($user_id, $total_harga, 'pending')";
        mysqli_query($conn, $insert_order);
        $order_id = mysqli_insert_id($conn);
        
        // Masukkan detail pesanan
        mysqli_data_seek($result, 0);
        while($row = mysqli_fetch_assoc($result)) {
            $produk_id = $row['produk_id'];
            $qty = $row['qty'];
            $harga = $row['harga'];
            
            $insert_detail = "INSERT INTO pesanan_detail (pesanan_id, produk_id, qty, harga) 
                             VALUES ($order_id, $produk_id, $qty, $harga)";
            mysqli_query($conn, $insert_detail);
        }
        
        // Kosongkan keranjang
        $delete_cart = "DELETE FROM keranjang WHERE user_id = $user_id";
        mysqli_query($conn, $delete_cart);
        
        header("Location: history_pesanan.php?success=1");
        exit();
    }
}

// Tampilkan form checkout
$user_id = $_SESSION['user_id'];
$query = "SELECT k.*, p.nama_produk, p.harga, p.gambar 
          FROM keranjang k 
          JOIN produk p ON k.produk_id = p.id 
          WHERE k.user_id = $user_id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0) {
    $total = 0;
    echo '<section class="checkout">';
    echo '<h1>Checkout</h1>';
    echo '<form method="post">';
    
    echo '<div class="order-summary">';
    echo '<h2>Ringkasan Pesanan</h2>';
    echo '<table>';
    echo '<tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr>';
    
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
        echo '</tr>';
    }
    
    echo '<tr><td colspan="3">Total</td><td>Rp '.number_format($total, 0, ',', '.').'</td></tr>';
    echo '</table>';
    echo '</div>';
    
    echo '<div class="shipping-info">';
    echo '<h2>Informasi Pengiriman</h2>';
    echo '<label for="address">Alamat:</label>';
    echo '<textarea id="address" name="address" required></textarea>';
    echo '</div>';
    
    echo '<button type="submit" class="btn">Proses Pembayaran</button>';
    echo '</form>';
    echo '</section>';
} else {
    echo '<p>Keranjang belanja Anda kosong.</p>';
}
?>

<?php include 'includes/footer.php'; ?>