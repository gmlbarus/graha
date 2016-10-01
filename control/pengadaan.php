<?php
class Pengadaan
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

	function supplier()
	{ 
		# 1. Obtain data supplier
		$data_supplier = $this->model->get_table('tbl_suppliers');

		# 2. Load supplier view
		$this->view->load_index('master/suppliers', array('supplier' => $data_supplier));
	}

	function stok()
	{
		# 1. Obtain data stok joined with tbl_obat
		$data_stok = $this->pengadaan_model->get_stok_obat();

		# 2. load stok view
		$this->view->load_index('pengadaan/stok', array('data_stok' => $data_stok));
	}

	function view_stok($id_obat)
	{
		# 1. Obtain data stok joined with tbl_obat
		$data_stok = $this->pengadaan_model->get_single_stok_obat($id_obat);

		# 2. load stok view
		$this->view->load_index('pengadaan/view_stok', array('data_stok' => $data_stok));	
	}

	function approval()
	{
		# 1. obtain data order joined tbl_obat
		$data_approval = $this->model->get_join_where('tbl_order', 'tbl_obat', 'id_obat', 'id_obat', 'status', 'pending');

		# 2. load approve view
		$this->view->load_index('pengadaan/approval', array('data_approval' => $data_approval));
	}

	/*
	 *---------------------------------------------------------------
	 * Off-menu UI
	 *---------------------------------------------------------------
	 *
	 * Define basic off-menu UI
	 */
	
	function tambah_supplier()
	{
		# 1. Obtain new ID for tbl_supplier
		$new_id_supplier = $this->model->new_id('id_supplier', 'tbl_suppliers', 'supl');

		# 2. load tambah_supplier view
		$this->view->load_index('master/tambah_supplier', array('id_supplier' => $new_id_supplier));
	}

	function edit_supplier($id_supplier)
	{
		# 1. obtain data supplier from id_supplier
		$data_supplier = $this->model->get_single('tbl_suppliers', 'id_supplier', $id_supplier);

		# 2. load edit_supplier view
		$this->view->load_index('master/ubah_supplier', array('supplier' => $data_supplier));
	}

	function view_supplier($id_supplier)
	{
		# 1. obtain data supplier from id_supplier
		$data_supplier = $this->model->get_single('tbl_suppliers', 'id_supplier', $id_supplier);

		# 2. load edit_supplier view
		$this->view->load_index('master/view_supplier', array('supplier' => $data_supplier));
	}

	function approve_order($id_order)
	{
		# 1. obtain data order joined tbl_obat where id_order
		$data_approved = $this->model->get_single_join_where('tbl_order', 'tbl_obat', 'id_obat', 'id_obat', 'id_order', $id_order);

		# 2. Obtain data supplier
		$data_supplier = $this->model->get_table('tbl_suppliers');

		# 3. load aprove_order view
		$this->view->load_index('pengadaan/approve_order', array('approved' => $data_approved, 'data_suppliers'  => $data_supplier));
	}

	// ubah pengiriman
	function pengadaan()
	{
		# 1. obtain data order where status admin = approved
		$data_pengadaan = $this->model->get_join_where('tbl_order', 'tbl_obat', 'id_obat', 'id_obat', 'status', 'approved');

		# 2. load pengadaan view
		$this->view->load_index('pengadaan/pengadaan', array('pengadaan' => $data_pengadaan));
	}

	function view_pengadaan($id_order)
	{
		# 1. obtain data order joined tbl_obat where id_order
		$data_pengadaan = $this->model->get_single_join_where('tbl_order', 'tbl_obat', 'id_obat', 'id_obat', 'id_order', $id_order);

		# 2. load pengadaan view
		$this->view->load_index('pengadaan/view_pengadaan', array('pengadaan' => $data_pengadaan));
	}

}