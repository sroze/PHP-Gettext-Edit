<?php
/**
 * GetTextEdit is an application which help you to manage, edit and
 * compile your Gettext translation files.
 * 
 * @author Samuel ROZE <samuel.roze@gmail.com>
 * @link   http://www.d-sites.com/projets/gettextedit/
 */
define('ROOT_PATH', realpath(dirname(__FILE__)).'/');

// What page is wanted by user ?
$page_name = (empty($_GET['page'])) ? 'index' : $_GET['page'];
if (!preg_match('#^([a-z0-9_-]+)$#i', $page_name)) {
	echo _('Invalid page name');
	exit();
}

if (!file_exists(PAGE_DIR.$page_name.'.php')) {
	echo _('Page doesn\'t exists');
	exit();
}

// Includes the header
require_once PAGE_DIR.'specifics/header.php';

// Includes the page
require_once PAGE_DIR.$page_name.'.php';

// Includes the footer
require_once PAGE_DIR.'specifics/footer.php';

?>