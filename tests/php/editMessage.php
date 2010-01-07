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
		$comments_part = substr($file_contents, $prev_position, $position-$prev_position);
		$comments_lines = array();
		$other_header_lines = array();
		
		$dieze_position = 0;
		$prev_dieze_position = 0;
		$start_comments = strpos($comments_part, "\n".'#');
		if ($start_comments !== false) {
			$comments_part = substr($comments_part, $start_comments);
			$part = $comments_part.$part;
			
			while (false !== ($dieze_position = strpos($comments_part, "\n".'#', $dieze_position))) {
				$dieze_position++;
				$dieze_end = strpos($comments_part, "\n", $dieze_position);
				$line = substr($comments_part, $dieze_position, $dieze_end-$dieze_position);
				
				switch (substr($line, 0, 2)) {
					case '# ':
						$comments_lines[] = $line;
						break;
					default:
						$other_header_lines[] = $line;
						break;
				}
				$prev_dieze_position = $dieze_position;
			}
		} else {
			// Puis on cherche..la fin!
			$comment_pos = strpos($part, "\n".'#');
			if ($comment_pos !== false) {
				$part = substr($part, 0, $comment_pos);
			} else {
				$prev_quote = strrpos($part, '"');
				$part = substr($part, 0, $prev_quote+1);
			}
		}
		$part = trim($part);
		$top_part = trim($top_part);
		
		var_dump($msgid, $part, $other_header_lines, '-------------------');
	}
	
	$prev_position = $position;
	$position++;
}

?>