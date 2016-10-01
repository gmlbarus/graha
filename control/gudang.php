<?php
Class Gudang
{
	function __construct()
	{
		# Gudang access only
		if ($_SESSION['type'] != 'gudang')
		{
			$_SESSION['gagal_login'] = 'Silakan login untuk masuk sebagai admin gudang';
			header('Location:' . base_url());
		}

		require_once 'view/view.php';
		$this->view = new view();

		require_once 'model/buku_model.php';
		$this->model = new buku_model();

		require_once 'model/master_model.php';
		$this->master = new master_model();

		require_once 'control/util.php';
	}

/*
 *---------------------------------------------------------------
 * Tampilan Obat
 *---------------------------------------------------------------
 *
 * define basic Obat UI
 */	

	function obat()
	{
		# 1. Obtain data obat
		$data_obat = $this->master->get_obat();

		# 2. load obat view
		$this->view->load_index('master/obat', array('obat' => $data_obat));
	}


	function tambah_obat()
	{
		# 1. Generate new ID for tbl_obat
		$new_id_obat = $this->model->new_id('id_obat', 'tbl_obat', 'obat');

		# 2. Obtain supplier data for Option Select
		$data_supplier = $this->model->get_table('tbl_suppliers');

		# 3. load tambah obat view
		$this->view->load_index('master/tambah_obat', array('id_obat' => $new_id_obat, 'suppliers' => $data_supplier));
	}

	function edit_obat($id_obat)
	{
		# 1. Obtain data obat from id_obat
		$data_obat = $this->model->get_single('tbl_obat', 'id_obat', $id_obat);

		# 2. Obtain supplier data for Option Select
		$data_supplier = $this->model->get_table('tbl_suppliers');

		# 2. load ubah obat view
		$this->view->load_index('master/ubah_obat', array('obat' => $data_obat,'suppliers' => $data_supplier));
	}

	function tambah_stok($id_obat)
	{
		# 1. obtain single data obat
		$data_obat = $this->model->get_single('tbl_obat', 'id_obat', $id_obat);

		# 2. generate new ID for id_stok
		$id_stok = $this->model->new_id('id_stok', 'tbl_stok', 'stok');

		# 3. obtain data supplier
		$data_supplier = $this->model->get_table('tbl_suppliers');

		# 4. load obat view
		$this->view->load_index('master/tambah_stok', array('obat' => $data_obat, 'suppliers' => $data_supplier, 'id_stok' => $id_stok));
	}

	function view_detail_obat($id_obat)
	{
		# 1. obtain single data obat
		$data_obat = $this->model->get_single('tbl_obat', 'id_obat', $id_obat);

		# 2. obtain data stok
		$data_stok = $this->model->get_table_where('tbl_stok', 'id_obat', $id_obat);

		# 4. load obat view
		$this->view->load_index('master/view_detail_obat', array('obat' => $data_obat, 'stok' => $data_stok));
	}

/*
 *---------------------------------------------------------------
 * User
 *---------------------------------------------------------------
 *
 * Define basic user UI
 */

	function user()
	{
	 	# 1. Obtain data user
		$data_user = $this->model->get_table('tbl_user');

		# 2. load user view
		$this->view->load_index('master/user', array('user' => $data_user));
	}

	function tambah_user()
	{
		# 1. load tambah user view
		$this->view->load_index('master/tambah_user');
	} 

	function edit_user($id_user)
	{
		# 1. Obtain data obat from id_obat
		$data_user = $this->model->get_single('tbl_user', 'id_user', $id_user);

		# 2. load ubah obat view
		$this->view->load_index('master/ubah_user', array('user' => $data_user));
	}

	function view_user($id_user)
	{
		# 1. Obtain data obat from id_obat
		$data_user = $this->model->get_single('tbl_user', 'id_user', $id_user);

		# 2. load ubah obat view
		$this->view->load_index('master/view_user', array('user' => $data_user));
	}

/*
 *---------------------------------------------------------------
 * Transaksi
 *---------------------------------------------------------------
 *
 * Define basic Transaksi UI
 */

	/*
	 *---------------------------------------------------------------
	 * Tampilan Pemesanan Obat (dulu Obat Habis)
	 *---------------------------------------------------------------
	 *
	 * Define basic Pesan Obat UI
	 */
	function pemesanan_obat()
	{
		# 1. obtain nearly out of stock data obat
		$data_obat_habis = $this->master->get_obatHabis();

		# 2. load obat view
		$this->view->load_index('transaksi/pemesanan_obat', array('obat' => $data_obat_habis));
	}

	function pesan_obat($id_obat)
	{
		# 1. obtain single data obat from id_obat
		$data_pesan = $this->model->get_single('tbl_obat', 'id_obat', $id_obat);

		# 2. Obtain supplier data for Option Select
		$data_supplier = $this->model->get_table('tbl_suppliers');

		# 3. perform eoq calculation
		$eoq = Utility::eoq($data_pesan->biaya_pesan, $data_pesan->kebutuhan, $data_pesan->harga);

		# 4. load pesan_obat view
		$this->view->load_index('transaksi/pesan_obat', array('pesan' => $data_pesan, 'data_supplier' => $data_supplier, 'eoq' => $eoq));
	}

	function permintaan_obat()
	{
		# 1. obtain obat order
		$data_order = $this->model->get_join('tbl_order', 'tbl_obat', 'id_obat', 'id_obat');

		# 2. load obat request view
		$this->view->load_index('transaksi/request_obat', array('order' => $data_order));
	}

	function view_permintaan_obat($id_order)
	{
		# 1. obtain obat order
		$data_order = $this->model->get_single('tbl_order', 'id_order', $id_order);

		# 2. load obat request view
		$this->view->load_index('transaksi/view_permintaan_obat', array('order' => $data_order));
	}

	function order_obat()
	{
		# 1. obtain order obat
		$data_order = $this->model->get_join('tbl_order', 'tbl_obat', 'id_obat', 'id_obat');

		# 2. load obat request view
		$this->view->load_index('transaksi/order_obat', array('order' => $data_order));
	}

	function edit_order_obat($id_order)
	{
		$data_order = $this->model->get_single('tbl_order', 'id_order', $id_order);

		# 2. load obat request view
		$this->view->load_index('transaksi/edit_order_obat', array('order' => $data_order));
	}

	function view_order_obat($id_order)
	{
		$data_order = $this->model->get_single_join_where('tbl_order', 'tbl_obat', 'id_obat', 'id_obat', 'id_order', $id_order);

		# 2. load obat request view
		$this->view->load_index('transaksi/view_order_obat', array('order' => $data_order));
	}

	function obat_masuk()
	{
		# 1. obtain data from tbl_barang_masuk
		$data_obat_masuk = $this->model->get_join('tbl_obat_masuk', 'tbl_obat', 'id_obat', 'id_obat');

		# 2. load barang_masuk view
		$this->view->load_index('transaksi/obat_masuk', array('obat_masuk' => $data_obat_masuk));
	}

	function view_obat_masuk($id_obat_masuk)
	{
		$data_obat_masuk = $this->model->get_single_join_where('tbl_obat_masuk', 'tbl_obat', 'id_obat', 'id_obat', 'id_obat_masuk', $id_obat_masuk);

		# 2. load barang_masuk view
		$this->view->load_index('transaksi/view_obat_masuk', array('obat_masuk' => $data_obat_masuk));
	}

	function obat_keluar()
	{
		# 1. obtain data from tbl_obat_keluar
		$data_obat_keluar = $this->model->get_join('tbl_obat_keluar', 'tbl_obat', 'id_obat', 'id_obat');

		# 2. load barang_masuk view
		$this->view->load_index('transaksi/obat_keluar', array('obat_keluar' => $data_obat_keluar));	
	}

	function view_obat_keluar($id_obat_keluar)
	{
		$data_obat_keluar = $this->model->get_single_join_where('tbl_obat_keluar', 'tbl_obat', 'id_obat', 'id_obat', 'id_obat_keluar', $id_obat_keluar);

		# 2. load barang_masuk view
		$this->view->load_index('transaksi/view_obat_keluar', array('obat_keluar' => $data_obat_keluar));
	}

	function tambah_obat_keluar($id_obat)
	{
		# 1. obtain single data obat from id_obat
		$data_obat = $this->master->get_obat('tbl_obat');

		# 2. load barang_masuk view
		$this->view->load_index('transaksi/tambah_obat_keluar', array('obat' => $data_obat));
	}

	function edit_obat_keluar($id_obat_keluar)
	{
		# 1. obtain single data obat from id_obat
		$data_obat = $this->model->get_single('tbl_obat_keluar', 'id_obat_keluar', $id_obat_keluar);

		# 2. load barang_masuk view
		$this->view->load_index('transaksi/ubah_obat_keluar', array('obat' => $data_obat));
	}
}
/* End of file awal.php */	