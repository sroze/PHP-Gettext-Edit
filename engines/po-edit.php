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

echo '{total:'.count($messages).',rows:[';

$i = 0;
foreach ($messages as $msgid => $informations) {
	if ($i > 0) {
		echo ',';
	}
	echo '{id:'.$i.',cell:[';
		echo $informations['fuzzy'].',\''.addslashes($msgid).'\',\''.addslashes($informations['msgstr']).'\'';
	echo ']}'."\n";
	
	$i++;
}

echo ']}';

?>