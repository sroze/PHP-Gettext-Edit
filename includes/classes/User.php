<?php
require_once ROOT_PATH.'includes/librairies/String.php';

/**
 * This class define a PHP-Gettext-Edit user.
 * 
 * @author Samuel ROZE <samuel.roze@gmail.com>
 */
class User
{
	/**
	 * User's informations.
	 * 
	 * @var integer
	 */
	private $informations = array();
	
	/**
	 * Construct the User instance with informations about the user.
	 * 
	 * @param array|integer $informations
	 * 
	 * @return User
	 */
	public function __construct ($informations)
	{
		if (!array_key_exists('id', $informations)) {
			throw new User_Exception(
				_('L\'ID utilisateur n\'est pas défini')
			);
		}
		
		$this->informations = $informations;
		
		define('CONNECTED', true);
		Rights::setUser((int) $this->get('id'));
	}
	
	/**
	 * Get an information about the user.
	 * 
	 * @param string $name
	 * 
	 * @return mixed
	 */
	public function get ($name)
	{
		if (isset($this->informations[$name])) {
			return $this->informations[$name];
		} else {
			throw new user_Exception(
				sprintf(
					_('Le champ "%s" n\'éxiste pas'),
					$name
				)
			);
		}
	}
	
	/**
	 * Create a new user.
	 * 
	 * @param string $username
	 * @param string $password
	 * @param string $email
	 * 
	 * @return integer $user_id
	 */
	static function create ($username, $password, $email)
	{
		global $_CONFIG;
		
		$query = Database::$sql->query(
			sprintf(
				Database::$requests->get('create_user'),
				Database::$prefix.'users',
				$username,
				String::crypt($password),
				$email
			)
		);
		
		if (!$query) {
			$sql_error = Database::$sql->errorInfo();
			throw new User_Exception(
				sprintf(
					_('Impossible de créer un nouveau compte utilisateur: %s'),
					$sql_error[2]
				)
			);
		}
		
		if (Database::$database_type == 'pgsql') {
			$query_result = $query->fetch();
			$id = $query_result['id'];
		} else {
			$id = Database::$sql->lastInsertId();
		}
		
		return $id;
	}
	
	/**
	 * Connect a user from its cookie.
	 * 
	 * @param string $cookie_data
	 * 
	 * @return User
	 */
	static function fromCookie ($cookie_data)
	{
		
	}
}

class User_Exception extends Exception {}
?>