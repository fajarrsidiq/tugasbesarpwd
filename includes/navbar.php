<?php
session_start();
?>
<nav class="navbar">
    <div class="logo">
        <a href="index.php">Toko Online</a>
    </div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="katalog.php">Katalog</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="keranjang.php">Keranjang</a></li>
            <li><a href="history_pesanan.php">Pesanan Saya</a></li>
            <?php if($_SESSION['level'] == 'admin'): ?>
                <li><a href="admin/dashboard.php">Admin</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>