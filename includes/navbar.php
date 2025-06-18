<nav class="navbar">
    <div class="logo">
        <a href="/toko_online/index.php">Toko Online</a>
    </div>
    <ul class="nav-links">
        <li><a href="/toko_online/index.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="/toko_online/katalog.php"><i class="fas fa-list"></i> Katalog</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="/toko_online/keranjang.php"><i class="fas fa-shopping-cart"></i> Keranjang</a></li>
            <li><a href="/toko_online/history_pesanan.php"><i class="fas fa-history"></i> Pesanan Saya</a></li>
            <?php if($_SESSION['level'] == 'admin'): ?>
                <li><a href="/toko_online/admin/dashboard.php"><i class="fas fa-cog"></i> Admin</a></li>
            <?php endif; ?>
            <li><a href="/toko_online/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php else: ?>
            <li><a href="/toko_online/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
            <li><a href="/toko_online/register.php"><i class="fas fa-user-plus"></i> Register</a></li>
        <?php endif; ?>
    </ul>
</nav>