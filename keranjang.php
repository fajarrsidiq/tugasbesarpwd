<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /toko_online/login.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/config/database.php';

// Proses hapus item dari keranjang
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $delete = "DELETE FROM keranjang WHERE id = $id AND user_id = {$_SESSION['user_id']}";
    mysqli_query($conn, $delete);
    $_SESSION['success'] = "Item berhasil dihapus dari keranjang";
    header("Location: /toko_online/keranjang.php");
    exit();
}

// Proses update quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $id = (int)$id;
        $qty = (int)$qty;
        if ($qty > 0) {
            $update = "UPDATE keranjang SET qty = $qty WHERE id = $id AND user_id = {$_SESSION['user_id']}";
            mysqli_query($conn, $update);
        }
    }
    $_SESSION['success'] = "Keranjang berhasil diupdate";
    header("Location: /toko_online/keranjang.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT k.*, p.nama_produk, p.harga, p.gambar 
          FROM keranjang k 
          JOIN produk p ON k.produk_id = p.id 
          WHERE k.user_id = $user_id";
$result = mysqli_query($conn, $query);
?>

<section class="cart">
    <div class="cart-header">
        <h1><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h1>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
    </div>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <form method="post" action="keranjang.php">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
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
                                <img src="/toko_online/assets/images/<?= $row['gambar'] ?>" width="60" alt="<?= $row['nama_produk'] ?>">
                                <span><?= $row['nama_produk'] ?></span>
                            </td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td>
                                <input type="number" name="qty[<?= $row['id'] ?>]" value="<?= $row['qty'] ?>" min="1" class="qty-input">
                            </td>
                            <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            <td>
                                <a href="/toko_online/keranjang.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <tr class="total-row">
                        <td colspan="3" class="text-right"><strong>Total</strong></td>
                        <td colspan="2"><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
                    </tr>
                </tbody>
            </table>
            
            <div class="cart-actions">
                <a href="/toko_online/katalog.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Lanjut Belanja
                </a>
                <button type="submit" name="update_cart" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i> Update Keranjang
                </button>
                <a href="/toko_online/checkout.php" class="btn btn-success">
                    <i class="fas fa-credit-card"></i> Checkout
                </a>
            </div>
        </form>
    <?php else: ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-cart fa-3x"></i>
            <p>Keranjang belanja Anda kosong</p>
            <a href="/toko_online/katalog.php" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Mulai Belanja
            </a>
        </div>
    <?php endif; ?>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>