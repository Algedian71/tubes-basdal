<?php 
// Pastikan koneksi dan session aman
require_once '../config/db.php'; 

// Cek ID Kamar dari URL (Misal: booking.php?id=1)
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$room_id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM rooms WHERE id = '$room_id'");
$room = mysqli_fetch_assoc($query);

// Jika kamar tidak ditemukan
if (!$room) {
    echo "Kamar tidak tersedia.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking <?= $room['nama_kamar']; ?> - UNIB HOTEL</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --gold: #c5a059;
            --dark-glass: rgba(0, 0, 0, 0.75);
        }

        body {
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), 
                        url('https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }

        /* NAVBAR MEWAH */
        .navbar {
            backdrop-filter: blur(15px);
            background: rgba(0,0,0,0.8) !important;
            border-bottom: 1px solid rgba(197, 160, 89, 0.3);
            padding: 15px 0;
        }
        .navbar-brand { font-family: 'Playfair Display', serif; color: var(--gold) !important; letter-spacing: 2px; }
        .nav-link { font-weight: 300; letter-spacing: 1px; transition: 0.3s; }
        .nav-link:hover { color: var(--gold) !important; }

        /* HERO & CARD SECTION */
        .glass-container {
            background: var(--dark-glass);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }

        .room-image {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            font-size: 2.5rem;
        }

        /* FORM STYLING */
        .booking-sidebar {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(197, 160, 89, 0.2);
        }

        .form-label { color: var(--gold); font-weight: 600; font-size: 0.9rem; }
        .form-control {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 10px;
            padding: 12px;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.15);
            border-color: var(--gold);
            color: white;
            box-shadow: none;
        }

        .btn-reserve {
            background: linear-gradient(45deg, #c5a059, #a38241);
            color: #000;
            border: none;
            font-weight: 700;
            padding: 15px;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: 0.4s;
        }
        .btn-reserve:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(197, 160, 89, 0.4);
            background: var(--gold);
        }

        .feature-icon {
            color: var(--gold);
            background: rgba(197, 160, 89, 0.1);
            padding: 10px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .price-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gold);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">UNIB HOTEL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php">Cari Kamar</a></li>
                <li class="nav-item"><a class="nav-link" href="riwayat.php">Riwayat Booking</a></li>
                <li class="nav-item ms-lg-3">
                    <a href="../auth/logout.php" class="btn btn-outline-light btn-sm px-3">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="glass-container p-4 p-md-5">
        <div class="row g-5">
            <div class="col-lg-7">
              <img src="../assets/img/<?php echo $room['gambar']; ?>" class="room-image mb-4" alt="Foto Kamar">

                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="section-title mb-0"><?= $room['nama_kamar']; ?></h1>
                    <span class="badge bg-primary px-3 py-2"><?= $room['tipe']; ?> Class</span>
                </div>
                
                <p class="text-white-50 lh-lg mb-4">
                    Nikmati pengalaman menginap tak terlupakan dengan fasilitas premium. 
                    Setiap detail dirancang untuk kenyamanan maksimal Anda selama berada di UNIB Hotel.
                </p>

                <div class="row g-4 mb-4">
                    <div class="col-md-4 d-flex align-items-center">
                        <i class="bi bi-wifi feature-icon"></i> <span>Free WiFi</span>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <i class="bi bi-snow feature-icon"></i> <span>Full AC</span>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <i class="bi bi-tv feature-icon"></i> <span>Smart TV</span>
                    </div>
                </div>

                <div class="alert alert-light border-0 py-3 px-4 mb-0" style="background: rgba(255, 255, 255, 0.1); border-left: 4px solid #d4af37 !important;">
    <p class="small text-white mb-0">
        <i class="bi bi-info-circle me-2"></i>
        Kebijakan Pembatalan: Gratis pembatalan sebelum tanggal check-in (syarat & ketentuan berlaku).
    </p>
</div>
            </div>

            <div class="col-lg-5">
                <div class="booking-sidebar shadow">
                    <h3 class="fw-bold mb-4" style="font-family: 'Playfair Display';">Detail Reservasi</h3>
                    
                   <form action="proses_booking.php" method="POST">
    <input type="hidden" name="room_id" value="<?= $room['id']; ?>">
    
    <input type="hidden" name="total_harga" value="<?= $room['harga']; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">TANGGAL CHECK-IN</label>
                            <input type="date" name="tgl_checkin" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">TANGGAL CHECK-OUT</label>
                            <input type="date" name="tgl_checkout" class="form-control" required>
                        </div>

                        <hr class="border-secondary opacity-25 my-4">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-white-50">Harga / Malam:</span>
                            <span class="fw-bold">Rp <?= number_format($room['harga'], 0, ',', '.'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <span class="text-white-50">Total Estimasi:</span>
                            <span class="price-display">Rp <?= number_format($room['harga'], 0, ',', '.'); ?>*</span>
                        </div>

                        <button type="submit" class="btn btn-reserve w-100 mb-3">
                            Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                        
                        <div class="text-center">
                            <a href="index.php" class="text-decoration-none small text-white-50 hover-gold">
                                <i class="bi bi-chevron-left me-1"></i> Kembali Cari Kamar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>