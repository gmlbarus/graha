<?php
/*
 *---------------------------------------------------------------
 * Penerimaan Model
 *---------------------------------------------------------------
 *
 * Define basic Transaksi UI
 */

Class Penerimaan_model
{
	function __construct()
	{
		# pengadaan access Only

		database_connect();
	}

	function get_stok_obat()
	{
		$query = "SELECT a.id_obat as id, `nama`, `satuan`, `harga`, 
						(SELECT SUM(stok)
					     FROM tbl_stok b
					     WHERE b.id_obat = id) as stok
					FROM tbl_obat a";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;			
	}

	function insert_obat_masuk($id_obat_masuk, $id_supplier, $id_obat, $tanggal, $jumlah, $harga)
	{
		$query = "INSERT INTO `tbl_obat_masuk`
				(`id_obat_masuk`, `id_supplier`, `id_obat`, `tanggal`, `jumlah`, `harga`)
				VALUES ('{$id_obat_masuk}','{$id_supplier}','{$id_obat}','{$tanggal}','{$jumlah}','{$harga}')";

		return mysql_query($query) or die(mysql_error());			
	}

	function update_obat($harga, $lead_time, $biaya_pesan, $id_obat)
	{
		$query = "UPDATE `tbl_obat` SET `harga`= {$harga},`lead_time`= {$lead_time},`biaya_pesan`={$biaya_pesan} WHERE id_obat = '{$id_obat}'";

		return mysql_query($query) or die(mysql_error());
	}

	function get_kebutuhan_perTahun($id_obat, $tahun)
	{
		$query = "SELECT SUM(jumlah) as jumlah FROM `tbl_obat_keluar` WHERE id_obat='{$id_obat}' AND YEAR(tanggal) = '{$tahun}' LIMIT 1";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil = $row->jumlah;
		return $hasil;
	}

	function insert_stok($id_stok, $id_supplier, $id_obat, $tanggal_kadaluarsa, $stok)
	{
		$query = "INSERT INTO `tbl_stok`(`id_stok`, `id_supplier`, `id_obat`, `tanggal_kadaluarsa`, `stok`)
					VALUES ('{$id_stok}','{$id_supplier}','{$id_obat}','{$tanggal_kadaluarsa}','{$stok}')";

		return mysql_query($query) OR die(mysql_error());			
	}


}