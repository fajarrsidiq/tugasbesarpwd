<?php 
include 'includes/header.php'; 
include 'includes/navbar.php';
include 'config/database.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM produk WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    
    if(!$product) {
        header("Location: katalog.php");
        exit();
    }
} else {
    header("Location: katalog.php");
    exit();
}
?>

<section class="product-detail">
    <div class="product-image">
        <img src="assets/images/<?php echo $product['gambar']; ?>" alt="<?php echo $product['nama_produk']; ?>">
    </div>
    <div class="product-info">
        <h1><?php echo $product['nama_produk']; ?></h1>
        <p class="price">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></p>
        <?php if($product['harga_awal'] > 0): ?>
            <p class="original-price">Rp <?php echo number_format($product['harga_awal'], 0, ',', '.'); ?></p>
        <?php endif; ?>
        <p>Kota: <?php echo $product['kota']; ?></p>
        <p>Estimasi: <?php echo $product['estimasi']; ?></p>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="produk_id" value="<?php echo $product['id']; ?>">
                <input type="number" name="qty" value="1" min="1">
                <button type="submit" class="btn">Tambah ke Keranjang</button>
            </form>
        <?php else: ?>
            <p>Silakan <a href="login.php">login</a> untuk menambahkan ke keranjang</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>