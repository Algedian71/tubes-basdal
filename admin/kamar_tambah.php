<?php require_once 'session_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kamar - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .btn-primary { background: #1a237e; border: none; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">Tambah Kamar Baru</h3>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </div>

                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Kamar</label>
                            <input type="text" name="nama_kamar" class="form-control" placeholder="Contoh: Deluxe Room 101" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tipe Kamar</label>
                            <select name="tipe" class="form-select" required>
                                <option value="Standard">Standard</option>
                                <option value="Deluxe">Deluxe</option>
                                <option value="Suite">Suite</option>
                                <option value="Presidential">Presidential</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga per Malam (Rp)</label>
                        <input type="number" name="harga" class="form-control" placeholder="850000" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Kamar</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Fasilitas: AC, TV, WiFi, Sarapan..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Kamar (Disarankan landscape)</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*" required>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary w-100 py-2 fw-bold">Simpan Data Kamar</button>
                </form>

                <?php
                if (isset($_POST['simpan'])) {
                    $nama  = mysqli_real_escape_string($conn, $_POST['nama_kamar']);
                    $tipe  = mysqli_real_escape_string($conn, $_POST['tipe']);
                    $harga = $_POST['harga'];
                    $desc  = mysqli_real_escape_string($conn, $_POST['deskripsi']);

                    // Logika Upload Gambar secara Profesional
                    $filename = $_FILES['gambar']['name'];
                    $ext      = pathinfo($filename, PATHINFO_EXTENSION);
                    $newname  = "room_" . time() . "." . $ext; // Supaya nama file unik
                    $target   = "../assets/img/" . $newname;

                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target)) {
                        $sql = "INSERT INTO rooms (nama_kamar, tipe, harga, deskripsi, gambar, status) 
                                VALUES ('$nama', '$tipe', '$harga', '$desc', '$newname', 'tersedia')";
                        
                        if (mysqli_query($conn, $sql)) {
                            echo "<script>alert('Data Kamar Berhasil Ditambah!'); window.location='index.php';</script>";
                        }
                    } else {
                        echo "<div class='alert alert-danger mt-3'>Gagal mengunggah gambar. Cek izin folder assets/img/!</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>