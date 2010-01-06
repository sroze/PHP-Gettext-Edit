<?php
require_once ROOT_PATH.'includes/classes/Project_File.php';

class Project_Template extends Project_File
{
	/**
	 * Contient l'instance décrivant le projet.
	 * 
	 * @var Project
	 */
	private $project;
	
	// Simple variables
	private $name;
	
	/**
	 * Languages that user can chose for xgettext.
	 * 
	 * @var array
	 */
	static $available_languages = array(
		'PHP', 
		'C', 
		'C++', 
		'Shell', 
		'Python', 
		'Scheme', 
		'Java', 
		'C#', 
		'Perl', 
		'Glade'
	);
	
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
		
		$this->file_path = $this->project->get('project_path').$this->project->get('project_languages_path').$this->name.'.pot';
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
	 * Create a new template.
	 * @param Project $project
	 * @param string  $name 			Name of the template, likes messages
	 * @param string  $type 			Type of template, like LC_MESSAGES
	 * @param string  $language 		In what code, like PHP, C, C++...
	 * @param array   $keywords 		Additionnal keywords
	 * @param array   $files			Files and directories to scan (cleaned by File::cleanTree please)
	 * @param bool    $delete_old		Delete, or not, entries that aren't still used
	 * 
	 * @return Project_Template
	 */
	static function create ($project, $name, $type, $language, $keywords = null, $files = null, $delete_old = false)
	{
		$template = new Project_Template($project, $name);
		$file_root = $project->get('project_path');
		
		if (file_put_contents($template->file_path, '') === false) {
			throw new Project_Template_Exception(
				sprintf(_('Impossible d\'écrire dans le nouveau fichier (%s)'), $template->file_path)
			);
		} else if (!in_array($language, self::$available_languages)) {
			throw new Project_Template_Exception(
				_('Le language de programmation n\'est pas valide')
			);
		}
		
		$keywords_string = '';
		if (!empty($keywords)) {
			foreach ($keywords as $keyword) {
				$keywords_string .= '--keyword="'.$keyword.'" ';
			}
		}
		
		$directories_string = '';
		$files_string = '';
		if (!empty($files)) {
			foreach ($files as $file) {
				if (substr($file, -1) == '/') { // directory
					$directories_string .= '--directory="'.$file_root.$file.'" ';
				} else {
					$files_string .= '"'.$file_root.$file.'" ';
				}
			}
		}
		
		$command = 'xgettext --force-po --add-location --sort-output --language="'.$language.'" --output="'.$template->file_path.'"'.
			$keywords_string.$directories_string.$files_string;
		$exec_result = exec($command);
		
		var_dump($command, $exec_result);
		
		if (!$template->check()) {
			throw new Project_Template_Exception(
				_('Une erreur inconnue est arrivée')
			);
		} else {
			return $template;
		}
	}
}

class Project_Template_Exception extends Exception {}
?>