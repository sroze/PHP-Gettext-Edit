<?php
define('ROOT_PATH', realpath(dirname(__FILE__)).'/');
$file_contents = file_get_contents(ROOT_PATH.'file.po');


$position = 0;
$prev_position = 0;
while (false !== ($position = strpos($file_contents, 'msgid', $position))) {
	$first_crochet = strpos($file_contents, '"', $position);
	
	if ($file_contents[$first_crochet+1] != '"') { // Chaine non-vide (n'est pas le header)
		$msgid_end_post = strpos($file_contents, '"', $first_crochet+1);
		$msgid = substr($file_contents, $first_crochet+1, $msgid_end_post-$first_crochet-1);
		
		$futur_msgid = strpos($file_contents, 'msgid', $position+1);
		if ($futur_msgid === false) {
			$futur_msgid = strlen($file_contents);
		}
		$part = substr($file_contents, $position, $futur_msgid-$position);
		
		// Puis on cherche..la fin!
		$comment_pos = strpos($part, "\n".'#');
		if ($comment_pos !== false) {
			$part = substr($part, 0, $comment_pos);
		} else {
			$prev_quote = strrpos($part, '"');
			$part = substr($part, 0, $prev_quote+1);
		}
		$part = trim($part);
		
		var_dump($msgid, $part);
	}
	
	$prev_position = $position;
	$position++;
}

?>