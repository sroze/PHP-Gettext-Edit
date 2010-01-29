<?php
/**
 * Retourne la liste des groupes d'un utilisateur, pour un context donné,
 * bien entendu.
 */
define('ROOT_PATH', realpath(dirname(__FILE__).'/../').'/');

require_once ROOT_PATH.'includes/ini_conf.php';
require_once ROOT_PATH.'includes/configuration.php';
require_once ROOT_PATH.'includes/librairies/Rights/Rights_Admin.php';

if (empty($_POST)) {
	$_POST = $_GET;
}
$context = GTE::buildContext($_POST);

if (!Rights::check('project_users_access', $context)) {
	header('HTTP/1.0 403 Forbidden');
	header('Status: 403');
	echo 'Forbidden';
	exit();
}

if ($_POST['query'] == 'select') {
	$user = (int) $_POST['user'];
	if (empty($user)) {
		echo 'Bad request';
		exit();
	}
	
	$groups = Rights_Admin::getUserGroups($user, $context);
	
	$out = array(
		'total' => count($groups),
		'rows' => array()
	);
			
	foreach ($groups as $informations) {
		
		$out['rows'][] = array(
			'id' => $informations['id'],
			'cell' => array(
				'#'.$informations['id'],
				$informations['name']
			)
		);
	}
			
	echo json_encode($out);
} else if ($_POST['query'] == 'list') {
	$groups = Rights_Admin::getGroups();
	
	foreach ($groups as $group) {
		echo $group['name'].',';
	}
} else {
	echo 'Bad query';
	exit();
}

?>