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

if (!isset($_GET['id'])) {
    header("Location: produk.php");
    exit();
}

$id = (int)$_GET['id'];
$query = "SELECT * FROM produk WHERE id = $id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header("Location: produk.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = (int)$_POST['harga'];
    $harga_awal = !empty($_POST['harga_awal']) ? (int)$_POST['harga_awal'] : 0;
    $kota = mysqli_real_escape_string($conn, $_POST['kota']);
    $estimasi = mysqli_real_escape_string($conn, $_POST['estimasi']);
    $gambar = $product['gambar'];
    
    // Jika ada gambar baru diupload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $new_gambar = 'produk_'.time().'.'.$ext;
        $target = $_SERVER['DOCUMENT_ROOT'].'/toko_online/assets/images/'.$new_gambar;
        
        // Validasi file gambar
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($ext), $allowed)) {
            $error = "Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.";
        } elseif ($_FILES['gambar']['size'] > 2000000) {
            $error = "Ukuran file terlalu besar. Maksimal 2MB.";
        } elseif (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
            // Hapus gambar lama
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/toko_online/assets/images/'.$product['gambar'])) {
                unlink($_SERVER['DOCUMENT_ROOT'].'/toko_online/assets/images/'.$product['gambar']);
            }
            $gambar = $new_gambar;
        } else {
            $error = "Gagal mengupload gambar.";
        }
    }
    
    if (empty($error)) {
        $query = "UPDATE produk SET 
                 nama_produk = '$nama_produk', 
                 harga = $harga, 
                 harga_awal = $harga_awal, 
                 kota = '$kota', 
                 estimasi = '$estimasi', 
                 gambar = '$gambar' 
                 WHERE id = $id";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Produk berhasil diupdate";
            header("Location: produk.php");
            exit();
        } else {
            $error = "Gagal mengupdate produk: " . mysqli_error($conn);
            // Hapus gambar baru jika query gagal
            if (isset($new_gambar) && file_exists($target)) {
                unlink($target);
            }
        }
    }
}
?>

<section class="form-container">
    <div class="form-header">
        <h1>Edit Produk</h1>
        <a href="produk.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <?php if (!empty($error)): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="post" enctype="multipart/form-data" class="form-produk">
        <div class="form-group">
            <label for="nama_produk">Nama Produk</label>
            <input type="text" id="nama_produk" name="nama_produk" value="<?= htmlspecialchars($product['nama_produk']) ?>" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" id="harga" name="harga" min="0" value="<?= $product['harga'] ?>" required>
            </div>
            
            <div class="form-group">
                <label for="harga_awal">Harga Awal (Diskon)</label>
                <input type="number" id="harga_awal" name="harga_awal" min="0" value="<?= $product['harga_awal'] ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="kota">Kota</label>
                <input type="text" id="kota" name="kota" value="<?= htmlspecialchars($product['kota']) ?>">
            </div>
            
            <div class="form-group">
                <label for="estimasi">Estimasi</label>
                <input type="text" id="estimasi" name="estimasi" value="<?= htmlspecialchars($product['estimasi']) ?>" placeholder="Contoh: 1-2 hari">
            </div>
        </div>
        
        <div class="form-group">
            <label for="gambar">Gambar Produk</label>
            <input type="file" id="gambar" name="gambar" accept="image/*">
            <small>Biarkan kosong jika tidak ingin mengubah gambar</small>
            <div class="current-image">
                <p>Gambar saat ini:</p>
                <img src="/toko_online/assets/images/<?= $product['gambar'] ?>" width="150" alt="<?= $product['nama_produk'] ?>">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>