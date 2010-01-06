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

echo '{total:'.count($messages).',rows:[';

$fuzzies = '';
$out = '';
foreach ($messages as $msgid => $informations) {
	
	if ($informations['fuzzy']) {
		if (!empty($fuzzies)) { $fuzzies .= ','; }
		$fuzzies .= '{id:'.$i.',cell:[1,\''.addslashes($msgid).'\',\''.addslashes($informations['msgstr']).'\']}'."\n";
	} else {
		if (!empty($out)) { $out .= ','; }
		$out .= '{id:'.$i.',cell:[1,\''.addslashes($msgid).'\',\''.addslashes($informations['msgstr']).'\']}'."\n";
	}
}
echo $fuzzies, $out;

echo ']}';

?>