<?php 
require_once '../config/db.php'; 

// Proteksi: Hanya customer yang boleh masuk
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UNIB HOTEL</title>
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
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--dark-gray) 100%);
            color: white;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2080&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            opacity: 0.15;
            z-index: -2;
        }
        
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(10,10,10,0.95), rgba(26,26,26,0.95));
            z-index: -1;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-gold);
            border-radius: 10px;
        }
        
        /* Modern Navbar */
        .navbar-luxury {
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            border-bottom: 1px solid rgba(198, 164, 63, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: white !important;
            letter-spacing: 2px;
        }
        
        .navbar-brand i {
            color: var(--primary-gold);
            margin-right: 8px;
        }
        
        .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 15px;
            transition: 0.3s;
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--primary-gold);
            transition: 0.3s;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 80%;
        }
        
        .nav-link:hover {
            color: var(--primary-gold) !important;
        }
        
        /* Welcome Section */
        .welcome-section {
            padding: 60px 0 40px;
        }
        
        .welcome-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 700;
        }
        
        .welcome-section p {
            color: rgba(255,255,255,0.7);
        }
        
        .gold-line {
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-gold), var(--primary-gold-dark));
            margin-top: 20px;
        }
        
        /* Room Cards */
        .card-room {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            transition: all 0.4s ease;
            overflow: hidden;
            height: 100%;
        }
        
        .card-room:hover {
            transform: translateY(-10px);
            border-color: var(--primary-gold);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .card-img-top {
            height: 230px;
            object-fit: cover;
        }
        
        .badge-room {
            background: var(--primary-gold);
            color: var(--dark-bg);
            font-weight: 700;
            padding: 5px 15px;
        }
        
        .card-title {
            color: white;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .card-text {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        .price-tag {
            color: var(--primary-gold);
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .per-malam {
            color: rgba(255,255,255,0.5);
            font-size: 0.8rem;
        }
        
        .btn-booking {
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 700;
            color: var(--dark-bg);
            width: 100%;
            transition: 0.3s;
        }
        
        .btn-booking:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(198, 164, 63, 0.3);
            color: var(--dark-bg);
        }
        
        /* Footer */
        .footer {
            background: rgba(10, 10, 10, 0.95);
            padding: 40px 0;
            margin-top: 80px;
            border-top: 1px solid rgba(198, 164, 63, 0.3);
            text-align: center;
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
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .welcome-section h2 {
                font-size: 1.8rem;
            }
            
            .card-room {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

<!-- Modern Navbar -->
<nav class="navbar-luxury">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-building"></i> UNIB HOTEL
        </a>
        <div class="d-flex align-items-center">
            <a class="nav-link active" href="index.php">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
            <a class="nav-link" href="riwayat.php">
                <i class="bi bi-clock-history me-1"></i> Riwayat
            </a>
            <a class="nav-link" href="../about.php">
                <i class="bi bi-info-circle me-1"></i> Tentang Kami
            </a>
            <a class="nav-link" href="../auth/logout.php">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
        </div>
    </div>
</nav>

<!-- Welcome Section -->
<div class="container welcome-section">
    <div data-aos="fade-up" data-aos-duration="1000">
        <h2 class="fw-bold mb-2">Selamat Datang, <?= $_SESSION['name']; ?>! 👋</h2>
        <p>Eksplorasi pilihan kamar terbaik untuk kenyamanan Anda.</p>
        <div class="gold-line"></div>
    </div>
</div>

<!-- Rooms Section -->
<div class="container py-4">
    <div class="row g-4">
        <?php
        // Ambil data kamar yang statusnya 'tersedia'
        $query = mysqli_query($conn, "SELECT * FROM rooms WHERE status = 'tersedia' ORDER BY id DESC");
        
        if(mysqli_num_rows($query) == 0) {
            echo "<div class='col-12 text-center py-5'>
                    <i class='bi bi-emoji-frown fs-1 text-white-50'></i>
                    <h5 class='text-white-50 mt-3'>Belum ada kamar yang tersedia saat ini.</h5>
                  </div>";
        }

        while($row = mysqli_fetch_assoc($query)):
        ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card-room">
                <img src="../assets/img/<?= $row['gambar']; ?>" class="card-img-top" alt="<?= $row['nama_kamar']; ?>">
                
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-room"><?= $row['tipe']; ?></span>
                        <span class="text-success small fw-bold">
                            <i class="bi bi-check-circle-fill"></i> Tersedia
                        </span>
                    </div>
                    
                    <h5 class="card-title"><?= $row['nama_kamar']; ?></h5>
                    <p class="card-text"><?= substr($row['deskripsi'], 0, 100); ?>...</p>
                    
                    <div class="mt-3">
                        <div class="mb-3">
                            <span class="price-tag">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>
                            <span class="per-malam">/ malam</span>
                        </div>
                        <a href="booking.php?id=<?= $row['id']; ?>" class="btn-booking text-decoration-none d-inline-block text-center">
                            <i class="bi bi-calendar-check me-2"></i>Booking Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="text-muted small mb-0">&copy; 2026 UNIB Hotel Management System - Teknik Informatika Bengkulu</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });
    
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar-luxury');
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(10, 10, 10, 0.98)';
        } else {
            navbar.style.background = 'rgba(10, 10, 10, 0.95)';
        }
    });
</script>
</body>
</html>