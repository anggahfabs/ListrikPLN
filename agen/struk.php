<?php  
	include '../config/koneksi.php';
	include '../library/fungsi.php';
	include '../library/auth.php';

	// session_start();
	$aksi = new oop();

	// Ambil parameter dari URL
	$id_pelanggan = $_GET['id_pelanggan'];
	$bulan = $_GET['bulan'];
	$tahun = $_GET['tahun'];

	// Ambil data dari view dan tabel
	$pembayaran = $aksi->caridata("qw_pembayaran WHERE id_pelanggan = '$id_pelanggan' AND bulan_bayar = '$bulan' AND tahun_bayar = '$tahun'");

	$penggunaan  = $aksi->caridata("penggunaan WHERE id_pelanggan = '$id_pelanggan' AND bulan = '$bulan' AND tahun = '$tahun'");
	$pelanggan   = $aksi->caridata("pelanggan WHERE id_pelanggan = '$id_pelanggan'");
	$tarif       = $pelanggan && isset($pelanggan['id_tarif']) ? $aksi->caridata("tarif WHERE id_tarif = '{$pelanggan['id_tarif']}'") : null;

	// Tampilkan pesan error dan debugging jika data tidak lengkap
	if (!$pembayaran || !$pelanggan || !$penggunaan || !$tarif) {
	    echo "<h2 style='color:red'>❌ Data tidak ditemukan atau tidak lengkap!</h2>";
	    echo "<pre>";
	    echo "ID Pelanggan: $id_pelanggan\n";
	    echo "Bulan: $bulan\n";
	    echo "Tahun: $tahun\n\n";

	    echo "--- PEMBAYARAN ---\n";
	    var_dump($pembayaran);
	    echo "\n--- PENGGUNAAN ---\n";
	    var_dump($penggunaan);
	    echo "\n--- PELANGGAN ---\n";
	    var_dump($pelanggan);
	    echo "\n--- TARIF ---\n";
	    var_dump($tarif);
	    echo "</pre>";
	    exit;
	}
?>




<!DOCTYPE html>
<html>
<head>
	<title>Cetak Struk <?php echo $pembayaran['id_pembayaran']; ?></title>
	<style type="text/css">
		/* #trans{
			
		}	 */
	</style>
</head>
<body onload="window.print()"  style="font-family:monospace;" >
	<table>
		<tr>
			<td colspan="6" align="center"><center>Struk Pembayaran Tagihan Listrik</center></td>
		</tr>
		<br>
		<tr>
			<td align="left">IDPEL</td>
			<td align="left">:</td>
			<td align="left"><?php echo $pembayaran['id_pelanggan']; ?></td>
			<td>&nbsp;&nbsp;&nbsp;</td>
			<td align="left">BL/TH</td>
			<td align="left">:</td>
			<td align="left"><?php $aksi->bulan_substr($bulan);echo substr($tahun, 2,2); ?></td>
		</tr>
		<tr>
			<td align="left">Nama</td>
			<td align="left">:</td>
			<td align="left"><?php echo $pelanggan['nama']; ?></td>
			<td>&nbsp;&nbsp;&nbsp;</td>
			<td align="left">Stand Meter</td>
			<td align="left">:</td>
			<td align="left"><?php echo $penggunaan['meter_awal']."-".$penggunaan['meter_akhir'];?></td>
		</tr>
		<tr>
			<td align="left">Tarif/Daya</td>
			<td align="left">:</td>
			<td align="left"><?php echo $tarif['kode_tarif']; ?></td>
		</tr>
		<tr>
			<td align="left">RP. TAG PLN</td>
			<td align="left">:</td>
			<td align="left"><?php echo $aksi->rupiah($pembayaran['jumlah_bayar']); ?></td>

		</tr>
		<tr>
			<td align="left">JFA REF</td>
			<td align="left">:</td>
			<?php
$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : '';
$hash = strtoupper(sha1($pembayaran['id_pembayaran'] . $id_user));
?>
<td align="left"><?php echo $hash; ?></td>

		<br>
		<tr>
			<td colspan="6" align="center"><center>PLN Menyatakan struk ini sebagai bukti pembayaran yang sah</center></td>
		</tr>
		<tr>
			<td align="left">Admin Bank</td>
			<td align="left">:</td>
			<td align="left"><?php echo $aksi->rupiah($pembayaran['biaya_admin']) ?></td>
		</tr>
		<tr>
			<td align="left">Total Bayar</td>
			<td align="left">:</td>
			<td align="left"><?php echo $aksi->rupiah($pembayaran['total_akhir']) ?></td>
		</tr>
		<tr>
			<td colspan="6" align="center">Terima Kasih<td>
		</tr>
		<tr>
			<td colspan="6" align="center">Rincian Tagihan dapat diakses di www.aplikasilistrik.co.id, Informasi Hubungi Call Center:000</td>
		</tr>
		<tr>
			<td colspan="6" align="center">Aplikasi Pembayaran Listrik / <?php echo $_SESSION['nama_lengkap']."/".$pembayaran['waktu_bayar']; ?></td>
		</tr>
	</table>
</body>
</html>