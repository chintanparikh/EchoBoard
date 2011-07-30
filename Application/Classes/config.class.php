<?php
/**
* This class is used to manage the configuration.
*/
class Config
{
	private static $instance;

	public function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new Config;
		}

		return self::$instance;
	}

	private function __construct()
	{
		# code...
	}

	public function __call($method, $property)
	{
		$config = Application::loadConfig($method);
		if( array_key_exists(0, $property) )
		{
			if( is_array($config[$method]) )
			{
				return $config[$method][$property[0]];
			}
			else
			{
				throw new Exception('Config file is not an array?');
			}
		}
		else{
			return $config[$method];
		}

			
		//Allows us to something like Config::database('host')
	}

	public static function __callStatic($method, $property)
	{
		$config = Application::loadConfig($method);
		if( array_key_exists(0, $property) )
		{
			if( is_array($config[$method]) )
			{
				return $config[$method][$property[0]];
			}
			else
			{
				throw new Exception('Config file is not an array?');
			}
		}
		else{
			return $config[$method];
		}

			
		//Allows us to something like Config::database('host')
	}
}

?>