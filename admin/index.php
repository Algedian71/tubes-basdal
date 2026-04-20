<?php 
require_once 'session_check.php'; 

// Hitung statistik untuk dashboard
$total_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms"))['total'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings"))['total'];
$pending_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status = 'pending'"))['total'];
$success_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status = 'success'"))['total'];
$total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'customer'"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) as total FROM bookings WHERE status = 'success'"))['total'];
$available_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms WHERE status = 'tersedia'"))['total'];
$booked_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms WHERE status = 'dipesan'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UNIB HOTEL</title>
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
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            border: none;
            padding: 10px 25px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(198, 164, 63, 0.3);
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
        
        .room-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
        }
        
        .badge-status {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .badge-tersedia {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .badge-dipesan {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .badge-terisi {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        
        .btn-action {
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            margin: 0 3px;
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
            
            .stat-number {
                font-size: 1.5rem;
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
                    <a href="index.php" class="nav-link active">
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
                    Selamat Datang, <?= $_SESSION['name']; ?>! 👋
                </h1>
                <p class="text-muted">Berikut adalah ringkasan kinerja hotel Anda hari ini.</p>
            </div>

            <!-- Stats Cards Row 1 -->
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-door-open"></i>
                        </div>
                        <div class="stat-number"><?= $total_rooms ?></div>
                        <div class="stat-label">Total Kamar</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-number"><?= $available_rooms ?></div>
                        <div class="stat-label">Kamar Tersedia</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-number"><?= $total_bookings ?></div>
                        <div class="stat-label">Total Booking</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-number"><?= $total_customers ?></div>
                        <div class="stat-label">Total Customer</div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards Row 2 -->
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="stat-number"><?= $pending_bookings ?></div>
                        <div class="stat-label">Pending Booking</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="stat-number"><?= $success_bookings ?></div>
                        <div class="stat-label">Booking Sukses</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="700">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="stat-number"><?= $booked_rooms ?></div>
                        <div class="stat-label">Kamar Dipesan</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="800">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="stat-number">Rp <?= $total_revenue ? number_format($total_revenue, 0, ',', '.') : '0' ?></div>
                        <div class="stat-label">Total Pendapatan</div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row g-4 mb-4">
                <div class="col-md-8" data-aos="fade-up" data-aos-delay="900">
                    <div class="chart-container">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="1000">
                    <div class="chart-container">
                        <canvas id="roomStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="modern-table" data-aos="fade-up" data-aos-delay="200">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama Kamar</th>
                                <th>Tipe</th>
                                <th>Harga/Malam</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = mysqli_query($conn, "SELECT * FROM rooms ORDER BY id DESC LIMIT 10");
                            $no = 1;
                            if(mysqli_num_rows($query) == 0):
                            ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada data kamar</p>
                                </td>
                            </tr>
                            <?php 
                            endif;
                            while($row = mysqli_fetch_assoc($query)): 
                                $status_class = $row['status'] == 'tersedia' ? 'badge-tersedia' : ($row['status'] == 'dipesan' ? 'badge-dipesan' : 'badge-terisi');
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <img src="../assets/img/<?= $row['gambar']; ?>" class="room-image" alt="<?= $row['nama_kamar']; ?>">
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($row['nama_kamar']); ?></td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-dark px-3 py-2">
                                        <?= htmlspecialchars($row['tipe']); ?>
                                    </span>
                                </td>
                                <td class="text-gold fw-bold">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge-status <?= $status_class; ?>">
                                        <?= ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="kamar_edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-action">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="kamar_hapus.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-action" onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
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
    
    // Booking Chart (Line/Bar)
    const bookingCtx = document.getElementById('bookingChart').getContext('2d');
    new Chart(bookingCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Jumlah Booking',
                data: [12, 19, 15, 17, 14, 20, 25, 22, 18, 23, 28, 30],
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
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Statistik Booking per Bulan'
                }
            }
        }
    });
    
    // Room Status Chart (Pie/Doughnut)
    const roomCtx = document.getElementById('roomStatusChart').getContext('2d');
    new Chart(roomCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tersedia', 'Dipesan', 'Terisi'],
            datasets: [{
                data: [<?= $available_rooms ?>, <?= $booked_rooms ?>, <?= $total_rooms - $available_rooms - $booked_rooms ?>],
                backgroundColor: ['#28a745', '#ffc107', '#6c757d'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Status Kamar'
                }
            }
        }
    });
</script>
</body>
</html>