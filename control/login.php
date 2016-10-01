<?php
class Login
{
	function __construct()
	{
		require_once 'model/login.php';
		$this->model = new login_model();
	}

	function sign_in()
	{
		# 1. destroy session
		//$this->_destroy_session();

		# 2. cek data login
		if($this->model->login($_POST['username'], $_POST['password']))
			$_SESSION['login'] = 'logged_in';

		header("location:".base_url());
	}

	private function _destroy_session()
	{
		// Unset all session values 
		$_SESSION = array();
		 
		// get session parameters 
		$params = session_get_cookie_params();
		 
		// Delete the actual cookie. 
		setcookie(session_name(),
		        '', time() - 42000, 
		        $params["path"], 
		        $params["domain"], 
		        $params["secure"], 
		        $params["httponly"]);
		 
		// Destroy session 
		session_destroy();
	}

	function sign_out()
	{
		$this->_destroy_session();

		header("location:".base_url());
	}
}
?>