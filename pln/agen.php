<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include '../library/auth.php';

// Jika tidak ada parameter menu, redirect ke halaman agen
if (!isset($_GET['menu'])) {
	header("location:hal_utama.php?menu=agen");
}

$table = "user";
$id = @$_GET['id'];
$where = "md5(sha1(id)) = '$id'";
$redirect = "?menu=agen";

// Membuat ID agen otomatis (format: A + tanggal + nomor urut 3 digit)
$today = date("Ymd");
$agen = $aksi->caridata("user WHERE id LIKE 'A$today%' AND role_id = 2 ORDER BY id DESC");

// if (!empty($agen['id'])) {
// 	// Jika sudah ada agen hari ini, ambil 3 digit terakhir lalu +1
// 	$kode = (int)substr($agen['id'], -3) + 1;
// 	$id_agen = sprintf("A" . $today . '%03s', $kode);
// } else {
// 	// Jika belum ada agen hari ini, mulai dari 001
// 	$id_agen = "A" . $today . "001";
// }

// Cek apakah username sudah digunakan oleh agen lain
@$cek_user = $aksi->cekdata("user WHERE username = '$_POST[username]' AND role_id = 2");

// Data untuk tambah agen baru
$field = array(
	// 'id' => @$user['id'],
	'username' => @$_POST['username'],
	// 'password'      => $_POST['password'],
	'password' => isset($_POST['password']) && $_POST['password'] !== '' ? ($_POST['password']) : null,
	// Aktifin code ini kalo mau pake password hash
	// 'password' => isset($_POST['password']) && $_POST['password'] !== '' ? sha1($_POST['password']) : null,
	'nama_lengkap' => @$_POST['nama_lengkap'],
	'alamat' => @$_POST['alamat'],
	'no_telepon' => @$_POST['no_telepon'],
	'biaya_admin' => @$_POST['biaya_admin'],
	'role_id' => 2, // Role 2 = Agen
	// 'jk' => @$_POST['jk'],
);

// Data untuk update agen
$field_ubah = array(
	'username' => @$_POST['username'],
	'password' => isset($_POST['password']) && $_POST['password'] !== '' ? ($_POST['password']) : null,
	// Jika ingin menggunakan hash password, aktifkan baris di bawah ini
	// 'password' => isset($_POST['password']) && $_POST['password'] !== '' ? sha1($_POST['password']) : null,

	'nama_lengkap' => @$_POST['nama_lengkap'],
	'alamat' => @$_POST['alamat'],
	'no_telepon' => @$_POST['no_telepon'],
	'biaya_admin' => @$_POST['biaya_admin'],
	// 'jk' => @$_POST['jk'],
);

// Proses simpan data agen baru
if (isset($_POST['simpan'])) {
	if ($cek_user > 0) {
		// Jika username sudah ada, tampilkan pesan
		$aksi->pesan("username sudah ada !!!");
	} else {
		$aksi->simpan($table, $field);
		$aksi->alert("Data berhasil disimpan", $redirect);
	}
}

// Proses edit (mengambil data untuk ditampilkan di form)
if (isset($_GET['edit'])) {
	$edit = $aksi->edit($table, $where);
}

// Proses update data agen
if (isset($_POST['ubah'])) {
	if ($cek_user < 0) {
		// Validasi yang benar seharusnya cek jika username sudah ada dan bukan milik dirinya sendiri
		$aksi->pesan("username sudah ada !!!");
	} else {
		$aksi->update($table, $field_ubah, $where);
		$aksi->alert("Data berhasil diubah", $redirect);
	}
}

// Proses hapus data agen
if (isset($_GET['hapus'])) {
	$aksi->hapus($table, $where);
	$aksi->alert("Data berhasil dihapus", $redirect);
}
?>

	<div class="container-fluid" style="margin-top: 20px;">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<?php
							if (@$_GET['id'] == "") {
								echo "<span style='color: white; font-weight: bold;'>INPUT AGEN</span>";
							} else {
								echo "<span style='color: white; font-weight: bold;'>UBAH AGEN</span>";;
							}
							?>
						</div>
						<div class="panel-body">
							<div class="col-md-12">
								<form method="post">
									<div class="form-group">
										<label>ID Agen</label>
										<input type="text" name="id" class="form-control" placeholder="ID Agen terisi otomatis" value="<?php echo isset($edit['id']) ? $edit['id'] : ''; ?>" readonly required>
									</div>
									<div class="form-group">
										<label>Username</label>
										<input type="text" name="username" class="form-control" value="<?php echo @$edit['username'] ?>" required placeholder="Masukan username Agen" maxlength="30">
									</div>
									<div class="form-group">
										<label>Password</label>
										<input type="password" name="password" class="form-control" value="<?php echo @$edit['password'] ?>" required placeholder="Masukan password Agen" maxlength="30">
									</div>
									<div class="form-group">
										<label>Biaya Admin</label>
										<input type="text" name="biaya_admin" class="form-control" value="<?php echo @$edit['biaya_admin']; ?>" placeholder="Masukan Biaya Admin" required onkeypress="return event.charCode >=48 && event.charCode <= 57" maxlength="5">
									</div>
									<div class="form-group">
										<label>Nama</label>
										<input type="text" name="nama_lengkap" class="form-control" value="<?php echo @$edit['nama_lengkap'] ?>" required placeholder="Masukan nama Agen" maxlength="50">
									</div>
									<div class="form-group">
										<label>Alamat</label>
										<textarea class="form-control" name="alamat" rows="3" placeholder="Masukan Alamat"  required><?php echo @$edit['alamat']; ?></textarea>
									</div>
									<div class="form-group">
										<label>No Telp</label>
										<input type="text" name="no_telepon" class="form-control" value="<?php echo @$edit['no_telepon']; ?>" required placeholder="Masukan No.Telepon Agen" onkeypress="return event.charCode >=48 && event.charCode <= 57" maxlength="15">
									</div>
									<!-- <div class="single-input">
										<label for="jenis_kelamin">Jenis Kelamin</label>
										<select id="jenis_kelamin" name="jk" class="form-control" required>
											<option value="">-- Pilih Jenis Kelamin --</option>
											<option value="Laki-laki">Laki-laki</option>
											<option value="Perempuan">Perempuan</option>
										</select>
									</div> -->
									<div class="form-group" style="margin-top: 20px;">
										<?php
										if (@$_GET['id'] == "") { ?>
											<input type="submit" name="simpan" class="btn btn-primary btn-block btn-lg" value="SIMPAN">
										<?php } else { ?>
											<input type="submit" name="ubah" class="btn btn-success btn-block btn-lg" value="UBAH">
										<?php } ?>
										<a href="?menu=agen" class="btn btn-danger btn-lg btn-block">Reset</a>
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
						<div class="panel-heading" style="color: white;"><b>Daftar Agen</b></div>
						<div class="panel-body">
							<div class="col-md-12">
								<?php
								if (isset($_POST['bcari'])) {
									@$text = $_POST['tcari'];
									@$cari = "WHERE id LIKE '%$text%' OR nama_lengkap LIKE '%$text%' OR alamat LIKE '%$text%' OR no_telepon LIKE '%$text%' OR biaya_admin LIKE '%$text%' OR username LIKE '%$text%'";

								} else {
									$cari = "";
								}
								?>
								<form method="post">
									<div class="input-group">
										<input type="text" name="tcari" class="form-control" value="<?php echo @$text; ?>" placeholder="Masukan Keyword Pencarian ...">
										<div class="input-group-btn">
											<button type="submit" name="bcari" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>&nbsp;CARI</button>
											<button type="submit" name="refresh" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span>&nbsp;REFRESH</button>
										</div>
									</div>
								</form>
							</div>
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover">
										<thead>
											<tr class="info">
												<th>No.</th>
												<th>ID Agen</th>
												<th>Nama</th>
												<th>No.Telepon</th>
												<th>Alamat</th>
												<th>Biaya Admin</th>
												<th>Username</th>
												<th>Password</th>
												<th>Akses</th>
												<th colspan="2">Aksi</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$no = 0;
											$aksi->tampil("user", "WHERE role_id = 2", "ORDER BY id DESC");
											$a = $aksi->tampil("user", "WHERE role_id = 2", "ORDER BY id DESC");

											if ($a == "") {
												$aksi->no_record(11);
											} else {
												foreach ($a as $r) {
													$cek = $aksi->cekdata("pembayaran WHERE id_user = '$r[id]'");
													$no++;
											?>
													<tr>
														<td align="center"><?php echo $no; ?>.</td>
														<td><?php echo $r['id']; ?></td>
														<td><?php echo $r['nama_lengkap']; ?></td>
														<td><?php echo $r['no_telepon']; ?></td>
														<td><?php echo $r['alamat']; ?></td>
														<td><?php $aksi->rupiah($r['biaya_admin']); ?></td>
														<td><?php echo $r['username']; ?></td>
														<td><?php echo substr(md5($r['password']), 0, 10); ?></td>
														<td><?php echo $r['role_id']; ?></td>
														<!-- <td><?= $r['jk']; ?></td> -->
														

														<!-- Tombol Hapus selalu muncul -->
														<td align="center">
															<a href="?menu=agen&hapus&id=<?php echo md5(sha1($r['id'])); ?>"
																onclick="return confirm('Yakin akan menghapus data ini?')">
																<span class="glyphicon glyphicon-trash"></span>
															</a>
														</td>

														<!-- Tombol Edit -->
														<td align="center">
															<a href="?menu=agen&edit&id=<?php echo md5(sha1($r['id'])); ?>">
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