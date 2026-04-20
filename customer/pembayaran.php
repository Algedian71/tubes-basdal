<?php 
require_once '../config/db.php'; 

if (!isset($_SESSION['login'])) header("Location: ../auth/login.php");

$booking_id = $_GET['id'];
$query = mysqli_query($conn, "SELECT bookings.*, rooms.nama_kamar, rooms.harga 
                              FROM bookings 
                              JOIN rooms ON bookings.room_id = rooms.id 
                              WHERE bookings.id = '$booking_id'");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['bayar'])) {
    $metode = $_POST['metode'];
    // Update status booking menjadi 'menunggu verifikasi'
    mysqli_query($conn, "UPDATE bookings SET status = 'menunggu verifikasi' WHERE id = '$booking_id'");
    
    echo "<script>alert('Pembayaran berhasil dikirim! Menunggu konfirmasi admin.'); window.location='riwayat.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran - Luxury Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-pay { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .bank-box { border: 2px solid #eee; border-radius: 10px; padding: 15px; cursor: pointer; transition: 0.3s; }
        .bank-box:hover { border-color: #1a237e; background: #f8f9ff; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-pay p-4">
                <h4 class="fw-bold mb-4">Detail Pembayaran</h4>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Kamar:</span>
                    <span class="fw-bold"><?= $data['nama_kamar']; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span>Total Tagihan:</span>
                    <span class="h4 fw-bold text-primary">Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?></span>
                </div>

                <form action="" method="POST">
                    <label class="form-label fw-bold mb-3">Pilih Metode Pembayaran:</label>
                    
                    <div class="bank-box mb-3 d-flex align-items-center">
                        <input type="radio" name="metode" value="BCA" checked class="me-3">
                        <div>
                            <p class="mb-0 fw-bold">Transfer Bank BCA</p>
                            <small class="text-muted">No Rek: 1234-567-890 a/n Luxury Hotel</small>
                        </div>
                    </div>

                    <div class="bank-box mb-4 d-flex align-items-center">
                        <input type="radio" name="metode" value="E-Wallet" class="me-3">
                        <div>
                            <p class="mb-0 fw-bold">E-Wallet (OVO/Dana/Gopay)</p>
                            <small class="text-muted">0812-3456-7890</small>
                        </div>
                    </div>

                    <button type="submit" name="bayar" class="btn btn-primary w-100 py-3 fw-bold shadow">
                        SAYA SUDAH TRANSFER
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>