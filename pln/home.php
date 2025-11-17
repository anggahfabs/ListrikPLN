<?php
if (!isset($_GET['menu'])) {
  header('location:hal_utama.php?menu=home');
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>HOME</title>
</head>

<body>
  <div class="wrapper">
    <div class="container mt-4">

      <!-- Ucapan Selamat Datang -->
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-info text-center shadow-sm">
            <h4 class="mb-0">
              Selamat Datang <strong><?php echo $_SESSION['nama_lengkap']; ?></strong> sebagai <strong>Petugas</strong>
            </h4>
            <p class="mb-0">di Aplikasi Pembayaran Listrik Pasca Bayar</p>
          </div>
        </div>
      </div>
    </div>
</body>

</html>