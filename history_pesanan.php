<?php 
include 'includes/header.php'; 
include 'includes/navbar.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config/database.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM pesanan WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<section class="order-history">
    <h1>Riwayat Pesanan</h1>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert success">Pesanan berhasil dibuat!</div>
    <?php endif; ?>
    
    <?php if(mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                    <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td><a href="detail_pesanan.php?id=<?php echo $row['id']; ?>">Detail</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Anda belum memiliki pesanan.</p>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>