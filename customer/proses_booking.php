<?php
// 1. Panggil koneksi dan cek session
require_once '../config/db.php';

// Cek login
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit;
}

// 2. Cek apakah ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// 3. Ambil data dari form
$user_id = $_SESSION['user_id'];
$room_id = (int)$_POST['room_id'];
$check_in = $_POST['tgl_checkin'];
$check_out = $_POST['tgl_checkout'];

// 4. VALIDASI TANGGAL
if (empty($check_in) || empty($check_out)) {
    echo "<script>alert('Tanggal check-in dan check-out harus diisi!'); window.history.back();</script>";
    exit;
}

// Format dan validasi tanggal
$check_in_clean = date('Y-m-d', strtotime($check_in));
$check_out_clean = date('Y-m-d', strtotime($check_out));

if ($check_in_clean == '1970-01-01' || $check_out_clean == '1970-01-01') {
    echo "<script>alert('Format tanggal tidak valid!'); window.history.back();</script>";
    exit;
}

// Pastikan check-out setelah check-in
if (strtotime($check_out_clean) <= strtotime($check_in_clean)) {
    echo "<script>alert('Tanggal check-out harus setelah tanggal check-in!'); window.history.back();</script>";
    exit;
}

// 5. Ambil data kamar untuk menghitung total harga
$query_room = "SELECT * FROM rooms WHERE id = '$room_id'";
$result_room = mysqli_query($conn, $query_room);
$room = mysqli_fetch_assoc($result_room);

if (!$room) {
    echo "<script>alert('Kamar tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// 6. HITUNG JUMLAH MALAM dan TOTAL HARGA
$datetime1 = new DateTime($check_in_clean);
$datetime2 = new DateTime($check_out_clean);
$interval = $datetime1->diff($datetime2);
$jumlah_malam = $interval->days;

$total_harga = $room['harga'] * $jumlah_malam;
$status = 'pending'; // Sesuai database: pending, menunggu verifikasi, success

// 7. Query INSERT ke database
$sql = "INSERT INTO bookings (user_id, room_id, tanggal_checkin, tanggal_checkout, status, total_harga) 
        VALUES ('$user_id', '$room_id', '$check_in_clean', '$check_out_clean', '$status', '$total_harga')";

if (mysqli_query($conn, $sql)) {
    $last_id = mysqli_insert_id($conn);
    echo "<script>
        alert('Booking berhasil! Total: Rp " . number_format($total_harga, 0, ',', '.') . " untuk $jumlah_malam malam');
        window.location='payment.php?id=$last_id';
    </script>";
} else {
    die("Gagal menyimpan booking: " . mysqli_error($conn));
}
?>