<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/header.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/config/database.php';

if (!isset($_GET['id'])) {
    header("Location: /toko_online/katalog.php");
    exit();
}

$id = (int)$_GET['id'];
$query = "SELECT * FROM produk WHERE id = $id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header("Location: /toko_online/katalog.php");
    exit();
}
?>

<section class="product-detail">
    <div class="product-image">
        <img src="/toko_online/assets/images/<?= $product['gambar'] ?>" alt="<?= $product['nama_produk'] ?>">
    </div>
    <div class="product-info">
        <h1><?= $product['nama_produk'] ?></h1>
        
        <div class="price">
            <span class="current-price">Rp <?= number_format($product['harga'], 0, ',', '.') ?></span>
            <?php if ($product['harga_awal'] > 0): ?>
                <span class="original-price">Rp <?= number_format($product['harga_awal'], 0, ',', '.') ?></span>
            <?php endif; ?>
        </div>
        
        <div class="product-meta">
            <div class="meta-item">
                <i class="fas fa-map-marker-alt"></i>
                <span><?= $product['kota'] ?></span>
            </div>
            <div class="meta-item">
                <i class="fas fa-truck"></i>
                <span><?= $product['estimasi'] ?></span>
            </div>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="/toko_online/add_to_cart.php" method="post" class="add-to-cart">
                <input type="hidden" name="produk_id" value="<?= $product['id'] ?>">
                <div class="quantity">
                    <label for="qty">Jumlah:</label>
                    <input type="number" id="qty" name="qty" value="1" min="1">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                </button>
            </form>
        <?php else: ?>
            <div class="login-notice">
                <p>Silakan <a href="/toko_online/login.php">login</a> untuk menambahkan produk ke keranjang</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>