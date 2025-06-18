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
?>

<section class="admin-dashboard">
    <div class="dashboard-header">
        <h1>Dashboard Admin</h1>
        <div class="quick-actions">
            <a href="produk.php" class="btn btn-primary">
                <i class="fas fa-box"></i> Kelola Produk
            </a>
            <a href="pesanan.php" class="btn btn-secondary">
                <i class="fas fa-clipboard-list"></i> Lihat Pesanan
            </a>
        </div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-content">
                <h3>Total Produk</h3>
                <?php
                $query = "SELECT COUNT(*) as total FROM produk";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                echo '<p class="stat-value">'.number_format($row['total']).'</p>';
                ?>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <h3>Total Pesanan</h3>
                <?php
                $query = "SELECT COUNT(*) as total FROM pesanan";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                echo '<p class="stat-value">'.number_format($row['total']).'</p>';
                ?>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>Pesanan Baru</h3>
                <?php
                $query = "SELECT COUNT(*) as total FROM pesanan WHERE status = 'pending'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                echo '<p class="stat-value">'.number_format($row['total']).'</p>';
                ?>
            </div>
        </div>
    </div>
    
    <div class="recent-section">
        <h2><i class="fas fa-history"></i> Aktivitas Terkini</h2>
        <div class="recent-orders">
            <?php
            $query = "SELECT p.id, u.username, p.total_harga, p.status, p.created_at 
                     FROM pesanan p JOIN users u ON p.user_id = u.id 
                     ORDER BY p.created_at DESC LIMIT 5";
            $result = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($result) > 0) {
                echo '<table class="order-table">';
                echo '<thead><tr>
                        <th>ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                      </tr></thead>';
                echo '<tbody>';
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>#'.$row['id'].'</td>';
                    echo '<td>'.$row['username'].'</td>';
                    echo '<td>Rp '.number_format($row['total_harga'], 0, ',', '.').'</td>';
                    echo '<td><span class="status-label '.$row['status'].'">'.ucfirst($row['status']).'</span></td>';
                    echo '<td>'.date('d M Y H:i', strtotime($row['created_at'])).'</td>';
                    echo '</tr>';
                }
                
                echo '</tbody></table>';
            } else {
                echo '<p class="no-data">Belum ada pesanan</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>