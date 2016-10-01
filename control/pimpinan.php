<?php
class Pimpinan
{	
	function __construct()
	{
		# code...
		if ($_SESSION['type'] != 'pimpinan')
		{
			$_SESSION['gagal_login'] = 'Silakan login untuk masuk sebagai pimpinan';
			header('Location:' . base_url());
		}

		require_once 'view/view.php';
		$this->view = new view();

		require_once 'model/buku_model.php';
		$this->model = new buku_model();

		require_once 'model/pemimpin_model.php';
		$this->pemimpin_model = new pemimpin_model();
	}

	function laporan_obat()
	{
		if (isset($_POST['bulan']) && isset($_POST['tahun']))
		{
			$data_obat = $this->pemimpin_model->get_laporan_obat($_POST['bulan'], $_POST['tahun']);
			$periode = "{$_POST['bulan']}-{$_POST['tahun']}";
		}
		else
		{
			$data_obat = $this->pemimpin_model->get_laporan_obat();
			$periode = '';
		}

		$this->view->load_index('pemimpin/laporan_obat', array('laporan' => $data_obat, 'periode' => $periode));
	}

	function laporan_lainnya()
	{
		$this->view->load_index('pemimpin/laporan_lainnya');
	}
}