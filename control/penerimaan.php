<?php
class Penerimaan
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

	function stok()
	{
		# 1. Obtain data obat
		$data_stok = $this->penerimaan_model->get_stok_obat();

		# 2. load obat view
		$this->view->load_index('penerimaan/stok', array('obat' => $data_stok));
	}

	function obat_masuk()
	{
		# 1. Obtain data tbl_obat_masuk
		$obat_masuk = $this->model->get_join('tbl_obat_masuk', 'tbl_obat', 'id_obat', 'id_obat');

		# 2. load obat_masuk view
		$this->view->load_index('penerimaan/obat_masuk', array('obat_masuk' => $obat_masuk));
	}

	function tambah_obat_masuk()
	{
		# 1. generate new obat masuk id
		$id_obat_masuk = $this->model->new_id('id_obat_masuk', 'tbl_obat_masuk', 'msuk');

		# 2. obtain data supplier
		$data_supplier = $this->model->get_table('tbl_suppliers');

		# 3. obtain data obat
		$data_obat = $this->model->get_table('tbl_obat');

		# 4. load obat masuk view
		$this->view->load_index('penerimaan/tambah_obat_masuk', array('id_obat_masuk' => $id_obat_masuk, 'data_supplier' => $data_supplier, 'data_obat' => $data_obat));
	}
}