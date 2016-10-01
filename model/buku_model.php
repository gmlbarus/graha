<?php
/**
* 
*/
class Buku_model
{
	
	function __construct()
	{
		# code...
		database_connect();
	}

	//read

	function get_table($table)
	{
		$query = "SELECT * FROM {$table}";
		
		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();

		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		return $hasil;
	}

	function get_table_where($table, $field, $value)
	{
		$hasil = array();

		$query = "SELECT * FROM {$table} WHERE {$field} = '{$value}'";
		
		$array = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_object($array))
			$hasil[] = $row;

		return $hasil;
	}

	function get_single($table, $field, $value)
	{
		$query = "SELECT * FROM {$table} WHERE {$field} = '{$value}' LIMIT 1";
		
		$array = mysql_query($query) or die(mysql_error());
		$hasil = '';

		while($row = mysql_fetch_object($array))
			$hasil = $row;
		return $hasil;
	}

	function get_join($table_1, $table_2, $primary, $index)
	{
		$query = "SELECT * FROM {$table_1}
				LEFT JOIN {$table_2}
				ON {$table_1}.{$primary} = {$table_2}.{$index}";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();
		
		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		
		return $hasil;
	}

	function get_join_where($table_1, $table_2, $primary, $index, $field, $value)
	{
		$query = "SELECT * FROM {$table_1}
				LEFT JOIN {$table_2}
				ON {$table_1}.{$primary} = {$table_2}.{$index}
				WHERE {$field} = '{$value}'";
				
		$array = mysql_query($query) or die(mysql_error());
		$hasil = array();
		
		while($row = mysql_fetch_object($array))
			$hasil[] = $row;
		
		return $hasil;
	}

	// get two table joined
	function get_single_join_where($table_1, $table_2, $primary, $index, $field, $value)
	{
		$query = "SELECT * FROM {$table_1}
				LEFT JOIN {$table_2}
				ON {$table_1}.{$primary} = {$table_2}.{$index}
				WHERE {$field} = '{$value}'
				LIMIT 1";

		$array = mysql_query($query) or die(mysql_error());
		$hasil = '';
		
		while($row = mysql_fetch_object($array))
			$hasil = $row;
		
		return $hasil;
	}


	// fungsi add new id for selected table.
	function new_id($field, $table, $prefix)
	{
		$query = "SELECT MAX(RIGHT({$field}, 6)) as kd_max FROM {$table}";
		$array = mysql_query($query) OR die(mysql_error());
		while ($row = mysql_fetch_object($array))
			$hasil[] = $row;

		$kd = "";
		if(count($hasil) > 0)
		{
			foreach($hasil as $k)
			{
				$tmp = ((int)$k->kd_max)+1;
				$kd = sprintf("%06s", $tmp);
			}
		}
		else
		{
			$kd = "000001";
		}	
		return $prefix."".$kd;
	}

	function update($tabel, $update, $update_value, $field, $value)
	{
		$query = "UPDATE {$tabel} SET {$update} = '{$update_value}' WHERE $field = '{$value}'";

		return mysql_query($query) or die(mysql_error());
	}

	// Mengubah username dan password akun Toko Buku
	function ubah_data_akun_customer()
	{
		if ( ! empty($_POST['password']))
		{
			$pass = md5("{$_POST['password']}spkkmeans");
			$query = "UPDATE `tbl_user`
				SET `username` = '{$_POST['username']}', `password` = '{$pass}'
				WHERE id_user = '{$_POST['id_toko']}'
				";
		}
		else
		{
			$query = "UPDATE `tbl_user`
				SET `username` = '{$_POST['username']}'
				WHERE id_user = '{$_POST['id_toko']}'";
		}
		
		return mysql_query($query) or die(mysql_error());
	}

	//delete
	function delete($field, $value, $table)
	{
		$query = "DELETE FROM {$table} WHERE {$field} = '{$value}'";
		return mysql_query($query) or die(mysql_error());
	}
}
?>