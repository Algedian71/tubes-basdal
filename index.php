<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Hotel - Selamat Datang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            margin: 0; 
            padding: 0; 
            overflow: hidden; /* Supaya gak ada scrollbar */
        }
        
        .hero {
            /* === GANTI GAMBAR BACKGROUND DI SINI === */
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2080&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease-out;
        }
        
        .hero p {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 40px;
            animation: fadeInUp 1.2s ease-out;
        }
        
        .hero-btns {
            animation: fadeInUp 1.5s ease-out;
        }
        
        .btn-luxury {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            text-decoration: none;
        }
        
        .btn-luxury:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-5px);
        }
        
        .navbar-luxury {
            position: absolute;
            top: 0;
            width: 100%;
            padding: 20px 0;
            background: transparent;
            z-index: 10;
        }
        
        .navbar-luxury .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: white;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-luxury">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-building"></i> UNIB HOTEL</a>
    </div>
</nav>

<div class="hero">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>Temukan Kenyamanan & Kemewahan Sejati</h1>
                <p>Pengalaman menginap tak terlupakan di UNIB BELAKANG, Indonesia.</p>
                <div class="hero-btns mt-4">
                    <a href="auth/login.php" class="btn-luxury mx-2 shadow"><i class="bi bi-box-arrow-in-right me-2"></i> Login</a>
                    <a href="auth/register.php" class="btn-luxury mx-2 shadow border-0" style="background: rgba(26, 35, 126, 0.8);">Buat Akun Anda Sekarang <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>