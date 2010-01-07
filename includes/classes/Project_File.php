<?php
abstract class Project_File
{
	public $file_path;
	abstract public function __construct();

	/**
	 * Check if the file exists.
	 * 
	 * @return bool
	 */
	public function check ()
	{
		if (!is_file($this->file_path)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Get contents of file.
	 * 
	 * @return string
	 */
	public function getContents ()
	{
		if (!$this->check()) {
			throw new Project_File_Exception(
				_('Invalid file')
			);
		}
		
		return file_get_contents($this->file_path);
	}
	
	/**
	 * Return purified headers, ie an array like:
	 * 	[Content-Type] => '...',
	 * 	...
	 * 
	 * @return array
	 */
	public function getHeaders ()
	{
		$headers = $this->getHeadersBrut();
		$result = array();
		
		foreach ($headers as $header) {
			if (substr($header, -2) == '\n') {
				$header = substr($header, 0, -2);
			}
			$x = explode(':', $header);
			$result[array_shift($x)] = trim(implode(':', $x));
		}
		
		return $result;
	}
	
	/**
	 * Change the headers.
	 * 
	 * @param array $headers Array with keys, ie like
	 * 
	 * @return bool
	 */
	public function setHeaders ($headers)
	{
		$result = array();
		
		foreach ($headers as $header => $content) {
			$result[] = $header.':'.$content;
		}
		
		return $this->setHeaderBrut($result);
	}
	
	/**
	 * Result the header lines which are in the file.
	 * 	[Content-Type] => '...',
	 * 	...
	 * 
	 * @return array
	 */
	private function getHeadersBrut ()
	{
		$result = array();
		$position = 0;
		
		$file_contents = $this->getContents();
		
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
							$result[] = str_replace('&#147;', '"', $string);
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
		
		return $result;
	}
	
	/**
	 * Set .po file headers.
	 * 
	 * @param array $headers Array with headers strings. (no keys)
	 * @return bool
	 */
	private function setHeaderBrut ($headers)
	{
		$result = 'msgid ""'."\n".'msgstr ""'."\n";
		foreach ($headers as $header) {
			if (substr($header, -2) != '\n') { // Important: '\n' et pas "\n"
				$header .= '\n';
			}
			
			$result .= '"'.str_replace('"', '&#147;', $header).'"'."\n";
		}
		
		$file_contents = $this->getContents();
		
		$position = 0;
		while (false !== ($position = strpos($file_contents, 'msgid', $position))) {
			$first_crochet = strpos($file_contents, '"', $position);
			
			if ($file_contents[$first_crochet+1] == '"') { // On a notre "header"
				// On va chercher le prochain msgid
				$futur_msgid = strpos($file_contents, 'msgid', $position+1);		
				// On va prendre le bout entre les deux
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
				
				break;
			}
			$position++;
		}
		
		if (empty($part)) { // pas d'en-tête actuellement
			$part = 'msgid ""'."\n".'msgstr ""'."\n".'"GetTextEdit-header"';
			$file_contents = $part."\n".$file_contents;
		}
		
		$puts = file_put_contents(
			$this->file_path,
			str_replace(
				$part,
				$result,
				$file_contents
			)
		);
		
		if (!$puts) {
			throw new Project_File_Exception(
				_('Impossible d\'écrire le nouveau fichier')
			);
		} else {
			return true;
		}
	}
	
	/**
	 * Get messages from .po file
	 * 
	 * @return array
	 */
	public function getMessages ()
	{
		$file_contents = $this->getContents();
		$result = array();
		
		$position = 0;
		$prev_position = 0;
		while (false !== ($position = strpos($file_contents, 'msgid', $position))) {
			$futur_msgid = strpos($file_contents, 'msgid', $position+1);
			$first_crochet = strpos($file_contents, '"', $position);
			
			if ($file_contents[$first_crochet+1] != '"') { // Chaine non-vide (n'est pas le header)
				$msgid_end_post = strpos($file_contents, '"', $first_crochet+1);
				$msgid = substr($file_contents, $first_crochet+1, $msgid_end_post-$first_crochet-1);
				
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
				
				$msgstr_position = strpos($file_contents, 'msgstr', $msgid_end_post);
				
				$bracket_position = $msgstr_position;
				$string = '';
				$i = 0;
				
				while (false !== ($bracket_position = strpos($file_contents, '"', $bracket_position))) {
					if ($futur_msgid != false && $bracket_position > $futur_msgid) {
						break;
					}
					
					if ($i%2) {
						if (isset($last_bracket_position)) {
							if (!empty($string) && $file_contents[$bracket_position-1] != '\\') {
								$string .= "\n";
							}
							$string .= substr($file_contents, $last_bracket_position+1, $bracket_position-$last_bracket_position-1);
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
				
				unset($last_bracket_position);
			}
			
			$prev_position = $position;
			$position++;
		}
		
		return $result;
	}
	
	/**
	 * Edit a message.
	 * 
	 * @param string $searched_msgid
	 * @param string $new_msgstr
	 * @param string $comments
	 * 
	 * @return void
	 */
	public function editMessage ($searched_msgid, $new_msgstr, $comments = false, $fuzzy = false)
	{
		$file_contents = $this->getContents();
		$comments_lines = array();
		
		$position = 0;
		$prev_position = 0;
		
		while (false !== ($position = strpos($file_contents, 'msgid', $position))) {
			$first_crochet = strpos($file_contents, '"', $position);
			
			if ($file_contents[$first_crochet+1] != '"') { // Chaine non-vide (n'est pas le header)
				$msgid_end_post = strpos($file_contents, '"', $first_crochet+1);
				$msgid = substr($file_contents, $first_crochet+1, $msgid_end_post-$first_crochet-1);
				
				if (addslashes($searched_msgid) == $msgid) {
				
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
								case '#,':
									if (!strpos($line, 'fuzzy')) {
										$other_header_lines[] = $line;
									}
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
					
					break;
				}
			}
			
			$prev_position = $position;
			$position++;
		}
		
		$new_comments_formated = '';
		foreach ($other_header_lines as $other_line) {
			$new_comments_formated .= $other_line."\n";
		}
		if ($comments !== false) {
			foreach (explode("\n", $comments) as $comment) {
				$new_comments_formated .= '# '.$comment."\n";
			}
		}
		if ($fuzzy) {
			$new_comments_formated .= '#, fuzzy'."\n";
		}
		
		$new_msgstr_formated = 'msgstr "';
		$x = explode("\n", $new_msgstr);
		$x = array_map('addslashes', $x);
		$new_msgstr_formated .= implode('"'."\n".'"', $x);
		$new_msgstr_formated .= '"';
		
		if (!isset($part)) { // le msgid n'a pas été trouvé
			$file_contents .= "\n\n".$new_comments_formated.
				'msgid "'.addslashes($searched_msgid).'"'."\n".
				$new_msgstr_formated;
		} else if ($new_msgstr === false) {
			$file_contents = str_replace($part, '', $file_contents);
		} else {
			$file_contents = str_replace(
				$part,
				$new_comments_formated.'msgid "'.addslashes($searched_msgid).'"'."\n".
				$new_msgstr_formated,
				$file_contents
			);
		}
		
		$puts = file_put_contents(
			$this->file_path,
			$file_contents
		);
		
		if (!$puts) {
			throw new Project_File_Exception(
				_('Impossible d\'écrire le nouveau fichier')
			);
		} else {
			return true;
		}
	}
}
class Project_File_Exception extends Exception {}
?>