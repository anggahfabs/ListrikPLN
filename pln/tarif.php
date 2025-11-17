<?php
if (!isset($_GET['menu'])) {
	header('location:hal_utama.php?menu=tarif');
}
// Include file autentikasi (untuk keamanan akses)
include '../library/auth.php';

// Nama tabel yang digunakan
$table = "tarif";
// Tangkap ID dari URL yang sudah di-hash
$id = @$_GET['id'];
$where = " md5(sha1(id_tarif)) = '$id'";
$redirect = "?menu=tarif"; //URL tujuan setelah aksi (redirect)

// Jika tombol edit ditekan (URL memiliki parameter 'edit'), ambil data yang akan diedit
if (isset($_GET['edit'])) {
	$edit = $aksi->edit($table, $where);
}

//untuk kebutuhan crud
@$golongan = $_POST['golongan'];
@$daya = $_POST['daya'];
@$tarif = $_POST['tarif'];
@$kode_tarif = $golongan . "/" . $daya; // Membuat kode tarif gabungan dari golongan dan daya

// Menyusun data dalam bentuk array untuk disimpan/diupdate
$data = array(
	'kode_tarif' => $kode_tarif,
	'golongan' => $golongan,
	'daya' => $daya,
	'tarif_perkwh' => $tarif,
);

// Mengecek apakah data dengan kode tarif yang sama sudah ada
$cek = $aksi->cekdata("tarif WHERE kode_tarif = '$kode_tarif'");

// Jika tombol simpan ditekan
if (isset($_POST['bsimpan'])) {
	if ($cek > 0) {
		$aksi->pesan("Data sudah ada");
	} else {
		// Simpan data ke database
		$aksi->simpan($table, $data);
		$aksi->alert("Data Berhasil Disimpan", $redirect);
	}
}

// Jika tombol edit ditekan, ambil ulang data yang akan diedit
if (isset($_GET['edit'])) {
	$edit = $aksi->edit($table, $where);
}

// Jika tombol ubah ditekan
if (isset($_POST['bubah'])) {
	// Cek apakah ada data lain dengan kode tarif yang sama
	@$cek = $aksi->cekdata("tarif WHERE kode_tarif = '$kode_tarif' AND kode_tarif != '$edit[kode_tarif]'");
	if ($cek > 0) {
		$aksi->pesan("Data sudah ada"); // Tampilkan pesan jika data duplikat ditemukan
	} else {
		$aksi->update($table, $data, $where); // Update data ke databas
		$aksi->alert("Data Berhasil Diubah", $redirect); //Tampilkan alert lalu redirect
	}
}

// Jika parameter 'hapus' ada di URL, hapus data berdasarkan kondisi WHERE
if (isset($_GET['hapus'])) {
	$aksi->hapus($table, $where);
	$aksi->alert("Data Berhasil Dihapus", $redirect);
}

// Jika tombol cari ditekan
if (isset($_POST['bcari'])) {
	$text = $_POST['tcari']; // Ambil input pencarian
	$cari = "WHERE id_tarif LIKE '%$text%' OR kode_tarif LIKE '%$text%' OR daya LIKE '%$text%' OR golongan LIKE '%$text%' OR tarif_perkwh LIKE '%$text%'";
} else { // Jika tidak mencari, tampilkan semua data
	$cari = ""; 
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Tarif</title>
</head>

<body>
	<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-4">
					<div class="panel panel-default">
						<?php if (!@$_GET['id']) { ?>
							<div class="panel-heading" style="color: white;"><b>Input Tarif</b></div>
						<?php } else { ?>
							<div class="panel-heading" style="color: white;">Ubah Tarif</div>
						<?php } ?>
						<div class="panel-body">
							<form method="post">
								<div class="col-md-12">
									<div class="form-group">
										<label>Golongan</label>
										<select name="golongan" class="form-control" required>
											<option value="">-- Pilih Golongan --</option>
											<option value="R1" <?php if (@$edit['golongan'] == 'R1') echo 'selected'; ?>>R-1</option>
											<option value="R2" <?php if (@$edit['golongan'] == 'R2') echo 'selected'; ?>>R-2</option>
											<option value="R3" <?php if (@$edit['golongan'] == 'R3') echo 'selected'; ?>>R-3</option>
										</select>
									</div>
									<div class="form-group">
										<label>Daya</label>
										<select name="daya" class="form-control" required>
											<option value="">-- Pilih Daya --</option>
											<option value="450VA" <?php if (@$edit['daya'] == '450VA') echo 'selected'; ?>>450VA</option>
											<option value="900VA" <?php if (@$edit['daya'] == '900VA') echo 'selected'; ?>>900VA</option>
											<option value="1300VA" <?php if (@$edit['daya'] == '1300VA') echo 'selected'; ?>>1300VA</option>
											<option value="2200VA" <?php if (@$edit['daya'] == '2200VA') echo 'selected'; ?>>2200VA</option>
											<option value="3500VA" <?php if (@$edit['daya'] == '3500VA') echo 'selected'; ?>>3500VA</option>
										</select>
									</div>
									<div class="form-group">
										<label>Tarif/KWH</label>
										<input type="text" name="tarif" class="form-control" placeholder="Masukan Tarif" required value="<?php echo @$edit['tarif_perkwh']; ?>" onkeypress='return event.charCode >=48 && event.charCode <=57'>
									</div>
									<div class="form-group">
										<?php
										if (@$_GET['id'] == "") { ?>
											<input type="submit" name="bsimpan" class="btn btn-primary btn-lg btn-block" value="SIMPAN" style="border-radius: 20px;">
										<?php } else { ?>
											<input type="submit" name="bubah" class="btn btn-success btn-lg btn-block" value="UBAH">
										<?php } ?>

										<a href="?menu=tarif" class="btn btn-danger btn-lg btn-block" style="border-radius: 20px;">RESET</a>
									</div>
								</div>
							</form>
						</div>
						<div class="panel-footer" style="color: white; text-align: center;">
							&nbsp; LSP Serkom 2025
						</div>

					</div>
				</div>
				<div class="col-md-8">
					<div class="panel panel-default">
						<div class="panel-heading" style="color: white;"><b>Daftar Tarif</b></div>
						<div class="panel-body">
							<div class="col-md-12">
								<form method="post">
									<div class="input-group">
										<input type="text" name="tcari" class="form-control" value="<?php echo @$text ?>" placeholder="Masukan Keyword Pencarian......">
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
											<th>Kode Tarif</th>
											<th>Golongan</th>
											<th>Daya</th>
											<th>Tarif/KWh</th>
											<th colspan="2">
												<center>AKSI</center>
											</th>
										</thead>
										<tbody>
											<?php
											$no = 0;
											$data = $aksi->tampil($table, $cari, "");
											if ($data == "") {
												$aksi->no_record(7);
											} else {
												foreach ($data as $r) {
													$no++;
											?>
													<tr>
														<td><?php echo $no; ?>.</td>
														<td><?php echo $r['kode_tarif']; ?></td>
														<td><?php echo $r['golongan']; ?></td>
														<td><?php echo $r['daya']; ?></td>
														<td><?php $aksi->rupiah($r['tarif_perkwh']); ?></td>

														<!-- Tombol Hapus (Selalu tampil + konfirmasi alert) -->
														<td align="center">
															<a href="?menu=tarif&hapus&id=<?php echo md5(sha1($r['id_tarif'])); ?>"
																onclick="return confirm('Yakin ingin menghapus data tarif ini?')">
																<span class="glyphicon glyphicon-trash"></span>
															</a>
														</td>

														<!-- Tombol Edit -->
														<td align="center">
															<a href="?menu=tarif&edit&id=<?php echo md5(sha1($r['id_tarif'])); ?>">
																<span class="glyphicon glyphicon-edit"></span>
															</a>
														</td>
													</tr>
											<?php
												}
											}
											?>
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
	</div>
	</div>
</body>

</html>