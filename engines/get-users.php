<?php
define('ROOT_PATH', realpath(dirname(__FILE__).'/../').'/');

require_once ROOT_PATH.'includes/ini_conf.php';
require_once ROOT_PATH.'includes/configuration.php';
require_once ROOT_PATH.'includes/librairies/Rights/Rights_Admin.php';

// Patch for performances
session_write_close();

$context = GTE::buildContext($_POST);

if (array_key_exists('language_file', $context)) {
	$right = 'language_file_users_';
} else if (array_key_exists('language', $context)) {
	$right = 'language_users_';
} else if (array_key_exists('template', $context)) {
	$right = 'template_users_';
} else if (array_key_exists('project', $context)) {
	$right = 'project_users_';
} else {
	echo 'Bad context';
	exit();
}

if (!Rights::check($right.'access', $context)) {
	header('HTTP/1.0 403 Forbidden');
	header('Status: 403');
	echo 'Forbidden';
	exit();
}

if ($_POST['query'] == 'select') {
	$users = GTE::getUsersHavingRight($right.'access', $context);
	
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
} else if ($_POST['query'] == 'delete' && Rights::check($right.'admin', $context)) {
	$user_name = $_POST['msgids'][0];
	$user_id = GTE::getUserIdFromUsername($user_name);
		
	Rights_Admin::removeUserRights($user_id, array('project_access'));
} else if ($_POST['query'] == 'select-more') { // Groups & Rights
	if (isset($_POST['user'])) {
		$groups_all = Rights_Admin::getUserGroups((int) $_POST['user'], $context);
		$groups = array();
		foreach ($groups_all as $group) {
			$groups[] = $group['name'];
		}
		
		$rights_all = Rights_Admin::getUserRights((int) $_POST['user'], $context);
		$rights = array();
		foreach ($rights_all as $right) {
			$rights[] = $right['name'];
		}
		
		echo json_encode(
			array(
				'groups' => $groups,
				'rights' => $rights
			)
		);
	} else {
		echo 'Invalid arguments';
	}
} else {
	echo 'Invalid query';
}

?>