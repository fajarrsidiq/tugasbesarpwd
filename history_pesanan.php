<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /toko_online/login.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/navbar.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/config/database.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM pesanan WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<section class="order-history">
    <div class="history-header">
        <h1><i class="fas fa-history"></i> Riwayat Pesanan</h1>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
    </div>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="order-table">
            <thead>
                <tr>
                    <th>ID Pesanan</th>
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
                        <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td>
                            <span class="status-label <?= $row['status'] ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
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
    <?php else: ?>
        <div class="no-orders">
            <i class="fas fa-box-open fa-3x"></i>
            <p>Anda belum memiliki pesanan</p>
            <a href="/toko_online/katalog.php" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Mulai Belanja
            </a>
        </div>
    <?php endif; ?>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>