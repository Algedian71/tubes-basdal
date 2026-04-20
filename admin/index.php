<?php require_once 'session_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UNIB HOTEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { min-height: 100vh; background: #1a237e; color: white; }
        .nav-link { color: rgba(255,255,255,0.8); }
        .nav-link:hover, .nav-link.active { color: white; background: rgba(255,255,255,0.1); }
        .card { border: none; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-3 d-none d-md-block">
            <h4 class="text-center fw-bold mb-4">ADMIN PANEL</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="index.php" class="nav-link active"><i class="bi bi-door-open me-2"></i> Kamar</a></li>
                <li class="nav-item mb-2"><a href="bookings.php" class="nav-link"><i class="bi bi-calendar-check me-2"></i> Bookings</a></li>
                <li class="nav-item mt-4"><a href="../auth/logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-left me-2"></i> Logout</a></li>
            </ul>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manajemen Kamar</h2>
                <a href="kamar_tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Kamar</a>
            </div>

            <div class="card p-3">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Foto Kamar</th>
                            <th>Nomor Kamar</th>
                            <th>Tipe</th>
                            <th>Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php 
    $query = mysqli_query($conn, "SELECT * FROM rooms ORDER BY id DESC");
    $no = 1;
    while($row = mysqli_fetch_assoc($query)): 
    ?>
    <tr>
        <td><?= $no++; ?></td>
        <td>
            <img src="../assets/img/<?= $row['gambar']; ?>" width="100" class="rounded shadow-sm">
        </td>
        <td class="fw-bold"><?= $row['nama_kamar']; ?></td>
        <td><span class="badge bg-info text-dark"><?= $row['tipe']; ?></span></td>
        <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
        <td>
            <span class="badge <?= $row['status'] == 'tersedia' ? 'bg-success' : 'bg-danger' ?>">
                <?= ucfirst($row['status']); ?>
            </span>
        </td>
        <td>
            <a href="kamar_edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil-square"></i>
            </a>
            <a href="kamar_hapus.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
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

</body>
</html>