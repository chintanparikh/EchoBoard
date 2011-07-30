<?php 
/**
* 
*/
class Controller extends Application
{
	
	function __construct()
	{
		/*
		print_r($config);
		$this->loadConfig('database');
		$this->loadClass('database');
		$this->loadClass('user');


		$db = Database::getInstance($config['db']);
		$user = new User();
		$user->register('timtam', 'timtam', 'timtam');
		print 'Done';
		*/
		 
		$this->loadModel('model');
		$data = $this->model['model']->dummyFunction();

		$this->loadView('view', $data);
	}
}

?>