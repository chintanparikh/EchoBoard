<?php 
/**
* 
*/
class Controller extends Application
{
	
	function __construct()
	{	
		$databaseInfo = Config::database();
		$this->loadClass('database');
		$this->loadClass('user');


		$db = Database::getInstance($databaseInfo);
		$user = new User();
		$user->register('timtam', 'timtam', 'timtam');
		print 'Done';
		
		 
		$this->loadModel('model');
		$data = $this->model['model']->dummyFunction();

		$this->loadView('view', $data);
	}
}

?>