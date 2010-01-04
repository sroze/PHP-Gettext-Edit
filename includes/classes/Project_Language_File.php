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
}
?>