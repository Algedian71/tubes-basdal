<?php require_once 'session_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { min-height: 100vh; background: #1a237e; color: white; }
        .nav-link { color: rgba(255,255,255,0.8); }
        .nav-link.active { color: white; background: rgba(255,255,255,0.1); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3 d-none d-md-block">
            <h4 class="text-center fw-bold mb-4">ADMIN PANEL</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="index.php" class="nav-link"><i class="bi bi-door-open me-2"></i> Kamar</a></li>
                <li class="nav-item mb-2"><a href="bookings.php" class="nav-link active"><i class="bi bi-calendar-check me-2"></i> Bookings</a></li>
                <li class="nav-item mt-4"><a href="../auth/logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-left me-2"></i> Logout</a></li>
            </ul>
        </div>

        <div class="col-md-10 p-4">
            <h2 class="fw-bold mb-4">Daftar Booking Masuk</h2>

            <div class="card shadow-sm border-0 p-3">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Customer</th>
                            <th>Kamar</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query sakti untuk menggabungkan 3 tabel (JOIN)
                        $sql = "SELECT bookings.*, users.name, rooms.nama_kamar 
                                FROM bookings 
                                JOIN users ON bookings.user_id = users.id 
                                JOIN rooms ON bookings.room_id = rooms.id 
                                ORDER BY bookings.id DESC";
                        $query = mysqli_query($conn, $sql);
                        
                        while($row = mysqli_fetch_assoc($query)):
                        ?>
                        <tr>
                            <td><?= $row['name']; ?></td>
                            <td><?= $row['nama_kamar']; ?></td>
                            <td><?= $row['tanggal_checkin']; ?></td>
                            <td><?= $row['tanggal_checkout']; ?></td>
                            <td class="fw-bold">Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                            <td>
    <?php if($row['status'] == 'menunggu verifikasi'): ?>
        <span class="badge bg-info text-dark">Menunggu Persetujuan</span>
    <?php elseif($row['status'] == 'success'): ?>
        <span class="badge bg-success">Terkonfirmasi</span>
    <?php else: ?>
        <span class="badge bg-secondary"><?= $row['status']; ?></span>
    <?php endif; ?>
</td>

<td>
    <?php if($row['status'] == 'menunggu verifikasi'): ?>
        <a href="booking_aksi.php?id=<?= $row['id']; ?>&action=konfirmasi" class="btn btn-sm btn-success fw-bold">
            <i class="bi bi-check-circle"></i> Setujui
        </a>
        <a href="booking_aksi.php?id=<?= $row['id']; ?>&action=tolak" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pesanan ini?')">
            Tolak
        </a>
    <?php elseif($row['status'] == 'success'): ?>
        <span class="text-success fw-bold"><i class="bi bi-patch-check-fill"></i> Selesai</span>
    <?php else: ?>
        <span class="text-muted">-</span>
    <?php endif; ?>
</td>
                            <td>
                                <?php if($row['status'] == 'pending'): ?>
                                    <a href="booking_aksi.php?id=<?= $row['id']; ?>&action=konfirmasi" class="btn btn-sm btn-success">Setujui</a>
                                    <a href="booking_aksi.php?id=<?= $row['id']; ?>&action=tolak" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak pesanan ini?')">Tolak</a>
                                <?php else: ?>
                                    <span class="text-success"><i class="bi bi-patch-check-fill"></i> Selesai</span>
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

</body>
</html>