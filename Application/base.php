<?php 
/**
* 
*/
class Application
{
	public $model = array();
	
	public function __construct()
	{
		require 'Classes/config.class.php';
		define("CONTROLLER_PATH", Config::main('controllerPath'));
		define("MODEL_PATH", Config::main('modelPath'));
		define("VIEW_PATH", Config::main('viewPath'));
		define("CLASS_PATH", Config::main('classPath'));

	}

	public function loadController($fileName)
	{
		require_once( CONTROLLER_PATH . '/' . $fileName . '.php' ); 
		$controller = new $fileName;
		return $this;
	}

	public function loadModel($fileName)
	{
		require_once( MODEL_PATH . '/' . $fileName . '.php' ); 
		$this->model[$fileName] = new $fileName;
		return $this;
	}

	public function loadView($fileName, $data = nul)
	{
		if(is_array($data))
		{
			extract($data);
		}
		ob_start();
		require_once( VIEW_PATH . '/' . $fileName . '.php' ); 
		ob_end_flush();
		return $this;
	}

	public function loadClass($fileName)
	{
		require_once( CLASS_PATH . '/' . $fileName . '.class.php' );
		return $this;
	}

	public function loadConfig($fileName)
	{
		$config = '';
		$path = BASE_PATH . '/Config/' . $fileName . '.php';
		if( file_exists($path)){
			require( BASE_PATH . '/Config/' . $fileName . '.php' );
			return $config;
		}
		else{
			throw new Exception('Config file does not exist!');
		}

	}

}


?>