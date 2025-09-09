<?php

defined('ROOTPATH') or exit('Access Denied!');

if ($_SERVER['SERVER_NAME'] == 'localhost') {
	/** database config **/
	define('DBNAME', 'ged');
	define('DBHOST', 'localhost');
	define('DBUSER', 'root');
	define('DBPASS', '');
	define('DBDRIVER', 'mysql');

	define('ROOT', 'http://localhost/gde/public');
} else {
	/** database config **/
	define('DBNAME', '');
	define('DBHOST', 'localhost');
	define('DBUSER', '');
	define('DBPASS', '');
	define('DBDRIVER', 'mysql');

	define('ROOT', '');
}

define('APP_NAME', "GDE - SETIC");
define('APP_DESC', "Gestion de Documents Électroniques - SETIC");

/** true means show errors **/
define('DEBUG', true);
