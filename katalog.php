<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<section class="katalog">
    <h1>Katalog Produk</h1>
    <div class="product-grid">
        <?php
        include 'config/database.php';
        $query = "SELECT * FROM produk";
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