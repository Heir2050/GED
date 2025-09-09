<?php 

session_start();

/**  Valid PHP Version? **/
$minPHPVersion = '8.0';
if (phpversion() < $minPHPVersion)
{
	die("Your PHP version must be {$minPHPVersion} or higher to run this app. Your current version is " . phpversion());
}

/**  Path to this file **/
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
//__DIR__: always printout the current path your in
// DIRECTORY_SEPARATOR is represented like / or \ depending of operating system
// ROOTPATH: this will show the actual path like this (C:\xampp\htdocs\MVC-Update1\public\) but
// at the same time to check if is exist to load the index.php. If is not exist,
// the index.php can't be loaded (for security)

// echo ROOTPATH;


require "../app/core/init.php";

DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

$app = new App;
$app->loadController();
