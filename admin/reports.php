<?php 
require_once 'session_check.php'; 

// Filter periode
$filter = $_GET['filter'] ?? 'semua';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Set tanggal berdasarkan filter
switch($filter) {
    case 'hari_ini':
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        break;
    case 'minggu_ini':
        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d');
        break;
    case 'bulan_ini':
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');
        break;
    case 'bulan_lalu':
        $start_date = date('Y-m-01', strtotime('first day of previous month'));
        $end_date = date('Y-m-t', strtotime('last day of previous month'));
        break;
    case 'tahun_ini':
        $start_date = date('Y-01-01');
        $end_date = date('Y-m-d');
        break;
    case 'semua':
    default:
        // Ambil data dari semua booking
        $start_date = '1970-01-01';
        $end_date = date('Y-m-d');
        break;
}

// Pastikan tanggal valid
if (empty($start_date) || empty($end_date)) {
    $start_date = '1970-01-01';
    $end_date = date('Y-m-d');
}

// Query data laporan
// Total pendapatan periode (dari booking yang success)
$query_revenue = "SELECT SUM(total_harga) as total FROM bookings 
                  WHERE status = 'success'";
if ($filter != 'semua') {
    $query_revenue .= " AND tanggal_checkin BETWEEN '$start_date' AND '$end_date'";
}
$revenue = mysqli_fetch_assoc(mysqli_query($conn, $query_revenue))['total'] ?? 0;

// Total booking periode (semua status)
$query_booking = "SELECT COUNT(*) as total FROM bookings";
if ($filter != 'semua') {
    $query_booking .= " WHERE tanggal_checkin BETWEEN '$start_date' AND '$end_date'";
}
$total_booking = mysqli_fetch_assoc(mysqli_query($conn, $query_booking))['total'] ?? 0;

// Total customer
$query_customer = "SELECT COUNT(*) as total FROM users WHERE role = 'customer'";
$total_customer = mysqli_fetch_assoc(mysqli_query($conn, $query_customer))['total'] ?? 0;

// Booking success
$query_success = "SELECT COUNT(*) as total FROM bookings WHERE status = 'success'";
if ($filter != 'semua') {
    $query_success .= " AND tanggal_checkin BETWEEN '$start_date' AND '$end_date'";
}
$total_success = mysqli_fetch_assoc(mysqli_query($conn, $query_success))['total'] ?? 0;

// Rata-rata nilai booking
$query_avg = "SELECT AVG(total_harga) as avg FROM bookings WHERE status = 'success'";
if ($filter != 'semua') {
    $query_avg .= " AND tanggal_checkin BETWEEN '$start_date' AND '$end_date'";
}
$avg_booking = mysqli_fetch_assoc(mysqli_query($conn, $query_avg))['avg'] ?? 0;

// Data untuk chart pendapatan per bulan (untuk semua data)
$chart_query = "SELECT 
                    MONTH(tanggal_checkin) as bulan,
                    YEAR(tanggal_checkin) as tahun,
                    SUM(total_harga) as total,
                    COUNT(*) as jumlah
                FROM bookings 
                WHERE status = 'success'
                GROUP BY YEAR(tanggal_checkin), MONTH(tanggal_checkin)
                ORDER BY tahun DESC, bulan DESC
                LIMIT 12";
$chart_labels = [];
$chart_data = [];
$result = mysqli_query($conn, $chart_query);
while($row = mysqli_fetch_assoc($result)) {
    $chart_labels[] = getNamaBulan($row['bulan']) . ' ' . $row['tahun'];
    $chart_data[] = $row['total'];
}
$chart_labels = array_reverse($chart_labels);
$chart_data = array_reverse($chart_data);

// Data untuk status booking
$status_query = "SELECT 
                    SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as success,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'menunggu verifikasi' THEN 1 ELSE 0 END) as verifikasi
                 FROM bookings";
$status_result = mysqli_query($conn, $status_query);
$status_data = mysqli_fetch_assoc($status_result);

// Data untuk tipe kamar terlaris
$top_rooms = mysqli_query($conn, "SELECT r.tipe, COUNT(b.id) as total_booking, SUM(b.total_harga) as total_revenue
                                  FROM bookings b
                                  JOIN rooms r ON b.room_id = r.id
                                  WHERE b.status = 'success'
                                  GROUP BY r.tipe
                                  ORDER BY total_booking DESC");

// Data booking terbaru (semua)
$recent_bookings = mysqli_query($conn, "SELECT b.*, u.name, r.nama_kamar 
                                        FROM bookings b
                                        JOIN users u ON b.user_id = u.id
                                        JOIN rooms r ON b.room_id = r.id
                                        ORDER BY b.id DESC
                                        LIMIT 10");

// Fungsi helper untuk nama bulan
function getNamaBulan($bulan) {
    $nama_bulan = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];
    return $nama_bulan[$bulan];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - UNIB HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-gold: #C6A43F;
            --primary-gold-dark: #A8882E;
            --dark-bg: #0A0A0A;
            --dark-gray: #1A1A1A;
            --sidebar-bg: #0f0f1a;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fb;
        }
        
        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #1a1a2e 100%);
            min-height: 100vh;
            color: white;
            position: sticky;
            top: 0;
        }
        
        .sidebar-brand {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(198, 164, 63, 0.3);
        }
        
        .sidebar-brand h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--primary-gold);
        }
        
        .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 12px;
            transition: 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            color: white;
        }
        
        .nav-link i {
            width: 25px;
            margin-right: 10px;
        }
        
        /* Main Content */
        .main-content {
            padding: 20px 30px;
        }
        
        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
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
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark-bg);
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.85rem;
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
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-gold-dark));
            border: none;
            padding: 8px 20px;
            border-radius: 12px;
            font-weight: 600;
            color: white;
        }
        
        /* Chart Container */
        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 350px;
            margin-bottom: 25px;
        }
        
        /* Table */
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
        }
        
        .modern-table tbody td {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .badge-status {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .badge-pending {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .badge-verifikasi {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                bottom: 0;
                width: 100%;
                z-index: 1000;
            }
            .main-content {
                margin-bottom: 80px;
            }
            .stat-number {
                font-size: 1.2rem;
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
                    <a href="customers.php" class="nav-link">
                        <i class="bi bi-people"></i> Data Customer
                    </a>
                </li>
                <li class="nav-item">
                    <a href="reports.php" class="nav-link active">
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
            <!-- Header -->
            <div class="mb-4">
                <h1 class="fw-bold" style="font-family: 'Playfair Display', serif;">Laporan Hotel</h1>
                <p class="text-muted">Analisis lengkap performa hotel Anda</p>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Filter Periode</label>
                        <select name="filter" class="form-select" onchange="this.form.submit()">
                            <option value="semua" <?= $filter == 'semua' ? 'selected' : '' ?>>Semua Data</option>
                            <option value="hari_ini" <?= $filter == 'hari_ini' ? 'selected' : '' ?>>Hari Ini</option>
                            <option value="minggu_ini" <?= $filter == 'minggu_ini' ? 'selected' : '' ?>>Minggu Ini</option>
                            <option value="bulan_ini" <?= $filter == 'bulan_ini' ? 'selected' : '' ?>>Bulan Ini</option>
                            <option value="tahun_ini" <?= $filter == 'tahun_ini' ? 'selected' : '' ?>>Tahun Ini</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="stat-number">Rp <?= number_format($revenue, 0, ',', '.') ?></div>
                        <div class="stat-label">Total Pendapatan</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-number"><?= $total_booking ?></div>
                        <div class="stat-label">Total Booking</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-number"><?= $total_success ?></div>
                        <div class="stat-label">Booking Selesai</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-number"><?= $total_customer ?></div>
                        <div class="stat-label">Total Customer</div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-8">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="chart-container">
                        <canvas id="topRoomsChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <canvas id="avgChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings Table -->
            <h3 class="mb-3"><i class="bi bi-clock-history me-2" style="color: var(--primary-gold);"></i> Booking Terbaru</h3>
            <div class="modern-table">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>ID Booking</th>
                                <th>Customer</th>
                                <th>Kamar</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($recent_bookings) == 0): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada data booking</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php while($booking = mysqli_fetch_assoc($recent_bookings)): ?>
                            <tr>
                                <td>#BKG-<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                <td><?= htmlspecialchars($booking['name']) ?></td>
                                <td><?= htmlspecialchars($booking['nama_kamar']) ?></td>
                                <td><?= date('d/m/Y', strtotime($booking['tanggal_checkin'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($booking['tanggal_checkout'])) ?></td>
                                <td class="fw-bold text-gold">Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                    if($booking['status'] == 'success') {
                                        echo '<span class="badge-status badge-success">Selesai</span>';
                                    } elseif($booking['status'] == 'pending') {
                                        echo '<span class="badge-status badge-pending">Pending</span>';
                                    } else {
                                        echo '<span class="badge-status badge-verifikasi">Verifikasi</span>';
                                    }
                                    ?>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_labels) ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?= json_encode($chart_data) ?>,
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
                    text: 'Grafik Pendapatan per Bulan',
                    font: { size: 14, weight: 'bold' }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
    
    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Pending', 'Verifikasi'],
            datasets: [{
                data: [<?= $status_data['success'] ?? 0 ?>, <?= $status_data['pending'] ?? 0 ?>, <?= $status_data['verifikasi'] ?? 0 ?>],
                backgroundColor: ['#28a745', '#ffc107', '#17a2b8'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Status Booking',
                    font: { size: 14, weight: 'bold' }
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Top Rooms Chart
    const topRoomsCtx = document.getElementById('topRoomsChart').getContext('2d');
    const roomLabels = [];
    const roomData = [];
    <?php 
    mysqli_data_seek($top_rooms, 0);
    while($room = mysqli_fetch_assoc($top_rooms)): 
    ?>
    roomLabels.push('<?= addslashes($room['tipe']) ?>');
    roomData.push(<?= $room['total_booking'] ?>);
    <?php endwhile; ?>
    
    new Chart(topRoomsCtx, {
        type: 'bar',
        data: {
            labels: roomLabels,
            datasets: [{
                label: 'Jumlah Booking',
                data: roomData,
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
                    text: 'Tipe Kamar Terlaris',
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
    
    // Average Chart
    const avgCtx = document.getElementById('avgChart').getContext('2d');
    new Chart(avgCtx, {
        type: 'bar',
        data: {
            labels: ['Rata-rata'],
            datasets: [{
                label: 'Rata-rata Nilai Booking (Rp)',
                data: [<?= $avg_booking ?>],
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
                    text: 'Rata-rata Nilai Booking',
                    font: { size: 14, weight: 'bold' }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
</body>
</html>