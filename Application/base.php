<?php 
/**
* 
*/
class Application
{
	public $model = array();
	
	public function __construct()
	{
		$this->loadConfig('config');
	}

	public function loadController($fileName)
	{
		require_once( CLASS_PATH . '/' . $fileName . '.php' ); 
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
		require_once( 'Application/Views/' . $fileName . '.php' ); 
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
		require_once( BASE_PATH . '/Config/' . $fileName . '.php' );
		return $this;
	}

}


?>