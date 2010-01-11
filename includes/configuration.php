<?php
require_once ROOT_PATH.'includes/librairies/File_INI.php';
require_once ROOT_PATH.'includes/classes/Database.php';

$config_ini = new File_INI(ROOT_PATH.'includes/configuration/configuration.ini');
$_CONFIG = $config_ini->read();

define('TEMPLATE_DIR', ROOT_PATH.'templates/'.$_CONFIG['template'].'/');

if (!is_dir(TEMPLATE_DIR)) {
	echo sprintf(_('Template %s doesn\'t exists'), $_CONFIG['template']);
}

define('PAGE_DIR', TEMPLATE_DIR.'pages/');

$sql = new Database(
	ROOT_PATH.'includes/configuration/gettextedit.db'
);

require ROOT_PATH.'includes/configuration/user.php';
require ROOT_PATH.'includes/configuration/language.php';
?>