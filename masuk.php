<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Jakarta");

include 'config/koneksi.php';
include 'library/fungsi.php';

$aksi = new oop();
$error = "";

// // Kalau sudah login, langsung alihkan ke halaman utama sesuai role-nya
if (isset($_SESSION['id_user'])) {
    if ($_SESSION['role'] == '1') {
        $aksi->redirect("pln/hal_utama.php");
    } elseif ($_SESSION['role'] == '2') {
        $aksi->redirect("agen/hal_utama.php");
    }
}

// Login
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = mysqli_query($conn, "SELECT * FROM user WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_array($sql);

    if ($data) {
        $_SESSION['id_user'] = $data['id']; 
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        $_SESSION['role'] = $data['role_id'];
        $_SESSION['biaya_admin'] = $data['biaya_admin'];
        // Setelah Login mengarah ke halaman...
        if ($data['role_id'] == '1') {
            $aksi->redirect("pln/hal_utama.php");
        } elseif ($data['role_id'] == '2') {
            $aksi->redirect("agen/hal_utama.php");
        } else {
            $error = "Role tidak dikenal.";
        }
    } else {
        $error = "Username atau password salah.";
    }
}
?>



<!DOCTYPE html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>Login - Aplikasi Pembayaran Listrik</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="shortcut icon" type="image/x-icon" href="images/atas.png" />
  <link rel="stylesheet" href="css/bootstrap-5.0.0-alpha-2.min.css" />
  <link rel="stylesheet" href="css/LineIcons.2.0.css" />
  <link rel="stylesheet" href="css/tiny-slider.css" />
  <link rel="stylesheet" href="css/glightbox.min.css" />
  <link rel="stylesheet" href="css/animate.css" />
  <link rel="stylesheet" href="css/index.css" />
</head>

<body style="overflow: hidden;">
  <section class="login-section login-style-1 img-bg mb-80">
    <div class="container">
      <div class="row">
        <div class="col-xl-5 col-lg-6">
          <div class="login-content-wrapper">
            <div class="section-title mb-40">
              <h3 class="mb-20">Log In</h3>
              <p>Silakan login untuk melanjutkan ke sistem pembayaran listrik.</p>
            </div>

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="post" action="" class="login-form">
              <div class="single-input">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan Username" value="<?php echo @$_POST['username']; ?>" required>
                <i class="lni lni-user"></i>
              </div>
              <div class="single-input">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
                <i class="lni lni-lock"></i>
              </div>
              <div class="form-footer">
                
              </div>
              <div class="signup-button mb-25">
                <button type="submit" name="login" class="button button-lg radius-10 btn-block">Submit</button>
              </div>
              <p class="mb-25">Belum punya akun petugas? Silahkan registrasi! <a href="registrasi.php">Registrasi</a></p>
            </form>
          </div>
        </div>
        <div class="col-xl-7 col-lg-6 align-self-end d-none d-lg-block">
          <div class="image text-lg-right">
            <img src="assets/img/signup/login-1/login-img.svg" alt="Login" class="w-100">
          </div>
        </div>
      </div>
    </div>
  </section>

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
