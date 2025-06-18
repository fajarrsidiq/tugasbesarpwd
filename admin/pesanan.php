<?php 
include '../../includes/header.php'; 
include '../../includes/navbar.php';

if(!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../../config/database.php';

// Update status pesanan
if(isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    
    $query = "UPDATE pesanan SET status = '$status' WHERE id = $order_id";
    mysqli_query($conn, $query);
    
    header("Location: pesanan.php?success=1");
    exit();
}

$query = "SELECT p.*, u.username 
          FROM pesanan p 
          JOIN users u ON p.user_id = u.id 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<section class="admin-orders">
    <h1>Kelola Pesanan</h1>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert success">Status pesanan berhasil diupdate!</div>
    <?php endif; ?>
    
    <table>
        <tr>
            <th>ID Pesanan</th>
            <th>Pelanggan</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="diproses" <?php echo $row['status'] == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                            <option value="dikirim" <?php echo $row['status'] == 'dikirim' ? 'selected' : ''; ?>>Dikirim</option>
                            <option value="selesai" <?php echo $row['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                        <noscript><button type="submit" name="update_status">Update</button></noscript>
                    </form>
                </td>
                <td><a href="../detail_pesanan.php?id=<?php echo $row['id']; ?>">Detail</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<?php include '../../includes/footer.php'; ?>