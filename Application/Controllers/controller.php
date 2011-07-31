<?php 
/**
* 
*/
class Controller extends Application
{
	
	function __construct()
	{	
		ob_start();
		$databaseInfo = Config::database();
		$this->loadClass('database');
		$this->loadClass('user');


		$db = Database::getInstance($databaseInfo);
		$user = new User();

		#$user->register('random', 'random', 'random');
		
		$user->login('random', 'random');
		print $user->isLoggedIn();
		$user->logout();
		 
		$this->loadModel('model');
		$data = $this->model['model']->dummyFunction();

		$this->loadView('view', $data);
		ob_end_flush();
	}
}

?>