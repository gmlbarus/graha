<?php
class Supplier_model
{
	
	function __construct()
	{
		database_connect();
	}

	function change_leadTime($id_obat)
	{
		$query = "UPDATE `tbl_obat`
				SET `lead_time`='{$_POST['lead_time']}',`biaya_pesan`='{$_POST['biaya_pesan']}'
				WHERE id_obat = '{$id_obat}'";

		return mysql_query($query) OR die (mysql_error());
	}

	function insert_pengiriman($id_pengiriman, $id_order, $jumlah, $date)
	{
		$query = "INSERT INTO `tbl_pengiriman`(`id_pengiriman`, `id_order`, `jumlah`, `tanggal`)
					VALUES ('{$id_pengiriman}','{$id_order}','{$jumlah}','{$date}')";

		return mysql_query($query) OR die (mysql_error());			
	}

	/**
	 * useless
	 *--------------------------------------
	 * considering to be removed
	 */
	function insert_stok($id_stok, $id_supplier, $id_obat, $tanggal_kirim)
	{
		$query = "INSERT INTO `tbl_stok`(`id_stok`, `id_supplier`, `id_obat`, `tanggal_kirim`, `tanggal_kadaluarsa`, `stok`)
		VALUES ('{$id_stok}','{$id_supplier}','{$id_obat}','{$tanggal_kirim}','{$_POST['tanggal_kadaluarsa']}','{$_POST['stok']}')";

		return mysql_query($query) OR die (mysql_error());
	}

	function change_status($id_order, $jumlah)
	{
		$query = "UPDATE `tbl_order` SET `supplier_status`='approved', `jumlah_kirim`='{$jumlah}' WHERE id_order = '{$id_order}'";

		return mysql_query($query) OR die (mysql_error());
	}

	function get_obat_terkirim($id_supplier)
	{
		$query = "SELECT a.id_order, nama, a.jumlah, a.tanggal
				FROM tbl_pengiriman a
					LEFT OUTER JOIN tbl_order b
						ON a.id_order = b.id_order
					LEFT OUTER JOIN tbl_obat c
						ON b.id_obat = c.id_obat
				WHERE id_supplier = '{$id_supplier}'";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;
	}

	function get_permintaan_obat($id_supplier)
	{
		$query = "SELECT *
				FROM tbl_order a
					LEFT JOIN tbl_obat b
					ON a.id_obat = b.id_obat
				WHERE id_supplier = '{$id_supplier}' AND status = 'approved' AND
                		a.id_order NOT IN (SELECT id_order FROM tbl_pengiriman)";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;		
	}
}