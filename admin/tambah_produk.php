<?php 
include '../../includes/header.php'; 
include '../../includes/navbar.php';

if(!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = $_POST['harga'];
    $harga_awal = $_POST['harga_awal'] ?? 0;
    $kota = mysqli_real_escape_string($conn, $_POST['kota']);
    $estimasi = mysqli_real_escape_string($conn, $_POST['estimasi']);
    
    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $ext = pathinfo($gambar, PATHINFO_EXTENSION);
    $new_name = 'produk_'.time().'.'.$ext;
    $target = "../../assets/images/".$new_name;
    
    if(move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
        $query = "INSERT INTO produk (nama_produk, harga, harga_awal, kota, estimasi, gambar) 
                  VALUES ('$nama_produk', $harga, $harga_awal, '$kota', '$estimasi', '$new_name')";
        
        if(mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Produk berhasil ditambahkan";
            header("Location: produk.php");
            exit();
        } else {
            $error = "Gagal menambahkan produk: " . mysqli_error($conn);
        }
    } else {
        $error = "Gagal mengupload gambar";
    }
}
?>

<section class="form-container">
    <div class="header">
        <h1>Tambah Produk Baru</h1>
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
            <input type="text" id="nama_produk" name="nama_produk" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" id="harga" name="harga" required>
            </div>
            
            <div class="form-group">
                <label for="harga_awal">Harga Awal (Diskon)</label>
                <input type="number" id="harga_awal" name="harga_awal">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="kota">Kota</label>
                <input type="text" id="kota" name="kota">
            </div>
            
            <div class="form-group">
                <label for="estimasi">Estimasi</label>
                <input type="text" id="estimasi" name="estimasi">
            </div>
        </div>
        
        <div class="form-group">
            <label for="gambar">Gambar Produk</label>
            <input type="file" id="gambar" name="gambar" required accept="image/*">
            <small>Format: JPG, PNG, JPEG (Max 2MB)</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-simpan">
                <i class="fas fa-save"></i> Simpan Produk
            </button>
        </div>
    </form>
</section>

<?php include '../../includes/footer.php'; ?>