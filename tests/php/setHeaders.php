<?php
include 'getHeaders.php';

var_dump('---------------');

$position = 0;
while (false !== ($position = strpos($file_contents, 'msgid', $position))) {
	$first_crochet = strpos($file_contents, '"', $position);
	
	if ($file_contents[$first_crochet+1] == '"') { // On a notre "header"
		// On va chercher le prochain msgid
		$futur_msgid = strpos($file_contents, 'msgid', $position+1);		
		// On va prendre le bout entre les deux
		$part = trim(substr($file_contents, $position, $futur_msgid-$position));
		
		break;
	}
	$position++;
}

?>