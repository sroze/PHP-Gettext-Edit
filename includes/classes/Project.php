<?php
require_once ROOT_PATH.'includes/librairies/File_INI.php';

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
