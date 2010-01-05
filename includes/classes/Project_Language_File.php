<?php
class Project_Language_File
{
	private $language;
	private $name;
	
	/**
	 * Constructeur.
	 * 
	 * @param Project_Language $language
	 * @param string $name
	 * 
	 * @return Project_Language_File
	 */
	public function __construct ($language, $name)
	{
		$this->language = $language;
		$this->name = $name;
	}
	
	/**
	 * Check if the file exists.
	 * 
	 * @return bool
	 */
	public function check ()
	{
		if (!is_file($this->language->directory_path.$name.'.po')) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Return the name of the language.
	 * 
	 * @return string
	 */
	public function getName ()
	{
		return $this->name;
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
	 * Result the header lines which are in the file.
	 * 
	 * @return array
	 */
	private function getHeadersBrut ()
	{
		$result = array();
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
							$result[] = $string;
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
}
?>