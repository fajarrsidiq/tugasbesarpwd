<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/header.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/config/database.php';
?>

<section class="product-catalog">
    <div class="catalog-header">
        <h1>Katalog Produk</h1>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['level'] == 'admin'): ?>
            <a href="/toko_online/admin/produk.php" class="btn btn-primary">
                <i class="fas fa-box"></i> Kelola Produk
            </a>
        <?php endif; ?>
    </div>
    
    <div class="product-grid">
        <?php
        $query = "SELECT * FROM produk";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="/toko_online/assets/images/<?= $row['gambar'] ?>" alt="<?= $row['nama_produk'] ?>">
                    </div>
                    <div class="product-info">
                        <h3><?= $row['nama_produk'] ?></h3>
                        <div class="price">
                            <span class="current-price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                            <?php if ($row['harga_awal'] > 0): ?>
                                <span class="original-price">Rp <?= number_format($row['harga_awal'], 0, ',', '.') ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-meta">
                            <span><i class="fas fa-map-marker-alt"></i> <?= $row['kota'] ?></span>
                            <span><i class="fas fa-truck"></i> <?= $row['estimasi'] ?></span>
                        </div>
                        <a href="/toko_online/detail_produk.php?id=<?= $row['id'] ?>" class="btn btn-secondary">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            <?php endwhile;
        } else {
            echo '<p class="no-data">Belum ada produk</p>';
        }
        ?>
    </div>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>