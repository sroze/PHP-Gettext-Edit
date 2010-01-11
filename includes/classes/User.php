<?php
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
				'The user id is undefined'
			);
		}
		
		$this->informations = $informations;
		
		define('CONNECTED', true);
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
				sprintf('Field "%s" doesn\'t exists', $name)
			);
		}
	}
	
	static function fromCookie ($cookie_data)
	{
		
	}
}

class User_Exception extends Exception {}
?>