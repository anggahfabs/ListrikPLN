<!DOCTYPE html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>Halaman Utama Aplikasi Pembayaran Listrik</title>
  <meta name="description" content="" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="shortcut icon" type="image/x-icon" href="images/atas.png" />
  <!-- Place favicon.ico in the root directory -->

  <!-- ========================= CSS here ========================= -->
  <!-- <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha-2.min.css" /> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="css/LineIcons.2.0.css" />
  <link rel="stylesheet" href="css/tiny-slider.css" />
  <link rel="stylesheet" href="css/glightbox.min.css" />
  <!-- <link rel="stylesheet" href="assets/css/animate.css" /> -->
  <link rel="stylesheet" href="css/index.css" />
  <style>
    html,
    body {
      overflow: hidden;
      height: 100vh;
    }

    .hero-content {
      margin-top: -40px;
      /* geser ke atas */
    }
  </style>

</head>

<body style="background: #E5E5E5">
  <div class="preloader">
    <div class="loader">
      <div class="spinner">
        <div class="spinner-container">
          <div class="spinner-rotator">
            <div class="spinner-left">
              <div class="spinner-circle"></div>
            </div>
            <div class="spinner-right">
              <div class="spinner-circle"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <section class="hero-section-wrapper-1 mb-100">
    <!-- NAVBAR -->
    <header class="header header-4">
      <div class="navbar-area">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-12">
              <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                  <img src="images/bsi.jpg" alt="Logo" width="40px">
                  <span class="text-dark fw-bold fs-5 mb-0">Sistem Uji Kompetensi UBSI</span>
                </a>
              </nav>
              <!-- navbar -->
            </div>
          </div>
          <!-- row -->
        </div>
        <!-- container -->
      </div>
      <!-- navbar area -->
    </header>
    <!-- NAVBAR END -->

    <div class="hero-section hero-style-1" style="padding-top: 40px;">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <div class="hero-content-wrapper" style="margin-top: -80px;">
              <h2>Aplikasi Pembayaran Listrik</h2>
              <p>Kelola dan proses pembayaran listrik dengan lebih terorganisir menggunakan platform digital yang aman, cepat, dan mudah digunakan.</p>
              <div class="d-flex flex-wrap gap-3">
                <a href="masuk.php"
                  class="button button-lg radius-50 button-outline"
                  style="padding: 15px 50px; font-size: 20px;">
                  Masuk</a>

              </div>
            </div>
          </div>
          <div class="col-lg-6 align-self-end">
            <div class="hero-image">
              <img src="images/awal.png" alt="">
            </div>
          </div>
        </div>
      </div>
      <div class="shapes">
        <img src="assets/img/hero/hero-1/shape-1.svg" alt="" class="shape shape-1">
        <img src="assets/img/hero/hero-1/shape-2.svg" alt="" class="shape shape-2">
        <img src="assets/img/hero/hero-1/shape-3.svg" alt="" class="shape shape-3">
        <img src="assets/img/hero/hero-1/shape-4.svg" alt="" class="shape shape-4">
      </div>
    </div>

    <!-- ========================= hero-1 end ========================= -->

  </section>

  <!-- ========================= JS here ========================= -->
  <!-- <script src="assets/js/bootstrap.5.0.0.alpha-2-min.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- <script src="assets/js/tiny-slider.js"></script> -->
  <!-- <script src="assets/js/count-up.min.js"></script> -->
  <script src="assets/js/imagesloaded.min.js"></script>
  <!-- <script src="assets/js/isotope.min.js"></script> -->
  <script src="assets/js/glightbox.min.js"></script>
  <!-- <script src="assets/js/wow.min.js"></script> -->
  <script src="assets/js/main.js"></script>

  <script>
    // header-2  toggler-icon
    let navbarToggler2 = document.querySelector(".header-2 .navbar-toggler");
    var navbarCollapse2 = document.querySelector(".header-2 .navbar-collapse");

    document.querySelectorAll(".header-2 .page-scroll").forEach(e =>
      e.addEventListener("click", () => {
        navbarToggler2.classList.remove("active");
        navbarCollapse2.classList.remove('show')
      })
    );
    navbarToggler2.addEventListener('click', function() {
      navbarToggler2.classList.toggle("active");
    })


    // header-4  toggler-icon
    let navbarToggler4 = document.querySelector(".header-4 .navbar-toggler");
    var navbarCollapse4 = document.querySelector(".header-4 .navbar-collapse");

    document.querySelectorAll(".header-4 .page-scroll").forEach(e =>
      e.addEventListener("click", () => {
        navbarToggler4.classList.remove("active");
        navbarCollapse4.classList.remove('show')
      })
    );
    navbarToggler4.addEventListener('click', function() {
      navbarToggler4.classList.toggle("active");
    })
  </script>
</body>

</html>