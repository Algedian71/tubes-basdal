<?php
require_once 'config/db.php';

// 1. Hapus admin lama jika ada
mysqli_query($conn, "DELETE FROM users WHERE email = 'admin@hotel.com'");

// 2. Buat password hash baru
$password_asli = "admin123";
$password_hash = password_hash($password_asli, PASSWORD_DEFAULT);

// 3. Masukkan kembali ke database
$query = "INSERT INTO users (name, email, password, role) 
          VALUES ('Administrator', 'admin@hotel.com', '$password_hash', 'admin')";

if (mysqli_query($conn, $query)) {
    echo "<h1>AKUN ADMIN BERHASIL DIRESET!</h1>";
    echo "Email: admin@hotel.com <br>";
    echo "Password: admin123 <br><br>";
    echo "<a href='auth/login.php'>Klik di sini untuk mencoba Login</a>";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>