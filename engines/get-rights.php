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

$context = GTE::buildContext($_POST);

if (!Rights::check('project_users_access', $context)) {
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
} else {
	echo 'Bad query';
	exit();
}

?>