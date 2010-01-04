<?php
class Project_Template
{
	/**
	 * Contient l'instance décrivant le projet.
	 * 
	 * @var Project
	 */
	private $project;
	
	// Variables simples
	private $name;
	public $template_file;
	
	/**
	 * Constructeur.
	 * 
	 * @param Project $project
	 * @param string  $name
	 * 
	 * @return Project_Template
	 */
	public function __construct ($project, $name)
	{
		$this->project = $project;
		$this->name = $name;
		
		$this->template_file = $this->project->get('project_path').$this->project->get('project_languages_path').$this->name.'.pot';
	}
	
	/**
	 * Retourne le nom du template.
	 * 
	 * @return string
	 */
	public function getName ()
	{
		return $this->name;
	}
	
	/**
	 * Check if the template exists.
	 * 
	 * @return bool
	 */
	public function check ()
	{
		if (!is_file($this->template_file)) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Get contents of the template file.
	 * 
	 * @return string
	 */
	public function getContents ()
	{
		return file_get_contents($this->template_file);
	}
}
?>