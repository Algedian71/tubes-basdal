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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UNIB HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { 
            background: #f0f2f5; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            margin: 0;
        }
        .card { 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            width: 100%;
            max-width: 400px; 
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
            <h3 class="text-center fw-bold mb-4">Login Hotel</h3>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger text-center p-2 small"><?= $error ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label small">Email</label>
                    <input type="email" name="email" class="form-control shadow-sm" placeholder="admin@hotel.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Password</label>
                    <input type="password" name="password" class="form-control shadow-sm" placeholder="Masukkan password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100 mb-3">Sign In</button>
            </form>

            <div class="text-center">
                <a href="../index.php" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>

            <hr class="my-4">
            <p class="text-center text-muted mb-0" style="font-size: 0.7rem;">
                Admin: admin@hotel.com | Pass: admin123
            </p>
        </div>
    </div>

</body>
</html>