<?php
include '../library/auth.php';

// Jika tidak ada parameter menu, arahkan ke menu penggunaan
if (!isset($_GET['menu'])) {
	header('location:hal_utama.php?menu=penggunaan');
}
// Inisialisasi variabel dasar
$table = "penggunaan";
$id = @$_GET['id'];
$where = " id_penggunaan = '$id'";
$redirect = "?menu=penggunaan";

// Logika saat form pencarian id_pelanggan dikirim
if (isset($_POST['id_pelanggan'])) {
	$id_pel = $_POST['id_pelanggan'];
	// Cek apakah sudah ada data penggunaan bulan ini (ditandai dengan meter_akhir masih 0)
	$penggunaan = $aksi->caridata("penggunaan WHERE id_pelanggan = '$id_pel' AND meter_akhir = '0'");
	if ($penggunaan == "") {
		$aksi->pesan('Data Bulan ini sudah diinput');
	}
	// Jika sedang dalam proses edit atau hapus
} elseif (isset($_GET['hapus']) or isset($_GET['edit'])) {
	$penggunaan = $aksi->caridata("penggunaan WHERE id_penggunaan = '$id'");
	$id_pel = $penggunaan['id_pelanggan'];
}

// Ambil data pelanggan dan tarif terkait
@$pelanggan = $aksi->caridata("pelanggan WHERE id_pelanggan = '$id_pel'");
@$tarif = $aksi->caridata("tarif WHERE id_tarif = '$pelanggan[id_tarif]'");
@$tarif_perkwh = $tarif['tarif_perkwh'];
@$id_guna = $penggunaan['id_penggunaan'];
@$mawal = $penggunaan['meter_awal'];
@$bulan = $penggunaan['bulan'];
@$tahun = $penggunaan['tahun'];

// Hitung bulan dan tahun berikutnya
if ($bulan == 12) {
	if ($bulan < 10) {
		$bln = ($bulan + 1);
		$next_bulan = "0" . $bln;
	} else {
		$next_bulan = $bulan + 1;
	}
	$next_tahun = $tahun + 1;
} else {
	if ($bulan < 10) {
		$bln = ($bulan + 1);
		$next_bulan = "0" . $bln;
	} else {
		$next_bulan = $bulan + 1;
	}
	$next_tahun = $tahun;
}
// echo $next_tahun."-".$next_bulan."-".$mawal."-".@$id_pel."<br>";

// Ambil nilai dari form input
@$id_pelanggan = $_POST['id_pelanggan'];
@$meter_akhir = $_POST['meter_akhir'];
@$meter_awal = $mawal;
@$tgl_cek = $_POST['tgl_cek'];
@$jumlah_meter = ($meter_akhir - $mawal);
@$jumlah_bayar = ($jumlah_meter * $tarif_perkwh);
@$id_penggunaan_next = $id_pel . $next_bulan . $next_tahun;

// Data penggunaan untuk bulan selanjutnya (otomatis)
@$field_next = array(
	'id_penggunaan' => $id_penggunaan_next,
	'id_pelanggan' => $id_pelanggan,
	'bulan' => $next_bulan,
	'tahun' => $next_tahun,
	'meter_awal' => $meter_akhir,
);

// Data untuk update meter_akhir bulan ini
@$field = array(
	'meter_akhir' => $meter_akhir,
	'tgl_cek' => $tgl_cek,
	'id_user' => $_SESSION['id_user'],
);

// Data untuk update meter_awal bulan berikutnya saat ubah
@$field_update = array('meter_awal' => $meter_akhir,);

// Data tagihan baru
@$field_tagihan = array(
	'id_pelanggan' => $id_pelanggan,
	'bulan' => $bulan,
	'tahun' => $tahun,
	'jumlah_meter' => $jumlah_meter,
	'tarif_perkwh' => $tarif_perkwh,
	'jumlah_bayar' => $jumlah_bayar,
	'status' => "Belum Bayar",
	'id_user' => $_SESSION['id_user'],
);

// Data tagihan update
@$field_tagihan_update = array(
	'jumlah_meter' => $jumlah_meter,
	'tarif_perkwh' => $tarif_perkwh,
	'jumlah_bayar' => $jumlah_bayar,
	'status' => "Belum Bayar",
	'id_user' => $_SESSION['id_user'],
);

// Proses penyimpanan data baru
if (isset($_POST['bsimpan'])) {
	if ($meter_akhir <= $meter_awal) {
		$aksi->pesan("Meter Akhir Tidak Mungkin Kurang dari Meter Awal");
	} else { // Simpan tagihan, update meter_akhir bulan ini, dan buat data penggunaan baru bulan depan
		$aksi->simpan("tagihan", $field_tagihan);
		$aksi->update($table, $field, "id_penggunaan = '$id_guna'");
		$aksi->simpan($table, array_merge($field_next, ['tgl_cek' => $tgl_cek, 'id_user' => $_SESSION['id_user']]));


		$aksi->alert("Data Berhasil Disimpan", $redirect);
	}
}

// Proses update data
if (isset($_POST['bubah'])) {
	// Update penggunaan bulan berikutnya, tagihan, dan meter_akhir bulan ini
	$aksi->update($table, $field_update, "id_penggunaan = '$id_penggunaan_next'");
	$aksi->update("tagihan", $field_tagihan_update, "id_pelanggan = '$id_pel' AND bulan = '$bulan' AND tahun = '$tahun'");
	$aksi->update($table, $field, $where);
	$aksi->alert("Data Berhasil Diubah", $redirect);
}

// Proses edit data
if (isset($_GET['edit'])) {
	$edit = $aksi->edit($table, $where);
}

// Proses hapus data
if (isset($_GET['hapus'])) {
	$aksi->update(
		"penggunaan",
		array(
			'meter_akhir' => 0,
			'tgl_cek' => "",
			'id_petugas' => "",
		),
		$where
	);

	// Hapus penggunaan bulan berikutnya dan tagihannya
	$aksi->hapus("penggunaan", "id_penggunaan = '$id_penggunaan_next'");
	$aksi->hapus("tagihan", "id_pelanggan = '$id_pel' AND bulan = '$bulan' AND tahun = '$tahun'");
	$aksi->alert("Data Berhasil Dihapus", $redirect);
}
// Proses pencarian data
if (isset($_POST['bcari'])) {
	$text = $_POST['tcari'];
	$cari = "WHERE id_pelanggan LIKE '%$text%' OR id_penggunaan LIKE '%$text%' OR meter_awal LIKE '%$text%' OR meter_akhir LIKE '%$text%' OR tahun LIKE '%$text%' OR nama_pelanggan LIKE '%$text%' OR nama_lengkap LIKE '%$text%'";

} else {
	// Hanya tampilkan data yang sudah memiliki meter_akhir
	$cari = " WHERE meter_akhir != 0";
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Pelanggan</title>
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<div class="panel panel-default">
						<?php if (!@$_GET['id']) { ?>
							<div class="panel-heading" style="color: white;">Input Penggunaan</div>
						<?php } else { ?>
							<div class="panel-heading" style="color: white;">Ubah Penggunaan - <?php echo @$id; ?></div>
						<?php } ?>
						<div class="panel-body">
							<form method="post">
								<div class="col-md-12">
									<div class="form-group">
										<label>ID Pelanggan</label>&nbsp;&nbsp;<span style="color:blue;font-size: 10px;">[TEKAN TAB]</span>
										<input type="text" name="id_pelanggan" class="form-control" placeholder="Masukan ID Pelanggan"  required value="<?php if (@$_GET['id'] == "") {
																																																																													echo @$id_pel;
																																																																												} else {
																																																																													echo @$edit['id_pelanggan'];
																																																																												} ?>" list="id_pel" onkeypress='return event.charCode >=48 && event.charCode <=57' <?php if (@$_GET['id']) {
																																																																																																																							echo "readonly";
																																																																																																																						} ?>>
										<datalist id="id_pel">
											<?php
											$a = mysqli_query($koneksi,"SELECT * FROM pelanggan");
											while ($b = mysqli_fetch_array($a)) { ?>
												<option value="<?php echo $b['id_pelanggan'] ?>"><?php echo $b['nama']; ?></option>
											<?php } ?>
										</datalist>
									</div>
									<div class="form-group">
										<label>Bulan Penggunaan</label>
										<input type="text" name="no_meter" class="form-control" placeholder="Bulan penggunaan" required readonly value="<?php if (@$_GET['id'] == "") {
																																																																			@$aksi->bulan(@$bulan);
																																																																			echo " " . @$tahun;
																																																																		} else {
																																																																			@$aksi->bulan(@$edit['bulan']);
																																																																			echo " " . @$edit['tahun'];
																																																																		} ?>">
									</div>
									<div class="form-group">
										<label>Meter Awal</label>
										<input type="text" name="meter_awal" class="form-control" placeholder="Meter Awal" required readonly value="<?php if (@$_GET['id'] == "") {
																																																																	echo @$mawal;
																																																																} else {
																																																																	echo @$edit['meter_awal'];
																																																																} ?>">
									</div>
									<div class="form-group">
										<label>Meter Akhir</label>
										<input type="text" name="meter_akhir" class="form-control" placeholder="Masukan Meter Akhir" required value="<?php echo @$edit['meter_akhir']; ?>" onkeypress='return event.charCode >=48 && event.charCode <=57'>
									</div>
									<div class="form-group">
										<label>Tanggal Pengecekan</label>
										<input type="date" name="tgl_cek" class="form-control" placeholder="Masukan Nama" required value="<?php echo @$edit['tgl_cek'] ?>">
									</div>

									<div class="form-group">
										<?php
										if (@$_GET['id'] == "") { ?>
											<input type="submit" name="bsimpan" class="btn btn-primary btn-lg btn-block" value="SIMPAN" style="border-radius: 20px;">
										<?php } else { ?>
											<input type="submit" name="bubah" class="btn btn-success btn-lg btn-block" value="UBAH">
										<?php } ?>

										<a href="?menu=penggunaan" class="btn btn-danger btn-lg btn-block" style="border-radius: 20px;">RESET</a>
									</div>
								</div>
							</form>
						</div>
						<div class="panel-footer" style="color: white; text-align: center;">
							&nbsp; LSP Serkom 2025
						</div>

					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading" style="color: white;"><b>Daftar Penggunaan</b></div>
					<div class="panel-body">
						<div class="col-md-12">
							<form method="post">
								<div class="input-group">
									<input type="text" name="tcari" class="form-control" value="<?php echo @$text ?>" placeholder="Masukan Keyword Pencarian (Kode Penggunaan, ID Pelanggan, Bulan[contoh : 01,09,12], Tahun, Nama Pelanggan, Nama Petugas) ......">
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
										<th>
											<center>No.</center>
										</th>
										<th>Kode Penggunaan</th>
										<th>ID Pelanggan</th>
										<th>Nama</th>
										<th>Bulan</th>
										<th>Meter Awal</th>
										<th>Meter Akhir</th>
										<th>Tanggal Cek</th>
										<th>Petugas</th>
										<!-- <th colspan="1">
											<center>Aksi</center>
										</th> -->
									</thead>
									<tbody>
										<?php
										$no = 0;
										$data = $aksi->tampil("qw_penggunaan", $cari, "ORDER BY tgl_cek DESC");
										if ($data == "") {
											$aksi->no_record(8);
										} else {
											foreach ($data as $r) {
												$cek = $aksi->cekdata("tagihan WHERE id_pelanggan = '$r[id_pelanggan]' AND bulan = '$r[bulan]' AND tahun = '$r[tahun]' AND status = 'Belum Bayar'");
												$no++; ?>

												<tr>
													<td align="center"><?php echo $no; ?>.</td>
													<td><?php echo $r['id_penggunaan'] ?></td>
													<td><?php echo $r['id_pelanggan'] ?></td>
													<td><?php echo $r['nama_pelanggan'] ?></td>
													<td><?php $aksi->bulan($r['bulan']);
															echo " " . $r['tahun']; ?></td>
													<td><?php echo $r['meter_awal'] ?></td>
													<td><?php echo $r['meter_akhir'] ?></td>
													<td><?php $aksi->format_tanggal($r['tgl_cek']); ?></td>
													<td><?php echo $r['nama_lengkap'] ?></td>
													<!-- <?php
													if ($cek == 0) { ?>
														<td colspan="2"></td>
													<?php } else { ?>
														<td align="center"><a href="?menu=penggunaan&hapus&id=<?php echo $r['id_penggunaan']; ?>"><span class="glyphicon glyphicon-trash"></span></a></td>
														<td align="center"><a href="?menu=penggunaan&edit&id=<?php echo $r['id_penggunaan']; ?>" ><span class="glyphicon glyphicon-edit"></span></a></td>
													<?php } ?> -->
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