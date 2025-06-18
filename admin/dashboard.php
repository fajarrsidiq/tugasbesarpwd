<?php 
include '../../includes/header.php'; 
include '../../includes/navbar.php';

if(!isset($_SESSION['user_id']) || $_SESSION['level'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../../config/database.php';
?>

<section class="admin-dashboard">
    <h1>Dashboard Admin</h1>
    
    <div class="stats">
        <div class="stat-card">
            <h3>Total Produk</h3>
            <?php
            $query = "SELECT COUNT(*) as total FROM produk";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            echo '<p>'.$row['total'].'</p>';
            ?>
        </div>
        
        <div class="stat-card">
            <h3>Total Pesanan</h3>
            <?php
            $query = "SELECT COUNT(*) as total FROM pesanan";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            echo '<p>'.$row['total'].'</p>';
            ?>
        </div>
        
        <div class="stat-card">
            <h3>Pesanan Baru</h3>
            <?php
            $query = "SELECT COUNT(*) as total FROM pesanan WHERE status = 'pending'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            echo '<p>'.$row['total'].'</p>';
            ?>
        </div>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>