<?php
/**
* 
*/
class Pemimpin_model
{
	
	function __construct()
	{
		# code...
		database_connect();
	}

	function get_stok_obat()
	{
		$query = "SELECT `id`, `nama`, `harga`, `stok`, `harga` * `stok` AS `total` FROM
					(SELECT a.id_obat AS `id`, `nama`, `harga`,
						(SELECT SUM(stok) FROM tbl_stok b WHERE b.id_obat = id) `stok`
						FROM tbl_obat a) AS tbl_gabungan

					";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;			
	}

	function get_laporan_obat($month = NULL, $year = NULL)
	{
		if (isset($month) && isset($year))
			$where = "MONTH(tanggal) = '{$month}' AND YEAR(tanggal) = '{$year}'";
		else
			$where = '1';

		$query = "SELECT a.id_obat AS `id`, `nama`,
					(SELECT SUM(jumlah) FROM tbl_obat_masuk b
						WHERE b.id_obat = id AND {$where}) AS `obat_masuk`,
					(SELECT SUM(jumlah) FROM tbl_obat_keluar c
						WHERE c.id_obat = id AND {$where}) AS `obat_keluar`,
					(SELECT SUM(stok) FROM tbl_stok d
						WHERE d.id_obat = id) AS `stok`
				FROM tbl_obat a;
				";
		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;
	}

	function get_obat_masuk($month, $year)
	{
		$query = "SELECT a.id_obat, nama, tanggal, jumlah
					FROM tbl_obat_masuk a
					LEFT JOIN tbl_obat b ON a.id_obat = b.id_obat
					WHERE MONTH(tanggal) = '{$month}' AND YEAR(tanggal) = '{$year}';
				";
		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;
	}

	function get_obat_keluar($month, $year)
	{
		$query = "SELECT a.id_obat, nama, tanggal, jumlah
					FROM tbl_obat_keluar a
					LEFT JOIN tbl_obat b ON a.id_obat = b.id_obat
					WHERE MONTH(tanggal) = '{$month}' AND YEAR(tanggal) = '{$year}';
				";
		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;
	}

	function get_obat()
	{
		$query = "SELECT id_obat, nama, satuan, harga
					FROM tbl_obat;
				";
		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;
	}

	function get_supplier()
	{
		$query = "SELECT *
					FROM tbl_suppliers;
				";
		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;
	}

	function get_pemesanan_obat($month, $year)
	{
		$query = "SELECT a.id_obat id, b.nama nama, c.nama supplier, tanggal, jumlah FROM tbl_order a
						LEFT JOIN tbl_obat b ON a.id_obat = b.id_obat
						LEFT JOIN tbl_suppliers c ON a.id_supplier = c.id_supplier 
					WHERE status = 'approved' AND MONTH(tanggal) = '{$month}' AND YEAR(tanggal) = '{$year}';
				";
		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;
	}

	function get_pengiriman_obat($month, $year)
	{
		$query = "SELECT c.id_obat id, c.nama nama, d.nama supplier, a.tanggal, a.jumlah FROM tbl_pengiriman a
						INNER JOIN tbl_order b ON a.id_order = b.id_order 
						LEFT JOIN tbl_obat c ON b.id_obat = c.id_obat
						LEFT JOIN tbl_suppliers d ON b.id_supplier = d.id_supplier  
					WHERE MONTH(a.tanggal) = '{$month}' AND YEAR(a.tanggal) = '{$year}';
				";
		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;
	}

	function get_permintaan_obat($month, $year)
	{
		$query = "SELECT a.id_obat, `nama`, `tanggal`, `status`, `jumlah` FROM tbl_order a
						LEFT JOIN tbl_obat b ON a.id_obat = b.id_obat
					WHERE MONTH(a.tanggal) = '{$month}' AND YEAR(a.tanggal) = '{$year}';
				";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;		
	}
}