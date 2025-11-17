<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include '../library/auth.php';

// Jika tidak ada parameter menu, redirect ke menu petugas
if (!isset($_GET['menu'])) {
	header("location:hal_utama.php?menu=petugas");
}

// Inisialisasi variabel
$table = "user";
$id = @$_GET['id'];
// Membuat kondisi WHERE dengan md5(sha1(id)) untuk keamanan (meskipun ini tidak terlalu aman dibanding prepared statement)
$where = "md5(sha1(id)) = '$id'";
$redirect = "?menu=petugas";

// Membuat id petugas otomatis (format: P + tanggal + nomor urut 3 digit)
// $today = date("Ymd");
// $petugas = $aksi->caridata("user WHERE id LIKE '%$today%' ORDER BY id DESC");
// if (!empty($petugas['id'])) {
// 	$kode = substr($petugas['id'], 9, 3) + 1;
// } else {
// 	$kode = 1;
// }
// $id_petugas = sprintf("P" . $today . '%03s', $kode);


// Cek apakah username sudah ada di database (untuk validasi saat simpan)
@$cek_user = $aksi->cekdata("user WHERE username = '$_POST[username]'");

// Membuat array field untuk proses insert
$field = array(
	'id' => @$_POST['id'],
	'username' => @$_POST['username'],
	'password' => @$_POST['password'],
	'nama_lengkap' => @$_POST['nama'],
	'alamat' => @$_POST['alamat'],
	'no_telepon' => @$_POST['no'],
	'role_id' => 1, // role_id 1 = petugas/admin
	'jk' => @$_POST['jk'],
);

// Array field untuk proses update (tanpa id dan role_id)
$field_ubah = array(
	'username' => @$_POST['username'],
	'password' => @$_POST['password'],
	'nama_lengkap' => @$_POST['nama'],
	'alamat' => @$_POST['alamat'],
	'no_telepon' => @$_POST['no'],
	'jk' => @$_POST['jk'],
);

// Proses simpan data petugas baru
if (isset($_POST['simpan'])) {
	if ($cek_user > 0) {
		// Jika username sudah ada, tampilkan pesan
		$aksi->pesan("username sudah ada !!!");
	} else {
		// Simpan data ke database
		$aksi->simpan($table, $field);
		$aksi->alert("Data berhasil disimpan", $redirect);
	}
}

// Proses edit data (menampilkan data ke form)
if (isset($_GET['edit'])) {
	$edit = $aksi->edit($table, $where);
}

// Proses update data petugas
if (isset($_POST['ubah'])) {
	// Cek jika username yang diedit sama dengan username orang lain
	@$cek_user = $aksi->cekdata("user WHERE username = '$_POST[username]' AND username != '$edit[username]'");
	if ($cek_user > 0) {
		$aksi->pesan("Username sudah ada!");
	} else {
		$aksi->update($table, $field_ubah, $where);
		$aksi->alert("Data berhasil diubah", $redirect);
	}
}

// Proses hapus data petugas
if (isset($_GET['hapus'])) {
	$aksi->hapus($table, $where);
	$aksi->alert("Data berhasil dihapus", $redirect);
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Petugas</title>
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php
							if (@$_GET['id'] == "") {
								echo "<span style='color: white; font-weight: bold;'>INPUT PETUGAS</span>";
							} else {
								echo "<span style='color: white; font-weight: bold;'>UBAH PETUGAS</span>";
							}
							?>
						</div>
						<div class="panel-body">
							<div class="col-md-12">
								<form method="post">
									<div class="form-group">
										<label>ID Petugas</label>
										<input type="text" name="id" class="form-control" value="<?php if (@$_GET['id'] == "") {
																																								echo @$id;
																																							} else {
																																								echo $edit['id'];
																																							} ?>" readonly required>
									</div>
									<div class="form-group">
										<label>Username</label>
										<input type="text" name="username" class="form-control" value="<?php echo @$edit['username'] ?>" required placeholder="Masukan Username Petugas" maxlength="30">
									</div>
									<div class="form-group">
										<label>Password</label>
										<input type="password" name="password" class="form-control" value="<?php echo @$edit['password'] ?>" required placeholder="Masukan Password Petugas" maxlength="30">
									</div>
									<div class="single-input">
										<label for="jenis_kelamin">Jenis Kelamin</label>
										<select id="jenis_kelamin" name="jk" class="form-control" required>
											<option value="">-- Pilih Jenis Kelamin --</option>
											<option value="Laki-laki">Laki-laki</option>
											<option value="Perempuan">Perempuan</option>
										</select>
									</div>


									<div class="form-group">
										<label>Nama</label>
										<input type="text" name="nama" class="form-control" value="<?php echo @$edit['nama'] ?>" required placeholder="Masukan nama Petugas" maxlength="50">
									</div>
									<div class="form-group">
										<label>Alamat</label>
										<textarea class="form-control" name="alamat" placeholder="Masukan Alamat" rows="3" required><?php echo @$edit['alamat']; ?></textarea>
									</div>
									<div class="form-group">
										<label>NO. Telepon</label>
										<input type="text" name="no" class="form-control" value="<?php echo @$edit['no_telepon']; ?>" required placeholder="Masukan No.Telepon Petugas" onkeypress="return event.charCode >=48 && event.charCode <= 57" maxlength="15">
									</div>
									<div class="form-group">
										<?php
										if (@$_GET['id'] == "") { ?>
											<input type="submit" name="simpan" class="btn btn-primary btn-block btn-lg" value="SIMPAN" style="border-radius: 20px;">
										<?php } else { ?>
											<input type="submit" name="ubah" class="btn btn-success btn-block btn-lg" value="UBAH">
										<?php } ?>
										<a href="?menu=petugas" class="btn btn-danger btn-lg btn-block" style="border-radius: 20px;">Reset</a>
									</div>
								</form>
							</div>
						</div>
						<div class="panel-footer" style="color: white; text-align: center;">
							&nbsp; LSP Serkom 2025
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="panel panel-default">
						<div class="panel-heading" style="color: white;"><b>Daftar Petugas</b></div>
						<div class="panel-body">
							<div class="col-md-12">
								<?php
								if (isset($_POST['bcari'])) {
									@$text = $_POST['tcari'];
									@$cari = "WHERE id_petugas LIKE '%$text%' OR nama LIKE '%$text%' OR alamat LIKE '%$text%' OR no_telepon LIKE '%$text%' OR jk LIKE '%$text%' OR username LIKE '%$text%'";
								} else {
									$cari = "";
								}
								?>
								<form method="post">
									<div class="input-group">
										<input type="text" name="tcari" class="form-control" value="<?php echo @$text; ?>" placeholder="Masukan Keyword Pencarian ...">
										<div class="input-group-btn">
											<button type="submit" name="bcari" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>&nbsp;Cari</button>
											<button type="submit" name="refresh" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span>&nbsp;Refresh</button>
										</div>
									</div>
								</form>
							</div>
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover">
										<thead>
											<th>No.</th>
											<th>ID Petugas</th>
											<th>Nama</th>
											<th>No.Telepon</th>
											<th>Alamat</th>
											<th>JK</th>
											<th>Username</th>
											<th>Password</th>
											<th>Akses</th>
											<th colspan="2">Aksi</th>
										</thead>
										<tbody>
											<?php
											$no = 0;
											$a = $aksi->tampil($table, ($cari == "" ? "WHERE role_id = 1" : "$cari AND role_id = 1"), "ORDER BY id DESC");
											if ($a == "") {
												$aksi->no_record(11);
											} else {
												echo "<pre>Session ID Petugas: " . $_SESSION['id_user'] . "</pre>";
												foreach ($a as $r) {
													$cek = $aksi->cekdata(" penggunaan WHERE id_user = '$r[id]'");
													if ($r['id'] != $_SESSION['id_user']) {
														$no++;


											?>


														<tr>
															<td align="center"><?php echo $no; ?>.</td>
															<td><?php echo $r['id']; ?></td>
															<td><?php echo $r['nama_lengkap']; ?></td>
															<td><?php echo $r['no_telepon']; ?></td>
															<td><?php echo $r['alamat']; ?></td>
															<td><?= $r['jk']; ?></td>

															<td><?php echo $r['username']; ?></td>
															<td><?php echo substr(md5($r['password']), 0, 10); ?></td>
															<td><?= $r['role_id'] == 1 ? 'Petugas' : 'Agen' ?></td>

															<?php
															if ($cek == 0) { ?>
																<td align="center">
																	<a href="?menu=petugas&hapus&id=<?php echo md5(sha1($r['id'])); ?>" onclick="return confirm('Yakin Akan hapus data ini ?')">
																		<span class="glyphicon glyphicon-trash"></span>
																	</a>
																</td>
															<?php } else { ?>
																<td>&nbsp;</td>
															<?php } ?>
															<td align="center">
																<a href="?menu=petugas&edit&id=<?php echo md5(sha1($r['id'])); ?>">
																	<span class="glyphicon glyphicon-edit"></span>
																</a>
															</td>
														</tr>

											<?php	}
												}
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
	</div>
</body>

</html>