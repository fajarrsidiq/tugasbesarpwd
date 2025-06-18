<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: /toko_online/index.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];
            
            if ($user['level'] == 'admin') {
                header("Location: /toko_online/admin/dashboard.php");
            } else {
                header("Location: /toko_online/index.php");
            }
            exit();
        } else {
            $error = "Password salah";
        }
    } else {
        $error = "Email tidak ditemukan";
    }
}
?>

<section class="auth-form">
    <div class="form-container">
        <h1><i class="fas fa-sign-in-alt"></i> Login</h1>
        
        <?php if (!empty($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </div>
        </form>
        
        <p class="auth-link">Belum punya akun? <a href="/toko_online/register.php">Daftar disini</a></p>
    </div>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>