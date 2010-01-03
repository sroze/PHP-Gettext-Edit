<?php
/**
 * GetTextEdit is an application which help you to manage, edit and
 * compile your Gettext translation files.
 * 
 * @author Samuel ROZE <samuel.roze@gmail.com>
 * @link   http://www.d-sites.com/projets/gettextedit/
 */
define('ROOT_PATH', realpath(dirname(__FILE__)).'/');

$uris = explode('/', $_SERVER['SCRIPT_NAME']); array_pop($uris); array_shift($uris); if (!empty($uris)) { $uris[] = ''; }
define('LOCAL_PATH', '/'.implode('/', $uris));

require_once ROOT_PATH.'includes/configuration.php';
require_once ROOT_PATH.'includes/classes/Project.php';

if (!((int)$_CONFIG['installed'])) {
	header('Location: '.LOCAL_PATH.'installation/index.php');
}

// What page is wanted by user ?
$_GET['page'] = (empty($_GET['page'])) ? 'index' : $_GET['page'];
if (!preg_match('#^([a-z0-9_-]+)$#i', $_GET['page'])) {
	echo _('Invalid page name');
	exit();
}

if (!file_exists(PAGE_DIR.$_GET['page'].'.php')) {
	echo _('Page doesn\'t exists');
	exit();
}

if (isset($_GET['project'])) {
	if (!preg_match('#^([0-9]+)$#', $_GET['project'])) {
		echo _('Project ID invalide');
		exit();
	}
	
	$project = new Project((int) $_GET['project']);
	
	try {
		$project->get('project_name');
	} catch (Exception $e) {
		echo $e->getMessage();
		exit();
	}
}

// Includes the header
require_once PAGE_DIR.'specifics/header.php';

// Includes the page
require_once PAGE_DIR.$_GET['page'].'.php';

// Includes the footer
require_once PAGE_DIR.'specifics/footer.php';

?>