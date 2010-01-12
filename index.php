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

define('INI_FILE_PATH', ROOT_PATH.'includes/configuration/configuration.ini');
$config_ini = new File_INI(INI_FILE_PATH);
$_CONFIG = $config_ini->read();

if (!((int)$_CONFIG['installed'])) {
	header('Location: '.LOCAL_PATH.'installation/index.php');
}

require_once ROOT_PATH.'includes/configuration.php';
require_once ROOT_PATH.'includes/classes/Project.php';

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
	if (!preg_match('#^([0-9]+)$#i', $_GET['project'])) {
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
	
	if (isset($_GET['template'])) {
		if (!preg_match('#^([a-z0-9_-]+)$#i', $_GET['template'])) {
			echo _('Nom du template invalide');
			exit();
		}
		
		$template = new Project_Template($project, $_GET['template']);
		
		if (!$template->check()) {
			echo _('Template inconnu');
			exit();
		}
	}
	if (isset($_GET['language'])) {
		if (!preg_match('#^([a-z0-9_-]+)$#i', $_GET['language'])) {
			echo _('Nom de la langue invalide');
			exit();
		}
		
		$language = new Project_Language($project, $_GET['language']);
		
		if (!$language->check()) {
			echo _('Langue inconnue');
			exit();
		}
		
		if (isset($_GET['file'])) {
			if (!preg_match('#^([a-z0-9_/-]+)$#i', $_GET['file']) || substr($_GET['file'], 0, 1) == '/') {
				echo _('Nom du fichier invalide');
				exit();
			}
			
			$language_file = new Project_Language_File($language, $_GET['file']);
			
			if (!$language_file->check()) {
				echo sprintf(_('Fichier %s inconnu dans la langue %s'), $_GET['file'], $language->getCode());
				exit();
			}
		}
	}
}

ob_start();
require_once PAGE_DIR.$_GET['page'].'.php';

$contents = ob_get_contents();
ob_end_clean();


// Includes the header
require_once PAGE_DIR.'specifics/header.php';

echo $contents;

// Includes the footer
require_once PAGE_DIR.'specifics/footer.php';

?>