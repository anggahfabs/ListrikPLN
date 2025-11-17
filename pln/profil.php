<?php
if (!isset($_GET['menu'])) {
	header('location:hal_utama.php?menu=profil');
}
include '../library/auth.php';

// Ambil data user berdasarkan session login
$petugas = $aksi->caridata("user WHERE id = '$_SESSION[id_user]'");

// Siapkan data dari form untuk diupdate ke tabel user
$field = array(
	'username' => @$_POST['username'],
	'password' => @$_POST['password'],
	'nama_lengkap' => @$_POST['nama_lengkap'],
	'alamat' => @$_POST['alamat'],
	'no_telepon' => @$_POST['no_telepon'],
	'jk' => @$_POST['jk'],
);

// Cek apakah username sudah dipakai user lain
@$cek_user = $aksi->cekdata("user WHERE username = '$_POST[username]' AND username != '$_SESSION[username]'");
if (isset($_POST['ubah'])) {
	if ($cek_user > 0) {
		$aksi->pesan("username sudah ada !!!");
	} else {
		// Update data user (petugas)
		$aksi->update("user", $field, "id = '$_SESSION[id_user]'");
		$aksi->alert("Data Berhasil diubah", "?menu=home");
		// Update session agar nama dan username baru langsung tampil
		$_SESSION['nama_lengkap'] = @$_POST['nama_lengkap'];
		$_SESSION['username_petugas'] = @$_POST['username'];
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>PROFIL</title>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<center style="color: white;">
								<h4>Ubah Data Diri</h4>
							</center>
						</div>
						<div class="panel-body">
							<div class="col-md-12">
								<form method="post">
									<div class="form-group">
										<label>ID Petugas</label>
										<input type="text" name="id" class="form-control" value="<?php echo $petugas['id'] ?>" readonly>
									</div>
									<div class="form-group">
										<label>Username</label>
										<input type="text" name="username" class="form-control" value="<?php echo $petugas['username'] ?>" required placeholder="Masukan username Anda">
									</div>
									<div class="form-group">
										<label>Password</label>
										<input type="password" name="password" class="form-control" value="<?php echo $petugas['password'] ?>" required placeholder="Masukan password Anda">
									</div>
									<div class="form-group">
										<label>Akses</label>
										<input type="text" class="form-control" value="<?php echo $petugas['role_id'] == 1 ? 'Petugas' : 'Agen'; ?>" readonly>

									</div>
									<div class="form-group">
										<label>Nama</label>
										<input type="text" name="nama_lengkap" class="form-control" value="<?php echo $petugas['nama_lengkap'] ?>" required placeholder="Masukan nama Anda">
									</div>
									<div class="form-group">
										<label>Jenis Kelamin</label>
										<select name="jk" class="form-control" required>
  <option value="Laki-Laki" <?= $petugas['jk'] == "Laki-Laki" ? "selected" : "" ?>>Laki-Laki</option>
  <option value="Perempuan" <?= $petugas['jk'] == "Perempuan" ? "selected" : "" ?>>Perempuan</option>
</select>

									</div>
									<div class="form-group">
										<label>Alamat</label>
										<textarea class="form-control" name="alamat" rows="3" required><?php echo $petugas['alamat']; ?></textarea>
									</div>
									<div class="form-group">
										<label>NO. Telpon</label>
										<input type="text" name="no_telepon" class="form-control" value="<?php echo $petugas['no_telepon']; ?>" required placeholder="Masukan No.Telepon Anda" onkeypress="return event.charCode >=48 && event.charCode <= 57">
									</div>
									<div class="form-group">
										<input type="submit" name="ubah" class="btn btn-primary btn-block btn-lg" value="UBAH DATA">
									</div>
								</form>
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