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
	        } else if (is_dir($this->directory_path.$file)) {
	        	foreach ($this->getFilesInDirectory($directory_name.'/'.$file) as $file2) {
	        		$result[] = $file2;
	        	}
	        }
	    }
	    
	    return $result;
	}
}
?>