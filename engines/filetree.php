<?php
define('ROOT_PATH', realpath(dirname(__FILE__).'/../').'/');

require_once ROOT_PATH.'includes/ini_conf.php';
require_once ROOT_PATH.'includes/configuration.php';
require_once ROOT_PATH.'includes/classes/Project.php';

// Patch for performances
session_write_close();

$_POST['dir'] = urldecode($_POST['dir']);
$project = new Project((int) $_POST['project']);
$root = $project->get('project_path');

if( file_exists($root . $_POST['dir']) ) {
	$files = scandir($root . $_POST['dir']);
	natcasesort($files);
	echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
	
	if ($_POST['dir'] == '/') {
		echo "<li class=\"head\"><input type=\"checkbox\" name=\"files[]\" value=\"/\" checked /><a href=\"#\" rel=\"/\" class=\"directory expanded\">".$root."</a>";
		echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
	}
	
	if( count($files) > 2 ) { /* The 2 accounts for . and .. */
		// All dirs
		foreach( $files as $file ) {
			if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file) ) {
				echo "<li><input type=\"checkbox\" name=\"files[]\" value=\"" . htmlentities($_POST['dir'] . $file) . "/\" /><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\" class=\"directory collapsed\">" . htmlentities($file) . "</a></li>";
			}
		}
		// All files
		foreach( $files as $file ) {
			if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file) ) {
				$ext = preg_replace('/^.*\./', '', $file);
				echo "<li><input type=\"checkbox\" name=\"files[]\" value=\"" . htmlentities($_POST['dir'] . $file) . "\" /><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\" class=\"file ext_$ext\">" . htmlentities($file) . "</a></li>";
			}
		}
	} else {
		echo '<li>Vide</li>';
	}
	if ($_POST['dir'] == '/') {
		echo "</li></ul>";
	}
	
	echo "</ul>";	
}

?>