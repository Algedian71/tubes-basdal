<?php 
require_once 'session_check.php'; 

// Proses hapus customer
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Cek apakah customer memiliki booking
    $cek_booking = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE user_id = '$id'");
    $booking_count = mysqli_fetch_assoc($cek_booking)['total'];
    
    if($booking_count > 0) {
        $error = "Customer tidak dapat dihapus karena masih memiliki $booking_count riwayat booking!";
    } else {
        $query = "DELETE FROM users WHERE id='$id' AND role='customer'";
        if(mysqli_query($conn, $query)) {
            echo "<script>alert('Customer berhasil dihapus!'); window.location='customers.php';</script>";
        } else {
            $error = "Gagal menghapus customer!";
        }
    }
}

// Proses reset password
if(isset($_POST['reset_password'])) {
    $id = $_POST['id'];
    $new_password = password_hash('customer123', PASSWORD_DEFAULT);
    $query = "UPDATE users SET password='$new_password' WHERE id='$id'";
    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Password berhasil direset menjadi: customer123'); window.location='customers.php';</script>";
    } else {
        $error = "Gagal mereset password!";
    }
}

// Hitung statistik
$total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'customer'"))['total'];
$active_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT user_id) as total FROM bookings WHERE status IN ('pending', 'menunggu verifikasi')"))['total'];
$total_spent = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) as total FROM bookings WHERE status = 'success'"))['total'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings"))['total'];

// Data untuk chart (top customer berdasarkan total booking)
$top_customers = mysqli_query($conn, "SELECT u.name, COUNT(b.id) as total_booking 
                                      FROM users u 
                                      LEFT JOIN bookings b ON u.id = b.user_id 
                                      WHERE u.role = 'customer' 
                                      GROUP BY u.id 
                                      ORDER BY total_booking DESC 
                                      LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Customer - UNIB HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
        
        /* Search Box */
        .search-box {
            background: white;
            border-radius: 50px;
            padding: 5px 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .search-box input {
            border: none;
            padding: 10px;
            width: 250px;
            outline: none;
        }
        
        .search-box button {
            background: none;
            border: none;
            color: var(--primary-gold);
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
        
        .customer-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            color: white;
        }
        
        .customer-name {
            font-weight: 700;
            margin-bottom: 3px;
        }
        
        .customer-email {
            font-size: 0.7rem;
            color: #999;
        }
        
        .badge-active {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .btn-action {
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            margin: 0 3px;
        }
        
        /* Modal Custom */
        .modal-custom .modal-content {
            border-radius: 20px;
            border: none;
        }
        
        .modal-custom .modal-header {
            background: linear-gradient(135deg, var(--dark-bg), var(--dark-gray));
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 20px 25px;
        }
        
        .modal-custom .modal-header .btn-close {
            background: white;
        }
        
        .modal-custom .modal-body {
            padding: 25px;
        }
        
        .booking-item {
            border-left: 3px solid var(--primary-gold);
            padding: 10px 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        /* Chart Container */
        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 300px;
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
                    <a href="bookings.php" class="nav-link">
                        <i class="bi bi-calendar-check"></i> Manajemen Booking
                    </a>
                </li>
                <li class="nav-item">
                    <a href="customers.php" class="nav-link active">
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
                    Data Customer
                </h1>
                <p class="text-muted">Kelola dan pantau semua data customer hotel</p>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-number"><?= $total_customers ?></div>
                        <div class="stat-label">Total Customer</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-number"><?= $active_bookings ?></div>
                        <div class="stat-label">Customer Aktif Booking</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="stat-number">Rp <?= number_format($total_spent, 0, ',', '.'); ?></div>
                        <div class="stat-label">Total Pendapatan</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="stat-number"><?= $total_bookings ?></div>
                        <div class="stat-label">Total Booking</div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row g-4 mb-4">
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="chart-container">
                        <canvas id="topCustomersChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="chart-container">
                        <canvas id="customerGrowthChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="section-header" data-aos="fade-up">
                <h2><i class="bi bi-grid-3x3-gap-fill me-2" style="color: var(--primary-gold);"></i> Daftar Customer</h2>
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari customer...">
                </div>
            </div>

            <div class="modern-table" data-aos="fade-up" data-aos-delay="200">
                <div class="table-responsive">
                    <table class="table mb-0" id="customersTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Total Booking</th>
                                <th>Total Belanja</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = mysqli_query($conn, "SELECT u.*, 
                                                          COUNT(b.id) as total_booking,
                                                          SUM(CASE WHEN b.status = 'success' THEN b.total_harga ELSE 0 END) as total_spent
                                                          FROM users u 
                                                          LEFT JOIN bookings b ON u.id = b.user_id 
                                                          WHERE u.role = 'customer' 
                                                          GROUP BY u.id 
                                                          ORDER BY u.id DESC");
                            $no = 1;
                            if(mysqli_num_rows($query) == 0):
                            ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada data customer</p>
                                </td>
                            </tr>
                            <?php 
                            endif;
                            while($row = mysqli_fetch_assoc($query)): 
                                $initial = strtoupper(substr($row['name'], 0, 1));
                            ?>
                            <tr>
                                <td data-label="No"><?= $no++; ?></td>
                                <td data-label="Customer">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="customer-avatar">
                                            <?= $initial; ?>
                                        </div>
                                        <div>
                                            <div class="customer-name"><?= htmlspecialchars($row['name']); ?></div>
                                            <div class="customer-email">Member sejak <?= date('d/m/Y', strtotime($row['created_at'] ?? 'now')); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Email"><?= htmlspecialchars($row['email']); ?></td>
                                <td data-label="Total Booking">
                                    <span class="fw-bold"><?= $row['total_booking']; ?></span> booking
                                </td>
                                <td data-label="Total Belanja" class="text-gold fw-bold">
                                    Rp <?= number_format($row['total_spent'], 0, ',', '.'); ?>
                                </td>
                                <td data-label="Status">
                                    <span class="badge-active">
                                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                    </span>
                                </td>
                                <td data-label="Aksi" class="text-center">
                                    <button class="btn btn-info btn-action" data-bs-toggle="modal" data-bs-target="#detailModal<?= $row['id']; ?>">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#resetModal<?= $row['id']; ?>">
                                        <i class="bi bi-key"></i>
                                    </button>
                                    <a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-action" onclick="return confirm('Yakin ingin menghapus customer ini? Semua data booking akan ikut terhapus!')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Modal Detail Customer -->
                            <div class="modal fade modal-custom" id="detailModal<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="bi bi-person-circle me-2"></i>Detail Customer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center gap-3 mb-3">
                                                        <div class="customer-avatar" style="width: 70px; height: 70px; font-size: 1.8rem;">
                                                            <?= $initial; ?>
                                                        </div>
                                                        <div>
                                                            <h4 class="mb-1"><?= htmlspecialchars($row['name']); ?></h4>
                                                            <p class="text-muted mb-0"><?= htmlspecialchars($row['email']); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="bg-light p-3 rounded-3">
                                                        <p class="mb-2"><strong>Total Booking:</strong> <?= $row['total_booking']; ?> kali</p>
                                                        <p class="mb-2"><strong>Total Belanja:</strong> Rp <?= number_format($row['total_spent'], 0, ',', '.'); ?></p>
                                                        <p class="mb-0"><strong>Member Sejak:</strong> <?= date('d F Y', strtotime($row['created_at'] ?? 'now')); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <h6 class="fw-bold mb-3">Riwayat Booking</h6>
                                            <?php
                                            $bookings = mysqli_query($conn, "SELECT b.*, r.nama_kamar 
                                                                            FROM bookings b 
                                                                            JOIN rooms r ON b.room_id = r.id 
                                                                            WHERE b.user_id = '{$row['id']}' 
                                                                            ORDER BY b.id DESC 
                                                                            LIMIT 5");
                                            if(mysqli_num_rows($bookings) > 0):
                                                while($booking = mysqli_fetch_assoc($bookings)):
                                            ?>
                                            <div class="booking-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?= htmlspecialchars($booking['nama_kamar']); ?></strong>
                                                        <br>
                                                        <small><?= date('d/m/Y', strtotime($booking['tanggal_checkin'])); ?> - <?= date('d/m/Y', strtotime($booking['tanggal_checkout'])); ?></small>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="fw-bold text-gold">Rp <?= number_format($booking['total_harga'], 0, ',', '.'); ?></span>
                                                        <br>
                                                        <small class="text-muted"><?= $booking['status']; ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php 
                                                endwhile;
                                            else:
                                                echo "<p class='text-muted text-center'>Belum ada riwayat booking</p>";
                                            endif;
                                            ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal Reset Password -->
                            <div class="modal fade modal-custom" id="resetModal<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="bi bi-key me-2"></i>Reset Password</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                <p>Reset password untuk customer <strong><?= htmlspecialchars($row['name']); ?></strong>?</p>
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                    Password akan direset menjadi: <strong>customer123</strong>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="reset_password" class="btn btn-primary-custom">Reset Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });
    
    // Top Customers Chart (Bar)
    const topCtx = document.getElementById('topCustomersChart').getContext('2d');
    new Chart(topCtx, {
        type: 'bar',
        data: {
            labels: [<?php 
                $labels = [];
                $data = [];
                mysqli_data_seek($top_customers, 0);
                while($row = mysqli_fetch_assoc($top_customers)) {
                    $labels[] = "'" . addslashes($row['name']) . "'";
                    $data[] = $row['total_booking'];
                }
                echo implode(',', $labels);
            ?>],
            datasets: [{
                label: 'Jumlah Booking',
                data: [<?= implode(',', $data) ?>],
                backgroundColor: 'rgba(198, 164, 63, 0.7)',
                borderColor: '#C6A43F',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Top 5 Customer Teraktif',
                    font: { size: 14, weight: 'bold' }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
    
    // Customer Growth Chart (Line)
    const growthCtx = document.getElementById('customerGrowthChart').getContext('2d');
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pertumbuhan Customer',
                data: [5, 8, 12, 15, 18, 22, 25, 28, 30, 33, 35, <?= $total_customers ?>],
                borderColor: '#C6A43F',
                backgroundColor: 'rgba(198, 164, 63, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Pertumbuhan Customer per Bulan',
                    font: { size: 14, weight: 'bold' }
                }
            }
        }
    });
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#customersTable tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if(text.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>