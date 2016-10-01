<?php
/*
 *---------------------------------------------------------------
 * Gudang Model
 *---------------------------------------------------------------
 *
 * Define basic Transaksi UI
 */

class Master_model  
{
	
	function __construct()
	{
		# Gudang access Only

		database_connect();
	}

	function insert_obat($id_obat, $nama, $satuan, $harga, $minimal_stock, $kebutuhan, $lead_time, $biaya_pesan)
	{
		$query = "INSERT INTO `tbl_obat`(`id_obat`, `nama`, `satuan`, `harga`, `minimal_stock`, `kebutuhan`, `lead_time`, `biaya_pesan`) 
					VALUES ('{$id_obat}','{$nama}','{$satuan}','{$harga}','{$minimal_stock}','{$kebutuhan}','{$lead_time}','{$biaya_pesan}')";
	
		return mysql_query($query) OR die(mysql_error());
	}

	function edit_obat($id_obat)
	{
		$query = "UPDATE `tbl_obat`
				SET `nama`='{$_POST['nama']}',`satuan`='{$_POST['satuan']}',`harga`='{$_POST['harga']}',`minimal_stock`='{$_POST['minimal_stock']}',`kebutuhan`='{$_POST['kebutuhan']}',`lead_time`='{$_POST['lead_time']}',`biaya_pesan`='{$_POST['biaya_pesan']}'
				WHERE id_obat = '{$id_obat}'";

		return mysql_query($query) OR die(mysql_error());
	}

	function insert_user($id_user)
	{
		$username = mysql_escape_string($_POST['username']);
		$password = mysql_escape_string($_POST['password']);

		$pass = sha1("{$password}Y38ca8ia0saXjwrwCwhrWy");

		$query = "INSERT INTO `tbl_user`(`id_user`, `nama`, `username`, `password`, `level`)
				VALUES ('{$id_user}','{$_POST['nama']}','{$username}','{$pass}','{$_POST['level']}')";

		return mysql_query($query) OR die(mysql_error());		
	}

	function ubah_user($id_user, $nama, $username, $level)
	{
		$username = mysql_escape_string($username);

		$query = "UPDATE `tbl_user` SET `nama`='{$nama}',`username`='{$username}',`level`='{$level}' WHERE `id_user` = '{$id_user}'";

		return mysql_query($query) OR die(mysql_error());			
	}

	function ubah_password($password, $id_user)
	{
		$password = mysql_escape_string($password);
		$pass = sha1("{$password}Y38ca8ia0saXjwrwCwhrWy");

		$query = "UPDATE `tbl_user` SET `password` = '{$password}' WHERE `id_user` = '{$id_user}'";

		return mysql_query($query) OR die(mysql_error());
	}

	
	function insert_stok($id_stok, $id_supplier, $id_obat, $tanggal_kadaluarsa, $stok)
	{
		$query = "INSERT INTO `tbl_stok`(`id_stok`, `id_supplier`, `id_obat`, `tanggal_kadaluarsa`, `stok`)
					VALUES ('{$id_stok}','{$id_supplier}','{$id_obat}','{$tanggal_kadaluarsa}','{$stok}')";

		return mysql_query($query) OR die(mysql_error());			
	}

	/*
	 *---------------------------------------------------------------
	 * Gudang Model
	 *---------------------------------------------------------------
	 *
	 * Obtain obat data and its stok
	 */
	function get_obat()
	{
		$query = "SELECT a.id_obat as `id`, `nama`, `satuan`, `harga`, `eoq`, `rop`, `minimal_stock`, 
						(SELECT SUM(stok)
					     FROM tbl_stok b
					     WHERE b.id_obat = id) as stok
					FROM tbl_obat a;
					";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;			
	}

/*---------------------------------------------------------------*/	

/*
 *---------------------------------------------------------------
 * Transaksi Model (shouldn't be here)
 *---------------------------------------------------------------
 *
 * Should remove to transaksi_model class soon.
 */

	function get_obatHabis()
	{
		$query = "SELECT * FROM 
					(SELECT id_obat as id, rop, nama, satuan, safety_stock, (SELECT SUM(STOK) FROM tbl_stok WHERE id_obat = id) as stok
					FROM `tbl_obat`) as stoks 
				WHERE stok IS NULL OR stok <= (rop + safety_stock) ";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;
	}

	function get_table_stok($id_obat)
	{
		$query = "SELECT `id_stok`, `stok` FROM `tbl_stok` WHERE `id_obat` = '{$id_obat}'
					ORDER BY `tanggal_kadaluarsa`  ASC";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;			
	}

	function insert_obat_keluar($id_obatKeluar, $id_obat, $tgl, $jumlah)
	{
		$query = "INSERT INTO `tbl_obat_keluar`(`id_obat_keluar`, `id_obat`, `tanggal`, `jumlah`)
				VALUES ('{$id_obatKeluar}','{$id_obat}','{$tgl}','{$jumlah}')";

		return mysql_query($query) OR die(mysql_error());		
	}

	function insert_order($id_order)
	{
		$query = "INSERT INTO `tbl_order`
				(`id_order`, `id_obat`, `jumlah`, `tanggal`)
				VALUES ('{$id_order}','{$_POST['id_obat']}','{$_POST['eoq']}','".date('Y-m-d')."')";

		return mysql_query($query) OR die(mysql_error());		
	}

	function ubah_stok_obat($sisa, $id_stok)
	{
		$query = "UPDATE `tbl_stok`
				SET `stok`= '{$sisa}'
				WHERE id_stok = '{$id_stok}'";

		return mysql_query($query) OR die(mysql_error());			
	}

	function insert_obat_masuk($id_obat_masuk, $id_supplier, $id_obat, $tanggal, $jumlah, $harga)
	{
		$query = "INSERT INTO `tbl_obat_masuk`
				(`id_obat_masuk`, `id_supplier`, `id_obat`, `tanggal`, `jumlah`, `harga`)
				VALUES ('{$id_obat_masuk}','{$id_supplier}','{$id_obat}','{$tanggal}','{$jumlah}','{$harga}')";

		return mysql_query($query) or die(mysql_error());			
	}
}