<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/header.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/config/database.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1>Selamat Datang di Toko Online Kami</h1>
        <p>Temukan produk terbaik dengan harga terbaik</p>
        <a href="/toko_online/katalog.php" class="btn btn-primary">Lihat Katalog</a>
    </div>
</section>

<section class="featured-products">
    <h2>Produk Unggulan</h2>
    <div class="product-grid">
        <?php
        $query = "SELECT * FROM produk LIMIT 4";
        $result = mysqli_query($conn, $query);
        
        while($row = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="/toko_online/assets/images/<?= $row['gambar'] ?>" alt="<?= $row['nama_produk'] ?>">
                </div>
                <div class="product-info">
                    <h3><?= $row['nama_produk'] ?></h3>
                    <div class="price">
                        <span class="current-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                        <?php if($row['harga_awal'] > 0): ?>
                            <span class="original-price">Rp <?= number_format($row['harga_awal'], 0, ',', '.') ?></span>
                        <?php endif; ?>
                    </div>
                    <a href="/toko_online/detail_produk.php?id=<?= $row['id'] ?>" class="btn btn-secondary">Lihat Detail</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>