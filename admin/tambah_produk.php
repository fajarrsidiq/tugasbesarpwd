<?php 
include '../../includes/header.php'; 
include '../../includes/navbar.php';

if(!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../../config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $harga_awal = $_POST['harga_awal'];
    $kota = $_POST['kota'];
    $estimasi = $_POST['estimasi'];
    
    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $target = "../../assets/images/".basename($gambar);
    
    move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
    
    $query = "INSERT INTO produk (nama_produk, harga, harga_awal, kota, estimasi, gambar) 
              VALUES ('$nama_produk', $harga, $harga_awal, '$kota', '$estimasi', '$gambar')";
    
    if(mysqli_query($conn, $query)) {
        header("Location: produk.php?success=1");
        exit();
    } else {
        $error = "Terjadi kesalahan. Silakan coba lagi.";
    }
}
?>

<section class="add-product">
    <h1>Tambah Produk</h1>
    
    <?php if(isset($error)): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama_produk">Nama Produk</label>
            <input type="text" id="nama_produk" name="nama_produk" required>
        </div>
        
        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" id="harga" name="harga" required>
        </div>
        
        <div class="form-group">
            <label for="harga_awal">Harga Awal (Diskon)</label>
            <input type="number" id="harga_awal" name="harga_awal">
        </div>
        
        <div class="form-group">
            <label for="kota">Kota</label>
            <input type="text" id="kota" name="kota">
        </div>
        
        <div class="form-group">
            <label for="estimasi">Estimasi</label>
            <input type="text" id="estimasi" name="estimasi">
        </div>
        
        <div class="form-group">
            <label for="gambar">Gambar</label>
            <input type="file" id="gambar" name="gambar" required>
        </div>
        
        <button type="submit" class="btn">Simpan</button>
    </form>
</section>

<?php include '../../includes/footer.php'; ?>