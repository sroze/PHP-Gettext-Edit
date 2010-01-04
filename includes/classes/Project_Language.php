<?php
require_once ROOT_PATH.'includes/librairies/Language_Str.php';

class Project_Language
{
	/**
	 * Instance of Project
	 * 
	 * @var Project
	 */
	private $project;
	
	// Simple variables
	private $code;
	public $directory_path;
	
	/**
	 * Constructeur.
	 * 
	 * @param Project $project
	 * @param string $code
	 * 
	 * @return Project_Language
	 */
	public function __construct ($project, $code)
	{
		$this->project = $project;
		$this->code = $code;
		
		$this->directory_path = $this->project->get('project_path').$this->project->get('project_languages_path').$this->code.'/';
	}
	
	/**
	 * Create a language.
	 * 
	 * @param Project $project
	 * @param string $code
	 * 
	 * @return Project_Language
	 */
	static function create ($project, $code)
	{
		if (is_dir($project->get('project_path').$project->get('project_languages_path').$code.'/')) {
			throw new Project_Language_Exception(
				sprintf(_('La langue %s existe déjà'), $code)
			);
		} else if (!mkdir($project->get('project_path').$project->get('project_languages_path').$code.'/')) {
			throw new Project_Language_Exception(
				_('Impossible de créer le dossier')
			);
		} 
		
		return new Project_Language($project, $code);
	}
	
	/**
	 * Create a new .po file for this language. (from a template, or not)
	 * 
	 * @param string $name
	 * @param string $template
	 * 
	 * @return Project_Language_File
	 */
	public function createFile ($name, $template = null)
	{
		$file_path = $this->directory_path.$name;
		
		if (is_file($file_path)) {
			throw new Project_Language_Exception(
				_('Le fichier existe déjà')
			);
		}
		
		if ($template == null) {
			$template = '';
		} else if (is_string($template)) {
			$template = new Project_Template($this->project, $template);
			$template = $template->template_file;
		} else if (is_object($template)) {
			if (get_class($template) == 'Project_Template') {
				$template = $template->template_file;
			} else {
				throw new Project_Language_Exception(
					_('Type de template inconnu')
				);
			}
		}
		
		if (empty($template)) {
			if (!file_puts_contents($file_path, '')) {
				throw new Project_Language_Exception(
					_('Impossible d\'écrire le fichier vide')
				);
			}
		} else {
			$msginit = shell_exec('msginit -i "'.$template.'" -o "'.$file_path.'"');
			var_dump($msginit, 'msginit -i "'.$template.'" -o "'.$file_path.'"');
		}
		
		return new Project_Language_File($this, $name);
	}
	
	/**
	 * Check if the language directory exists.
	 * 
	 * @return bool
	 */
	public function check ()
	{
		if (is_dir($this->directory_path)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Return the code of the language
	 * 
	 * @return string
	 */
	public function getCode ()
	{
		return $this->code;
	}
	
	/**
	 * Return the formated name of the language
	 * 
	 * @return string
	 */
	public function getName ()
	{
		return _(Language_Str::get($this->code));
	}
	
	/**
	 * Return the list of all .po files which are in the language.
	 * 
	 * @return array
	 */
	public function getFiles ()
	{
		return $this->getFilesInDirectory('');
	}
	
	/**
	 * Search .po files from directory and its sub-directories.
	 * 
	 * @param string $directory_name
	 * 
	 * @return array
	 */
	private function getFilesInDirectory ($directory_name)
	{
		$directory = opendir($this->directory_path.$directory_name);
	
		$result = array();
	    /* Ceci est la façon correcte de traverser un dossier. */
	    while (false !== ($file = readdir($directory))) {
	        if (is_file($this->directory_path.$file) && substr($file, -3) == '.po') {
	        	$result[] = str_replace(
				        		'/',
				        		'@',
				        		$directory_name.substr($file, 0, -3)
				        	);
	        } else if (is_dir($this->directory_path.$file) && substr($file, 0, 1) != '.') {
	        	foreach ($this->getFilesInDirectory($directory_name.'/'.$file) as $file2) {
	        		$result[] = $file2;
	        	}
	        }
	    }
	    
	    return $result;
	}
}

class Project_Language_Exception extends Exception {}
?>