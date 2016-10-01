<?php
class Login_model
{
	
	function __construct()
	{
		# code...
		$_SESSION = array();
		$this->mysqli = database_connect2();
	}

	function cek_login()
	{
		$_SESSION = array();

		# 1. cek kuery
		$username = mysql_escape_string($_POST['username']);
		$password = mysql_escape_string($_POST['password']);

		$pass = sha1("{$password}Y38ca8ia0saXjwrwCwhrWy");

		$query = "SELECT id_user, nama, level, username
					FROM tbl_user
					WHERE username 		= '{$username}'
						AND password 	= '{$pass}'";
		
		# 2. cek if num_row > 0
		$array = mysql_query($query) or die(mysql_error());
		
		while($row = mysql_fetch_object($array))
			$hasil = $row;

		if (mysql_num_rows($array) > 0)
		{
			# 3. return TRUE/FALSE
			$_SESSION['id_user'] = $hasil->id_user;
			$_SESSION['nama'] = $hasil->nama;
			$_SESSION['type'] = $hasil->level;

			return TRUE;
		}
		else
		{
			$_SESSION['gagal_login'] = 'username atau password salah';
			
			return FALSE;
		}
	}

	function login($email, $password)
	{
	    $query = "SELECT id_user, nama, level, password, username
			        FROM tbl_user
			       WHERE username = ?
			        LIMIT 1";
	    // Using prepared statements means that SQL injection is not possible. 
	    if ($stmt = $this->mysqli->prepare($query))
	    {
	        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
	        $stmt->execute();    // Execute the prepared query.
	        $stmt->store_result();
	 
	        // get variables from result.
	        $stmt->bind_result($id_user, $nama, $level, $db_pass, $username);
	        $stmt->fetch();
	 		
	        // hash the password with the unique salt.
	        $user_password = sha1("{$password}Y38ca8ia0saXjwrwCwhrWy");
	        if ($stmt->num_rows == 1)
	        {
	            // If the user exists we check if the account is locked
	            // from too many login attempts 
	        	
	        	if ($this->_checkbrute($id_user))
	                // Account is locked 
	                // Send an email to user saying their account is locked
	                return false;
	            
	            else
	            {
	                // Check if the password in the database matches
	                // the password the user submitted.
	                if ($db_pass == $user_password)
	                {
	                    // Password is correct!
	                    // Get the user-agent string of the user.
	                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
	                    // XSS protection as we might print this value
	                    //$id_user = preg_replace("/[^0-9]+/", "", $id_user);
	                    $_SESSION['user_id'] = $id_user;
	                    // XSS protection as we might print this value
	                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
	                                                                "", 
	                                                                $username);
	                    $_SESSION['type'] = $level;
	                    $_SESSION['nama'] = $nama;
	                    $_SESSION['login_string'] = hash('sha512', $user_password . $user_browser);
	                    // Login successful.
	                    return true;
	                }
	                else
	                {
	                    // Password is not correct
	                    // We record this attempt in the database
	                    $_SESSION['gagal_login'] = 'username atau password salah';
	                    $now = time();
	                    
	                    $this->mysqli->query("INSERT INTO login_attempts(user_id, time)
	                                    VALUES ('$user_id', '$now')");
	                    return false;
	                }
	            }
	        } 
	        else
	        {	
	        	$_SESSION['gagal_login'] = 'username atau password salah';
	            // No user exists.
	            return false;
	        }
	    }
	    else
	    {
	    	$_SESSION['gagal_login'] = 'username atau password salah';
            // No user exists.
            return false;
	    }
	}

	private function _checkbrute($user_id)
	{
	    // Get timestamp of current time 
	    $now = time();
	 
	    // All login attempts are counted from the past 2 hours. 
	    $valid_attempts = $now - (2 * 60 * 60);

	    // query
	    $query = "SELECT time 
                 FROM login_attempts 
                 WHERE user_id = ? 
                AND time > '{$valid_attempts}'";
	 
	    if ($stmt = $this->mysqli->prepare($query)) {
	        $stmt->bind_param('i', $user_id);
	 
	        // Execute the prepared query. 
	        $stmt->execute();
	        $stmt->store_result();
	 
	        // If there have been more than 5 failed logins 
	        if ($stmt->num_rows > 5000) {
	            return true;
	        } else {
	            return false;
	        }
	    }
	}
}
?>