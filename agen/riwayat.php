<?php
include '../library/auth.php';
if (!isset($_GET['menu'])) {
	header('location:hal_utama.php?menu=riwayat');
}

// Dasar Variabel Awal
$table = "pembayaran";
$redirect = "?menu=riwayat";

// Jika agen melakukan pencarian
if (isset($_POST['bcari'])) {
	$text = $_POST['tcari'];
	$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';

// Filter pencarian berdasarkan ID agen dan mencocokkan kata kunci pada berbagai kolom
$cari = "WHERE id_user = '$id_user' AND (id_pelanggan LIKE '%$text%' OR id_pembayaran LIKE '%$text%' OR jumlah_bayar LIKE '%$text%' OR nama_lengkap LIKE '%$text%' OR tahun_bayar LIKE '%$text%' OR nama_pelanggan LIKE '%$text%' OR bulan_bayar LIKE '%$text%' OR total_akhir LIKE '%$text%' OR bayar LIKE '%$text%' OR kembali LIKE '%$text%')";
} else {
	// Jika tidak sedang melakukan pencarian, tampilkan semua riwayat milik agen yang login
	$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';
$cari = " WHERE id_user = '$id_user'";

}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Riwayat</title>
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="color: white;"><b>Daftar Riwayat Pembayaran</b></div>
					<div class="panel-body">
						<div class="col-md-12">
							<form method="post">
								<div class="input-group">
									<input type="text" name="tcari" class="form-control" value="<?php echo @$text ?>" placeholder="Masukan Keyword Pencarian (ID Pembayaran, ID Pelanggan, Bulan[contoh : 01,09,12], Tahun, Nama Pelanggan, Nama )......">
									<div class="input-group-btn">
										<button type="submit" name="bcari" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>&nbsp;Cari</button>
										<button type="submit" name="brefresh" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span>&nbsp;Refresh</button>
									</div>
								</div>
							</form>
						</div>
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<th>No.</th>
										<th>ID Pembayaran</th>
										<th>ID Pelanggan</th>
										<th>Nama Pelanggan</th>
										<th>Waktu</th>
										<th>Bulan Bayar</th>
										<th>
											<center>Jumlah Bayar</center>
										</th>
										<th>
											<center>Biaya Admin</center>
										</th>
										<th>
											<center>Total Akhir</center>
										</th>
										<th>
											<center>Bayar</center>
										</th>
										<th>
											<center>Kembali</center>
										</th>
										<th>
											<center>Petugas</center>
										</th>
										<th>
											<center>Cetak<br>Struk</center>
										</th>
									</thead>
									<tbody>
										<?php
										$no = 0;
										$data = $aksi->tampil("qw_pembayaran", $cari, " order by id_pembayaran desc");
										if ($data == "") {
											$aksi->no_record(13);
										} else {
											foreach ($data as $r) {
												$no++; ?>
												<tr>
													<td><?php echo $no; ?>.</td>
													<td><?php echo $r['id_pembayaran']; ?></td>
													<td><?php echo $r['id_pelanggan']; ?></td>
													<td><?php echo $r['nama_pelanggan']; ?></td>
													<td><?php echo $r['waktu_bayar']; ?></td>
													<td><?php $aksi->bulan($r['bulan_bayar']);
															echo " " . $r['tahun_bayar']; ?></td>
													<td><?php $aksi->rupiah($r['jumlah_bayar']); ?></td>
													<td><?php $aksi->rupiah($r['biaya_admin']); ?></td>
													<td><?php $aksi->rupiah($r['total_akhir']); ?></td>
													<td><?php $aksi->rupiah($r['bayar']); ?></td>
													<td><?php $aksi->rupiah($r['kembali']); ?></td>
													<td><?php echo $r['nama_lengkap'] ?></td>
													<td><a href="struk.php?id_pelanggan=<?php echo $r['id_pelanggan']; ?>&bulan=<?php echo $r['bulan_bayar']; ?>&tahun=<?php echo $r['tahun_bayar']; ?>" target="_blank" class="btn btn-primary">CETAK</a></td>
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
</body>

</html>