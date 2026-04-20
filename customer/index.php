<?php 
require_once '../config/db.php'; 

// Proteksi: Hanya customer yang boleh masuk
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNIB Hotel - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
    body {
        /* Background Luxury dengan Overlay Gelap agar tulisan putih menonjol */
        background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)), 
                    url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2080&auto=format&fit=crop');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        color: white;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
    }

    /* Navbar Transparent Glass */
    .navbar {
        backdrop-filter: blur(10px);
        background: rgba(0,0,0,0.8) !important;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        
        /* EFEK SEMBUNYI */
        position: fixed;
        top: -80px; /* Sembunyi ke atas */
        width: 100%;
        transition: top 0.4s ease-in-out; /* Animasi halus */
        z-index: 1000;
    }

    /* Saat kursor di area atas, navbar muncul */
    .navbar.show-nav {
        top: 0;
    }

    /* Area pemicu (invisible sensor) agar user gampang memunculkan navbar */
    #nav-trigger {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 20px; /* Area sensitif 20px di paling atas */
        z-index: 999;
    }

    /* Welcome Text Styling */
    .welcome-section h2 {
        font-family: 'Playfair Display', serif;
        color: #ffffff !important;
        text-shadow: 2px 4px 10px rgba(0,0,0,0.8);
        font-size: 2.5rem;
    }

    .welcome-section p {
        color: #d1d1d1 !important;
        text-shadow: 1px 2px 5px rgba(0,0,0,0.5);
    }

    /* Card Kamar Glassmorphism */
    .card-room {
        background: rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        transition: 0.4s;
        overflow: hidden;
    }

    .card-room:hover {
        transform: translateY(-10px);
        border: 1px solid #c5a059;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    /* Memastikan teks di dalam kartu terlihat */
    .card-title {
        color: #ffffff !important;
        font-size: 1.4rem;
    }

    .card-text {
        color: #cccccc !important;
    }

    .price-tag {
        color: #c5a059 !important; /* Warna Emas */
        font-weight: 700;
        font-size: 1.25rem;
    }

    .per-malam {
        color: rgba(255,255,255,0.6) !important;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-booking {
        background-color: #c5a059 !important;
        border: none !important;
        color: #000 !important;
        font-weight: 700;
        border-radius: 10px;
    }

    .btn-booking:hover {
        background-color: #e2b86d !important;
    }
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-building"></i> UNIB HOTEL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link active fw-bold" href="index.php">Cari Kamar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="riwayat.php">Riwayat Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../about.php">Tentang Kami</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a href="../auth/logout.php" class="btn btn-outline-light btn-sm px-3">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row mb-5 welcome-section">
        <div class="col">
            <h2 class="fw-bold mb-1">Selamat Datang, <?= $_SESSION['name']; ?>!</h2>
            <p>Eksplorasi pilihan kamar terbaik untuk kenyamanan Anda.</p>
            <div style="width: 80px; height: 4px; background: #c5a059; border-radius: 10px;"></div>
        </div>
    </div>

    <div class="row">
        <?php
        // Ambil data kamar yang statusnya 'tersedia'
        $query = mysqli_query($conn, "SELECT * FROM rooms WHERE status = 'tersedia' ORDER BY id DESC");
        
        if(mysqli_num_rows($query) == 0) {
            echo "<div class='col-12 text-center py-5'><h5 class='text-white-50 italic'>Belum ada kamar yang tersedia saat ini.</h5></div>";
        }

        while($row = mysqli_fetch_assoc($query)):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card card-room h-100 border-0 shadow-lg">
                <img src="../assets/img/<?= $row['gambar']; ?>" class="card-img-top" alt="Kamar" style="height: 230px; object-fit: cover;">
                
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between mb-3 align-items-center">
                        <span class="badge" style="background: #c5a059; color: black; font-weight: 700;"><?= $row['tipe']; ?></span>
                        <span class="text-info small fw-bold"><i class="bi bi-patch-check-fill"></i> Tersedia</span>
                    </div>
                    
                    <h5 class="card-title fw-bold mb-3"><?= $row['nama_kamar']; ?></h5>
                    <p class="card-text small mb-4"><?= substr($row['deskripsi'], 0, 100); ?>...</p>
                    
                    <div class="mt-auto">
                        <div class="mb-3">
                            <span class="price-tag">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>
                            <span class="per-malam">/ malam</span>
                        </div>
                        <a href="booking.php?id=<?= $row['id']; ?>" class="btn btn-booking w-100 py-2">
                            BOOKING SEKARANG <i class="bi bi-chevron-right small"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<footer class="text-center py-5" style="color: rgba(255,255,255,0.4);">
    &copy; 2026 UNIB Hotel Management System - Teknik Informatika Bengkulu
</footer>

<div id="nav-trigger"></div>

<script>
    const nav = document.querySelector('.navbar');
    const trigger = document.querySelector('#nav-trigger');

    // Munculkan pas mouse menyentuh area pemicu atas
    trigger.addEventListener('mouseenter', () => {
        nav.classList.add('show-nav');
    });

    // Tetap muncul pas mouse di dalam navbar
    nav.addEventListener('mouseenter', () => {
        nav.classList.add('show-nav');
    });

    // Sembunyi lagi pas mouse keluar dari navbar
    nav.addEventListener('mouseleave', () => {
        nav.classList.add('show-nav'); // Hapus baris ini kalau mau langsung sembunyi
        // Kita kasih delay sedikit biar gak kagok
        setTimeout(() => {
            if (!nav.matches(':hover')) {
                nav.classList.remove('show-nav');
            }
        }, 300);
    });
</script>
</body>
</html>