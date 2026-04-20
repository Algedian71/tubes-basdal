<?php 
require_once '../config/db.php'; 

if (!isset($_SESSION['login'])) header("Location: ../auth/login.php");

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil detail booking, data user, dan data kamar sekaligus
$query = mysqli_query($conn, "SELECT bookings.*, users.name, users.email, rooms.nama_kamar, rooms.tipe, rooms.harga 
                              FROM bookings 
                              JOIN users ON bookings.user_id = users.id 
                              JOIN rooms ON bookings.room_id = rooms.id 
                              WHERE bookings.id = '$booking_id' AND bookings.user_id = '$user_id'");

$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$data) {
    echo "Data invoice tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?= $data['nama_kamar']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #eee; }
        .invoice-box {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            border: 1px solid #eee;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
        }
        @media print {
            .no-print { display: none; }
            body { background: #fff; }
            .invoice-box { box-shadow: none; border: none; margin: 0; }
        }
    </style>
</head>
<body>

<div class="container no-print mt-3 text-center">
    <button onclick="window.print()" class="btn btn-primary shadow-sm px-4">
        <i class="bi bi-printer"></i> Cetak Struk (PDF)
    </button>
    <a href="riwayat.php" class="btn btn-secondary shadow-sm">Kembali</a>
</div>

<div class="invoice-box">
    <div class="row mb-4">
        <div class="col-6">
            <h2 class="fw-bold text-primary">LUXURY HOTEL</h2>
            <p class="text-muted mb-0">Jl. Teknik Informatika, Bengkulu</p>
            <p class="text-muted">Telp: 0812-xxxx-xxxx</p>
        </div>
        <div class="col-6 text-end">
            <h4 class="fw-bold">INVOICE</h4>
            <p class="mb-0">ID Booking: #INV-<?= $data['id']; ?></p>
            <p>Tanggal: <?= date('d/m/Y'); ?></p>
        </div>
    </div>

    <hr>

    <div class="row mb-4">
        <div class="col-6">
            <h6 class="text-muted fw-bold">DITAGIHKAN KEPADA:</h6>
            <h5 class="fw-bold"><?= strtoupper($data['name']); ?></h5>
            <p class="mb-0"><?= $data['email']; ?></p>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="bg-light">
            <tr>
                <th>Item / Deskripsi</th>
                <th class="text-center">Durasi</th>
                <th class="text-end">Harga/Malam</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Menginap di <?= $data['nama_kamar']; ?></strong><br>
                    <small class="text-muted">Tipe: <?= $data['tipe']; ?></small><br>
                    <small class="text-muted">Check-in: <?= date('d M Y', strtotime($data['tanggal_checkin'])); ?></small><br>
                    <small class="text-muted">Check-out: <?= date('d M Y', strtotime($data['tanggal_checkout'])); ?></small>
                </td>
                <td class="text-center">
                    <?php 
                        $tgl1 = new DateTime($data['tanggal_checkin']);
                        $tgl2 = new DateTime($data['tanggal_checkout']);
                        $durasi = $tgl1->diff($tgl2)->days;
                        echo $durasi . " Malam";
                    ?>
                </td>
                <td class="text-end">Rp <?= number_format($data['harga'], 0, ',', '.'); ?></td>
                <td class="text-end fw-bold text-primary">Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end fw-bold">TOTAL BAYAR</td>
                <td class="text-end fw-bold h5 text-primary">Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-5">
        <p class="mb-1 text-muted fw-bold">Status Pembayaran:</p>
        <?php if($data['status'] == 'success'): ?>
            <span class="badge bg-success p-2 px-3 fs-6">LUNAS / TERKONFIRMASI</span>
        <?php else: ?>
            <span class="badge bg-warning text-dark p-2 px-3 fs-6">MENUNGGU KONFIRMASI</span>
        <?php endif; ?>
    </div>

    <div class="mt-5 text-center text-muted border-top pt-3">
        <small>Terima kasih telah memilih Luxury Hotel sebagai tempat istirahat Anda.</small>
    </div>
</div>

</body>
</html>