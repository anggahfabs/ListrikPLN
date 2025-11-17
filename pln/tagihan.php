<?php
// Jika parameter GET 'menu' tidak ada, arahkan ke halaman utama dengan menu 'tagihan'
if (!isset($_GET['menu'])) {
	header("location:hal_utama.php?menu=tagihan");
}
include '../library/auth.php'; // Include file autentikasi untuk memastikan user sudah login

// Inisialisasi variabel utama
$table = "tagihan"; // Nama tabel utama yang digunakan
$redirect = "?menu=alamat";

// Inisialisasi variabel pencarian kosong
$cari = "";

// Jika tombol cari berbasis teks ditekan
// if (isset($_POST['bcari_text'])) {
// 	$text = $_POST['tcari']; // Ambil input dari form pencarian
// 	if (empty($text)) {
// 		echo "<script>alert('Kolom pencarian tidak boleh kosong!'); window.location.href='?menu=tagihan';</script>";
// 		exit;
// 	}
// 	// Filter pencarian berdasarkan berbagai kolom yang relevan
// 	$cari = "WHERE id_pelanggan LIKE '%$text%' OR bulan LIKE '%$text%' OR tahun LIKE '%$text%' OR jumlah_meter LIKE '%$text%' OR tarif_perkwh LIKE '%$text%' OR jumlah_bayar LIKE '%$text%' OR nama_lengkap LIKE '%$text%' OR nama_pelanggan LIKE '%$text%' OR status LIKE '%$text%'";
// }
// saat tombol refresh di tekan, maka akan ke reset
if (isset($_POST['brefresh'])) {
	unset($_SESSION['filter_bulan']);
	unset($_SESSION['filter_tahun']);
	unset($_SESSION['filter_status']);
	header("location:?menu=tagihan");
	exit;
}

// Jika tombol pencarian berdasarkan filter bulan, tahun, dan status ditekan
if (isset($_POST['bcari'])) {
	// Validasi form input
	if (empty($_POST['bulan']) || empty($_POST['tahun'] || empty($_POST['status']))) {
		echo "<script>alert('Status, bulan, dan tahun wajib diisi!'); window.location.href='?menu=tagihan';</script>";
		exit;
	}

	// Ambil input dan sanitasi
	$bln = $_POST['bulan'];
if (strlen($bln) == 1) {
	$bln = "0".$bln;
}
$bln_cari = mysqli_real_escape_string($koneksi, $bln);

	$thn_cari = mysqli_real_escape_string($koneksi, $_POST['tahun']);
	$status = mysqli_real_escape_string($koneksi, $_POST['status']);

	// Simpan di session jika perlu
	$_SESSION['filter_bulan'] = $bln_cari;
	$_SESSION['filter_tahun'] = $thn_cari;
	$_SESSION['filter_status'] = $status;

	// Buat query
	$cari = "WHERE status = '$status' AND bulan = '$bln_cari' AND tahun = '$thn_cari'";

	// Ambil data
	$data = $aksi->tampil("qw_tagihan", $cari, "ORDER BY id_tagihan DESC");

	// Link cetak
	$link_print = "print.php?tagihan_bulan&status=$status&bulan=$bln_cari&tahun=$thn_cari";
	$link_excel = "print.php?excel&tagihan_bulan&status=$status&bulan=$bln_cari&tahun=$thn_cari";
}


?>
<!DOCTYPE html>
<html>

<head>
	<title>Tagihan</title>
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="color:white" ;>
						<b>Daftar Tagihan</b>
					</div>
					<div class="panel-body">
						<form method="post">
							<div class="col-md-12">
								<div class="col-md-7 pull-left">
									<label>Filter Pencarian</label>
									<p>NOTE: <i>Pencarian berdasarkan bulan pemakaian!</i></p>
									<div class="input-group">
										<span class="input-group-addon">Status</span>
										<select name="status" class="form-control">
											<option value="Terbayar" <?php if (@$status == "Terbayar") {
																									echo "selected";
																								} ?>>Sudah Bayar</option>
											<option value="Belum Bayar" <?php if (@$status == "Belum Bayar") {
																										echo "selected";
																									} ?>>Belum Bayar</option>
										</select>
										<span class="input-group-addon">Bulan</span>
										<select name="bulan" class="form-control">
											<?php
											for ($a = 1; $a <= 12; $a++) {
												if ($a < 10) {
													$b = "0" . $a;
												} else {
													$b = $a;
												} ?>
												<option value="<?php echo $b; ?>" <?php if ($b == @$bln_cari) {
																														echo "selected";
																													} ?>><?php $aksi->bulan($b); ?></option>

											<?php } ?>
										</select>
										<div class="input-group-addon" id="pri">Tahun</div>
										<select name="tahun" class="form-control">
											<?php
											for ($a = date("Y"); $a < 2031; $a++) {
											?>
												<option value="<?php echo $a; ?>" <?php if ($a == @$thn_cari) {
																														echo "selected";
																													} ?>><?php echo @$a; ?></option>
											<?php } ?>
										</select>
										<div class="input-group-btn">
											<button type="submit" name="bcari" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>&nbsp;</button>
											<button type="submit" name="brefresh" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span>&nbsp;</button>
										</div>
									</div>
								</div>

								<!-- <div class="col-md-5 pull-right">
									<label>Filter Dengan Pencarian</label>
									<div class="input-group">
										<input type="text" name="tcari" class="form-control" placeholder="Masukan Keyword [bulan 01 = januari, lainnya]" value="<?php echo @$text ?>">
										<div class="input-group-btn">
											<button type="submit" name="bcari_text" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>&nbsp;</button>
											<button type="submit" name="brefresh" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span>&nbsp;</button>
										</div>
									</div>
								</div> -->
							</div>
						</form>
<br>
						<br>
						<hr>
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<th>
											<center>No.</center>
										</th>
										<th>ID Pelanggan</th>
										<th>Nama Pelanggan</th>
										<th>Bulan</th>
										<th>Jumlah Meter</th>
										<th>Jumlah Bayar</th>
										<th>Nama Petugas</th>
										<th>Status</th>
									</thead>
									<tbody>
										<?php
										$no = 0;
									
										$a = $aksi->tampil("qw_tagihan", $cari, "ORDER BY id_pelanggan DESC");

										if (!isset($data)) {
	$data = $aksi->tampil("qw_tagihan", $cari, "ORDER BY id_pelanggan DESC");
}

if (empty($data)) {
	$aksi->no_record(8);
} else {
	foreach ($data as $r) {

												$no++;
										?>
												<tr>
													<td align="center"><?php echo $no; ?>.</td>
													<td><?php echo $r['id_pelanggan']; ?></td>
													<td><?php echo $r['nama_pelanggan']; ?></td>
													<td><?php $aksi->bulan($r['bulan']);
															echo " " . $r['tahun']; ?></td>
													<td><?php echo $r['jumlah_meter']; ?></td>
													<td><?php $aksi->rupiah($r['jumlah_bayar']); ?></td>
													<td><?php echo $r['nama_lengkap']; ?></td>
													<td><?php echo $r['status']; ?></td>
												</tr>
										<?php }
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="panel-footer" style="color: white; text-align: center;">
						&nbsp; LSP Serkom 2025
					</div>
				</div>
			</div>
		</div>
	</div>
	<br><br><br><br><br><br>
</body>

</html>