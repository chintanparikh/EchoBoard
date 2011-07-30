<?php 
define("BASE_PATH", "Application");
require 'Application/base.php';

$application = new Application();
$application->loadController('controller');

?>