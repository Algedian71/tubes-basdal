<?php 
require_once 'session_check.php'; 

// ============================================
// PROSES KONFIRMASI BOOKING (tanpa file terpisah)
// ============================================
if(isset($_GET['konfirmasi'])) {
    $id = (int)$_GET['konfirmasi'];
    
    // Ambil room_id dari booking
    $get_booking = mysqli_query($conn, "SELECT room_id FROM bookings WHERE id = '$id'");
    if(mysqli_num_rows($get_booking) > 0) {
        $booking = mysqli_fetch_assoc($get_booking);
        $room_id = $booking['room_id'];
        
        // Update status booking menjadi success
        mysqli_query($conn, "UPDATE bookings SET status = 'success' WHERE id = '$id'");
        
        // Update status kamar menjadi terisi
        mysqli_query($conn, "UPDATE rooms SET status = 'terisi' WHERE id = '$room_id'");
        
        echo "<script>
            alert('✅ Booking berhasil dikonfirmasi!\\nStatus kamar telah diupdate menjadi TERISI.');
            window.location='bookings.php';
        </script>";
    } else {
        echo "<script>
            alert('❌ Booking tidak ditemukan!');
            window.location='bookings.php';
        </script>";
    }
    exit;
}

// ============================================
// PROSES TOLAK BOOKING (tanpa file terpisah)
// ============================================
if(isset($_GET['tolak'])) {
    $id = (int)$_GET['tolak'];
    
    // Ambil room_id dari booking
    $get_booking = mysqli_query($conn, "SELECT room_id FROM bookings WHERE id = '$id'");
    if(mysqli_num_rows($get_booking) > 0) {
        $booking = mysqli_fetch_assoc($get_booking);
        $room_id = $booking['room_id'];
        
        // Update status kamar kembali ke tersedia
        mysqli_query($conn, "UPDATE rooms SET status = 'tersedia' WHERE id = '$room_id'");
        
        // Hapus booking
        mysqli_query($conn, "DELETE FROM bookings WHERE id = '$id'");
        
        echo "<script>
            alert('✅ Booking berhasil dibatalkan!\\nStatus kamar telah dikembalikan ke TERSEedia.');
            window.location='bookings.php';
        </script>";
    } else {
        echo "<script>
            alert('❌ Booking tidak ditemukan!');
            window.location='bookings.php';
        </script>";
    }
    exit;
}

// ============================================
// HITUNG STATISTIK
// ============================================
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings"))['total'];
$pending_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status = 'pending'"))['total'];
$verifikasi_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status = 'menunggu verifikasi'"))['total'];
$success_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status = 'success'"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) as total FROM bookings WHERE status = 'success'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Booking - UNIB HOTEL</title>
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
            --sidebar-bg: #0f0f1a;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fb;
            overflow-x: hidden;
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
        
        /* Sidebar Modern */
        .sidebar {
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #1a1a2e 100%);
            min-height: 100vh;
            color: white;
            transition: all 0.3s ease;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .sidebar-brand {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(198, 164, 63, 0.3);
            margin-bottom: 20px;
        }
        
        .sidebar-brand h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--primary-gold);
            margin: 0;
        }
        
        .sidebar-brand p {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.5);
            margin: 0;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(198, 164, 63, 0.1);
            color: var(--primary-gold);
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            color: white;
            box-shadow: 0 5px 15px rgba(198, 164, 63, 0.3);
        }
        
        .nav-link i {
            width: 25px;
            margin-right: 10px;
        }
        
        /* Main Content */
        .main-content {
            padding: 20px 30px;
            min-height: 100vh;
        }
        
        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(198, 164, 63, 0.1);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .stat-icon i {
            font-size: 1.5rem;
            color: var(--primary-gold);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark-bg);
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        /* Section Header */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .section-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-bg);
            margin: 0;
        }
        
        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .filter-btn {
            background: transparent;
            border: 2px solid #e0e0e0;
            padding: 8px 20px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: var(--primary-gold);
            border-color: var(--primary-gold);
            color: white;
        }
        
        /* Table Modern */
        .modern-table {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .modern-table thead th {
            background: linear-gradient(135deg, var(--dark-bg), var(--dark-gray));
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
        }
        
        .modern-table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .modern-table tbody tr:hover {
            background: rgba(198, 164, 63, 0.03);
        }
        
        /* Status Badges */
        .badge-status {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .badge-pending {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .badge-verifikasi {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }
        
        .badge-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .badge-cancel {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        /* Buttons */
        .btn-approve {
            background: rgba(40, 167, 69, 0.1);
            border: none;
            padding: 5px 15px;
            border-radius: 10px;
            color: #28a745;
            font-weight: 600;
            transition: 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-approve:hover {
            background: #28a745;
            color: white;
        }
        
        .btn-reject {
            background: rgba(220, 53, 69, 0.1);
            border: none;
            padding: 5px 15px;
            border-radius: 10px;
            color: #dc3545;
            font-weight: 600;
            transition: 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-reject:hover {
            background: #dc3545;
            color: white;
        }
        
        .customer-info .name {
            font-weight: 700;
            margin-bottom: 3px;
        }
        
        .customer-info .email {
            font-size: 0.7rem;
            color: #999;
        }
        
        .room-info .room-name {
            font-weight: 700;
            margin-bottom: 3px;
        }
        
        .room-info .room-type {
            font-size: 0.7rem;
            color: var(--primary-gold);
        }
        
        .price-total {
            font-weight: 700;
            color: var(--primary-gold);
            font-size: 1rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                position: fixed;
                bottom: 0;
                width: 100%;
                z-index: 1000;
            }
            
            .sidebar-brand {
                display: none;
            }
            
            .nav-link {
                display: inline-block;
                padding: 10px;
            }
            
            .main-content {
                padding: 20px 15px;
                margin-bottom: 70px;
            }
            
            .modern-table thead {
                display: none;
            }
            
            .modern-table tbody tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid #f0f0f0;
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

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-0">
            <div class="sidebar-brand">
                <h4><i class="bi bi-building"></i> UNIB</h4>
                <p>Hotel Management System</p>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="rooms.php" class="nav-link">
                        <i class="bi bi-door-open"></i> Manajemen Kamar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="bookings.php" class="nav-link active">
                        <i class="bi bi-calendar-check"></i> Manajemen Booking
                    </a>
                </li>
                <li class="nav-item">
                    <a href="customers.php" class="nav-link">
                        <i class="bi bi-people"></i> Data Customer
                    </a>
                </li>
                <li class="nav-item">
                    <a href="reports.php" class="nav-link">
                        <i class="bi bi-graph-up"></i> Laporan
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="../auth/logout.php" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-left"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 main-content">
            <!-- Welcome Section -->
            <div class="mb-4" data-aos="fade-up">
                <h1 class="fw-bold" style="font-family: 'Playfair Display', serif;">
                    Manajemen Booking
                </h1>
                <p class="text-muted">Kelola dan pantau semua pemesanan kamar hotel</p>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-number"><?= $total_bookings ?></div>
                        <div class="stat-label">Total Booking</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="stat-number"><?= $pending_bookings + $verifikasi_bookings ?></div>
                        <div class="stat-label">Menunggu Proses</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="stat-number"><?= $success_bookings ?></div>
                        <div class="stat-label">Booking Selesai</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="stat-number">Rp <?= $total_revenue ? number_format($total_revenue, 0, ',', '.') : '0' ?></div>
                        <div class="stat-label">Total Pendapatan</div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section" data-aos="fade-up">
                <div class="d-flex flex-wrap gap-2">
                    <button class="filter-btn active" data-filter="all">Semua</button>
                    <button class="filter-btn" data-filter="pending">Menunggu Pembayaran</button>
                    <button class="filter-btn" data-filter="menunggu verifikasi">Menunggu Verifikasi</button>
                    <button class="filter-btn" data-filter="success">Terkonfirmasi</button>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="modern-table" data-aos="fade-up" data-aos-delay="200">
                <div class="table-responsive">
                    <table class="table mb-0" id="bookingsTable">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Kamar</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT bookings.*, users.name, users.email, rooms.nama_kamar, rooms.tipe 
                                    FROM bookings 
                                    JOIN users ON bookings.user_id = users.id 
                                    JOIN rooms ON bookings.room_id = rooms.id 
                                    ORDER BY bookings.id DESC";
                            $query = mysqli_query($conn, $sql);
                            
                            if(mysqli_num_rows($query) == 0):
                            ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada booking</p>
                                </td>
                            </tr>
                            <?php 
                            endif;
                            
                            while($row = mysqli_fetch_assoc($query)):
                                $status_class = '';
                                $status_text = '';
                                $status_icon = '';
                                
                                if($row['status'] == 'pending') {
                                    $status_class = 'badge-pending';
                                    $status_text = 'Menunggu Pembayaran';
                                    $status_icon = 'bi-hourglass-split';
                                } elseif($row['status'] == 'menunggu verifikasi') {
                                    $status_class = 'badge-verifikasi';
                                    $status_text = 'Menunggu Verifikasi';
                                    $status_icon = 'bi-arrow-repeat';
                                } elseif($row['status'] == 'success') {
                                    $status_class = 'badge-success';
                                    $status_text = 'Terkonfirmasi';
                                    $status_icon = 'bi-check-circle';
                                } else {
                                    $status_class = 'badge-cancel';
                                    $status_text = 'Dibatalkan';
                                    $status_icon = 'bi-x-circle';
                                }
                            ?>
                            <tr data-status="<?= $row['status']; ?>">
                                <td data-label="Customer">
                                    <div class="customer-info">
                                        <div class="name"><?= htmlspecialchars($row['name']); ?></div>
                                        <div class="email"><?= htmlspecialchars($row['email']); ?></div>
                                    </div>
                                </td>
                                <td data-label="Kamar">
                                    <div class="room-info">
                                        <div class="room-name"><?= htmlspecialchars($row['nama_kamar']); ?></div>
                                        <div class="room-type"><?= htmlspecialchars($row['tipe']); ?></div>
                                    </div>
                                </td>
                                <td data-label="Check-in">
                                    <i class="bi bi-calendar-check me-1 text-gold"></i>
                                    <?= date('d/m/Y', strtotime($row['tanggal_checkin'])); ?>
                                </td>
                                <td data-label="Check-out">
                                    <i class="bi bi-calendar-x me-1 text-gold"></i>
                                    <?= date('d/m/Y', strtotime($row['tanggal_checkout'])); ?>
                                </td>
                                <td data-label="Total Harga">
                                    <div class="price-total">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></div>
                                </td>
                                <td data-label="Status">
                                    <span class="badge-status <?= $status_class; ?>">
                                        <i class="bi <?= $status_icon; ?>"></i>
                                        <?= $status_text; ?>
                                    </span>
                                </td>
                                <td data-label="Aksi" class="text-center">
                                    <?php if($row['status'] == 'pending' || $row['status'] == 'menunggu verifikasi'): ?>
                                        <a href="?konfirmasi=<?= $row['id']; ?>" class="btn-approve" onclick="return confirm('Konfirmasi booking ini?\\nStatus kamar akan berubah menjadi TERISI.')">
                                            <i class="bi bi-check-circle"></i> Setujui
                                        </a>
                                        <a href="?tolak=<?= $row['id']; ?>" class="btn-reject" onclick="return confirm('Tolak booking ini?\\nBooking akan dihapus dan status kamar kembali TERSEedia.')">
                                            <i class="bi bi-x-circle"></i> Tolak
                                        </a>
                                    <?php elseif($row['status'] == 'success'): ?>
                                        <span class="text-success fw-bold">
                                            <i class="bi bi-patch-check-fill"></i> Selesai
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
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
        once: true,
        offset: 100
    });
    
    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const tableRows = document.querySelectorAll('#bookingsTable tbody tr');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            
            tableRows.forEach(row => {
                if (filterValue === 'all' || row.getAttribute('data-status') === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
</body>
</html>