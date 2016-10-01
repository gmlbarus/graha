<?php
class Supplier
{
	
	function __construct()
	{
		if ($_SESSION['type'] != 'supplier')
		{
			$_SESSION['gagal_login'] = 'Silakan login untuk masuk sebagai supplier';
			header('Location:' . base_url());
		}

		require_once 'view/view.php';
		$this->view = new view();

		require_once 'model/buku_model.php';
		$this->model = new buku_model();

		require_once 'model/supplier_model.php';
		$this->supplier_model = new supplier_model();
	}

	function permintaan()
	{
		# 1. obtain id Supplier from session
		$id_supplier = $_SESSION['user_id'];

		# 2. Obtain data permintaan
		$data_order = $this->supplier_model->get_permintaan_obat($id_supplier);

		# 3. load obat view
		$this->view->load_index('supplier/supplier', array('order' => $data_order));
	}

	function terima_pesanan($id_order)
	{
		# 1. obtain data order where id
		$data_order = $this->model->get_single_join_where('tbl_order', 'tbl_obat', 'id_obat', 'id_obat', 'tbl_order.id_order', $id_order);

		# 2. load terima permintaan view
		$this->view->load_index('supplier/terima_pesanan', array('order' => $data_order));
	
	}

	function pengiriman()
	{
		# 1. obtain id Supplier from session
		$id_supplier = $_SESSION['user_id'];	

		# 1. obtain approved data order
		$data_kirim = $this->supplier_model->get_obat_terkirim($id_supplier);

		# 2. load view
		$this->view->load_index('supplier/pengiriman', array('pengiriman' => $data_kirim));
	}
}