<?php
require_once ROOT_PATH.'includes/librairies/File_INI.php';
require_once ROOT_PATH.'includes/librairies/Language_Str.php';

require_once ROOT_PATH.'includes/classes/Project_Template.php';
require_once ROOT_PATH.'includes/classes/Project_Language.php';
require_once ROOT_PATH.'includes/classes/Project_Language_File.php';

/**
 * Cette classe, Project, permet de gérer les différents projets au seins
 * de GetTextEdit.
 * 
 * @author Samuel ROZE <samuel.roze@gmail.com>
 */
class Project
{
	/**
	 * Contient le fichier INI, sous forme de tableau
	 * 
	 * @var array
	 */
	private $ini;
	
	/**
	 * Contient l'instance File_INI pour le fichier INI actuel.
	 * 
	 * @var File_INI
	 */
	private $file_ini_instance;
	
	/**
	 * Instance de base de données.
	 * 
	 * @var Database
	 */
	private $sql;
	
	/**
	 * ID du projet défini par cette classe.
	 * 
	 * @var integer
	 */
	public $id = null;
	
	/**
	 * Contient les informations du projet, une fois chargée depuis la base.
	 * 
	 * @var array
	 */
	private $informations = false;
	
	/**
	 * Constructeur.
	 * 
	 * @return Project
	 */
	public function __construct ($id = null)
	{
		global $sql;
		$this->sql = $sql;
		
		$this->id = $id;
	}
	/**
	 * Create a new project.
	 * 
	 * @param string $name
	 * @param string $path
	 * @param string $lang_path
	 * 
	 * @return integer $id
	 */
	static function create ($name, $path, $lang_path)
	{
		global $sql;
			
		$this->checkData($name, $path, $lang_path);
		
		$query = $sql->query('INSERT INTO projects (project_name, project_path, project_languages_path) VALUES
			("'.$name.'", "'.$path.'", "'.$lang_path.'")
		');
		
		if ($query) {
			return $sql->lastInsertId();
		}
	}
	
	/**
	 * Edit project's fields.
	 * 
	 * @param string $name
	 * @param string $path
	 * @param string $lang_path
	 * 
	 * @return bool
	 */
	public function edit ($name, $path, $lang_path)
	{
		$this->checkData($name, $path, $lang_path);
		
		$query = $this->sql->query('UPDATE projects SET 
			project_name = "'.$name.'", 
			project_path = "'.$path.'", 
			project_languages_path = "'.$lang_path.'"
			WHERE id = '.$this->id
		);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Delete the project.
	 * 
	 * @return bool
	 */
	public function delete ()
	{
		$query = $this->sql->query('DELETE FROM projects WHERE id = '.$this->id);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Check and parse data from user.
	 * 
	 * @param string $name
	 * @param string $path
	 * @param string $lang_path
	 * 
	 * @return bool
	 */
	private function checkData ($name, &$path, &$lang_path)
	{
		$path = str_replace('"', '\\"', $path);				
		$lang_path = str_replace('"', '\\"', $lang_path);
		if (substr($lang_path, -1) != '/') {
			$lang_path .= '/';
		}
		
		$path = realpath($path);
		if ($path === false) {
			throw new Project_Exception(
				sprintf(_('Le dossier "%s" n\'éxiste pas ou n\'est pas accessible'), $path)
			);
		}
		$path .= '/';
		
		if (!is_dir($path.$lang_path)) {
			throw new Project_Exception(
				sprintf(_('Le dossier "%s" n\'éxiste pas au sein du projet'), $lang_path)
			);
		}
		
		return true;
	}
	
	/**
	 * Return all languages of the project.
	 * 
	 * @return array
	 */
	public function getLanguages ()
	{
		$directory_path = $this->get('project_path').$this->get('project_languages_path');
		$directory = opendir($directory_path);
	
		$result = array();
	    /* Ceci est la façon correcte de traverser un dossier. */
	    while (false !== ($file = readdir($directory))) {
	        if (substr($file, 0, 1) != '.' && is_dir($directory_path.$file)) {
	        	$result[] = new Project_Language($this, $file);
	        }
	    }
	    
	    return $result;
	}
	
	/**
	 * Retourne la liste des templates
	 * 
	 * @return array
	 */
	public function getTemplates ()
	{
		$directory_path = $this->get('project_path').$this->get('project_languages_path');
		$directory = opendir($directory_path);
	
		$result = array();
	    /* Ceci est la façon correcte de traverser un dossier. */
	    while (false !== ($file = readdir($directory))) {
	        if (is_file($directory_path.$file) && substr($file, -4) == '.pot') {
	        	$result[] = new Project_Template(
	        		$this,
	        		substr($file, 0, -4)
	        	);
	        }
	    }
	    
	    return $result;
	}
	
	/**
	 * Récupère la liste des projets
	 * 
	 * @return array
	 */
	public function getList ()
	{
		$query = $this->sql->query(
			'SELECT project_id, project_name FROM projects'
		);
		
		return $query->fetchAll();
	}
	
	/**
	 * Récupère toutes les informations du projet depuis la base de données.
	 * 
	 * @return void
	 */
	private function getAll ()
	{
		$query = $this->sql->query(
			'SELECT * FROM projects WHERE project_id = '.$this->id
		);
		
		$this->informations = $query->fetch();
		
		if (empty($this->informations)) {
			throw new Project_Exception(
				sprintf(_('Le projet #%d ne semble pas exister'), $this->id)
			);
		}
	}
	
	/**
	 * Retourne une information tirée de la base de données à propos de project.
	 * 
	 * @param string $field
	 * @return mixed
	 */
	public function get ($field)
	{
		if (!$this->informations) {
			$this->getAll();
		}
		
		if (array_key_exists($field, $this->informations)) {
			return $this->informations[$field];
		} else {
			return null;
		}
	}
	
}

class Project_Exception extends Exception {}
