<?php
define('ROOT_PATH', realpath(dirname(__FILE__).'/../').'/');

require_once ROOT_PATH.'includes/ini_conf.php';
require_once ROOT_PATH.'includes/configuration.php';
require_once ROOT_PATH.'includes/librairies/Rights/Rights_Admin.php';

if (!Rights::check('project_users_access', array(
		'project' => $_POST['project']
	))) {
	echo 'Forbidden';
	exit();
}

if (isset($_POST['project'])) {
	$users = GTE::getUsersHavingRight('project_access', array(
		'project' => $_GET['project']
	));
	
	$out = array(
		'total' => count($users),
		'rows' => array()
	);
	
	foreach ($users as $informations) {
		
		$out['rows'][] = array(
			'id' => $informations['id'],
			'cell' => array(
				$informations['id'],
				$informations['username'],
				'<img class="loading" />'
			)
		);
	}
	
	echo json_encode($out);
} else {
	echo 'Invalid arguments';
}

?>