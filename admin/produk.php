<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/header.php';

// Cek session dan level admin
if (!isset($_SESSION['user_id'])) {
    header("Location: /toko_online/login.php");
    exit();
}

if ($_SESSION['level'] !== 'admin') {
    header("Location: /toko_online/index.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/config/database.php';

// Proses hapus produk
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "SELECT gambar FROM produk WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    
    if ($product) {
        // Hapus gambar dari server
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/toko_online/assets/images/'.$product['gambar'])) {
            unlink($_SERVER['DOCUMENT_ROOT'].'/toko_online/assets/images/'.$product['gambar']);
        }
        
        // Hapus produk dari database
        $delete = "DELETE FROM produk WHERE id = $id";
        if (mysqli_query($conn, $delete)) {
            $_SESSION['success'] = "Produk berhasil dihapus";
        } else {
            $_SESSION['error'] = "Gagal menghapus produk: " . mysqli_error($conn);
        }
    }
    header("Location: produk.php");
    exit();
}
?>

<section class="admin-products">
    <div class="form-header">
        <h1>Kelola Produk</h1>
        <a href="tambah_produk.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
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
                
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <img src="/toko_online/assets/images/<?= $row['gambar'] ?>" width="50" alt="<?= $row['nama_produk'] ?>">
                        </td>
                        <td><?= $row['nama_produk'] ?></td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td class="actions">
                            <a href="edit_produk.php?id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="produk.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>