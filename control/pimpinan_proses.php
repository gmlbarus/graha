<?php
class Pimpinan_proses
{
	
	function __construct()
	{
		if ($_SESSION['type'] != 'pimpinan')
		{
			$_SESSION['gagal_login'] = 'Silakan login untuk masuk sebagai pimpinan';
			header('Location:' . base_url());
		}

		require_once 'view/view.php';
		$this->view = new view();

		require_once 'model/pemimpin_model.php';
		$this->pemimpin_model = new pemimpin_model();

		require_once 'system/pdf/dompdf_config.inc.php';
		$this->dompdf = new DOMPDF();
	}

	private function _create_pdf($view_file, $parameter = array(), $nama_file)
	{
		ob_start();
		$this->view->load($view_file, $parameter);
		$content = ob_get_contents();
		ob_end_clean();

		$this->dompdf->load_html($content);
		$this->dompdf->set_paper(DOMPDF_DEFAULT_PAPER_SIZE, 'portrait');
		$this->dompdf->render();

		$this->dompdf->stream("{$nama_file}.pdf", array("Attachment" => '0'));

		exit(0);
	}

	public function laporan_lainnya()
	{
		if (isset($_POST['tipe_laporan']))
		{
			switch ($_POST['tipe_laporan'])
			{
				case 'laporan_stok':
					$this->_laporan_stok();
					break;

				case 'laporan_obat_masuk':
					$this->_laporan_obat_masuk();
					break;

				case 'laporan_obat_keluar':
					$this->_laporan_obat_keluar();
					break;

				case 'laporan_obat':
					$this->_laporan_obat();
					break;

				case 'laporan_pemesanan_obat':
					$this->_laporan_pemesanan_obat();
					break;

				case 'laporan_pengiriman_obat':
					$this->_laporan_pengiriman_obat();
					break;

				case 'laporan_supplier':
					$this->_laporan_supplier();
					break;

				case 'laporan_permintaan_obat':
					$this->_laporan_permintaan_obat();
					break;											
				
				default:
					header('location:' . base_url('pimpinan/laporan_lainnya'));
					break;
			}
		}
		else
			header('location:' . base_url('pimpinan/laporan_lainnya'));
	}

	private function _laporan_obat()
	{
		$jenis_laporan = 'LAPORAN OBAT';
		$tanggal = date('d M Y');
		$user_pemimpin = $_SESSION['nama'];	
		$data_obat = $this->pemimpin_model->get_obat();

		$this->_create_pdf('pemimpin/laporan_obat_pdf', array('jenis_laporan' => $jenis_laporan, 'tanggal' => $tanggal, 'user_pemimpin' => $user_pemimpin, 'data_tabel' => $data_obat), 'laporan data obat');
	}

	private function _laporan_stok()
	{
		$jenis_laporan = 'LAPORAN STOK';
		$periode = '';
		$tanggal = date('d M Y');
		$user_pemimpin = $_SESSION['nama'];	
		$data_stok = $this->pemimpin_model->get_stok_obat();
		
		$this->_create_pdf('pemimpin/laporan_stok', array('jenis_laporan' => $jenis_laporan, 'periode' => $periode, 'tanggal' => $tanggal, 'user_pemimpin' => $user_pemimpin, 'data_tabel' => $data_stok), 'laporan stok');
	}

	private function _laporan_supplier()
	{
		$jenis_laporan = 'LAPORAN SUPPLIER';
		$periode = '';
		$tanggal = date('d M Y');
		$user_pemimpin = $_SESSION['nama'];	
		$data_supplier = $this->pemimpin_model->get_supplier();
		
		$this->_create_pdf('pemimpin/laporan_supplier', array('jenis_laporan' => $jenis_laporan, 'periode' => $periode, 'tanggal' => $tanggal, 'user_pemimpin' => $user_pemimpin, 'data_tabel' => $data_supplier), 'laporan supplier');
	}

	private function _laporan_obat_masuk()
	{
		$jenis_laporan = 'LAPORAN OBAT MASUK';
		$periode = "{$_POST['bulan']} - {$_POST['tahun']}";
		$tanggal = date('d M Y');
		$user_pemimpin = $_SESSION['nama'];	
		$data_obat_masuk = $this->pemimpin_model->get_obat_masuk($_POST['bulan'], $_POST['tahun']);

		$this->_create_pdf('pemimpin/laporan_obat_masuk', array('jenis_laporan' => $jenis_laporan, 'periode' => $periode, 'tanggal' => $tanggal, 'user_pemimpin' => $user_pemimpin, 'data_tabel' => $data_obat_masuk), "laporan obat masuk PER {$tanggal}");
	}

	private function _laporan_obat_keluar()
	{
		$jenis_laporan = 'LAPORAN OBAT KELUAR';
		$periode = "{$_POST['bulan']} - {$_POST['tahun']}";
		$tanggal = date('d M Y');
		$user_pemimpin = $_SESSION['nama'];	
		$data_obat_keluar = $this->pemimpin_model->get_obat_keluar($_POST['bulan'], $_POST['tahun']);

		$this->_create_pdf('pemimpin/laporan_obat_keluar', array('jenis_laporan' => $jenis_laporan, 'periode' => $periode, 'tanggal' => $tanggal, 'user_pemimpin' => $user_pemimpin, 'data_tabel' => $data_obat_keluar), "laporan obat keluar PER {$tanggal}");
	}

	private function _laporan_pemesanan_obat()
	{
		$jenis_laporan = 'LAPORAN PEMESANAN OBAT';
		$periode = "{$_POST['bulan']} - {$_POST['tahun']}";
		$tanggal = date('d M Y');
		$user_pemimpin = $_SESSION['nama'];	
		$data_pemesanan_obat = $this->pemimpin_model->get_pemesanan_obat($_POST['bulan'], $_POST['tahun']);

		$this->_create_pdf('pemimpin/laporan_pemesanan_obat', array('jenis_laporan' => $jenis_laporan, 'periode' => $periode, 'tanggal' => $tanggal, 'user_pemimpin' => $user_pemimpin, 'data_tabel' => $data_pemesanan_obat), "laporan pemesanan obat PER {$tanggal}");
	}

	private function _laporan_pengiriman_obat()
	{
		$jenis_laporan = 'LAPORAN PENGIRIMAN OBAT';
		$periode = "{$_POST['bulan']} - {$_POST['tahun']}";
		$tanggal = date('d M Y');
		$user_pemimpin = $_SESSION['nama'];	
		$data_pengiriman_obat = $this->pemimpin_model->get_pengiriman_obat($_POST['bulan'], $_POST['tahun']);

		$this->_create_pdf('pemimpin/laporan_pengiriman_obat', array('jenis_laporan' => $jenis_laporan, 'periode' => $periode, 'tanggal' => $tanggal, 'user_pemimpin' => $user_pemimpin, 'data_tabel' => $data_pengiriman_obat), "laporan pengiriman obat PER {$tanggal}");
	}

	private function _laporan_permintaan_obat()
	{
		$jenis_laporan = 'LAPORAN PERMINTAAN OBAT';
		$periode = "{$_POST['bulan']} - {$_POST['tahun']}";
		$tanggal = date('d M Y');
		$user_pemimpin = $_SESSION['nama'];	
		$data_permintaan_obat = $this->pemimpin_model->get_permintaan_obat($_POST['bulan'], $_POST['tahun']);

		$this->_create_pdf('pemimpin/laporan_permintaan_obat', array('jenis_laporan' => $jenis_laporan, 'periode' => $periode, 'tanggal' => $tanggal, 'user_pemimpin' => $user_pemimpin, 'data_tabel' => $data_permintaan_obat), "laporan permintaan obat PER {$tanggal}");
	}
}