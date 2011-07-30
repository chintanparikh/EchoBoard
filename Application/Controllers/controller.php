<?php 
/**
* 
*/
class Controller extends Application
{
	
	function __construct()
	{
		$this->loadModel('model');
		$data = $this->model['model']->dummyFunction();

		$this->loadView('view', $data);
	}
}

?>