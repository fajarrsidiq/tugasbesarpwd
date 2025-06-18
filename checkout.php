<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /toko_online/login.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/config/database.php';

$user_id = $_SESSION['user_id'];

// Proses checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data keranjang
    $query = "SELECT k.*, p.harga 
              FROM keranjang k 
              JOIN produk p ON k.produk_id = p.id 
              WHERE k.user_id = $user_id";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Hitung total harga
        $total_harga = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $total_harga += $row['harga'] * $row['qty'];
        }
        
        // Buat pesanan
        $insert_order = "INSERT INTO pesanan (user_id, total_harga, status) 
                         VALUES ($user_id, $total_harga, 'pending')";
        mysqli_query($conn, $insert_order);
        $order_id = mysqli_insert_id($conn);
        
        // Masukkan detail pesanan
        mysqli_data_seek($result, 0);
        while ($row = mysqli_fetch_assoc($result)) {
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
        
        $_SESSION['success'] = "Pesanan berhasil dibuat! Nomor pesanan: #$order_id";
        header("Location: /toko_online/history_pesanan.php");
        exit();
    } else {
        $_SESSION['error'] = "Keranjang belanja kosong";
        header("Location: /toko_online/keranjang.php");
        exit();
    }
}

// Ambil data keranjang untuk ditampilkan
$query = "SELECT k.*, p.nama_produk, p.harga, p.gambar 
          FROM keranjang k 
          JOIN produk p ON k.produk_id = p.id 
          WHERE k.user_id = $user_id";
$result = mysqli_query($conn, $query);
?>

<section class="checkout">
    <div class="checkout-header">
        <h1><i class="fas fa-credit-card"></i> Checkout</h1>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
    </div>
    
    <div class="checkout-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="checkout-summary">
                <h2><i class="fas fa-receipt"></i> Ringkasan Pesanan</h2>
                <table class="order-summary">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        while ($row = mysqli_fetch_assoc($result)): 
                            $subtotal = $row['harga'] * $row['qty'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td class="product-info">
                                    <img src="/toko_online/assets/images/<?= $row['gambar'] ?>" width="50" alt="<?= $row['nama_produk'] ?>">
                                    <span><?= $row['nama_produk'] ?></span>
                                </td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td><?= $row['qty'] ?></td>
                                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <tr class="total-row">
                            <td colspan="3" class="text-right"><strong>Total</strong></td>
                            <td><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="checkout-form">
                <h2><i class="fas fa-truck"></i> Informasi Pengiriman</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="nama">Nama Penerima</label>
                        <input type="text" id="nama" name="nama" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telepon">No. Telepon</label>
                        <input type="tel" id="telepon" name="telepon" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="alamat">Alamat Lengkap</label>
                        <textarea id="alamat" name="alamat" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="catatan">Catatan (Opsional)</label>
                        <textarea id="catatan" name="catatan" rows="2"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart fa-3x"></i>
                <p>Keranjang belanja Anda kosong</p>
                <a href="/toko_online/katalog.php" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Mulai Belanja
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>