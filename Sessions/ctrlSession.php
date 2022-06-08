<?php
class CtrlSession
{
	
	// ++++++++++++++++++++++++++++++
	// This Class manages the session and prevents hijacking attacks
	// 
	// references:
	// https://www.php.net/manual/en/function.session-regenerate-id.php
	// https://www.php.net/manual/en/session.configuration.php
	// https://www.php.net/manual/en/features.session.security.management.php
	// https://blog.teamtreehouse.com/how-to-create-bulletproof-sessions
	// ++++++++++++++++++++++++++++++
	
	public function ctrlMain($name)
	{
		ini_set("session.use_strict_mode", 1);
		ini_set("session.use_cookies", 1);
		ini_set("session.use_only_cookies", 1);
		ini_set("session.use_trans_sid", 0);
		ini_set("session.cache_limiter", "nocache");
		
		if(PHP_VERSION_ID >= 70100)
		{
			ini_set("session.sid_length", "48");
			ini_set("session.sid_bits_per_character", "6");
			ini_set("session.hash_function", "sha256");
		}

	
		$lifetime = 0;
		$domain = $_SERVER["HTTP_HOST"];
		$secure = true;	// if you only want to receive the cookie over HTTPS
		$httponly = true;	// prevent JavaScript access to session cookie
		$samesite = "Lax";	// Accepts only GET requests from out of the domain
		
		if(PHP_VERSION_ID < 70300) {
			session_set_cookie_params($lifetime, "/; samesite=" . $samesite, $domain, $secure, $httponly);
		} else {
			session_set_cookie_params([
				"lifetime" => $lifetime,
				"path" => "/",
				"domain" => $domain,
				"secure" => $secure,
				"httponly" => $httponly,
				"samesite" => $samesite
			]);
		}

		session_start();

		if(isset($_SESSION["timestamp"]) && ($_SESSION["timestamp"] + ini_get("session.gc_maxlifetime") < time ()))
		{
			session_write_close();		
			session_start();
		}
		$_SESSION["timestamp"] = time();

		// Make sure the session hasn't expired, and destroy it if it has
		if($this->validate())
		{
			// Check to see if the session is new or a hijacking attempt
			if($this->prevHackSession() === false)
			{
				// Reset session data and regenerate id
				if(isset($_SESSION["login"])) unset($_SESSION["login"]);
				$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
				$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];

				$this->regenerate();

			// Give a 5% chance of the session id changing on any request
			} elseif(mt_rand(1, 100) <= 5){
				$this->regenerate();

			}
		} else {

			$this->destroy();

			header("Location: " . $_SERVER["HTTP_HOST"]);
			exit;
		}
		
	}


	private function validate()
	{

		if( isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES']) )
			return false;

		if(isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time())
			return false;

		return true;
		
	}


	public function regenerate()
	{
		
		// If this session is obsolete it means there already is a new id
		if(isset($_SESSION['OBSOLETE']) && $_SESSION['OBSOLETE'] == true)
			return;

		// Set current session to expire in 10 seconds
		$_SESSION['OBSOLETE'] = true;
		$_SESSION['EXPIRES'] = time() + 10;
		
		// destroy cookies		
		if(ini_get("session.use_cookies"))
		{
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
			   $params["path"], $params["domain"],
			   $params["secure"], $params["httponly"]
		    );
		}
		 
		
		if(isset($_SESSION["userId"]))
		{
			// Grab current session ID and close both sessions to allow other scripts to use them
			$novoId = session_create_id("user-" . $_SESSION["userId"]);
			
			$data = $_SESSION;
			session_write_close();
		
			// Set session ID to the new one, and start it back up again
			session_id($novoId);
			session_start();
			$_SESSION = $data;
		}
		else
		{			
			// Create new session without destroying the old one	
			session_regenerate_id(false);
			
			// Grab current session ID and close both sessions to allow other scripts to use them
			$novoId = session_id();
			
			session_write_close();
		
			// Set session ID to the new one, and start it back up again
			session_id($novoId);
			session_start();
		}

		// Now we unset the obsolete and expiration values for the session we want to keep
		unset($_SESSION['OBSOLETE']);
		unset($_SESSION['EXPIRES']);
		
	}
			
	private function prevHackSession()
	{
		if(!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent']))
		{
			return false;
		}
		if ($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR'])
		{
			return false;
		}
		if($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
		{
			return false;
		}
		return true;
	}		




	public function destroy()
	{

		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
			   $params["path"], $params["domain"],
			   $params["secure"], $params["httponly"]
		    );
		}

		// Finally, destroy the session.
		session_destroy();
			
	}


	
}
?>