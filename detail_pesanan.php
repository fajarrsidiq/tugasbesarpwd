<?php 
include 'includes/header.php'; 
include 'includes/navbar.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config/database.php';

if(isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    
    // Cek apakah pesanan milik user yang login
    $check = "SELECT * FROM pesanan WHERE id = $order_id AND user_id = $user_id";
    $result = mysqli_query($conn, $check);
    
    if(mysqli_num_rows($result) == 0) {
        header("Location: history_pesanan.php");
        exit();
    }
    
    $order = mysqli_fetch_assoc($result);
    
    // Ambil detail pesanan
    $query = "SELECT pd.*, p.nama_produk, p.gambar 
              FROM pesanan_detail pd 
              JOIN produk p ON pd.produk_id = p.id 
              WHERE pd.pesanan_id = $order_id";
    $details = mysqli_query($conn, $query);
} else {
    header("Location: history_pesanan.php");
    exit();
}
?>

<section class="order-detail">
    <h1>Detail Pesanan #<?php echo $order['id']; ?></h1>
    
    <div class="order-info">
        <p><strong>Tanggal:</strong> <?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></p>
        <p><strong>Total:</strong> Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></p>
    </div>
    
    <h2>Produk</h2>
    <table>
        <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php while($item = mysqli_fetch_assoc($details)): ?>
            <tr>
                <td>
                    <img src="assets/images/<?php echo $item['gambar']; ?>" width="50">
                    <?php echo $item['nama_produk']; ?>
                </td>
                <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                <td><?php echo $item['qty']; ?></td>
                <td>Rp <?php echo number_format($item['harga'] * $item['qty'], 0, ',', '.'); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<?php include 'includes/footer.php'; ?>