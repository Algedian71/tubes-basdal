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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Reservasi - Luxury Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)), 
                        url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2070&auto=format&fit=crop');
            background-size: cover; background-attachment: fixed; color: white; font-family: 'Poppins', sans-serif;
        }
        .navbar { backdrop-filter: blur(15px); background: rgba(0,0,0,0.8) !important; border-bottom: 1px solid #c5a059; }
        .glass-card {
            background: rgba(0, 0, 0, 0.8) !important; 
            border: 1px solid rgba(197, 160, 89, 0.3);
            border-radius: 15px; padding: 25px;
        }
        .table { color: white !important; margin-bottom: 0; vertical-align: middle; }
        .table tbody td {
            color: #ffffff !important; 
            background: transparent !important;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 20px 10px;
        }
        .nama-kamar { color: #ffffff !important; font-weight: 700; font-size: 1.1rem; }
        .text-gold { color: #c5a059 !important; }
        .status-badge { padding: 6px 12px; border-radius: 5px; font-weight: 600; font-size: 0.75rem; }
        .bg-verifikasi { background: #17a2b8; color: white; }
        .bg-success-custom { background: #28a745; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">UNIB HOTEL</a>
        <div class="navbar-nav ms-auto align-items-center">
            <a class="nav-link" href="../index.php">Home</a>
            <a class="nav-link" href="index.php">Cari Kamar</a>
            <a class="nav-link active fw-bold" href="riwayat.php">Riwayat Booking</a>
            <a href="../auth/logout.php" class="btn btn-outline-light btn-sm ms-lg-3">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h1 style="font-family: 'Playfair Display', serif; color: #c5a059;" class="mb-4">Riwayat Reservasi</h1>
    
    <div class="glass-card shadow-lg">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr class="text-gold">
                        <th>DETAIL KAMAR</th>
                        <th>JADWAL MENGINAP</th>
                        <th>TOTAL BIAYA</th>
                        <th>STATUS</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Pastikan query mengambil kolom yang benar dari tabel bookings
                    $query = mysqli_query($conn, "SELECT b.*, r.nama_kamar 
                                                 FROM bookings b 
                                                 JOIN rooms r ON b.room_id = r.id 
                                                 WHERE b.user_id = '$user_id' 
                                                 ORDER BY b.id DESC");
                    
                    while($row = mysqli_fetch_assoc($query)): 
                    ?>
                    <tr>
                        <td>
                            <div class="nama-kamar"><?= $row['nama_kamar']; ?></div>
                            <small style="color: rgba(255,255,255,0.5);">#BKG-<?= $row['id']; ?></small>
                        </td>
                        <td>
    <div class="small mb-1">
        <i class="bi bi-calendar-check text-info me-2"></i>
        <?php 
            // Kita cek semua kemungkinan nama kolom yang mungkin kamu buat di database
            if (isset($row['check_in'])) { echo $row['check_in']; }
            elseif (isset($row['tgl_checkin'])) { echo $row['tgl_checkin']; }
            elseif (isset($row['tanggal_checkin'])) { echo $row['tanggal_checkin']; }
            else { echo "Data Kosong"; }
        ?>
    </div>
    <div class="small">
        <i class="bi bi-calendar-x text-danger me-2"></i>
        <?php 
            if (isset($row['check_out'])) { echo $row['check_out']; }
            elseif (isset($row['tgl_checkout'])) { echo $row['tgl_checkout']; }
            elseif (isset($row['tanggal_checkout'])) { echo $row['tanggal_checkout']; }
            else { echo "Data Kosong"; }
        ?>
    </div>
</td>
                        <td class="text-gold fw-bold">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                        <td>
                            <?php 
                            if($row['status'] == 'pending') echo '<span class="status-badge bg-warning text-dark">Menunggu Bayar</span>';
                            elseif($row['status'] == 'menunggu verifikasi') echo '<span class="status-badge bg-verifikasi">Sedang Diverifikasi</span>';
                            elseif($row['status'] == 'success') echo '<span class="status-badge bg-success-custom">Terkonfirmasi</span>';
                            else echo '<span class="status-badge bg-secondary">Batal</span>';
                            ?>
                        </td>
                        <td class="text-center">
                            <?php if($row['status'] == 'success'): ?>
                                <a href="invoice.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning fw-bold px-3 shadow-sm text-dark">Invoice</a>
                            <?php else: ?>
                                <span style="color: rgba(255,255,255,0.4); font-size: 0.8rem;">Diproses</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>