<?php
require_once ROOT_PATH.'includes/librairies/Rights/SQL/Rights_SQL_pgsql.php';
require_once ROOT_PATH.'includes/librairies/Rights/SQL_Intervals/SQL/SQL_Intervals_pgsql.php';
require_once ROOT_PATH.'includes/librairies/Rights/SQL_Intervals/SQL_Intervals.php';
require_once ROOT_PATH.'includes/librairies/Rights/Rights.php';

require_once ROOT_PATH.'includes/classes/User.php';

// We need session
session_start();

if (!empty($_SESSION['user_informations'])) {
	$_USER = new User($_SESSION['user_informations']);
} else if (!empty($_COOKIE['user'])) {
	$_USER = User::fromCookie($_COOKIE['user']);
} else {
	define('CONNECTED', false);
}
?>