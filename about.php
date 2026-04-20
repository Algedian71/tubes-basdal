<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Luxury Hotel - Experience Elegance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --gold: #c5a059; }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            /* BACKGROUND UTAMA SELURUH HALAMAN */
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), 
                        url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: white;
        }

        /* Navbar Blur Effect */
        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(0,0,0,0.3);
        }

        .section-title { font-family: 'Playfair Display', serif; color: var(--gold); font-size: 3rem; }

        /* Glassmorphism Card: Efek Kaca Transparan */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 40px;
            transition: 0.4s;
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-10px);
            border: 1px solid var(--gold);
        }

        .stats-box {
            background: rgba(197, 160, 89, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid var(--gold);
            border-radius: 20px;
            padding: 40px 0;
            margin-bottom: 50px;
        }

        .gold-line { width: 100px; height: 3px; background: var(--gold); margin: 20px auto; }

        .icon-circle {
            width: 70px; height: 70px;
            background: var(--gold);
            color: #000;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
            box-shadow: 0 0 20px rgba(197, 160, 89, 0.5);
        }

        p { color: #e0e0e0; line-height: 1.8; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="index.php">UNIB HOTEL</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link me-3" href="index.php">Home</a>
            <a class="nav-link border-bottom border-gold" href="about.php">About</a>
            <a class="btn btn-primary ms-3 px-4 shadow" href="auth/login.php">Login</a>
        </div>
    </div>
</nav>

<div class="container py-5 mt-5">
    <div class="text-center mb-5">
        <h5 class="text-uppercase mb-3" style="letter-spacing: 5px; color: var(--gold);">Our Story</h5>
        <h1 class="section-title">Tentang Kemewahan Kami</h1>
        <div class="gold-line"></div>
    </div>

    <div class="stats-box mb-5">
        <div class="row text-center">
            <div class="col-md-3 border-end border-secondary">
                <h2 class="fw-bold text-white">150+</h2>
                <p class="small text-uppercase mb-0 text-gold" style="color: var(--gold);">Kamar Mewah</p>
            </div>
            <div class="col-md-3 border-end border-secondary">
                <h2 class="fw-bold text-white">12+</h2>
                <p class="small text-uppercase mb-0" style="color: var(--gold);">Tahun Melayani</p>
            </div>
            <div class="col-md-3 border-end border-secondary">
                <h2 class="fw-bold text-white">5</h2>
                <p class="small text-uppercase mb-0" style="color: var(--gold);">Restoran Bintang 5</p>
            </div>
            <div class="col-md-3">
                <h2 class="fw-bold text-white">24/7</h2>
                <p class="small text-uppercase mb-0" style="color: var(--gold);">Layanan Prima</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-md-10">
            <div class="glass-card text-center">
                <p class="fs-4 italic mb-4" style="font-family: 'Playfair Display', serif; color: white;">"Menghadirkan kenyamanan istana ke dalam istirahat Anda."</p>
                <p>Terletak strategis di Bengkulu, Luxury Hotel bukan sekadar tempat menginap. Kami adalah perpaduan antara seni, kenyamanan, dan pelayanan kelas dunia. Dengan pemandangan langsung ke arah kota dan fasilitas yang dirancang khusus untuk kepuasan Anda, kami memastikan setiap detik kunjungan Anda menjadi memori yang indah.</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="glass-card text-center h-100">
                <div class="icon-circle"><i class="bi bi-gem"></i></div>
                <h4 class="fw-bold mb-3">Kualitas Premium</h4>
                <p class="small">Semua perlengkapan kamar kami menggunakan material terbaik untuk kualitas tidur maksimal.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card text-center h-100">
                <div class="icon-circle"><i class="bi bi-shield-lock"></i></div>
                <h4 class="fw-bold mb-3">Privasi Terjamin</h4>
                <p class="small">Sistem keamanan dan privasi tamu adalah prioritas utama kami dalam melayani.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card text-center h-100">
                <div class="icon-circle"><i class="bi bi-cup-hot"></i></div>
                <h4 class="fw-bold mb-3">Kuliner Eksklusif</h4>
                <p class="small">Nikmati menu spesial dari chef internasional yang hanya tersedia untuk tamu kami.</p>
            </div>
        </div>
    </div>
</div>

<footer class="mt-5 py-5 text-center" style="background: rgba(0,0,0,0.5);">
    <p class="mb-0 small text-secondary">&copy; 2026 UNIB HOTEL Management. All Rights Reserved.</p>
</footer>

</body>
</html>