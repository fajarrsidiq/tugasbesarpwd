<?php 
include '../../includes/header.php'; 
include '../../includes/navbar.php';

if(!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../../config/database.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM produk WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    
    if(!$product) {
        header("Location: produk.php");
        exit();
    }
} else {
    header("Location: produk.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $harga_awal = $_POST['harga_awal'];
    $kota = $_POST['kota'];
    $estimasi = $_POST['estimasi'];
    
    // Jika ada gambar baru diupload
    if($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        $target = "../../assets/images/".basename($gambar);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
        
        // Hapus gambar lama
        unlink("../../assets/images/".$product['gambar']);
    } else {
        $gambar = $product['gambar'];
    }
    
    $query = "UPDATE produk SET 
              nama_produk = '$nama_produk', 
              harga = $harga, 
              harga_awal = $harga_awal, 
              kota = '$kota', 
              estimasi = '$estimasi', 
              gambar = '$gambar' 
              WHERE id = $id";
    
    if(mysqli_query($conn, $query)) {
        header("Location: produk.php?success=1");
        exit();
    } else {
        $error = "Terjadi kesalahan. Silakan coba lagi.";
    }
}
?>

<section class="edit-product">
    <h1>Edit Produk</h1>
    
    <?php if(isset($error)): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nama_produk">Nama Produk</label>
            <input type="text" id="nama_produk" name="nama_produk" value="<?php echo $product['nama_produk']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" id="harga" name="harga" value="<?php echo $product['harga']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="harga_awal">Harga Awal (Diskon)</label>
            <input type="number" id="harga_awal" name="harga_awal" value="<?php echo $product['harga_awal']; ?>">
        </div>
        
        <div class="form-group">
            <label for="kota">Kota</label>
            <input type="text" id="kota" name="kota" value="<?php echo $product['kota']; ?>">
        </div>
        
        <div class="form-group">
            <label for="estimasi">Estimasi</label>
            <input type="text" id="estimasi" name="estimasi" value="<?php echo $product['estimasi']; ?>">
        </div>
        
        <div class="form-group">
            <label for="gambar">Gambar</label>
            <input type="file" id="gambar" name="gambar">
            <p>Gambar saat ini: <img src="../../assets/images/<?php echo $product['gambar']; ?>" width="50"></p>
        </div>
        
        <button type="submit" class="btn">Simpan</button>
    </form>
</section>

<?php include '../../includes/footer.php'; ?>