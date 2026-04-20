<?php 
require_once 'session_check.php'; 

// Proses tambah kamar
if(isset($_POST['tambah_kamar'])) {
    $nama_kamar = mysqli_real_escape_string($conn, $_POST['nama_kamar']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $new_gambar = time() . '_' . $gambar;
    $upload_path = "../assets/img/" . $new_gambar;
    
    if(move_uploaded_file($tmp_name, $upload_path)) {
        $query = "INSERT INTO rooms (nama_kamar, tipe, harga, status, deskripsi, gambar) 
                  VALUES ('$nama_kamar', '$tipe', '$harga', '$status', '$deskripsi', '$new_gambar')";
        if(mysqli_query($conn, $query)) {
            echo "<script>alert('Kamar berhasil ditambahkan!'); window.location='rooms.php';</script>";
        } else {
            $error = "Gagal menambahkan kamar!";
        }
    } else {
        $error = "Gagal upload gambar!";
    }
}

// Proses edit kamar
if(isset($_POST['edit_kamar'])) {
    $id = $_POST['id'];
    $nama_kamar = mysqli_real_escape_string($conn, $_POST['nama_kamar']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    if(!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $new_gambar = time() . '_' . $gambar;
        $upload_path = "../assets/img/" . $new_gambar;
        move_uploaded_file($tmp_name, $upload_path);
        $query = "UPDATE rooms SET nama_kamar='$nama_kamar', tipe='$tipe', harga='$harga', status='$status', deskripsi='$deskripsi', gambar='$new_gambar' WHERE id='$id'";
    } else {
        $query = "UPDATE rooms SET nama_kamar='$nama_kamar', tipe='$tipe', harga='$harga', status='$status', deskripsi='$deskripsi' WHERE id='$id'";
    }
    
    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Kamar berhasil diupdate!'); window.location='rooms.php';</script>";
    } else {
        $error = "Gagal mengupdate kamar!";
    }
}

// Proses hapus kamar
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM rooms WHERE id='$id'";
    if(mysqli_query($conn, $query)) {
        echo "<script>alert('Kamar berhasil dihapus!'); window.location='rooms.php';</script>";
    }
}

// Hitung statistik
$total_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms"))['total'];
$available_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms WHERE status = 'tersedia'"))['total'];
$booked_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms WHERE status = 'dipesan'"))['total'];
$occupied_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms WHERE status = 'terisi'"))['total'];

// Data untuk chart
$standard_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms WHERE tipe = 'Standard'"))['total'];
$deluxe_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms WHERE tipe = 'Deluxe'"))['total'];
$suite_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms WHERE tipe = 'Suite'"))['total'];
$presidential_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms WHERE tipe = 'Presidential'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kamar - UNIB HOTEL</title>
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
        
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 10px 15px;
            transition: 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-gold);
            box-shadow: 0 0 0 3px rgba(198, 164, 63, 0.1);
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
                    <a href="rooms.php" class="nav-link active">
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
                    Manajemen Kamar
                </h1>
                <p class="text-muted">Kelola semua data kamar hotel Anda</p>
            </div>

            <!-- Stats Cards -->
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
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="stat-number"><?= $booked_rooms ?></div>
                        <div class="stat-label">Kamar Dipesan</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="stat-number"><?= $occupied_rooms ?></div>
                        <div class="stat-label">Kamar Terisi</div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row g-4 mb-4">
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="chart-container">
                        <canvas id="roomStatusChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="chart-container">
                        <canvas id="roomTypeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Rooms Table -->
            <div class="section-header" data-aos="fade-up">
                <h2><i class="bi bi-grid-3x3-gap-fill me-2" style="color: var(--primary-gold);"></i> Daftar Kamar</h2>
                <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#tambahModal">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Kamar
                </button>
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
                            $query = mysqli_query($conn, "SELECT * FROM rooms ORDER BY id DESC");
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
                                $status_class = '';
                                if($row['status'] == 'tersedia') {
                                    $status_class = 'badge-tersedia';
                                } elseif($row['status'] == 'dipesan') {
                                    $status_class = 'badge-dipesan';
                                } else {
                                    $status_class = 'badge-terisi';
                                }
                            ?>
                            <tr>
                                <td data-label="No"><?= $no++; ?></td>
                                <td data-label="Foto">
                                    <img src="../assets/img/<?= $row['gambar']; ?>" class="room-image" alt="<?= $row['nama_kamar']; ?>">
                                </td>
                                <td data-label="Nama Kamar" class="fw-bold"><?= htmlspecialchars($row['nama_kamar']); ?></td>
                                <td data-label="Tipe">
                                    <span class="badge bg-info bg-opacity-10 text-dark px-3 py-2">
                                        <?= htmlspecialchars($row['tipe']); ?>
                                    </span>
                                </td>
                                <td data-label="Harga" class="text-gold fw-bold">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td data-label="Status">
                                    <span class="badge-status <?= $status_class; ?>">
                                        <?= ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td data-label="Aksi" class="text-center">
                                    <button class="btn btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-action" onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Modal Edit -->
                            <div class="modal fade modal-custom" id="editModal<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Kamar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Kamar</label>
                                                    <input type="text" name="nama_kamar" class="form-control" value="<?= $row['nama_kamar']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tipe Kamar</label>
                                                    <select name="tipe" class="form-select" required>
                                                        <option value="Standard" <?= $row['tipe'] == 'Standard' ? 'selected' : ''; ?>>Standard</option>
                                                        <option value="Deluxe" <?= $row['tipe'] == 'Deluxe' ? 'selected' : ''; ?>>Deluxe</option>
                                                        <option value="Suite" <?= $row['tipe'] == 'Suite' ? 'selected' : ''; ?>>Suite</option>
                                                        <option value="Presidential" <?= $row['tipe'] == 'Presidential' ? 'selected' : ''; ?>>Presidential</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Harga per Malam</label>
                                                    <input type="number" name="harga" class="form-control" value="<?= $row['harga']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="tersedia" <?= $row['status'] == 'tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                                                        <option value="dipesan" <?= $row['status'] == 'dipesan' ? 'selected' : ''; ?>>Dipesan</option>
                                                        <option value="terisi" <?= $row['status'] == 'terisi' ? 'selected' : ''; ?>>Terisi</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea name="deskripsi" class="form-control" rows="3"><?= $row['deskripsi']; ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Gambar (Kosongkan jika tidak ingin mengubah)</label>
                                                    <input type="file" name="gambar" class="form-control" accept="image/*">
                                                    <small class="text-muted">Gambar saat ini: <?= $row['gambar']; ?></small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="edit_kamar" class="btn btn-primary-custom">Simpan Perubahan</button>
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

<!-- Modal Tambah Kamar -->
<div class="modal fade modal-custom" id="tambahModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Kamar Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kamar</label>
                        <input type="text" name="nama_kamar" class="form-control" placeholder="Contoh: Room 101" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Kamar</label>
                        <select name="tipe" class="form-select" required>
                            <option value="Standard">Standard</option>
                            <option value="Deluxe">Deluxe</option>
                            <option value="Suite">Suite</option>
                            <option value="Presidential">Presidential</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga per Malam</label>
                        <input type="number" name="harga" class="form-control" placeholder="Contoh: 500000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="dipesan">Dipesan</option>
                            <option value="terisi">Terisi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Fasilitas kamar, view, dll..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Kamar</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_kamar" class="btn btn-primary-custom">Tambah Kamar</button>
                </div>
            </form>
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
    
    // Room Status Chart (Doughnut)
    const roomCtx = document.getElementById('roomStatusChart').getContext('2d');
    new Chart(roomCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tersedia', 'Dipesan', 'Terisi'],
            datasets: [{
                data: [<?= $available_rooms ?>, <?= $booked_rooms ?>, <?= $occupied_rooms ?>],
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
                    text: 'Status Kamar',
                    font: { size: 14, weight: 'bold' }
                }
            }
        }
    });
    
    // Room Type Chart (Bar)
    const typeCtx = document.getElementById('roomTypeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'bar',
        data: {
            labels: ['Standard', 'Deluxe', 'Suite', 'Presidential'],
            datasets: [{
                label: 'Jumlah Kamar',
                data: [<?= $standard_count ?>, <?= $deluxe_count ?>, <?= $suite_count ?>, <?= $presidential_count ?>],
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
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Distribusi Tipe Kamar',
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
</script>
</body>
</html>