<?php
/**
* 
*/
class Pengadaan_proses
{
	
	function __construct()
	{
		if ($_SESSION['type'] != 'pengadaan')
		{
			$_SESSION['gagal_login'] = 'Silakan login untuk masuk sebagai admin pengadaan';
			header('Location:' . base_url());
		}

		require_once 'view/view.php';
		$this->view = new view();

		require_once 'model/buku_model.php';
		$this->model = new buku_model();

		require_once 'model/pengadaan_model.php';
		$this->pengadaan_model = new pengadaan_model();
	}

	/*
	 *---------------------------------------------------------------
	 * Supplier
	 *---------------------------------------------------------------
	 *
	 * Define Supplier database change
	 */

 	function tambah_supplier()
	{
		$this->pengadaan_model->insert_supplier($_POST['id_supplier'], $_POST['nama'], $_POST['alamat'], $_POST['telepon']);

		header("location:".base_url('pengadaan/supplier'));
	}

	function hapus_supplier($id_supplier)
	{
		$this->model->delete('id_supplier', $id_supplier, 'tbl_suppliers');

		header("location:".base_url('pengadaan/supplier'));
	}

	function ubah_supplier($id_supplier)
	{
		$this->pengadaan_model->edit_supplier($_POST['nama'], $_POST['alamat'], $_POST['telepon'], $id_supplier);

		header("location:".base_url('pengadaan/supplier'));
	}


	/*
	 *---------------------------------------------------------------
	 * Supplier
	 *---------------------------------------------------------------
	 *
	 * Define Supplier database change
	 */
	function approve_order()
	{
		# 1. add supplier id and change status
		$this->pengadaan_model->approve_order($_POST['id_supplier'], $_POST['id_order']);

		# 2. redirect
		header("location:".base_url('pengadaan/approval'));
	}

	function decline_order($id_order)
	{
		# 1. Set admnin Status
		$this->pengadaan_model->decline_order($id_order);

		# 2. redirect
		header("location:".base_url('pengadaan/approval'));
	}
}