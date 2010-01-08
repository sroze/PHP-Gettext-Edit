<?php
$file_path = '/home/mon/fichier/LC_MESSAGES/message.po';
var_dump($file_path);

$last_bracket = strrpos($file_path, '/');
var_dump($last_bracket);

$last_bracket2 = strripos($file_path, '/', 20);
var_dump($last_bracket2);

?>