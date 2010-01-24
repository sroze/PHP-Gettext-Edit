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

if ($_POST['query'] == 'select') {
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
					'#'.$informations['id'],
					$informations['username'],
					'<span class="icon loading" />',
					'<span class="icon loading" />'
				)
			);
		}
		
		echo json_encode($out);
	} else {
		echo 'Invalid arguments';
	}
} else if ($_POST['query'] == 'delete') {
	if (isset($_POST['project'])) {
		$user_name = $_POST['msgids'][0];
		$user_id = GTE::getUserIdFromUsername($user_name);
		
		Rights_Admin::removeUserRights($user_id, array('project_access'));
	} else {
		echo 'Invalid arguments';
	}
} else {
	echo 'Invalid query';
}

?>