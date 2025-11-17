<?php
// session_start();
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "dbpln");

// Ambil semua role dari tabel role untuk dropdown pilihan role saat registrasi
$roleQuery = mysqli_query($conn, "SELECT * FROM role");

// Proses registrasi saat tombol submit ditekan
if (isset($_POST['submit'])) {
  // Mengambil dan membersihkan input
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $nama_lengkap = trim($_POST['nama_lengkap']);
  $role_id = $_POST['role_id'];
  $alamat = trim($_POST['alamat']);
  $no_telepon = trim($_POST['no_telepon']);
  $jk = $_POST['jk'];

  // Validasi semua field wajib diisi
  if ($username == "" || $password == "" || $nama_lengkap == "" || $role_id == "" || $alamat == "" || $no_telepon == "" || $jk == "") {
    echo "<script>alert('Semua field wajib diisi!');</script>";
  } else {
    // Cek apakah username sudah ada di database
    $cek = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
      echo "<script>alert('Username sudah digunakan!');</script>";
    } else {
      // Menyimpan data user baru ke table user
      $simpan = mysqli_query($conn, "INSERT INTO user 
        (username, password, nama_lengkap, role_id, alamat, no_telepon, jk) 
        VALUES 
        ('$username', '$password', '$nama_lengkap', $role_id, '$alamat', '$no_telepon', '$jk')");

      if ($simpan) {
        // Jika berhasil, tampilkan alert dan redirect ke halaman login (masuk.php)
        echo "<script>alert('Registrasi berhasil!'); window.location='masuk.php';</script>";
      } else {
        // Jika gagal menyimpan
        echo "<script>alert('Gagal menyimpan data!');</script>";
      }
    }
  }
}
?>


<!DOCTYPE html>
<html class="no-js" lang="id">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>Registrasi Petugas - Aplikasi Pembayaran Listrik</title>
  <link rel="shortcut icon" type="image/x-icon" href="images/atas.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- CSS -->
  <link rel="stylesheet" href="css/bootstrap-5.0.0-alpha-2.min.css" />
  <link rel="stylesheet" href="css/LineIcons.2.0.css" />
  <link rel="stylesheet" href="css/tiny-slider.css" />
  <link rel="stylesheet" href="css/glightbox.min.css" />
  <!-- <link rel="stylesheet" href="assets/css/animate.css" /> -->
  <link rel="stylesheet" href="css/index.css" />
</head>

<body>
  <section class="signup signup-style-1">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="signup-content-wrapper">
            <div class="section-title" style="text-align: center;">
              <h3 class="mb-0">Registrasi Petugas</h3>
              <p>Aplikasi Pembayaran Listrik</p><br>
            </div>
            <div class="image">
              <img src="images/registrasi.jpg" alt="Ilustrasi" class="w-100" style="border-radius: 20px;">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="signup-form-wrapper">
            <form method="POST" action="" class="signup-form">
              <div class="single-input" style="text-align: center;">
                <label for="role_id" style="margin-bottom: 5px;" style="text-align: center;">
                  <h4>Role : (1) Petugas
                </label>
                <input type="hidden" id="role_id" name="role_id" value="1">
              </div>

              <div class="single-input" style="margin-bottom: 5px;">
                <label for="username" style="margin-bottom: 5px;">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required>
              </div>

              <div class="single-input" style="margin-bottom: 5px;">
                <label for="password" style="margin-bottom: 5px;">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
              </div>

              <div class="single-input" style="margin-bottom: 5px;">
                <label for="nama_lengkap" style="margin-bottom: 5px;">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" required>
              </div>

              <div class="single-input" style="margin-bottom: 5px;">
                <label for="alamat" style="margin-bottom: 5px;">Alamat</label>
                <input type="text" id="alamat" name="alamat" placeholder="Alamat" required>
              </div>

              <div class="single-input" style="margin-bottom: 5px;">
                <label for="no_telepon" style="margin-bottom: 5px;">No Telepon</label>
                <input type="text" id="no_telepon" name="no_telepon" placeholder="No Telepon" required>
              </div>

              <!-- <div class="single-input" style="margin-bottom: 5px;">
                <label for="biaya_admin" style="margin-bottom: 5px;">Biaya Admin</label>
                <input type="number" id="biaya_admin" name="biaya_admin" placeholder="Contoh: 2.500" required>
              </div> -->

              <div class="single-input">
                <label for="jk">Jenis Kelamin</label>
                <select id="jk" name="jk" class="form-control" required>
                  <option value="">-- Pilih Jenis Kelamin --</option>
                  <option value="Laki-laki">Laki-laki</option>
                  <option value="Perempuan">Perempuan</option>
                </select>
              </div>

              <div class="signup-button mb-25">
                <button type="submit" name="submit" class="button button-lg radius-10 btn-block">Sign up</button>
              </div>
              <p>Sudah punya akun? <a href="masuk.php">Login</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- JS -->
  <script src="assets/js/bootstrap.5.0.0.alpha-2-min.js"></script>
  <script src="assets/js/tiny-slider.js"></script>
  <script src="assets/js/count-up.min.js"></script>
  <script src="assets/js/imagesloaded.min.js"></script>
  <script src="assets/js/isotope.min.js"></script>
  <script src="assets/js/glightbox.min.js"></script>
  <script src="assets/js/wow.min.js"></script>
  <script src="assets/js/main.js"></script>

</body>

</html>