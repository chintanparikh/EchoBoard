<?php
/**
* This class is used to manage the configuration.
*/
class Config
{
	private static $instance;

	public function getInstance()
	{
		if(!self::$instance){
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
		return $config[$method][$property];
		//Allows us to something like Config::database('host')
	}

	public static function __callStatic($method, $property)
	{
		$config = Application::loadConfig($method);
		return $config[$method][$property[0]];
		//Allows us to something like Config::database('host')
	}
}

?>