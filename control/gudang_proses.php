<?php
class Gudang_proses
{
	function __construct()
	{
		# Gudang access only
		if ($_SESSION['type'] != 'gudang')
		{
			$_SESSION['gagal_login'] = 'Silakan login untuk masuk sebagai admin gudang';
			header('Location:' . base_url());
		}
		require_once 'model/buku_model.php';
		$this->model = new buku_model();

		require_once 'model/master_model.php';
		$this->master_model = new master_model();

		require_once 'control/util.php';
	}

/*
 *---------------------------------------------------------------
 * Obat
 *---------------------------------------------------------------
 *
 * Define Obat database change
 */

	function tambah_obat()
	{
		# 1. insert data obat
		$this->master_model->insert_obat($_POST['id_obat'], $_POST['nama'], $_POST['satuan'], $_POST['harga'], $_POST['minimal_stock'], $_POST['kebutuhan'], $_POST['lead_time'], $_POST['biaya_pesan']);

		# 2. insert data stok
		$id_stok = $this->model->new_id('id_stok', 'tbl_stok', 'stok');
		$this->master_model->insert_stok($id_stok, $_POST['id_supplier'], $_POST['id_obat'], '', $_POST['stok']);

		# 3. evaluate eoq
		$eoq = Utility::eoq($_POST['biaya_pesan'], $_POST['kebutuhan'], $_POST['harga']);
		$this->model->update('tbl_obat', 'eoq', $eoq, 'id_obat', $_POST['id_obat']);

		# 4. evaluate rop
		$rop = Utility::rop($_POST['kebutuhan'], $_POST['lead_time']);
		$this->model->update('tbl_obat', 'rop', $rop, 'id_obat', $_POST['id_obat']);

		# 5. evaluate safety stock
		$ss = Utility::ss($_POST['lead_time'], $_POST['kebutuhan']);
		$this->model->update('tbl_obat', 'safety_stock', $ss, 'id_obat', $_POST['id_obat']);

		# 5. redirect
		header("location:".base_url('gudang/obat'));
	}

	function hapus_obat($id_obat)
	{
		$this->model->delete('id_obat', $id_obat, 'tbl_obat');

		header("location:".base_url('gudang/obat'));
	}

	function ubah_obat($id_obat)
	{
		# 1. update data obat
		$this->master_model->edit_obat($id_obat);

		# 2. update ROP & EOQ
		if ($_POST['kebutuhan'] != $_POST['kebutuhan_old'])
		{
			# 3. evaluate eoq
			$eoq = Utility::eoq($_POST['biaya_pesan'], $_POST['kebutuhan'], $_POST['harga']);
			$this->model->update('tbl_obat', 'eoq', $eoq, 'id_obat', $_POST['id_obat']);

			# 4. evaluate rop
			$rop = Utility::rop($_POST['kebutuhan'], $_POST['lead_time']);
			$this->model->update('tbl_obat', 'rop', $rop, 'id_obat', $_POST['id_obat']);
		}

		# 5. redirect
		header("location:".base_url('gudang/obat'));
	}

	function tambah_stok($id_obat)
	{
		# 1. add stok to tbl_stok
		$this->master_model->insert_stok($_POST['id_stok'], $_POST['id_supplier'], $id_obat, $_POST['tanggal_kirim'], $_POST['tanggal_kadaluarsa'], $_POST['stok']);

		# 2. redirect
		header("location:".base_url('gudang/obat'));
	}
	
/*---------------------------------------------------------------*/

/*
 *---------------------------------------------------------------
 * USER
 *---------------------------------------------------------------
 *
 * Define User database change
 */

	function tambah_user()
	{
		switch ($_POST['level'])
		{
			case 'pimpinan':
				$new_id_user = $this->model->new_id('id_user', 'tbl_user', 'pimp');
				break;

			case 'supplier':
				$new_id_user = $this->model->new_id('id_user', 'tbl_user', 'supl');
				break;

			case 'pengadaan':
				$new_id_user = $this->model->new_id('id_user', 'tbl_user', 'pgdn');
				break;

			case 'penerimaan':
				$new_id_user = $this->model->new_id('id_user', 'tbl_user', 'trma');
				break;			
			
			default:
				exit('505.Internal Error.');
				break;
		}

		if (isset($new_id_user))
			$this->master_model->insert_user($new_id_user);

		header("location:".base_url('gudang/user'));
	}

	function hapus_user($id_user)
	{
		$this->model->delete('id_user', $id_user, 'tbl_user');

		header("location:".base_url('gudang/user'));
	}

	function ubah_user()
	{
		$this->master_model->ubah_user($_POST['id_user'], $_POST['nama'], $_POST['username'], $_POST['level']);

		if ($_POST['password'] !== '')
			$this->master_model->ubah_password($_POST['password'], $_POST['id_user']);

		header("location:".base_url('gudang/user'));
	}

/*---------------------------------------------------------------*/	

/*
 *---------------------------------------------------------------
 * Transaksi
 *---------------------------------------------------------------
 *
 * Should remove to transaksi class soon.
 */

	function pesan_obat()
	{
		$id_order = $this->model->new_id('id_order', 'tbl_order', 'ORDR');

		# 1. add data pesanan to tbl_order
		$this->master_model->insert_order($id_order);

		# 2. redirect to gudang obat habis
		header("location:".base_url('gudang/order_obat'));
	}

	function edit_order_obat()
	{
		$this->model->update('tbl_order', 'jumlah', $_POST['eoq'], 'id_order', $_POST['id_order']);

		# 2. redirect to gudang obat habis
		header("location:".base_url('gudang/order_obat'));
	}

	function hapus_order_obat($id_order)
	{
		$this->model->delete('id_order', $id_order, 'tbl_order');

		header("location:".base_url('gudang/order_obat'));
	}

	function kirim_obat($id_obat, $request)
	{
		# 1. obtain data obat
		$data_obat = $this->model->get_single('id_obat', $id_obat, 'tbl_obat');

		# 2. calculate stok accumulation
		$stok = $data_obat->stok - $request;

		# 3. change stok in database
		$this->master_model->ubah_stok_obat($id_obat, $stok);

		# 4. redirect to permintaan
		header("location:".base_url('gudang/permintaan_obat'));
	}

	function tambah_obat_keluar()
	{
		$jumlah_keluar = $_POST['jumlah'];

		# 1. obtain data obat keluar from tbl_stok
		$stok = $this->master_model->get_tabel_stok($_POST['id_obat']);

		# 2. decrease stok from tbl_stok
		$index = 0;

		while ($jumlah_keluar > 0 && $index < count($stok))
		{
			if ($jumlah_keluar >= $stok[$index]->stok)
			{
				$this->model->delete('id_stok', $stok[$index]->id_stok, 'tbl_stok');
				$jumlah_keluar -= $stok[$index]->stok;
			}
			else
			{
				$sisa = $stok[$index]->stok - $jumlah_keluar;
				$jumlah_keluar = 0;
				$this->master_model->ubah_stok_obat($sisa, $stok[$index]->id_stok);
			}

			$index ++;
		}

		// new in this proses
		$id_obat_keluar = $this->model->new_id('id_obat_keluar', 'tbl_obat_keluar', 'klwr');
		

		$this->master_model->insert_obat_keluar($id_obat_keluar, $_POST['id_obat'], date('Y-m-d'), $_POST['jumlah']);

		header("location:".base_url('gudang/obat_keluar'));
	}

	function ubah_obat_keluar()
	{
		$id_obat_keluar = $_POST['id_obat_keluar'];
		$jumlah = $_POST['jumlah'];

		$this->model->update('tbl_obat_keluar', 'jumlah', $jumlah, 'id_obat_keluar', $id_obat_keluar);

		header("location:".base_url('gudang/obat_keluar'));
	}

	function hapus_obat_keluar($id_obat_keluar)
	{
		$this->model->delete('id_obat_keluar', $id_obat_keluar, 'tbl_obat_keluar');

		header("location:".base_url('gudang/obat_keluar'));
	}
}