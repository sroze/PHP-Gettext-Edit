<?php
define('TEMPLATE_DIR', ROOT_PATH.'templates/'.$_CONFIG['template'].'/');

if (!is_dir(TEMPLATE_DIR)) {
	echo sprintf(_('Template %s doesn\'t exists'), $_CONFIG['template']);
}

define('PAGE_DIR', TEMPLATE_DIR.'pages/');

require_once ROOT_PATH.'includes/librairies/Rights/SQL/Rights_SQL_pgsql.php';
require_once ROOT_PATH.'includes/librairies/Rights/SQL_Intervals/SQL/SQL_Intervals_pgsql.php';
require_once ROOT_PATH.'includes/librairies/Rights/SQL_Intervals/SQL_Intervals.php';
require_once ROOT_PATH.'includes/librairies/Rights/Rights.php';
require_once ROOT_PATH.'includes/librairies/String.php';
require_once ROOT_PATH.'includes/classes/User.php';
require_once ROOT_PATH.'includes/classes/Database.php';

require ROOT_PATH.'includes/configuration/database.php';

Rights_Config::init($sql, $_CONFIG['database']['prefix'].'rights_');

require ROOT_PATH.'includes/configuration/user.php';
require ROOT_PATH.'includes/configuration/language.php';
require ROOT_PATH.'includes/classes/GTE.php';
?>