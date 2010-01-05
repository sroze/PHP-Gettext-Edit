<?php
define('ROOT_PATH', realpath(dirname(__FILE__)).'/');

$command = '/usr/bin/msginit2 --input="'.ROOT_PATH.'file.pot" --output="'.ROOT_PATH.'file.po" --locale=fr_FR --no-translator';
$exec = exec($command);
var_dump($command, $exec);
?>
