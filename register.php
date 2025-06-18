<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: /toko_online/index.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Cek apakah email sudah terdaftar
    $check = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "Email sudah terdaftar";
    } else {
        $query = "INSERT INTO users (username, email, password) 
                 VALUES ('$username', '$email', '$password')";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Pendaftaran berhasil. Silakan login.";
            header("Location: /toko_online/login.php");
            exit();
        } else {
            $error = "Terjadi kesalahan. Silakan coba lagi.";
        }
    }
}
?>

<section class="auth-form">
    <div class="form-container">
        <h1><i class="fas fa-user-plus"></i> Register</h1>
        
        <?php if (!empty($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
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
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </div>
        </form>
        
        <p class="auth-link">Sudah punya akun? <a href="/toko_online/login.php">Login disini</a></p>
    </div>
</section>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/toko_online/includes/footer.php';
?>