<?php 
include '../../includes/header.php'; 
include '../../includes/navbar.php';

if(!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../../config/database.php';

// Proses hapus produk
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "SELECT gambar FROM produk WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    
    if($product) {
        unlink("../../assets/images/".$product['gambar']);
        $delete = "DELETE FROM produk WHERE id = $id";
        mysqli_query($conn, $delete);
        $_SESSION['success'] = "Produk berhasil dihapus";
    }
}
?>

<section class="admin-products">
    <div class="header">
        <h1>Kelola Produk</h1>
        <a href="tambah_produk.php" class="btn btn-tambah">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = "SELECT * FROM produk";
                $result = mysqli_query($conn, $query);
                
                while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><img src="../../assets/images/<?= $row['gambar']; ?>" width="50"></td>
                        <td><?= $row['nama_produk']; ?></td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td class="actions">
                            <a href="edit_produk.php?id=<?= $row['id']; ?>" class="btn btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="produk.php?hapus=<?= $row['id']; ?>" class="btn btn-hapus" onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>