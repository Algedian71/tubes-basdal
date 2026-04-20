<?php 
require_once '../config/db.php'; 

// Jika sudah login, langsung lempar ke dashboard
if(isset($_SESSION['login'])) {
    if($_SESSION['role'] == 'admin') { header("Location: ../admin/index.php"); }
    else { header("Location: ../customer/index.php"); }
    exit;
}

if (isset($_POST['login'])) {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query  = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['login']   = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name']    = $row['name'];
            $_SESSION['role']    = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../customer/index.php");
            }
            exit;
        } else {
            $error = "Password salah! Pastikan password anda benar.";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UNIB HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gold: #C6A43F;
            --primary-gold-dark: #A8882E;
            --dark-bg: #0A0A0A;
            --dark-gray: #1A1A1A;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow-x: hidden;
        }
        
        /* Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2080&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            opacity: 0.15;
            z-index: 0;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-gold);
            border-radius: 10px;
        }
        
        /* Main Container */
        .login-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        /* Modern Card */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            transition: transform 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
        }
        
        /* Card Header */
        .card-header {
            background: linear-gradient(135deg, var(--dark-bg), var(--dark-gray));
            padding: 40px 30px 30px;
            text-align: center;
            position: relative;
        }
        
        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--primary-gold);
        }
        
        .logo-icon {
            width: 70px;
            height: 70px;
            background: rgba(198, 164, 63, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .logo-icon i {
            font-size: 2.5rem;
            color: var(--primary-gold);
        }
        
        .card-header h3 {
            font-family: 'Playfair Display', serif;
            color: white;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .card-header p {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
        }
        
        /* Card Body */
        .card-body {
            padding: 40px 35px;
        }
        
        /* Form Controls */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark-bg);
            display: block;
        }
        
        .form-group label i {
            color: var(--primary-gold);
            margin-right: 8px;
        }
        
        .input-group-custom {
            position: relative;
        }
        
        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-gold);
            z-index: 1;
        }
        
        .form-control {
            height: 50px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 10px 15px 10px 45px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            border-color: var(--primary-gold);
            box-shadow: 0 0 0 3px rgba(198, 164, 63, 0.1);
            outline: none;
        }
        
        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            z-index: 1;
        }
        
        .password-toggle:hover {
            color: var(--primary-gold);
        }
        
        /* Button */
        .btn-login {
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(198, 164, 63, 0.3);
        }
        
        /* Demo Credentials */
        .demo-credentials {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            padding: 15px;
            margin-top: 25px;
            text-align: center;
        }
        
        .demo-credentials p {
            font-size: 0.75rem;
            margin-bottom: 8px;
            color: #666;
            font-weight: 600;
        }
        
        .demo-credentials .credential-item {
            display: inline-block;
            background: white;
            padding: 4px 12px;
            border-radius: 8px;
            margin: 0 5px;
            font-size: 0.7rem;
            font-family: monospace;
            color: var(--primary-gold-dark);
            font-weight: 600;
        }
        
        /* Divider */
        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: calc(50% - 60px);
            height: 1px;
            background: #e0e0e0;
        }
        
        .divider::before {
            left: 0;
        }
        
        .divider::after {
            right: 0;
        }
        
        .divider span {
            background: white;
            padding: 0 15px;
            color: #999;
            font-size: 0.85rem;
        }
        
        /* Footer Links */
        .footer-links {
            text-align: center;
            margin-top: 25px;
        }
        
        .footer-links a {
            color: var(--primary-gold);
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .footer-links a:hover {
            color: var(--primary-gold-dark);
            transform: translateX(-3px);
        }
        
        /* Alert */
        .alert-custom {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            margin-bottom: 25px;
            font-size: 0.85rem;
        }
        
        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .card-body {
                padding: 30px 25px;
            }
            
            .card-header {
                padding: 30px 25px;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card" data-aos="fade-up" data-aos-duration="1000">
        <div class="card-header">
            <div class="logo-icon">
                <i class="bi bi-building"></i>
            </div>
            <h3>Selamat Datang Kembali</h3>
            <p>Login untuk mengakses akun Anda</p>
        </div>
        
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert-custom">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label><i class="bi bi-envelope"></i> Email</label>
                    <div class="input-group-custom">
                        <i class="bi bi-envelope-fill"></i>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required autofocus>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="bi bi-lock"></i> Password</label>
                    <div class="input-group-custom">
                        <i class="bi bi-key-fill"></i>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                        <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                    </div>
                </div>
                
                <button type="submit" name="login" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                </button>
            </form>
            
            <div class="divider">
                <span>atau</span>
            </div>
            
            <div class="footer-links">
                <a href="register.php">
                    <i class="bi bi-person-plus-fill"></i> Belum punya akun? Daftar
                </a>
                <br>
                <a href="../index.php" class="mt-2 d-inline-block">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
            
            <!-- Demo Credentials -->
            <div class="demo-credentials">
                <p><i class="bi bi-info-circle-fill me-1"></i> Demo Credentials</p>
                <div>
                    <span class="credential-item">📧 admin@hotel.com</span>
                    <span class="credential-item">🔑 admin123</span>
                </div>
                <div class="mt-2">
                    <span class="credential-item">📧 user@hotel.com</span>
                    <span class="credential-item">🔑 user123</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true
    });
    
    // Password Toggle
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    
    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    }
    
    // Form validation animation
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });
    
    // Auto fill demo credentials (optional)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('demo') === 'admin') {
        document.querySelector('input[name="email"]').value = 'admin@hotel.com';
        document.querySelector('input[name="password"]').value = 'admin123';
    }
</script>
</body>
</html>