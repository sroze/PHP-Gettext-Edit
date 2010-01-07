<?php
define('ROOT_PATH', realpath(dirname(__FILE__).'/../').'/');

require_once ROOT_PATH.'includes/configuration.php';
require_once ROOT_PATH.'includes/classes/Project.php';
require_once ROOT_PATH.'includes/classes/Project_Language.php';
require_once ROOT_PATH.'includes/classes/Project_Language_File.php';

$project = new Project($_POST['project']);
$language = new Project_Language($project, $_POST['language']);
$language_file = new Project_Language_File($language, $_POST['file']);

$messages = $language_file->getMessages();
$asc = ($_POST['sortorder'] == 'asc') ? true : false;

switch ($_POST['sortname']) {
	case 'msgid':
		if ($asc) {
			ksort($messages);
		} else {
			krsort($messages);
		}
		break;
}

// No expires
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
header("Cache-Control: no-cache, must-revalidate" ); 
header("Pragma: no-cache" );
header("Content-type: text/x-json");

$out = array(
	'total' => count($messages),
	'rows' => array()
);

$i = 0;
foreach ($messages as $msgid => $informations) {
	
	$row = array(
		'id' => $i,
		'cell' => array(
			($informations['fuzzy'] ? '1' : '0'),
			$msgid,
			$informations['msgstr']
		),
		'comments' => $informations['comments'],
		'references' => $informations['references'],
		'fuzzy' => $informations['fuzzy']
	);
	
	if ($informations['fuzzy']) {
		array_unshift($out['rows'], $row);
	} else {
		array_push($out['rows'], $row);
	}
	$i++;
}

echo json_encode($out);

?>