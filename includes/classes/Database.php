<?php
class Database
{
	/**
	 * Adresse du fichier de base de données SQLLite
	 * 
	 * @var string
	 */
	public $filename;
	
	/**
	 * Instance de PDO, avec la connexion à la base.
	 * 
	 * @var PDO
	 */
	private $database;
	
	/**
	 * Constructeur.
	 * 
	 * @param string $filename
	 * @return Database
	 */
	public function __constuct ($filename)
	{
		$this->filename = $filename;
		var_dump($filename, $this->filename);
	}
	
	/**
	 * Créé la connection au fichier de base de données.
	 * 
	 * @return bool
	 */
	private function openConnection ()
	{
		$this->database = new PDO('sqlite:'.$this->filename);
		if (!$this->database) {
			throw new Database_Exception(
				'Impossible de se connecter à la base de données: '.$error
			);
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Envoi une requête à la base.
	 * 
	 * @param string $query
	 * @return PDOStatement
	 */
	public function query ($query)
	{
		if (!$this->database) {
			$this->openConnection();
		}
		
		return $this->database->query($query);
	}
	
	/**
	 * Renvoi l'ID du dernier élément enregistré.
	 * 
	 * @return integer
	 */
	public function lastInsertId ()
	{
		return (int) $this->database->lastInsertId();
	}
}

class Database_Exception extends Exception {}
?>