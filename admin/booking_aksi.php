<?php
require_once '../config/db.php'; // Pastikan koneksi db terpanggil
require_once 'session_check.php';

$id     = $_GET['id'];
$action = $_GET['action'];

if ($action == 'konfirmasi') {
    // 1. Ambil room_id dari booking tersebut terlebih dahulu
    $get_booking = mysqli_query($conn, "SELECT room_id FROM bookings WHERE id = '$id'");
    $booking     = mysqli_fetch_assoc($get_booking);
    $room_id     = $booking['room_id'];

    // 2. Ubah status booking jadi 'success'
    mysqli_query($conn, "UPDATE bookings SET status = 'success' WHERE id = '$id'");

    // 3. TAMBAHKAN INI: Ubah status kamar jadi 'terisi' agar tidak muncul di user
    mysqli_query($conn, "UPDATE rooms SET status = 'terisi' WHERE id = '$room_id'");

} else {
    // Logika jika ditolak (sudah ada di kode kamu)
    $get_room = mysqli_fetch_assoc(mysqli_query($conn, "SELECT room_id FROM bookings WHERE id = '$id'"));
    $room_id  = $get_room['room_id'];

    mysqli_query($conn, "UPDATE rooms SET status = 'tersedia' WHERE id = '$room_id'");
    mysqli_query($conn, "DELETE FROM bookings WHERE id = '$id'");
}

header("Location: bookings.php");
exit;
?>