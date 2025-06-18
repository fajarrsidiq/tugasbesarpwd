<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<section class="hero">
    <h1>Selamat Datang di Toko Online Kami</h1>
    <p>Temukan produk terbaik dengan harga terbaik</p>
    <a href="katalog.php" class="btn">Lihat Katalog</a>
</section>

<section class="featured-products">
    <h2>Produk Unggulan</h2>
    <div class="product-grid">
        <?php
        include 'config/database.php';
        $query = "SELECT * FROM produk LIMIT 4";
        $result = mysqli_query($conn, $query);
        
        while($row = mysqli_fetch_assoc($result)) {
            echo '<div class="product-card">';
            echo '<img src="assets/images/'.$row['gambar'].'" alt="'.$row['nama_produk'].'">';
            echo '<h3>'.$row['nama_produk'].'</h3>';
            echo '<p>Rp '.number_format($row['harga'], 0, ',', '.').'</p>';
            echo '<a href="detail_produk.php?id='.$row['id'].'" class="btn">Lihat Detail</a>';
            echo '</div>';
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>