<?php 
include '../../includes/header.php'; 
include '../../includes/navbar.php';

if(!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../../config/database.php';

if(!isset($_GET['id'])) {
    header("Location: produk.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM produk WHERE id = $id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if(!$product) {
    header("Location: produk.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = $_POST['harga'];
    $harga_awal = $_POST['harga_awal'] ?? 0;
    $kota = mysqli_real_escape_string($conn, $_POST['kota']);
    $estimasi = mysqli_real_escape_string($conn, $_POST['estimasi']);
    
    // Jika ada gambar baru diupload
    if($_FILES['gambar']['name']) {
        // Hapus gambar lama
        unlink("../../assets/images/".$product['gambar']);
        
        // Upload gambar baru
        $gambar = $_FILES['gambar']['name'];
        $ext = pathinfo($gambar, PATHINFO_EXTENSION);
        $new_name = 'produk_'.time().'.'.$ext;
        $target = "../../assets/images/".$new_name;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
    } else {
        $new_name = $product['gambar'];
    }
    
    $query = "UPDATE produk SET 
              nama_produk = '$nama_produk', 
              harga = $harga, 
              harga_awal = $harga_awal, 
              kota = '$kota', 
              estimasi = '$estimasi', 
              gambar = '$new_name' 
              WHERE id = $id";
    
    if(mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Produk berhasil diupdate";
        header("Location: produk.php");
        exit();
    } else {
        $error = "Gagal mengupdate produk: " . mysqli_error($conn);
    }
}
?>

<section class="form-container">
    <div class="header">
        <h1>Edit Produk</h1>
        <a href="produk.php" class="btn btn-kembali">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <?php if(isset($error)): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="post" enctype="multipart/form-data" class="form-produk">
        <div class="form-group">
            <label for="nama_produk">Nama Produk</label>
            <input type="text" id="nama_produk" name="nama_produk" value="<?= $product['nama_produk']; ?>" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" id="harga" name="harga" value="<?= $product['harga']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="harga_awal">Harga Awal (Diskon)</label>
                <input type="number" id="harga_awal" name="harga_awal" value="<?= $product['harga_awal']; ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="kota">Kota</label>
                <input type="text" id="kota" name="kota" value="<?= $product['kota']; ?>">
            </div>
            
            <div class="form-group">
                <label for="estimasi">Estimasi</label>
                <input type="text" id="estimasi" name="estimasi" value="<?= $product['estimasi']; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="gambar">Gambar Produk</label>
            <input type="file" id="gambar" name="gambar" accept="image/*">
            <small>Biarkan kosong jika tidak ingin mengubah gambar</small>
            <div class="current-image">
                <p>Gambar saat ini:</p>
                <img src="../../assets/images/<?= $product['gambar']; ?>" width="150">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-simpan">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</section>

<?php include '../../includes/footer.php'; ?>