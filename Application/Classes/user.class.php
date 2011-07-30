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
	}

	public function __destruct()
	{
		# terminate database connection
		# terminate session
	}

	/**
	* Registers a user
	*
	*/
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
	*/
	public function login($username, $password), $rememberMe = true)
	{
		$username = $this->sanitize($username);
		$password = $this->encrypt($this->sanitize($password));
		
		$result = $this->checkCredentials($username, $password);
		#we also want to make sure that if $result returns more than one row, it fails. This still needs to be added.
		if( $result )
		{
			#start the session
			#cookies, if rememberMe is true
			return true;
		}
		else
		{
			return false;	
		}
	}

	public function checkCredentials($username, $password)
	{
		$query = "SELECT * FROM {$this->table} WHERE username = ? AND password = ?";
		$result = $this->database->prepare($query)
						->execute($username, $password)
						->fetchAll();
		return $result;
	}

	/**
	* Logs a user out
	*
	*/
	public function logout()
	{
		# clear cookies
		# end session
		# return true on success, throw Exception(s) on fail
	}

	/**
	* Checks whether the user is logged in
	*
	*/
	public function isLoggedIn()
	{
		# get session variables
		# return true if user is logged in, false otherwise
	}

	/**
	* Checks whether the param specified by $param is already taken (might be used to check whether a username is already taken)
	*
	*/
	public function exists($param, $value)
	{
		# query the database to check if the value is taken
		# return true is value is taken, false otherwise.
	}

	/**
	* Sanitize the value given
	*
	*/
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
    *
    */
    protected function encrypt($password)
    {
    	return md5(sha1($password) . md5($password));
    }
}
?>