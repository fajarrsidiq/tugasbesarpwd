<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /toko_online/login.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/config/database.php';

if (!isset($_GET['id'])) {
    header("Location: /toko_online/history_pesanan.php");
    exit();
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Cek apakah pesanan milik user yang login
$query = "SELECT p.*, u.username 
          FROM pesanan p 
          JOIN users u ON p.user_id = u.id 
          WHERE p.id = $order_id AND p.user_id = $user_id";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    header("Location: /toko_online/history_pesanan.php");
    exit();
}

// Ambil detail pesanan
$query = "SELECT pd.*, p.nama_produk, p.gambar 
          FROM pesanan_detail pd 
          JOIN produk p ON pd.produk_id = p.id 
          WHERE pd.pesanan_id = $order_id";
$details = mysqli_query($conn, $query);
?>

<section class="order-detail">
    <div class="detail-header">
        <h1><i class="fas fa-file-invoice"></i> Detail Pesanan #<?= $order['id'] ?></h1>
        <a href="/toko_online/history_pesanan.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <div class="order-info">
        <div class="info-item">
            <h3>Status Pesanan</h3>
            <p>
                <span class="status-label <?= $order['status'] ?>">
                    <?= ucfirst($order['status']) ?>
                </span>
            </p>
        </div>
        
        <div class="info-item">
            <h3>Tanggal Pesanan</h3>
            <p><?= date('d M Y H:i', strtotime($order['created_at'])) ?></p>
        </div>
        
        <div class="info-item">
            <h3>Total Pembayaran</h3>
            <p class="total-price">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></p>
        </div>
    </div>
    
    <div class="order-products">
        <h2><i class="fas fa-boxes"></i> Produk yang Dipesan</h2>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = mysqli_fetch_assoc($details)): ?>
                    <tr>
                        <td class="product-info">
                            <img src="/toko_online/assets/images/<?= $item['gambar'] ?>" width="50" alt="<?= $item['nama_produk'] ?>">
                            <span><?= $item['nama_produk'] ?></span>
                        </td>
                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td>Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td><strong>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>