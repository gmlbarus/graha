<?php
/*
 *---------------------------------------------------------------
 * Pengadaan Model
 *---------------------------------------------------------------
 *
 * Define basic Transaksi UI
 */

Class Pengadaan_model
{
	function __construct()
	{
		# pengadaan access Only

		database_connect();
	}

	function insert_supplier($id_supplier, $nama, $alamat, $telepon)
	{
		$query = "INSERT INTO `tbl_suppliers`(`id_supplier`, `nama`, `alamat`, `telepon`)
				VALUES ('$id_supplier}','{$nama}','{$alamat}','{$telepon}')";

		return mysql_query($query) OR die(mysql_error());			
	}

	function edit_supplier($nama, $alamat, $telepon, $id_supplier)
	{
		$query = "UPDATE `tbl_suppliers`
				SET `nama`='{$nama}',`alamat`='{$alamat}',`telepon`='{$telepon}'
				WHERE id_supplier = '{$id_supplier}'";

		return mysql_query($query) OR die(mysql_error());
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

	function get_single_stok_obat($id_obat)
	{
		$query = "SELECT `a`.`id_obat` as id, `nama`, `satuan`, `harga`, 
						(SELECT SUM(stok)
					     FROM tbl_stok b
					     WHERE b.id_obat = id) as stok
					FROM `tbl_obat` `a`
					WHERE `a`.`id_obat` = '{$id_obat}'
					LIMIT 1";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = '';

		while($row = mysql_fetch_object($array))
			$hasil = $row;
		return $hasil;			
	}

	/**
	 *---------------------------------------------------------------
	 * Pengadaan Model
	 *---------------------------------------------------------------
	 */

	function approve_order($id_supplier, $id_order)
	{
		$query = "UPDATE
				`tbl_order` SET `id_supplier`='{$id_supplier}',`status`='approved'
				WHERE id_order = '{$id_order}'";

		return mysql_query($query) or die(mysql_error());
	}

	function decline_order($id_order)
	{
		$query = "UPDATE
				`tbl_order` SET `status`='declined'
				WHERE id_order = '{$id_order}'";

		return mysql_query($query) or die(mysql_error());
	}


}