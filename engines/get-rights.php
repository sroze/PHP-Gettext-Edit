<?php
/**
 * Retourne le status des droits demandés. C'est-à-dire que l'application
 * PHP-Gettext-Edit va lui envoyer une liste de droits et /engines/get-rights.pĥp
 * va retourner une liste de la forme:
 * array(
 * 	array(
 * 		'name' => nom du droit,
 * 		'id' => id du droit,
 * 		'grant' => true/false,
 * 		'from' => 'user'|'group'|''
 * 	)
 * ...
 * )
 */
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

$user = (int) $_POST['user'];
if (empty($user)) {
	echo 'Bad request';
	exit();
}

if ($_POST['query'] == 'select') {
	echo json_encode(
		Rights_Admin::getUserRightEffectiveInformations(
			$user,
			$_POST['right'],
			$context
		)
	);
} else if ($_POST['query'] == 'delete' && Rights::check($right.'admin', $context)) {
	$groups = json_decode($_POST['groups']);
	foreach ($groups as $k => $group) {
		$groups[$k] = (int) $group;
	}
	
	Rights_Admin::removeUserGroups(
		$user,
		$groups,
		$context
	);
	
	header("HTTP/1.0 204 No Content");
	header("status: 204");
} else {
	echo 'Bad query';
	exit();
}

?>