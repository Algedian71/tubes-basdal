<?php 
require_once '../config/db.php'; 

if (isset($_POST['register'])) {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = 'customer'; // Default untuk pendaftar baru

    // Cek apakah email sudah ada
    $cek_email = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='login.php';</script>";
        } else {
            $error = "Gagal mendaftar, coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UNIB HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { 
            background: #f0f2f5; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            margin: 0;
            padding: 20px 0;
        }
        .card { 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            width: 100%;
            max-width: 450px; 
        }
        .btn-primary { 
            background: #1a237e; 
            border: none; 
            padding: 10px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: #0d145a;
        }
    </style>
</head>
<body>

    <div class="card p-4">
        <div class="card-body">
            <h3 class="text-center fw-bold mb-2">Buat Akun</h3>
            <p class="text-center text-muted small mb-4">Daftar untuk mulai booking kamar mewah</p>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger text-center p-2 small"><?= $error ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label small">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control shadow-sm" placeholder="Masukkan nama" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Email</label>
                    <input type="email" name="email" class="form-control shadow-sm" placeholder="nama@email.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Password</label>
                    <input type="password" name="password" class="form-control shadow-sm" placeholder="Buat password" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100 mb-3">Daftar Sekarang</button>
            </form>

            <div class="text-center mt-2">
                <p class="small mb-2 text-muted">Sudah punya akun? <a href="login.php" class="text-decoration-none fw-bold">Login</a></p>
                <hr class="my-3">
                <a href="../index.php" class="text-decoration-none small text-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

</body>
</html>