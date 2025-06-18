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

// Update status pesanan
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query = "UPDATE pesanan SET status = '$status' WHERE id = $order_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Status pesanan berhasil diupdate";
    } else {
        $_SESSION['error'] = "Gagal mengupdate status: " . mysqli_error($conn);
    }
    header("Location: pesanan.php");
    exit();
}

$query = "SELECT p.*, u.username 
          FROM pesanan p 
          JOIN users u ON p.user_id = u.id 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<section class="admin-orders">
    <div class="form-header">
        <h1>Kelola Pesanan</h1>
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
                    <th>ID Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>#<?= $row['id'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td>
                            <form method="post" class="status-form">
                                <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                <select name="status" onchange="this.form.submit()" class="status-select <?= $row['status'] ?>">
                                    <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="diproses" <?= $row['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                    <option value="dikirim" <?= $row['status'] == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                    <option value="selesai" <?= $row['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td>
                            <a href="/toko_online/detail_pesanan.php?id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i> Detail
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