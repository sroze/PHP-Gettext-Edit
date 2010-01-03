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
	 * Constructeur.
	 * 
	 * @return Project
	 */
	public function __construct ()
	{
		global $sql;
		
		$this->sql = $sql;
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
	
}