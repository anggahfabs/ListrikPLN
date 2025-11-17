<?php
// Mulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi ke database
include '../config/koneksi.php';

// Cek: apakah sudah login?
if (!isset($_SESSION['id_user'])) {
    echo "<script>
        alert('Anda belum login. Silakan login terlebih dahulu!');
        window.location.href = '../masuk.php';
    </script>";
    exit;
}


// Cek: apakah user masih ada di database?
$id_user = $_SESSION['id_user'];
$query = mysqli_query($conn, "SELECT * FROM user WHERE id = '$id_user'");

if (!$query || mysqli_num_rows($query) === 0) {
    // User tidak ditemukan, kemungkinan dihapus manual dari DB
    session_destroy();
    header("Location: ../masuk.php?pesan=akun_dihapus");
    exit;
}

$currentDir = basename(dirname(__FILE__)); // misalnya: "pln" atau "agen"

if ($currentDir == 'pln' && $_SESSION['role'] != '1') {
    echo "<script>
        alert('Akses ditolak. Halaman ini hanya untuk Petugas!');
        window.location.href = '../masuk.php';
    </script>";
    exit;
}

if ($currentDir == 'agen' && $_SESSION['role'] != '2') {
    echo "<script>
        alert('Akses ditolak. Halaman ini hanya untuk Agen!');
        window.location.href = '../masuk.php';
    </script>";
    exit;
}
?>
