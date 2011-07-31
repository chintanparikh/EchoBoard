<?php
/**
* Class to deal with the user system, requires that the database class be present and included.
*/
class User
{
	public $database;
	public $table;
	
	public function __construct()
	{
		if( class_exists('Database') )
		{
			$this->database = Database::getInstance(); //Notice we are NOT passing through database information here, it should have already been done. We are simply accessing the instance of the database object.
		}
		else
		{
			throw new Exception('Database class not present, you MUST include the database class and instantiate it before creating a User object.');
		}

		$this->table = Config::user('table');
		session_start();
	}

	public function __destruct()
	{
		# terminate database connection
		if( session_id() != '' )
		{
			session_destroy();
		}
	}

	/**
	 * Registers a user
	 *
	 * @param string $username $password $email
	 * @return bool
	 * @author Chintan Parikh
	 **/
	public function register($username, $password, $email)
	{
		$username = $this->sanitize($username);
		$password = $this->sanitize($password);
		$email = $this->sanitize($email);
		# need to check if username already taken, but that probably goes outside of this function
		$query = "INSERT INTO {$this->table} VALUES ('', ?, ?, ?)";
		$this->database->prepare($query)
						->execute($username, $this->encrypt($password), $email);

		return true;
	}

	/**
	 * Logs a user in
	 *
	 * @param string $username $password
	 * @param bool $rememberMe
	 * @return bool
	 * @author Chintan Parikh
	 **/
	public function login($username, $password, $rememberMe = true)
	{
		$username = $this->sanitize($username);
		$password = $this->sanitize($password);
		
		$result = $this->checkCredentials($username, $password);
		#we also want to make sure that if $result returns more than one row, it fails. This still needs to be added.
		if( $result )
		{
			$info = $this->getInfo($username);
			if( session_id() == '')
			{
				session_start(); #it might be a good idea to make a session class, we'll see how things go.
			}
			$_SESSION['username'] = $info['username'];
			$_SESSION['password'] = $info['password'];

			if($rememberMe)
			{
				//Don't forget that cookies must be set before any output, might need to use output buffering to achieve that.
				setcookie('username', $info['username']);
				setcookie('password', $info['password']);
			}

			return true;
		}
		else
		{
			return false;	
		}
	}

	/**
	 * Checks whether a users username and password are correct
	 *
	 * @param string $username $password
	 * @return bool
	 * @author Chintan Parikh
	 **/
	public function checkCredentials($username, $password)
	{
		$user = $this->getInfo($username);
		if ( $user['password'] == $this->encrypt($password) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
    /**
     * Gets the information of a user from a user name (id, password, email)
     *
     * @param string $username
     * @return array
     * @author Chintan Parikh
     **/
    public function getInfo($username)
    {
		$query = "SELECT * FROM {$this->table} WHERE username = ?";
    	$result = $this->database->prepare($query)
					->execute($username)
					->fetchAll();
		return $result[0];
    }

	/**
	 * Logs a user out
	 *
	 * @return bool
	 * @author Chintan Parikh 
	 **/
	public function logout()
	{

		setcookie('username', '', time() - 3600);
		setcookie('password', '', time() - 3600);
		if( session_id() != '' )
		{
			session_destroy();
		}
		
		return true;
	}

	/**
	 * Checks whether the user is logged in
	 *
	 * @return bool
	 * @author Chintan Parikh
	 **/
	public function isLoggedIn()
	{
		if( isset($_SESSION['username']) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
 	 * Checks whether the param specified by $param is already taken (might be used to check whether a username is already taken)
 	 *
	 **/
	public function exists($param, $value)
	{
		# query the database to check if the value is taken
		# return true is value is taken, false otherwise.
	}

	/**
	* Sanitize the value given
	*
	* @param string $input
	* @return string
	* @author Chintan Parikh
	**/
	protected function sanitize($input){   
		if ( get_magic_quotes_gpc() )
		{
			$input = stripslashes($input);
		}

	    $search = array(
		    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	  	);

		$output = preg_replace($search, '', $input);
		return $output;
    }

    /**
     * Encrypts the password, NEEDS TO BE MADE MORE SECURE, USE BCRYPT!
     *
	 * @param string $password
     * @return string
     * @author Chintan Parikh
     **/
    protected function encrypt($password)
    {
    	# this is currently VERY bad encryption. Later on, I'll change it to work with bcrypt, which is way better.
    	return md5(sha1($password) . md5($password));
    }

}
?>