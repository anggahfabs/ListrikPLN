
<?php
include '../config/koneksi.php';  // Menyertakan file koneksi ke database
include '../library/fungsi.php'; // Menyertakan file berisi class/fungsi OOP yang digunakan untuk alert, caridata, dll
include '../library/auth.php'; // Menyertakan file berisi class/fungsi OOP yang digunakan untuk alert, caridata, dll
// session_start();

// Mengatur zona waktu agar sesuai dengan waktu lokal (WIB)
date_default_timezone_set("Asia/Jakarta");

// Membuat objek dari class 'oop'
$aksi = new oop();
// Cek login & role

// ----------------------
// Validasi Login dan Role
// ----------------------

if (empty($_SESSION['username']) || $_SESSION['role'] != '2') {
	$aksi->alert("Harap Login Terlebih Dahulu!", "../masuk.php");
}

// ----------------------
// Proses Logout
// ----------------------

// Jika ada parameter GET 'logout', maka hapus semua session yang berkaitan dengan user
if (isset($_GET['logout'])) {
	unset($_SESSION['username']);
	unset($_SESSION['id_user']);
	unset($_SESSION['nama_lengkap']);
	unset($_SESSION['role']);
	// unset($_SESSION['akses_agen']);
	$aksi->alert("Keluar berhasil!", "../masuk.php");
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>Agen Listrik</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<style type="text/css">
		html {
			height: 100%;
		}

		body {
			min-height: 100%;
			position: relative;
			padding-top: 70px; /* Offset for fixed navbar */
			padding-bottom: 80px; /* Space for footer */
			margin: 0;
		}

		.wrapper {
			/* No specific flex styles needed */
		}

		.footer {
			background-color: #337ab7;
			color: white;
			padding: 10px 0;
			text-align: center;
			position: absolute;
			bottom: 0;
			width: 100%;
			height: 60px;
		}

		.navbar-collapse {
			background-color: #337ab7;
		}
	</style>
</head>

<body>
	<div class="wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="navbar navbar-fixed-top navbar-default">
						<div class="navbar-header">
							<a href="?menu=home" class="navbar-brand" style="margin-top: -23px;">
							</a>
						</div>
						<div class="navbar-collapse collapse">
							<ul class="nav navbar-nav">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="transaksi" style="color: white;">
										<div class="glyphicon glyphicon-shopping-cart" style="color: white;"></div>&nbsp;
										<strong style="color: white;">Transaksi</strong>&nbsp;
										<span class="caret"></span>
									</a>
									<ul class="dropdown-menu" aria-labelledbay="transaksi">
										<li>
											<a href="?menu=riwayat">
												<strong>Riwayat Pembayaran</strong>
											</a>
										</li>
										<li>
											<a href="?menu=pembayaran">
												<strong>Kelola Pembayaran</strong>
											</a>
										</li>
									</ul>
								</li>

								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="laporan" style="color: white;">
										<div class="glyphicon glyphicon-duplicate" style="color: white;"></div>&nbsp;
										<strong style="color: white;">Laporan</strong>&nbsp;
										<span class="caret"></span>
									</a>
									<ul class="dropdown-menu" arai-labelledby="laporan">
										<li>
											<a href="?menu=laporan">
												<strong>Riwayat Pembayaran</strong>
											</a>
										</li>
									</ul>
								</li>
							</ul>

							<ul class="nav navbar-nav navbar-right" style="margin-right: 50px;">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="akun">
										<div class="glyphicon glyphicon-user" style="color: white;"></div>&nbsp;
										<strong style="color: white;"><?php echo $_SESSION['nama_lengkap']; ?></strong>&nbsp;
										<span class="caret" style="color: white;"></span>
									</a>
									<ul class="dropdown-menu" aria-labelledby="akun">
										<li>
											<a href="?menu=profil">
												<div class="glyphicon glyphicon-cog"></div>&nbsp;
												<strong>Profil</strong>
											</a>
										</li>
										<li>
											<a href="?logout" onclick="return conf">
												<div class="glyphicon glyphicon-log-out"></div>&nbsp;&nbsp;
												<strong>Keluar</strong>
											</a>
										</li>
									</ul>

								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<?php
			switch (@$_GET['menu']) {
				case 'home':
					include 'home.php';
					break;
				case 'riwayat':
					echo "<br> <br>";
					include 'riwayat.php';
					break;
				case 'pembayaran':
					echo "<br> <br>";
					include 'pembayaran.php';
					break;
				case 'laporan':
					echo "<br> <br>";
					include 'laporan.php';
					break;
				case 'profil':
					echo "<br> <br>";
					include 'profil.php';
					break;
				// case 'struk':include'struk.php'; break;
				default:
					$aksi->redirect("?menu=home");
					break;
			}
			?>
		</div>
	</div>


	<footer class="footer">
		<p>
			<strong style="color: white;">Angga Reksa</strong>&nbsp;
			<br>
			<strong style="color: white;">UBSI Margonda</strong>
		</p>
	</footer>

	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$("#tbayar").keyup(function() {
			var totalakhir = parseInt($("#ttotalakhir").val());
			var bayar = parseInt($("#tbayar").val());
			var kembalian = 0;
			if (bayar < totalakhir) {
				kembalian = "";
			};
			if (bayar > totalakhir) {
				kembalian = bayar - totalakhir;
			};
			$("#tkembalian").val(kembalian);
		});
	</script>
</body>

</html>