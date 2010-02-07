<?php
define('ROOT_PATH', realpath(dirname(__FILE__).'/../').'/');

require_once ROOT_PATH.'includes/ini_conf.php';
require_once ROOT_PATH.'includes/configuration.php';

require_once ROOT_PATH.'includes/classes/Project.php';
require_once ROOT_PATH.'includes/classes/Project_Language.php';
require_once ROOT_PATH.'includes/classes/Project_Language_File.php';
require_once ROOT_PATH.'includes/classes/Project_Template.php';

// Patch for performances
session_write_close();

if ((int) $_POST['project'] == 0) {
	echo 'Bad request';
	exit();
}
$project = new Project($_POST['project']);

if (array_key_exists('template', $_POST)) {
	$template = new Project_Template($project, $_POST['template']);
	$last_edited_files = $template->getEditedFiles();
				
	if (!empty($last_edited_files)) {
		echo 'ko';
	} else {
		echo 'ok';
	}
} else if (array_key_exists('language', $_POST)) {
	$language = new Project_Language($project, $_POST['language']);
	$language_warnings = $language->getWarnings();
	if (!empty($language_warnings)) {
		echo 'ko';
	} else {
		echo 'ok';
	}
} else {
	echo 'Bad query';
	exit();
}

?>