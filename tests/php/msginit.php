<?php
define('ROOT_PATH', realpath(dirname(__FILE__)).'/');

$exec = exec('msginit -i "'.ROOT_PATH.'file.pot" -o "'.ROOT_PATH.'file.po" --no-translator');
var_dump($exec);
?>
