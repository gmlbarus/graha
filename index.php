<?php
/*
 *	Start the Session
 */
include_once 'system/session.php';
sec_session_start();

/*
 *	Define base_url function
 *	Change clustering with web folder name
 */
function base_url($path = '')
{
	$path = "https://morris/" . $path;
	
	return $path;
}

/*
 *	Define database connection configuration
 */
function database_connect()
{
	# code...
	mysql_connect(HOST, USER, PASSWORD);
	mysql_select_db(DATABASE) or die(mysql_error());
}

function database_connect2()
{
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

	if ($mysqli->connect_error)
    	die('Connect Error, '. $mysqli->connect_errno . ': ' . $mysqli->connect_error);
    else
    	return $mysqli;
}

/*
 *---------------------------------------------------------------
 * ROUTING
 *---------------------------------------------------------------
 *
 * Define basic application routing
 */

if (isset($_GET['class']) AND isset($_GET['function']))
{
	if (is_dir('control'))
	{
		if (file_exists("control/{$_GET['class']}.php"))
		{
			include("control/{$_GET['class']}.php");
			if (class_exists($_GET['class']))
			{
				if ($_GET['class'] != 'login')
					if ( ! isset($_SESSION['user_id'], $_SESSION['login_string'], $_SESSION['type']))
						header("location:".base_url());

				$control = new $_GET['class']();
				if (method_exists($control, $_GET['function']))
				{
					$control->$_GET['function']($_GET['arg1'], $_GET['arg2'], $_GET['arg3'], $_GET['arg4']);
				}
				else
					exit("404. Not Found.");
			}
			else
				exit("404. Not Found.");
		}
		else
			exit("404. Not Found.");
	}
	else
		exit('Your control folder path does not appear to be set correctly. Please make sure that folder "control" exists');
}
else
{
	include("view/view.php");
	$view = new view();
	if(isset($_SESSION['user_id'], $_SESSION['login_string'], $_SESSION['type']))
	{
		switch ($_SESSION['type'])
		{
			case 'gudang':
				# code...
				header("location:".base_url('gudang/obat'));
				break;

			case 'supplier':
				header("location:".base_url('supplier/permintaan'));
				break;

			case 'pimpinan':
				header("location:".base_url('pimpinan/laporan_obat'));
				break;

			case 'pengadaan':
				header("location:".base_url('pengadaan/supplier'));
				break;

			case 'penerimaan':
				header("location:".base_url('penerimaan/stok'));
				break;			
			
			case 'satpam':
				header("location:".base_url('satpam/masuk'));
				break;

			default:
				# code...
				$view->load('login/login');
				break;
		}
	}
	else
		$view->load('login/login');
}
/* End of file awal.php */