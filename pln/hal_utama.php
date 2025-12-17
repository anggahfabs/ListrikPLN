
<?php
include '../config/koneksi.php';
include '../library/fungsi.php';
include "../library/auth.php";


date_default_timezone_set("Asia/Jakarta");

$aksi = new oop();



// Proses logout
if (isset($_GET['logout'])) {
	unset($_SESSION['username']);
	unset($_SESSION['id_user']);
	unset($_SESSION['nama_lengkap']);
	unset($_SESSION['role']);
	$aksi->alert("Keluar berhasil!", "../masuk.php");
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Petugas PLN</title>
	<link rel="shortcut icon" type="image/x-icon" href="images/atas.png">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<!-- <link rel="stylesheet" href="../css/custom.css"> -->
	<style>
		html {
			height: 100%;
		}

		body {
			min-height: 100%;
			position: relative;
			padding-top: 70px; /* Compensate for navbar-fixed-top */
			padding-bottom: 80px; /* Space for footer */
			margin: 0;
		}

		.wrapper {
			/* No specific flex styles needed here */
		}

		.container {
			padding-bottom: 20px;
		}

		.footer {
			background-color: #337ab7;
			color: white;
			padding: 10px 0;
			text-align: center;
			width: 100%;
			position: absolute;
			bottom: 0;
			height: 60px; /* Explicit height */
		}
	</style>


</head>

<body>
	
	<div class="wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="navbar navbar-fixed-top navbar-inverse inverse">
						<a href="?menu=home" class="navbar-brand" style="margin-top: -10px;">
						</a>
						<ul class="nav navbar-nav">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="kelola">
									<div class="glyphicon glyphicon-edit"></div>&nbsp;
									<strong>Data Dasar</strong>
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu" aria-labelledby="kelola">
									<li>
										<a href="?menu=tarif">Kelola Tarif</a>
									</li>
									<li>
										<a href="?menu=pelanggan">Kelola Pelanggan</a>
									</li>
									<li>
										<a href="?menu=agen">Kelola Agen</a>
									</li>
									<li>
										<a href="?menu=petugas">Kelola Petugas</a>
									</li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="transaksi">
									<div class="glyphicon glyphicon-refresh"></div>&nbsp;
									<strong>Transaksi</strong>
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu" aria-labelledby="transaksi">
									<li>
										<a href="?menu=tagihan">Daftar Tagihan</a>
									</li>
									<li>
										<a href="?menu=penggunaan">Kelola Penggunaan</a>
									</li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="laporan">
									<div class="glyphicon glyphicon-duplicate"></div>&nbsp;
									<strong>Laporan</strong>
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu" aria-labelledby="laporan">
									<li>
										<a href="?menu=laporan&tarif">Laporan Data Tarif</a>
									</li>
									<li>
										<a href="?menu=laporan&pelanggan">Laporan Data Pelanggan</a>
									</li>
									<li>
										<a href="?menu=laporan&agen">Laporan Data Agen</a>
									</li>
									<li>
										<div class="divider"></div>
									</li>
									<li>
										<a href="?menu=laporan&tagihan_bulan">Laporan Tagihan (Perbulan)</a>
									</li>
									<li>
										<a href="?menu=laporan&tunggakan">Laporan Tunggakan</a>
									</li>
									<li>
										<a href="?menu=laporan&riwayat_penggunaan">Laporan Riwayat Penggunaan (Pertahun)</a>
									</li>
								</ul>
							</li>
						</ul>

						<ul class="nav navbar-nav navbar-right" style="margin-right: 50px;">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="akun">
									<div class="glyphicon glyphicon-user"></div>&nbsp;
									<strong><?php echo isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'User';
													?></strong>&nbsp;
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu" aria-labelledby="akun">
									<li>
										<a href="?menu=profil">
											<div class="glyphicon glyphicon-cog"></div>&nbsp;
											<strong>Profil</strong>
										</a>
									</li>
									<li>
										<a href="?logout" onclick="return confirm('Apakah anda yakin ingin keluar dari akun ini?')">
											<div class="glyphicon glyphicon-log-out"></div>&nbsp;
											<strong>Keluar</strong>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<?php
			// Mengecek nilai parameter `menu` dari URL dan menampilkan halaman yang sesuai
			switch (@$_GET['menu']) {
				case 'home':
					include 'home.php';
					break;
				case 'tarif':
					include 'tarif.php';
					break;
				case 'pelanggan':
					include 'pelanggan.php';
					break;
				case 'petugas':
					include 'petugas.php';
					break;
				case 'agen':
					include 'agen.php';
					break;
				case 'penggunaan':
					include 'penggunaan.php';
					break;
				case 'tagihan':
					include 'tagihan.php';
					break;
				case 'laporan':
					include 'laporan.php';
					break;
				case 'profil':
					include 'profil.php';
					break;
				default:
					$aksi->redirect("?menu=home"); // Jika menu tidak ditemukan, arahkan ke halaman home
					break;
			}
			?>
		</div>
	</div>
	<!-- <br><br> -->
	<footer class="footer">
		<p>
			<strong style="color: white;">Angga Reksa</strong>&nbsp;
			<br>
			<strong style="color: white;">UBSI Margonda</strong>
		</p>

	</footer>
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
</body>

</html>