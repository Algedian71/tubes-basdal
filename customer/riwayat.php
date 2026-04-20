<?php 
require_once '../config/db.php'; 

// Proteksi Login
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking - UNIB HOTEL</title>
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
            background: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070&auto=format&fit=crop');
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
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-gold-dark);
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
        
        /* Page Header */
        .page-header {
            padding: 60px 0 30px;
            text-align: center;
        }
        
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff, var(--primary-gold));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 15px;
        }
        
        .gold-line {
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-gold), var(--primary-gold-dark));
            margin: 20px auto;
        }
        
        /* Stats Cards */
        .stats-mini {
            display: inline-flex;
            gap: 30px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            border-radius: 50px;
            margin-top: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .stat-mini-item {
            text-align: center;
        }
        
        .stat-mini-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-gold);
        }
        
        .stat-mini-label {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.6);
        }
        
        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 30px;
            transition: all 0.4s ease;
        }
        
        .glass-card:hover {
            border-color: var(--primary-gold);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        /* Modern Table */
        .modern-table {
            width: 100%;
            color: white;
        }
        
        .modern-table thead th {
            background: rgba(198, 164, 63, 0.15);
            padding: 15px 20px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--primary-gold);
            border-bottom: 2px solid rgba(198, 164, 63, 0.3);
        }
        
        .modern-table tbody td {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }
        
        .room-name {
            font-weight: 700;
            font-size: 1rem;
            color: white;
            margin-bottom: 5px;
        }
        
        .booking-id {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
            font-family: monospace;
        }
        
        .date-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .date-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
        }
        
        .date-item i {
            width: 20px;
            font-size: 0.9rem;
        }
        
        .price-total {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--primary-gold);
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        
        .status-verifikasi {
            background: rgba(23, 162, 184, 0.2);
            color: #17a2b8;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }
        
        .status-success {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .status-cancel {
            background: rgba(108, 117, 125, 0.2);
            color: #adb5bd;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }
        
        /* Button */
        .btn-invoice {
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--dark-bg);
            transition: 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-invoice:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(198, 164, 63, 0.3);
            color: var(--dark-bg);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: rgba(255,255,255,0.2);
            margin-bottom: 20px;
        }
        
        .empty-state h4 {
            font-family: 'Playfair Display', serif;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: rgba(255,255,255,0.5);
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
        
        /* Fungsi untuk menghitung jumlah malam */
        .total-malam {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.6);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .modern-table thead {
                display: none;
            }
            
            .modern-table tbody tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 15px;
                padding: 15px;
            }
            
            .modern-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
                border: none;
            }
            
            .modern-table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--primary-gold);
                margin-right: 15px;
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
            <a class="nav-link" href="index.php">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
            <a class="nav-link active" href="riwayat.php">
                <i class="bi bi-clock-history me-1"></i> Riwayat
            </a>
            <a class="nav-link" href="../about.php">
                <i class="bi bi-info-circle me-1"></i> Tentang Kami
            </a>
        </div>
    </div>
</nav>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <h1 class="page-title">Riwayat Booking</h1>
            <div class="gold-line"></div>
            <p class="text-white-50">Lihat semua pemesanan kamar Anda</p>
            
            <?php 
            // Hitung statistik
            $total_booking = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE user_id = '$user_id'"))['total'];
            $total_spent = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) as total FROM bookings WHERE user_id = '$user_id' AND status = 'success'"))['total'];
            $pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE user_id = '$user_id' AND status = 'pending'"))['total'];
            ?>
            
            <div class="stats-mini">
                <div class="stat-mini-item">
                    <div class="stat-mini-number"><?= $total_booking ?></div>
                    <div class="stat-mini-label">Total Booking</div>
                </div>
                <div class="stat-mini-item">
                    <div class="stat-mini-number"><?= $pending_count ?></div>
                    <div class="stat-mini-label">Menunggu</div>
                </div>
                <div class="stat-mini-item">
                    <div class="stat-mini-number">Rp <?= $total_spent ? number_format($total_spent, 0, ',', '.') : '0' ?></div>
                    <div class="stat-mini-label">Total Belanja</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Riwayat Content -->
<div class="container py-4">
    <div class="glass-card" data-aos="fade-up" data-aos-duration="1000">
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Detail Kamar</th>
                        <th>Jadwal Menginap</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Query dengan kolom yang sesuai database: tanggal_checkin, tanggal_checkout
                    $query = mysqli_query($conn, "SELECT b.*, r.nama_kamar, r.tipe 
                                                 FROM bookings b 
                                                 JOIN rooms r ON b.room_id = r.id 
                                                 WHERE b.user_id = '$user_id' 
                                                 ORDER BY b.id DESC");
                    
                    if(mysqli_num_rows($query) == 0):
                    ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-calendar-x"></i>
                                    <h4>Belum Ada Booking</h4>
                                    <p>Anda belum melakukan pemesanan kamar. Yuk, booking sekarang!</p>
                                    <a href="index.php" class="btn-invoice mt-3">
                                        <i class="bi bi-search"></i> Cari Kamar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php 
                    endif;
                    
                    while($row = mysqli_fetch_assoc($query)): 
                        // Menggunakan kolom yang sesuai dengan database: tanggal_checkin dan tanggal_checkout
                        $check_in = $row['tanggal_checkin'];
                        $check_out = $row['tanggal_checkout'];
                        
                        // Hitung jumlah malam
                        $datetime1 = new DateTime($check_in);
                        $datetime2 = new DateTime($check_out);
                        $interval = $datetime1->diff($datetime2);
                        $jumlah_malam = $interval->days;
                        
                        // Format tanggal
                        $check_in_formatted = date('d/m/Y', strtotime($check_in));
                        $check_out_formatted = date('d/m/Y', strtotime($check_out));
                    ?>
                    <tr>
                        <td data-label="Detail Kamar">
                            <div class="room-name"><?= htmlspecialchars($row['nama_kamar']); ?></div>
                            <div class="booking-id">#BKG-<?= str_pad($row['id'], 6, '0', STR_PAD_LEFT); ?></div>
                            <small class="text-gold"><?= htmlspecialchars($row['tipe']); ?></small>
                        </td>
                        <td data-label="Jadwal Menginap">
                            <div class="date-info">
                                <div class="date-item">
                                    <i class="bi bi-calendar-check text-gold"></i>
                                    <span>Check-in: <?= $check_in_formatted; ?></span>
                                </div>
                                <div class="date-item">
                                    <i class="bi bi-calendar-x text-gold"></i>
                                    <span>Check-out: <?= $check_out_formatted; ?></span>
                                </div>
                                <div class="date-item">
                                    <i class="bi bi-clock text-gold"></i>
                                    <span><?= $jumlah_malam; ?> malam</span>
                                </div>
                            </div>
                        </td>
                        <td data-label="Total Biaya">
                            <div class="price-total">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></div>
                        </td>
                        <td data-label="Status">
                            <?php 
                            $status_class = '';
                            $status_text = '';
                            $status_icon = '';
                            
                            if($row['status'] == 'pending') {
                                $status_class = 'status-pending';
                                $status_text = 'Menunggu Pembayaran';
                                $status_icon = 'bi-hourglass-split';
                            } elseif($row['status'] == 'menunggu verifikasi') {
                                $status_class = 'status-verifikasi';
                                $status_text = 'Sedang Diverifikasi';
                                $status_icon = 'bi-arrow-repeat';
                            } elseif($row['status'] == 'success') {
                                $status_class = 'status-success';
                                $status_text = 'Terkonfirmasi';
                                $status_icon = 'bi-check-circle';
                            } else {
                                $status_class = 'status-cancel';
                                $status_text = 'Dibatalkan';
                                $status_icon = 'bi-x-circle';
                            }
                            ?>
                            <span class="status-badge <?= $status_class; ?>">
                                <i class="bi <?= $status_icon; ?>"></i>
                                <?= $status_text; ?>
                            </span>
                        </td>
                        <td data-label="Aksi" class="text-center">
                            <?php if($row['status'] == 'success'): ?>
                                <a href="invoice.php?id=<?= $row['id']; ?>" class="btn-invoice">
                                    <i class="bi bi-receipt"></i> Invoice
                                </a>
                            <?php elseif($row['status'] == 'pending'): ?>
                                <a href="payment.php?id=<?= $row['id']; ?>" class="btn-invoice">
                                    <i class="bi bi-credit-card"></i> Bayar
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
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