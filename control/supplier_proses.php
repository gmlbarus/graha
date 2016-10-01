<?php
class Supplier_proses
{
	
	function __construct()
	{
		if ($_SESSION['type'] != 'supplier')
		{
			$_SESSION['gagal_login'] = 'Silakan login untuk masuk sebagai supplier';
			header('Location:' . base_url());
		}

		require_once 'model/supplier_model.php';
		$this->model = new supplier_model();

		require_once 'model/buku_model.php';
		$this->default_model = new buku_model();
	}

	function terima_permintaan($id_order)
	{
		# 1. new id_pengiriman
		$id_pengiriman = $this->default_model->new_id('id_pengiriman', 'tbl_pengiriman', 'kirm');

		# 2. add accepted order to tbl_pengiriman
		$this->model->insert_pengiriman($id_pengiriman, $id_order, $_POST['jumlah_disetujui'], date('Y-m-d'));

		# 3. redirect
		header("location:".base_url('supplier/permintaan'));
	}

	function tolak_pesanan($id_order)
	{
		# 1. set supplier_status = declined
		$this->default_model->update('tbl_order', 'status', 'rejected', 'id_order', $id_order);

		# 2. redirect
		header("location:".base_url('supplier/permintaan'));
	}
}