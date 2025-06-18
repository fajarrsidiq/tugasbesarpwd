<?php 
include '../../includes/header.php'; 
include '../../includes/navbar.php';

if(!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../../config/database.php';
?>

<section class="admin-products">
    <h1>Kelola Produk</h1>
    
    <a href="tambah_produk.php" class="btn">Tambah Produk</a>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert success">Produk berhasil diupdate!</div>
    <?php endif; ?>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Gambar</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php
        $query = "SELECT * FROM produk";
        $result = mysqli_query($conn, $query);
        
        while($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>'.$row['id'].'</td>';
            echo '<td><img src="../../assets/images/'.$row['gambar'].'" width="50"></td>';
            echo '<td>'.$row['nama_produk'].'</td>';
            echo '<td>Rp '.number_format($row['harga'], 0, ',', '.').'</td>';
            echo '<td>';
            echo '<a href="edit_produk.php?id='.$row['id'].'">Edit</a> | ';
            echo '<a href="hapus_produk.php?id='.$row['id'].'" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</a>';
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</section>

<?php include '../../includes/footer.php'; ?>