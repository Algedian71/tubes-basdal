<?php
// 1. Panggil koneksi (db.php sudah otomatis start session)
require_once '../config/db.php';

// 2. Cek apakah ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil data dari form (Pastikan name di form booking.php sesuai)
    $user_id     = $_SESSION['user_id'];
    $room_id     = mysqli_real_escape_string($conn, $_POST['room_id']);
    $check_in    = mysqli_real_escape_string($conn, $_POST['tgl_checkin']);
    $check_out   = mysqli_real_escape_string($conn, $_POST['tgl_checkout']);
    $total_harga = mysqli_real_escape_string($conn, $_POST['total_harga']);
    $status      = 'Pending';

    // 3. Query INSERT (Sesuaikan nama kolom dengan image_fd799d.jpg)
    $sql = "INSERT INTO bookings (user_id, room_id, tanggal_checkin, tanggal_checkout, status, total_harga) 
            VALUES ('$user_id', '$room_id', '$check_in', '$check_out', '$status', '$total_harga')";

    if (mysqli_query($conn, $sql)) {
        // 4. Ambil ID terakhir dan pindah ke pembayaran
        $last_id = mysqli_insert_id($conn);
        header("Location: pembayaran.php?id=" . $last_id);
        exit;
    } else {
        // Jika gagal, tampilkan errornya apa
        die("Gagal Query: " . mysqli_error($conn));
    }
} else {
    // Jika diakses langsung tanpa klik tombol, balik ke index
    header("Location: index.php");
    exit;
}
?>