<?php
include 'system/database.php';

function sec_session_start()
{
    $session_name = 'KlinikUnsri_secure_session';   // Set a custom session name
    
    $secure = SECURE;
    
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE)
        exit("Could not initiate a safe session (ini_set).");

    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session 
    session_regenerate_id(true);    // regenerated the session, delete the old one. 
}

function login_check()
{
    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], $_SESSION['login_string'], $_SESSION['type']))
    {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        $mysqli = database_connect2();
 
        if ($stmt = $mysqli->prepare("SELECT password 
                                      FROM tbl_user 
                                      WHERE id_user = ? LIMIT 1"))
        {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1)
            {
                var_dump($_SESSION);
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                if ($login_check == $login_string)
                    // Logged In!!!! 
                    return TRUE;

                else

                    // Not logged in 
                    $_SESSION['gagal_login'] = "pass:{$password}</br>";return FALSE;
            }
            else
                // Not logged in 
                return FALSE;
            
        }
        else
            // Not logged in 
            return FALSE;
    }
    else
        // Not logged in 
        return FALSE;
}