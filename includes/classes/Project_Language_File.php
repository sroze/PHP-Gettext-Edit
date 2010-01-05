<?php
require_once ROOT_PATH.'includes/classes/Project_File.php';

class Project_Language_File extends Project_File
{
	private $language;
	private $name;
	
	private $file_path;
	
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
}

class Project_Language_File_Exception extends Exception {}
?>