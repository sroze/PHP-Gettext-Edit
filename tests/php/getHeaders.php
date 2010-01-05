<?php
define('ROOT_PATH', realpath(dirname(__FILE__)).'/');

function getHeaderRegex () {
	$header_regex = '#msgid:space:"":space:msgstr:space:(.+):space:msgid#U';
	$espace_regex = '([ 	'."\n".']*)';
	
	return str_replace(':space:', $espace_regex, $header_regex);
}

$file_contents = file_get_contents(ROOT_PATH.'file.po');

/*
$regex = getHeaderRegex();
var_dump($regex);


$preg = preg_match($regex, $file_contents, $matches);
var_dump($preg, $matches);
*/

$position = 0;
while (false !== ($position = strpos($file_contents, 'msgid', $position))) {
	$futur_msgid = strpos($file_contents, 'msgid', $position+1);
	$first_crochet = strpos($file_contents, '"', $position);
	
	if ($file_contents[$first_crochet+1] == '"') { // On a notre "header"
		
		$msgstr_position = strpos($file_contents, 'msgstr', $first_crochet);
		
		$bracket_position = $msgstr_position;
		$string = '';
		
		while (false !== ($bracket_position = strpos($file_contents, '"', $bracket_position))) {
			if ($futur_msgid != false && $bracket_position > $futur_msgid) {
				break;
			}
			
			if (isset($last_bracket_position)) {
				$string .= substr($file_contents, $last_bracket_position+1, $bracket_position-$last_bracket_position-1);
			}
			
			if ($file_contents[$bracket_position-1] != '\\') {
				$string = trim($string);
				if ($string != '"' AND !empty($string)) {
					var_dump($string);
				}
				$string = '';
			}
			
			$last_bracket_position = $bracket_position;
			$bracket_position++;
		}
		
		break;
	}
	$position++;
}
?>