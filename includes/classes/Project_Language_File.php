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
		
		return new Project_Language_File($language, $template->getType().'/'.$name);
	}
	
	/**
	 * Update file from its template.
	 * 
	 * @return bool
	 */
	public function update ()
	{
		
	}
	
	/**
	 * Return the template instance of file.
	 * 
	 * @return Project_Template
	 */
	private function getTemplate ()
	{
		$headers = $this->getHeaders();
	}
}

class Project_Language_File_Exception extends Exception {}
?>