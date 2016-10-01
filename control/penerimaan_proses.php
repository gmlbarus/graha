<?php
class Penerimaan_proses
{
	function __construct()
	{
		if ($_SESSION['type'] != 'penerimaan')
		{
			$_SESSION['gagal_login'] = 'Silakan login untuk masuk sebagai admin penerimaan';
			header('Location:' . base_url());
		}

		require_once 'view/view.php';
		$this->view = new view();

		require_once 'model/buku_model.php';
		$this->model = new buku_model();

		require_once 'model/penerimaan_model.php';
		$this->penerimaan_model = new penerimaan_model();
	}

	function tambah_obat_masuk()
	{
		# 1. insert obat_masuk
		$this->penerimaan_model->insert_obat_masuk($_POST['id_obat_masuk'], $_POST['id_supplier'], $_POST['id_obat'], $_POST['tanggal_masuk'], $_POST['jumlah_masuk'], $_POST['harga']);

		# 2. insert data stok
		$id_stok = $this->model->new_id('id_stok', 'tbl_stok', 'stok');
		$tanggal_kadaluarsa = date("Y-m-d", strtotime($_POST['tanggal_kadaluarsa']));
		$this->penerimaan_model->insert_stok($id_stok, $_POST['id_supplier'], $_POST['id_obat'], $tanggal_kadaluarsa, $_POST['jumlah_masuk']);

		# 3. redirect
		header("location:".base_url('penerimaan/obat_masuk'));
	}
}