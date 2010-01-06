<?php
require_once ROOT_PATH.'includes/classes/Project_File.php';

class Project_Language_File extends Project_File
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
		
		$this->file_path = $this->language->directory_path.$this->name.'.po';
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
	 * Create a new .po file.
	 * 
	 * @param Project_Language 	$language
	 * @param string			$name
	 * @param Project_Template	$template
	 * 
	 * @return Project_Language_File
	 */
	static function create ($language, $name, $template)
	{
		if (empty($template)) {
			throw new Project_Language_Exception(
				_('Pour initialiser un fichier .po, il faut un template')
			);
		} else if (is_string($template)) {
			$template = new Project_Template($language->project, $template);
		} else if (is_object($template)) {
			if (get_class($template) == 'Project_Template') {
				// Perfect!
			} else {
				throw new Project_Language_Exception(
					sprintf(_('Type de template inconnu: %s'), get_class($template))
				);
			}
		}
		
		$directory_path = $language->directory_path.$template->getType().'/';
		if (!is_dir($directory_path)) {
			if (!mkdir($directory_path)) {
				throw new Project_Language_Exception(
					sprintf(_('Impossible de créer le dossier du type: %s'), $directory_path)
				);
			}
		}
		
		$file_path = $directory_path.$name.'.po';
		
		if (is_file($file_path)) {
			throw new Project_Language_Exception(
				_('Le fichier existe déjà')
			);
		} else if (file_put_contents($file_path, '') === false) {
			throw new Project_Language_Exception(
				sprintf(_('Impossible d\'écrire le fichier: %s'), $file_path)
			);
		}
		
		if (!empty($template)) {
			$command = 'msginit --input="'.$template->file_path.'" --output-file="'.$file_path.'" --no-translator --locale="'.$language->getCode().'"';
			$exec_result = exec($command);
			//var_dump($command, $exec_result);
		}
		
		$language_file = new Project_Language_File($language, $template->getType().'/'.$name);
		$language_file_headers = $language_file->getHeaders();
		$language_file_headers['GetTextEdit-template'] = $template->getName();
		$language_file->setHeaders($language_file_headers);
		
		return $language_file;
	}
	
	/**
	 * Update file from its template.
	 * 
	 * @return bool
	 */
	public function update ()
	{
		$template = $this->getTemplate();
		
		$command = 'msgmerge '.
			'--update '.
			'--sort-output '.
			'--quiet '.
			'"'.$this->file_path.'" "'.$template->file_path.'"';
		$exec_result = exec($command);
		//var_dump($command, $exec_result);
	}
	
	/**
	 * Delete the file.
	 * 
	 * @return bool
	 */
	public function delete ()
	{
		if (!unlink($this->file_path)) {
			throw new Project_Language_File_Exception(
				sprintf(_('Impossible de supprimer le fichier: %s'), $this->file_path)
			);
		} else {
			return true;
		}
	}
	
	/**
	 * Return the name of the file template
	 * 
	 * @return string
	 */
	public function getTemplateName ()
	{
		$template = $this->getTemplate();
		return $template->getName();
	}
	
	/**
	 * Return the template instance of file.
	 * 
	 * @return Project_Template
	 */
	private function getTemplate ()
	{
		$headers = $this->getHeaders();
		
		if (!array_key_exists('GetTextEdit-template', $headers)) {
			throw new Project_Language_Exception(
				_('Impossible d\'identifier le template de ce fichier')
			);
		} else {
			return new Project_Template(
				$this->language->project,
				trim($headers['GetTextEdit-template'])
			);
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
				$result[$msgid]['comments'] = substr($result[$msgid]['comments'], 0, -1);
				
				unset($last_bracket_position);
			}
			
			$prev_position = $position;
			$position++;
		}
		
		return $result;
	}
}

class Project_Language_File_Exception extends Exception {}
?>