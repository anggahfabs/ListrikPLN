<?php
include '../library/auth.php';  
// Jika parameter GET 'menu' tidak ada, arahkan ke halaman profil secara default
	if (!isset($_GET['menu'])) {
	 	header('location:hal_utama.php?menu=profil');
	}

// Ambil data user/agen yang sedang login dari database, berdasarkan session id_user
	$agen = $aksi->caridata("user WHERE id = '$_SESSION[id_user]'");

	// Ambil data input dari form POST untuk diolah ke dalam array field
	$field = array(
		'username'=>@$_POST['username'],
		'password'=>@$_POST['password'],
		'nama_lengkap'=>@$_POST['nama_lengkap'],
		'alamat'=>@$_POST['alamat'],
		'no_telepon'=>@$_POST['no_telepon'],
		'biaya_admin'=>@$_POST['biaya_admin'],
	);

	// Cek apakah username yang dimasukkan sudah digunakan oleh user lain
	@$cek_user = $aksi->cekdata("user WHERE username = '$_POST[username]' AND username != '$_SESSION[username]'");
	
	// Cek apakah username yang dimasukkan sudah digunakan oleh user lain
	if (isset($_POST['ubah'])) {
		// Jika username sudah dipakai oleh orang lain (selain user saat ini), tampilkan pesan error
		if($cek_user > 0){
			$aksi->pesan("username sudah ada !!!");
		}else{
			// Update data user ke tabel 'user' berdasarkan id dari session
			$aksi->update("user",$field,"id = '$_SESSION[id_user]'");
			// Tampilkan alert dan redirect ke halaman home/dashboard
			$aksi->alert("Data Berhasil diubah","?menu=home");
			// Perbarui juga data session agar sesuai dengan data terbaru
			$_SESSION['nama_lengkap']=@$_POST['nama_lengkap'];
			$_SESSION['username']=@$_POST['username'];
			$_SESSION['biaya_admin']=@$_POST['biaya_admin'];
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pofil</title>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading"><center style="color: white;"><h4>Ubah Data Diri</h4></center></div>
						<div class="panel-body">
							<div class="col-md-12">
								<form method="post">
									<div class="form-group">
										<label>ID Agen</label>
										<input type="text" name="id" class="form-control" value="<?php echo $agen['id'] ?>" readonly>
									</div>
									<div class="form-group">
										<label>Username</label>
										<input type="text" name="username" class="form-control" value="<?php echo $agen['username'] ?>" required placeholder="Masukan username Anda" maxlength="30"> 
									</div>
									<div class="form-group">
										<label>Password</label>
										<input type="password" name="password" class="form-control" value="<?php echo $agen['password'] ?>" required placeholder="Masukan password Anda" maxlength="30"> 
									</div>
									<div class="form-group">
										<label>Biaya Admin</label>
										<input type="text" name="biaya_admin"  class="form-control" value="<?php echo $agen['biaya_admin']; ?>" placeholder="Masukan Biaya Admin" required onkeypress="return event.charCode >=48 && event.charCode <= 57" maxlength="5">
									</div>
									<div class="form-group">
										<label>Akses</label>
										<input type="text" class="form-control" value="<?php echo $agen['role_id'] == 2 ? 'Agen' : 'Petugas'; ?>" readonly>

									</div>
									<div class="form-group">
										<label>Nama</label>
										<input type="text" name="nama_lengkap" class="form-control" value="<?php echo $agen['nama_lengkap'] ?>" required placeholder="Masukan Nama Anda" maxlength="50"> 
									</div>
									<div class="form-group">
										<label>Alamat</label>
										<textarea class="form-control" name="alamat" rows="3" required><?php echo $agen['alamat']; ?></textarea>
									</div>
									<div class="form-group">
										<label>NO. Telepon</label>
										<input type="text" name="no_telepon" class="form-control" value="<?php echo $agen['no_telepon']; ?>" required placeholder="Masukan No.Telepon Anda" onkeypress="return event.charCode >=48 && event.charCode <= 57" maxlength="15">
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