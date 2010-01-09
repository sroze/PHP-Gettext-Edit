<?php
$file_path = '/home/mon/fichier/LC_MESSAGES/message.po';
var_dump($file_path);

$last_bracket = strrpos($file_path, '/');
var_dump($last_bracket);

$last_bracket2 = strrpos($file_path, '/', -1*(strlen($file_path)-$last_bracket+1));
var_dump($last_bracket2);

?>