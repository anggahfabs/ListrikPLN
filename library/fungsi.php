<?php
// // Membuat class bernama "oop" yang berisi berbagai metode bantu untuk operasi CRUD dan utilitas lainnya
class oop
{

// Fungsi untuk menyimpan data ke dalam tabel
	public function simpan($table, $field)
	{
		global $koneksi; // Gunakan koneksi global

		$sql = "INSERT INTO $table SET ";
		foreach ($field as $key => $value) {
			$sql .= "$key = '$value',";
		}
		$sql = substr($sql, 0, -1); //hapus koma terakhir
		$query = mysqli_query($koneksi, $sql);

		// Jika query gagal, tampilkan pesan error dan SQL yang dijalankan
		if (!$query) {
			die("❌ Gagal Simpan ke $table: " . mysqli_error($koneksi) . "<br>SQL: $sql");
		}
	}

// Fungsi untuk menampilkan semua data dari tabel dengan kondisi tambahan (where, cari)
	function tampil($table, $where, $cari)
	{
		global $conn; // gunakan koneksi dari file konfigurasi
		$sql = mysqli_query($conn, "SELECT * FROM $table $where $cari");

		$jalan = [];
		while ($data = mysqli_fetch_array($sql)) {
			$jalan[] = $data;
		}

		return $jalan;
	}

// Fungsi untuk mengambil 1 baris data berdasarkan kondisi tertentu (edit)
	function edit($table, $where)
	{
		global $conn;
		$sql = "SELECT * FROM $table WHERE $where";
		$query = mysqli_query($conn, $sql);
		if (!$query) {
			die("Query Error: " . mysqli_error($conn));
		}
		$data = mysqli_fetch_array($query);
		return $data;
	}

// Fungsi untuk menghapus data berdasarkan kondisi
	function hapus($table, $where)
	{
		global $conn; // koneksi dari config/koneksi.php
		mysqli_query($conn, "DELETE FROM $table WHERE $where") or die(mysqli_error($conn));
	}

	// Fungsi untuk mengupdate data dalam tabel
	function update($table, array $field, $where)
	{
		global $koneksi; // tambahkan ini agar bisa akses koneksi dari luar fungsi

		$sql = "UPDATE $table SET ";
		foreach ($field as $key => $value) {
			$sql .= "$key = '$value',";
		}
		$sql = rtrim($sql, ',');
		$sql .= " WHERE $where";

		$jalan = mysqli_query($koneksi, $sql); // pakai mysqli_query dan $koneksi
	}

// Fungsi untuk mengambil 1 data pertama dari tabel (misalnya data settingan)
	public function caridata($table)
	{
		global $conn;
		$sql = "SELECT * FROM $table";
		$query = mysqli_query($conn, $sql);
		return mysqli_fetch_assoc($query);
	}

// Fungsi untuk menghitung jumlah data dalam tabel (digunakan untuk cek data sudah ada atau belum)
	public function cekdata($table)
	{
		global $conn;
		$sql = "SELECT COUNT(*) as jumlah FROM $table";
		$query = mysqli_query($conn, $sql);
		$data = mysqli_fetch_assoc($query);
		return $data['jumlah'];
	}

	// Fungsi untuk menampilkan pesan alert sederhana
	function pesan($pesan)
	{
		echo "<script>alert('$pesan');</script>";
	}

	// Fungsi untuk menampilkan pesan dan langsung redirect ke halaman lain
	function alert($pesan, $alamat)
	{
		echo "<script>alert('$pesan');document.location.href='$alamat'</script>";
	}

	function redirect($alamat)
	{
		echo "<script>document.location.href='$alamat'</script>";
	}

	// Fungsi untuk menampilkan pesan 'Data tidak ada' jika hasil query kosong
	function no_record($col)
	{
		echo "<tr><td colspan='$col' align='center'>Data tidak ada!</td></tr>";
	}

	// Fungsi untuk menampilkan format uang dalam bentuk Rupiah
	function rupiah($uang)
	{
		if (!is_numeric($uang)) {
			$uang = 0;
		}
		echo "Rp. " . number_format($uang, 0, ',', '.') . ",-";
	}

	// Fungsi untuk mengubah angka bulan (01–12) menjadi nama bulan lengkap
	function bulan($bulan)
	{
		switch ($bulan) {
			case '01':
				$bln = "Januari";
				break;
			case '02':
				$bln = "Februari";
				break;
			case '03':
				$bln = "Maret";
				break;
			case '04':
				$bln = "April";
				break;
			case '05':
				$bln = "Mei";
				break;
			case '06':
				$bln = "Juni";
				break;
			case '07':
				$bln = "Juli";
				break;
			case '08':
				$bln = "Agustus";
				break;
			case '09':
				$bln = "September";
				break;
			case '10':
				$bln = "Oktober";
				break;
			case '11':
				$bln = "November";
				break;
			case '12':
				$bln = "Desember";
				break;
			default:
				$bln = "";
				break;
		}
		echo $bln;
	}

	// Fungsi untuk mengubah angka bulan (01–12) menjadi singkatan 3 huruf
	function bulan_substr($bulan)
	{
		switch ($bulan) {
			case '01':
				$bln = "JAN";
				break;
			case '02':
				$bln = "FEB";
				break;
			case '03':
				$bln = "MAR";
				break;
			case '04':
				$bln = "APR";
				break;
			case '05':
				$bln = "MEI";
				break;
			case '06':
				$bln = "JUN";
				break;
			case '07':
				$bln = "JUL";
				break;
			case '08':
				$bln = "AGU";
				break;
			case '09':
				$bln = "SEP";
				break;
			case '10':
				$bln = "OKT";
				break;
			case '11':
				$bln = "NOV";
				break;
			case '12':
				$bln = "DES";
				break;
			default:
				$bln = "";
				break;
		}
		echo $bln;
	}

	// Fungsi untuk memformat tanggal lengkap dalam format "DD NamaBulan YYYY"
	function format_tanggal($tanggal)
	{
		$tahun = substr($tanggal, 0, 4);
		$bulan = substr($tanggal, 5, 2);
		$tanggal = substr($tanggal, 8, 2);
		switch ($bulan) {
			case '01':
				$bln = "Januari";
				break;
			case '02':
				$bln = "Februari";
				break;
			case '03':
				$bln = "Maret";
				break;
			case '04':
				$bln = "April";
				break;
			case '05':
				$bln = "Mei";
				break;
			case '06':
				$bln = "Juni";
				break;
			case '07':
				$bln = "Juli";
				break;
			case '08':
				$bln = "Agustus";
				break;
			case '09':
				$bln = "September";
				break;
			case '10':
				$bln = "Oktober";
				break;
			case '11':
				$bln = "November";
				break;
			case '12':
				$bln = "Desember";
				break;
			default:
				$bln = "";
				break;
		}
		echo $tanggal . " " . $bln . " " . $tahun;
	}

	// Fungsi untuk menampilkan nama hari berdasarkan angka (1–7)
	function hari($today)
	{
		switch ($today) {
			case '1':
				@$hari = "Senin";
				break;
			case '2':
				@$hari = "Selasa";
				break;
			case '3':
				@$hari = "Rabu";
				break;
			case '4':
				@$hari = "Kamis";
				break;
			case '5':
				@$hari = "Jumat";
				break;
			case '6':
				@$hari = "Sabtu";
				break;
			case '7':
				@$hari = "Minggu";
				break;
			default:
				@$hari = "";
				break;
		}
		echo @$hari;
	}

	// function login($table,$username,$password,$alamat){
	// 	@session_start();
	// 	$sql = mysql_query("SELECT * FROM $table WHERE username = '$username' AND password = '$password'");
	// 	$cek = mysql_num_rows($sql);
	// 	$data = mysql_fetch_array($sql);
	// 	if ($cek > 0) {
	// 		if ($table == "petugas") {
	// 			@$_SESSION['username_petugas'] = $data['username'];
	// 			@$_SESSION['id_petugas'] = $data['id_petugas'];
	// 			@$_SESSION['nama_lengkap'] = $data['nama'];
	// 			@$_SESSION['akses_petugas'] = $data['akses'];
	// 			$this->alert("Login Berhasil! Selamat datang ".$data['nama'],$alamat);
	// 		}elseif($table == "agen"){
	// 			@$_SESSION['username'] = $data['username'];
	// 			@$_SESSION['biaya_admin'] = $data['biaya_admin'];
	// 			@$_SESSION['id_agen'] = $data['id_agen'];
	// 			@$_SESSION['nama_lengkap'] = $data['nama'];
	// 			@$_SESSION['akses_agen'] = $data['akses'];
	// 			$this->alert("Login Berhasil! Selamat datang ".$data['nama'],$alamat);
	// 		}
	// 	}else{
	// 		$this->pesan("Username atau Password yang anda masukan salah!");
	// 	}
	// }

	// function upload($tempat)
	// {
	// 	@$alamatfile = $_FILES['foto']['tmp_name'];
	// 	@$namafile = $_FILES['foto']['name'];
	// 	move_uploaded_file($alamatfile, "$tempat/$namafile");
	// 	return $namafile;
	// }
}
?>