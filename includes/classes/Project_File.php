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
			$result[array_shift($x)] = implode(':', $x);
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
				$comment_pos = strpos($part, '#');
				if ($comment_pos !== false) {
					$part = substr($part, 0, $comment_pos);
				} else {
					$prev_quote = strrpos($part, '"');
					$part = substr($part, 0, $prev_quote);
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
}
class Project_File_Exception extends Exception {}
?>