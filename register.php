<?php 
include 'includes/header.php'; 
include 'includes/navbar.php';

if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Cek apakah email sudah terdaftar
    $check = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check);
    
    if(mysqli_num_rows($result) > 0) {
        $error = "Email sudah terdaftar";
    } else {
        $query = "INSERT INTO users (username, email, password) 
                 VALUES ('$username', '$email', '$password')";
        
        if(mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Pendaftaran berhasil. Silakan login.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Terjadi kesalahan. Silakan coba lagi.";
        }
    }
}
?>

<section class="auth-form">
    <h1>Register</h1>
    <?php if(isset($error)): ?>
        <div class="alert error"><?php echo $error; ?></div>
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
        <button type="submit" class="btn">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
</section>

<?php include 'includes/footer.php'; ?>