<?php

include '../library/auth.php';
if (!isset($_GET['menu'])) {
	header('location:hal_utama.php?menu=laporan');

	$bulanini = $_POST['bulan'];
	$tahunini = $_POST['tahun'];

	$cari = "WHERE MONTH(tgl_bayar) = '$bulanini' AND YEAR(tgl_bayar) ='$tahunini' AND id_user = '$_SESSION[id_user]'";
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Laporan</title>
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<!-- INI LAPORAN TARIF -->
				<?php
				if (isset($_GET['tarif'])) {
					$table = "tarif";
					$cari = "";
					$link_print = "print.php?tarif";
					$link_excel = "print.php?excel&tarif";
					$judul = "LAPORAN DAFTAR TARIF";
				?>
					<div class="panel panel-default">
						<div class="panel-heading" style="color: white;">
							<b>Laporan Daftar Tarif</b>
							<div class="pull-right">
								<a href="<?php echo $link_print ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-print"></div>&nbsp;&nbsp;<label>Cetak</label>
								</a>
								&nbsp;&nbsp;
								<a href="<?php echo $link_excel ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-floppy-save"></div>&nbsp;&nbsp;<label>Export Excel</label>
								</a>
							</div>
						</div>
						<div class="panel-body">
							<center><label><?php echo @$judul; ?></label></center>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<th>
											<center>No.</center>
										</th>
										<th>
											<center>Kode Tarif</center>
										</th>
										<th>
											<center>Golongan</center>
										</th>
										<th>
											<center>Daya</center>
										</th>
										<th>
											<center>Tarif/KWh</center>
										</th>
									</thead>
									<tbody>
										<?php
										$no = 0; // Inisialisasi nomor urut
										// Ambil data dari tabel (misalnya "tarif", "penggunaan", dsb) dengan kondisi pencarian dan urutkan berdasarkan kolom 'golongan' secara ascending
										$data = $aksi->tampil($table, $cari, "ORDER BY golongan ASC");
										// Jika data kosong (tidak ditemukan)
										if ($data == "") {
											// Tampilkan pesan "Data tidak ada!" dengan colspan 5 kolom
											$aksi->no_record(5);
										} else { // Jika data ada, lakukan perulangan untuk setiap record (baris data)
											foreach ($data as $r) {
												$no++; //Tambahkan nomor urut (digunakan untuk menampilkan nomor baris pada tabel)
										?>

												<tr>
													<td align="center"><?php echo $no; ?>.</td>
													<td align="center"><?php echo $r['kode_tarif'] ?></td>
													<td align="center"><?php echo $r['golongan'] ?></td>
													<td align="center"><?php echo $r['daya'] ?></td>
													<td align="right"><?php $aksi->rupiah($r['tarif_perkwh']) ?></td>
												</tr>

										<?php }
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- INI END LAPORAN TARIF -->

					<!-- INI LAPORAN PELANGGAN -->
				<?php } elseif (isset($_GET['pelanggan'])) { // Cek apakah URL mengandung parameter ?pelanggan
					$table = "pelanggan"; // Tentukan tabel yang digunakan
					$cari = ""; // tidak pakai filter pencarian
					$link_print = "print.php?pelanggan"; // Link untuk cetak laporan pelanggan
					$link_excel = "print.php?excel&pelanggan"; // Link untuk ekspor Excel laporan pelanggan
					$judul = "LAPORAN DAFTAR PELANGGAN"; // Judul laporan
				?>
					<div class="panel panel-default">
						<div class="panel-heading" style="color: white;">
							<b>Laporan Daftar Pelanggan</b>
							<div class="pull-right">
								<a href="<?php echo $link_print ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-print"></div>&nbsp;&nbsp;<label>Cetak</label>
								</a>
								&nbsp;&nbsp;
								<a href="<?php echo $link_excel ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-floppy-save"></div>&nbsp;&nbsp;<label>Export Excel</label>
								</a>
							</div>
						</div>
						<div class="panel-body">
							<center><label><?php echo @$judul; ?></label></center>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<th>
											<center>No.</center>
										</th>
										<th>
											<center>ID Pelanggan</center>
										</th>
										<th>
											<center>No.Meter</center>
										</th>
										<th>
											<center>Nama</center>
										</th>
										<th>
											<center>Alamat</center>
										</th>
										<th>
											<center>Tenggang</center>
										</th>
										<th>
											<center>Kode Tarif</center>
										</th>
									</thead>
									<tbody>
										<!-- Menampilkan daftar pelanggan dari tabel, lalu ambil data tarif terkait setiap pelanggan berdasarkan id_tarif  -->
										<?php
										$no = 0;
										$data = $aksi->tampil($table, $cari, "ORDER BY id_pelanggan");
										if ($data == "") {
											$aksi->no_record(9);
										} else {
											foreach ($data as $r) {
												$a = $aksi->caridata("tarif WHERE id_tarif = '$r[id_tarif]'");
												$no++; ?>
												<tr>
													<td align="center"><?php echo $no; ?>.</td>
													<td align="center"><?php echo $r['id_pelanggan'] ?></td>
													<td align="center"><?php echo $r['no_meter'] ?></td>
													<td><?php echo $r['nama'] ?></td>
													<td><?php echo $r['alamat'] ?></td>
													<td align="center"><?php echo $r['tenggang'] ?></td>
													<td align="center"><?php echo $a['kode_tarif'] ?></td>
												</tr>

										<?php }
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- INI END LAPORAN PELANGGAN -->

					<!-- INI LAPORAN AGEN -->
				<?php } elseif (isset($_GET['agen'])) {
					$table = "agen";
					$cari = "";
					$link_print = "print.php?agen";
					$link_excel = "print.php?excel&agen";
					$judul = "LAPORAN DAFTAR AGEN";
				?>
					<div class="panel panel-default">
						<div class="panel-heading" style="color: white;">
							<b>Laporan Daftar Agen</b>
							<div class="pull-right">
								<a href="<?php echo $link_print ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-print"></div>&nbsp;&nbsp;<label>Cetak</label>
								</a>
								&nbsp;&nbsp;
								<a href="<?php echo $link_excel ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-floppy-save"></div>&nbsp;&nbsp;<label>Export Excel</label>
								</a>
							</div>
						</div>
						<div class="panel-body">
							<center><label><?php echo @$judul; ?></label></center>

							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<th width="5%">
											<center>No.</center>
										</th>
										<th width="13%">
											<center>ID Agen</center>
										</th>
										<th width="20%">
											<center>Nama</center>
										</th>
										<th width="12%">
											<center>No.Telepon</center>
										</th>
										<th>
											<center>Alamat</center>
										</th>
										<th width="12%">
											<center>Biaya Admin</center>
										</th>
									</thead>
									<tbody>
										<!-- Menampilkan daftar agen (user dengan role 2) dan menghitung jumlah data pembayaran untuk masing-masing agen -->
										<?php
										$no = 0;
										$a = $aksi->tampil("user", "WHERE role_id = 2", "ORDER BY id ASC");

										if ($a == "") {
											$aksi->no_record(7);
										} else {
											foreach ($a as $r) {
												$cek = $aksi->cekdata(" pembayaran WHERE id_user = '$r[id]'");

												$no++;
										?>
												<tr>
													<td align="center"><?php echo $no; ?>.</td>
													<td align="center"><?php echo $r['id']; ?></td>
													<td><?php echo $r['nama_lengkap']; ?></td>
													<td align="center"><?php echo $r['no_telepon']; ?></td>
													<td><?php echo $r['alamat']; ?></td>
													<td align="right"><?php $aksi->rupiah($r['biaya_admin']); ?></td>
												</tr>

										<?php	}
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- INI END LAPORAN AGEN -->

					<!-- INI LAPORAN TAGIHAN BULAN -->

					<!-- Menampilkan laporan tagihan berdasarkan status, bulan, dan tahun yang dipilih oleh user -->
				<?php } elseif (isset($_GET['tagihan_bulan'])) {
					$data = ""; //inisiasi table kosong
					if (isset($_POST['bcari'])) { // Validasi input kosong
						if (empty($_POST['status']) || empty($_POST['bulan']) || empty($_POST['tahun'])) {
							echo "<script>alert('Semua kolom (Status, Bulan, Tahun) wajib diisi!'); window.location.href='?menu=laporan&tagihan_bulan';</script>";
							exit;
						}

						$table = "qw_tagihan"; // Gunakan view qw_tagihan sebagai sumber data
						$status = $_POST['status']; // Ambil status tagihan (Lunas / Belum)
						$bulanini = $_POST['bulan']; // Ambil bulan yang dipilih
						$tahunini = $_POST['tahun']; // Ambil tahun yang dipilih

						// Hindari SQL Injection dengan membersihkan input
						$status_safe = mysqli_real_escape_string($koneksi, $status);
						$bulan_safe = mysqli_real_escape_string($koneksi, $bulanini);
						$tahun_safe = mysqli_real_escape_string($koneksi, $tahunini);

						// Filter pencarian data berdasarkan status, bulan, dan tahun
						$cari = "WHERE status = '$status_safe' AND bulan = '$bulan_safe' AND tahun = '$tahun_safe'";

						// Ambil data hasil filter dari view qw_tagihan
						$data = $aksi->tampil($table, $cari, "");
						// Siapkan link cetak dan ekspor excel dengan parameter filter
						$link_print = "print.php?tagihan_bulan&status=$status&bulan=$bulanini&tahun=$tahunini";
						$link_excel = "print.php?excel&tagihan_bulan&status=$status&bulan=$bulanini&tahun=$tahunini";
						// Judul laporan yang ditampilkan di atas tabel
						$judul = "LAPORAN TAGIHAN " . strtoupper($status ?? '') . " BULAN " . strtoupper($bulanini ?? '') . " TAHUN " . ($tahunini ?? '');
					} else {
						@$data = ""; // Jika belum cari, data tetap kosong
					}
				?>
					<div class="panel panel-default">
						<div class="panel-heading" style="color: white;">
							<b>Laporan Tagihan Per-bulan</b>
							<div class="pull-right">
								<a href="<?php echo $link_print ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-print"></div>&nbsp;&nbsp;<label>Cetak</label>
								</a>
								&nbsp;&nbsp;
								<a href="<?php echo $link_excel ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-floppy-save"></div>&nbsp;&nbsp;<label>Export Excel</label>
								</a>
							</div>
						</div>
						<div class="panel-body">
							<div class="col-md-12">
								<form method="post">
									<div class="input-group">
										<div class="input-group-addon">Status</div>
										<select name="status" class="form-control" required>
											<option></option>
											<option value="Terbayar" <?php if (@$status == "Terbayar") {
																									echo "selected";
																								} ?>>Terbayar</option>
											<option value="Belum Bayar" <?php if (@$status == "Belum Bayar") {
																										echo "selected";
																									} ?>>Belum Bayar</option>
										</select>
										<div class="input-group-addon">Bulan</div>
										<select name="bulan" class="form-control">
											<option></option>
											<?php
											// Membuat dropdown <option> untuk memilih bulan (01–12)
											for ($a = 1; $a <= 12; $a++) {
												if ($a < 10) {
													$b = "0" . $a; // Jika angka < 10, tambahkan 0 di depan (misal: 01, 02)
												} else {
													$b = $a;
												} ?>

												<!-- Menampilkan opsi bulan, dan beri atribut selected jika sesuai dengan bulan yang sedang dipilih -->
												<option value="<?php echo $b; ?>" <?php if (@$b == @$bulanini) {
																														echo "selected"; // Menandai bulan yang sedang dipilih
																													} ?>>
													<!-- Menampilkan nama bulan (misal: Januari) -->
													<?php echo $aksi->bulan($b); ?>
												</option>


											<?php } ?>
										</select>
										<div class="input-group-addon" id="pri">Tahun</div>
										<select name="tahun" class="form-control">
											<option></option>
											<?php
											// Membuat dropdown tahun dari 2018 hingga 2030
											for ($a = 2025; $a < 2031; $a++) {
											?>
												<option value="<?php echo $a; ?>" <?php if (@$a == @$tahunini) {
																														echo "selected"; // Menandai tahun yang sedang dipilih
																													} ?>><?php echo @$a; ?></option>
											<?php } ?>
										</select>
										<div class="input-group-btn">
											<button type="submit" name="bcari" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>&nbsp;CARI</button>
											<a href="?menu=laporan&tagihan_bulan" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span>&nbsp;REFRESH</a>
										</div>
									</div>
								</form>
							</div>
							<?php
							$judul_status = isset($status) ? strtoupper($status) : ''; // Mengubah nilai status menjadi huruf besar jika ada, jika tidak kosong
							$judul_bulan = isset($bulanini) ? $aksi->bulan($bulanini) : ''; // Mengubah angka bulan menjadi nama bulan (misal: 08 -> Agustus) jika ada, jika tidak kosong
							$judul_tahun = isset($tahunini) ? $tahunini : ''; // Mengambil nilai tahun jika tersedia, jika tidak kosong
							?>

							<center>
								<label>Laporan Tagihan <br><?= $judul_status . " BULAN " . $judul_bulan . " TAHUN " . $judul_tahun; ?></label>
							</center>

							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover">
										<thead>
											<th>
												<center>No.</center>
											</th>
											<th>
												<center>ID Pelanggan</center>
											</th>
											<th>
												<center>Nama Pelanggan</center>
											</th>
											<th>
												<center>Bulan</center>
											</th>
											<th>
												<center>Jumlah Meter</center>
											</th>
											<th>
												<center>Jumlah Bayar</center>
											</th>
											<th>
												<center>Status</center>
											</th>
											<th>
												<center>Petugas</center>
											</th>
										</thead>
										<tbody>
											<?php
											$no = 0; // Inisialisasi nomor urut baris
											if ($data == "") {
												$aksi->no_record(8); // Jika data kosong, tampilkan pesan "Data tidak ada" dengan colspan 8 kolom
											} else {
												foreach ($data as $r) {  // Jika ada data, lakukan perulangan untuk menampilkannya baris per baris
													$no++;  // Tambahkan nomor urut

											?>

													<tr>
														<td align="center"><?php echo $no; ?>.</td>
														<td align="center"><?php echo $r['id_pelanggan'] ?></td>
														<td><?php echo $r['nama_pelanggan'] ?></td>
														<td><?php $aksi->bulan($r['bulan']);
																echo " " . $r['tahun']; ?></td>
														<td align="center"><?php echo $r['jumlah_meter'] ?></td>
														<td align="right"><?php $aksi->rupiah($r['jumlah_bayar']) ?></td>
														<td align="center"><?php echo $r['status']; ?></td>
														<td align="center"><?php echo $r['nama_lengkap']; ?></td>
													</tr>

											<?php }
											} ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- INI END LAPORAN TAGIHAN BULAN -->

					<!-- INI LAPORAN TUNGGAKAN -->
				<?php } elseif (isset($_GET['tunggakan'])) {
					$table = "qw_tagihan";
					$cari = "WHERE status = 'Belum Bayar'";
					$link_print = "print.php?tunggakan";
					$link_excel = "print.php?excel&tunggakan";
					$judul = "LAPORAN PELANGGAN YANG MEMILIKI TUNGGAKAN LEBIH DARI 3 BULAN";
				?>
					<div class="panel panel-default">
						<div class="panel-heading" style="color: white;">
							<b>Laporan Daftar Pelanggan Yang Memiliki Tunggakan</b>
							<div class="pull-right">
								<a href="<?php echo $link_print ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-print"></div>&nbsp;&nbsp;<label>Cetak</label>
								</a>
								&nbsp;&nbsp;
								<a href="<?php echo $link_excel ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-floppy-save"></div>&nbsp;&nbsp;<label>Export Excel</label>
								</a>
							</div>
						</div>
						<div class="panel-body">
							<div class="col-md-12">
								<center><label><?php echo @$judul; ?></label></center>
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover">
										<thead>
											<th>
												<center>No.</center>
											</th>
											<th>
												<center>ID Pelanggan</center>
											</th>
											<th>
												<center>Nama Pelanggan</center>
											</th>
											<th>
												<center>Alamat</center>
											</th>
											<th>
												<center>Banyak Tunggakan</center>
											</th>
											<th>
												<center>Bulan</center>
											</th>
											<th>
												<center>Total Meter</center>
											</th>
											<th>
												<center>Tarif/Kwh</center>
											</th>
											<th>
												<center>Total Tunggakan</center>
											</th>
										</thead>

										<tbody>
											<?php
											$no = 0;

											// Ambil semua data pelanggan dari database, diurutkan berdasarkan nama
											$data = $aksi->tampil("pelanggan", "", "ORDER BY nama ASC");
											if ($data == "") {
												$aksi->no_record(8); //Tampilkan pesan "Data tidak ada" jika data pelanggan kosong
											} else {
												foreach ($data as $r) { //Looping setiap data pelangga
													// Cek berapa tagihan pelanggan yang belum dibayar
													$cek = $aksi->cekdata("tagihan WHERE id_pelanggan = '$r[id_pelanggan]' AND status = 'Belum Bayar'");
											?>
													<?php
													if ($cek >= 3) { //Jika pelanggan memiliki 3 atau lebih tagihan yang belum dibayar
														$no++;

														//Tampilkan singkatan bulan dan dua digit tahun dari tagihan yang belum dibayar
														while ($bln = mysqli_fetch_array($bulan_query)) {
															$aksi->bulan_substr($bln['bulan']); // Cetak singkatan bulan (JAN, FEB, dst.)
															echo substr($bln['tahun'], 2, 2) . ","; //Cetak dua digit terakhir dari tahun, diikuti koma
														}
													?>
														<tr>
															<td align="center"><?php echo $no; ?>.</td>
															<td align="center"><?php echo $r['id_pelanggan'] ?></td>
															<td><?php echo $r['nama'] ?></td>
															<td align="left"><?php echo $r['alamat'] ?></td>
															<td align="center"><?php echo $sum['bln_tunggak']; ?>&nbsp;Bulan</td>
															<td align="center">
																<?php while ($bln = mysqli_fetch_array($bulan)) {
																	$aksi->bulan_substr($bln['bulan']);
																	echo substr($bln['tahun'], 2, 2) . ",";
																} ?>

															</td>
															<td align="center"><?php echo $sum['jml_meter'] ?></td>
															<td align="right"><?php $aksi->rupiah($sum['tarif_perkwh']); ?></td>
															<td align="right"><?php $aksi->rupiah($sum['jml_bayar']); ?></td>
														</tr>
											<?php }
												}
											} ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- INI END LAPORAN TUNGGAKAN -->

					<!-- INI LAPORAN RIWAYAT PENGGUNAAN -->
				<?php } elseif (isset($_GET['riwayat_penggunaan'])) {
					if (isset($_POST['bcari'])) {

						// Validasi input kosong
						if (empty($_POST['id_pelanggan']) || empty($_POST['tahun'])) {
							echo "<script>alert('ID Pelanggan dan Tahun harus diisi!');window.location.href='?menu=laporan&riwayat_penggunaan';</script>";
							exit;
						}

						// Ambil nilai dari form
						$id_pelanggan = $_POST['id_pelanggan'];
						$tahun = $_POST['tahun'];

						// Ambil data pelanggan
						$pelanggan = $aksi->caridata("pelanggan WHERE id_pelanggan = '$id_pelanggan'");

						// Query data penggunaan
						$cari = "WHERE id_pelanggan = '$id_pelanggan' AND tahun = '$tahun'";
						$data = $aksi->tampil("qw_tagihan", $cari, "ORDER BY bulan ASC");

						// Link cetak & export
						$link_print = "print.php?riwayat_penggunaan&id_pelanggan=$id_pelanggan&tahun=$tahun";
						$link_excel = "print.php?excel&riwayat_penggunaan&id_pelanggan=$id_pelanggan&tahun=$tahun";

						// Judul laporan
						$judul = "LAPORAN RIWAYAT PENGGUNAN: <br>$id_pelanggan - " . strtoupper($pelanggan['nama']) . " PADA TAHUN $tahun";
					} else {
						$data = "";
					}
				?>

					<div class="panel panel-default">
						<div class="panel-heading" style="color: white;">
							<b>Laporan Riwayat Penggunaan Per-tahun</b>
							<div class="pull-right">
								<a href="<?php echo $link_print ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-print"></div>&nbsp;&nbsp;<label>Cetak</label>
								</a>
								&nbsp;&nbsp;
								<a href="<?php echo $link_excel ?>" target="_blank" style="color: white;">
									<div class="glyphicon glyphicon-floppy-save"></div>&nbsp;&nbsp;<label>Export Excel</label>
								</a>
							</div>
						</div>
						<div class="panel-body">
							<div class="col-md-12">
								<form method="post">
									<div class="input-group">
										<div class="input-group-addon">ID Pelanggan</div>
										<input type="text" name="id_pelanggan" class="form-control" placeholder="Masukan ID Pelanggan" value="<?php echo @$id_pelanggan ?>" list="id_pel" onkeypress='return event.charCode >=48 && event.charCode <=57' <?php if (@$_GET['id']) {
																																																																																																																				echo "readonly";
																																																																																																																			} ?>>
										<datalist id="id_pel">
											<?php
											// Ambil semua data dari tabel pelanggan
											$a = mysqli_query($koneksi, "SELECT * FROM pelanggan");

											//Looping setiap data pelanggan untuk ditampilkan sebagai <option> dalam dropdown
											while ($b = mysqli_fetch_array($a)) { ?>
												<option value="<?php echo $b['id_pelanggan'] ?>">
													<?php echo $b['nama']; ?></option>
											<?php } ?>
										</datalist>
										<div class="input-group-addon" id="pri">Tahun</div>
										<select name="tahun" class="form-control">
											<option></option>
											<?php
											// Loop untuk membuat opsi tahun dari 2018 sampai 2030
											for ($a = 2025; $a < 2031; $a++) {
											?>
												<option value="<?php echo $a; ?>" <?php if (@$a == @$tahun) {
																														echo "selected";
																													} ?>><?php echo @$a; ?></option>
											<?php } ?>
										</select>
										<div class="input-group-btn">
											<button type="submit" name="bcari" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>&nbsp;Cari</button>
											<a href="?menu=laporan&riwayat_penggunaan" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span>&nbsp;Refresh</a>
										</div>
									</div>
								</form>
							</div>
							<center><label><?php echo @$judul; ?></label></center>
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover">
										<thead>
											<th>
												<center>No.</center>
											</th>
											<th>
												<center>ID Pelanggan</center>
											</th>
											<th>
												<center>Nama Pelanggan</center>
											</th>
											<th>
												<center>Bulan</center>
											</th>
											<th>
												<center>Meter Awal</center>
											</th>
											<th>
												<center>Meter Akhir</center>
											</th>
											<th>
												<center>Jumlah Meter</center>
											</th>
											<th>
												<center>Tarif/KWh</center>
											</th>
											<th>
												<center>Jumlah Bayar</center>
											</th>
										</thead>
										<tbody>
											<?php
											$no = 0;
											if ($data == "") {
												$aksi->no_record(9);
											} else {
												foreach ($data as $r) {
													$no++;
													$penggunaan = $aksi->caridata("penggunaan WHERE id_pelanggan = '$r[id_pelanggan]' AND bulan = '$r[bulan]' AND tahun = '$r[tahun]'");
											?>
													<tr>
														<td align="center"><?php echo $no; ?>.</td>
														<td align="center"><?php echo $r['id_pelanggan']; ?></td>
														<td align="left"><?php echo $r['nama_pelanggan']; ?></td>
														<td align="center"><?php $aksi->bulan($r['bulan']);
																								echo " " . $r['tahun']; ?></td>
														<td align="center"><?php echo $penggunaan['meter_awal']; ?></td>
														<td align="center"><?php echo $penggunaan['meter_akhir']; ?></td>
														<td align="center"><?php echo $r['jumlah_meter']; ?></td>
														<td align="right"><?php $aksi->rupiah($r['tarif_perkwh']); ?></td>
														<td align="right"><?php $aksi->rupiah($r['jumlah_bayar']); ?></td>
													</tr>
											<?php }
											}
											//Menghitung total jumlah_meter dan jumlah_bayar dari tagihan untuk pelanggan tertentu di tahun tertentu
											@$sum = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(jumlah_meter) as meter,SUM(jumlah_bayar) as bayar FROM tagihan WHERE id_pelanggan = '$id_pelanggan' AND tahun = '$tahun'"));

											?>
										</tbody>
										<tfoot>
											<tr>
												<!-- Menampilkan total jumlah_meter -->
												<td colspan="6" align="right">TOTAL METER :</td>
												<td align="center"><?php echo $sum['meter']; ?></td>
												<!--Menampilkan total jumlah_bayar dalam format rupiah -->
												<td align="right">TOTAL BAYAR :</td>
												<td align="right"><?php $aksi->rupiah($sum['bayar']); ?></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<!-- INI END LAPORAN RIWAYAT PENGGUNAAN -->

			</div>
		</div>
	</div>
</body>

</html>