<?php
require_once ROOT_PATH.'includes/librairies/File_INI.php';
require_once ROOT_PATH.'includes/classes/Database.php';

define('INI_FILE_PATH', ROOT_PATH.'includes/configuration/configuration.ini');
$config_ini = new File_INI(INI_FILE_PATH);
$_CONFIG = $config_ini->read();

define('TEMPLATE_DIR', ROOT_PATH.'templates/'.$_CONFIG['template'].'/');

if (!is_dir(TEMPLATE_DIR)) {
	echo sprintf(_('Template %s doesn\'t exists'), $_CONFIG['template']);
}

define('PAGE_DIR', TEMPLATE_DIR.'pages/');

require ROOT_PATH.'includes/configuration/database.php';
require ROOT_PATH.'includes/configuration/user.php';
require ROOT_PATH.'includes/configuration/language.php';
?>