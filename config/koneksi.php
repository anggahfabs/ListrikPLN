<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "dbpln";

// Buat koneksi
$conn = mysqli_connect($host, $user, $pass, $dbname);
// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
<?php
$koneksi = mysqli_connect("localhost", "root", "", "dbpln");
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
}
?>

