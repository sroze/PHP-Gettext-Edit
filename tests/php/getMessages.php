<?php
define('ROOT_PATH', realpath(dirname(__FILE__)).'/');
$file_contents = file_get_contents(ROOT_PATH.'file.po');

$result = array();

$position = 0;
$prev_position = 0;
while (false !== ($position = strpos($file_contents, 'msgid', $position))) {
	$futur_msgid = strpos($file_contents, 'msgid', $position+1);
	$msgstr_position = strpos($file_contents, 'msgstr', $position);
	
	$bracket_position = $position;
	$i = 0;
	$msgid = '';
	while (false !== ($bracket_position = strpos($file_contents, '"', $bracket_position))) {
		if ($msgstr_position != false && $bracket_position > $msgstr_position) {
			break;
		}
			
		if ($i%2) {
			if (isset($last_bracket_position)) {
				$msgid .= substr($file_contents, $last_bracket_position+1, $bracket_position-$last_bracket_position-1);
			}
		}
		if ($file_contents[$bracket_position-1] != '\\') {
			$i++;
		}
			
		$last_bracket_position = $bracket_position;
		$bracket_position++;
	}
	
	if (!empty($msgid)) {		
		$result[$msgid] = array(
			'msgstr' => null,
			'references' => array(),
			'fuzzy' => false,
			'comments' => ''
		);
		
		$comments_part = substr($file_contents, $prev_position, $position-$prev_position);
		
		$dieze_position = 0;
		while (false !== ($dieze_position = strpos($comments_part, "\n".'#', $dieze_position))) {
			$dieze_position++;
			$end = strpos($comments_part, "\n", $dieze_position);
			$line = substr($comments_part, $dieze_position, $end-$dieze_position);
			
			switch (substr($line, 0, 2)) {
				case '#:':
					$result[$msgid]['references'][] = trim(substr($line, 2));
					break;
				case '#,':
					if (strpos($line, 'fuzzy')) {
						$result[$msgid]['fuzzy'] = true;
					}
					break;
				default:
					$result[$msgid]['comments'] .= trim(substr($line, 1))."\n";
					break;
			}
		}
		
		$bracket_position = $msgstr_position;
		$string = '';
		$i = 0;
		
		while (false !== ($bracket_position = strpos($file_contents, '"', $bracket_position))) {
			if ($futur_msgid != false && $bracket_position > $futur_msgid) {
				break;
			}
			
			if ($i%2) {
				if (isset($last_bracket_position)) {
					$string .= substr($file_contents, $last_bracket_position+1, $bracket_position-$last_bracket_position-1);
					var_dump('.=', $string);
				}
			}
			if ($file_contents[$bracket_position-1] != '\\') {
				$i++;
			}
			
			$last_bracket_position = $bracket_position;
			$bracket_position++;
		}
		
		$result[$msgid]['msgstr'] = $string;
		if (!empty($result[$msgid]['comments'])) {
			$result[$msgid]['comments'] = substr($result[$msgid]['comments'], 0, -1);
		}
	}
	
	$prev_position = $position;
	$position++;
	unset($last_bracket_position);
}

var_dump($result);

?>