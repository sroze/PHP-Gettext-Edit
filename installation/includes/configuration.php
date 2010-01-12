<?php
require_once ROOT_PATH.'includes/librairies/File_INI.php';

define('INI_FILE_PATH', ROOT_PATH.'includes/configuration/configuration.ini');
$config_ini = new File_INI(INI_FILE_PATH);
$_CONFIG = $config_ini->read();

define('TEMPLATE_DIR', ROOT_PATH.'templates/'.$_CONFIG['template'].'/');

$available_databases = array(
	'mysql',
	'pgsql'
);

require_once ROOT_PATH.'includes/librairies/Rights/SQL/Rights_SQL_pgsql.php';
require_once ROOT_PATH.'includes/librairies/Rights/SQL_Intervals/SQL/SQL_Intervals_pgsql.php';
require_once ROOT_PATH.'includes/librairies/Rights/SQL_Intervals/SQL_Intervals.php';
require_once ROOT_PATH.'includes/librairies/Rights/Rights.php';
require_once ROOT_PATH.'includes/classes/User.php';
?>