<?php
session_start();

$host = "localhost:3307"; // TAMBAHKAN :3307 DI SINI
$user = "root";
$pass = "";
$db   = "db_hotel";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

function base_url($path = "") {
    return "http://localhost/hotel-app/" . $path;
}
?>