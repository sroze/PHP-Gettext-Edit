<?php
require_once 'Rights_Config.php';

/**
 * This Rights class allow you to manage users' rights for your PHP application. You
 * can create rights, sub-rights and users groups (and sub-groups). Users can have
 * specifics rights (allowed or forbidden), belong to groups and sub-groups which
 * have rights (allowed or forbidden). For sub-groups and sub-rights, rights of them
 * inethit from them parents.
 * 
 * @author Samuel ROZE <samuel.roze@gmail.com>
 * @link http://www.d-sites.com/projets/librairies/Rights
 * @version 0.2
 */
class Rights 
{
	const KEY_PAIRS_DELIMITER = ';';
	const KEY_VALUES_DELIMITER = ':';
	
	/**
	 * User ID.
	 * 
	 * @var int
	 */
	static $user_id = 0;
	
	/**
	 * Check if user have this right in a specific context, or not.
	 * 
	 * @param integer|string	   $right		   Right ID or Right Name   
	 * @param array|integer|string $context  	   Which context
	 * @param boolean			   $context_strict {@see generateContextNotNullQuery}
	 * 
	 * @return bool
	 */
	static function check ($right_id_name, $context = null, $context_strict = false)
	{
		if (Rights::$user_id == 0) {
			throw new Rights_Exception(
				'User ID is empty'
			);
		}
		// Get rights informations
		$right = Rights::getRightInformations($right_id_name);		
		
		// We will look at user's rights, to check if he has it.
		$query_rights = sprintf(Rights_Config::$requests->get('in_user_rights'),
				Rights_Config::$prefix.Rights_Config::$tables['users_rights'],
				Rights_Config::$prefix.Rights_Config::$tables['rights_list'],
				Rights_Config::$prefix.Rights_Config::$tables['context_list'],
				Rights::$user_id,
				$right['from'],
				$right['to'],
				Rights::generateContextNotNullQuery($context, 'ur', $context_strict)
			);
			
		$r_rights = Rights_Config::$sql->query(
			$query_rights
		);
		
		if (!$r_rights) {
			$errorInfo = Rights_Config::$sql->errorInfo();
			
			throw new Rights_Exception(
				sprintf(
					'Unable check right in user\'s right: %s. Request: %s',
					$errorInfo[2],
					$query_rights
				)
			);	
		}
		
		$in_user_rights = $r_rights->fetch();
		if ($in_user_rights) {
			if ($in_user_rights['grant'] == true) {
				return true;
			} else {
				return false;
			}
		}
		
		// Now, we will check groups rights
		$query_groups = sprintf(Rights_Config::$requests->get('in_user_groups'),
				Rights_Config::$prefix.Rights_Config::$tables['users_groups'],
				Rights_Config::$prefix.Rights_Config::$tables['groups_list'], // 
				Rights_Config::$prefix.Rights_Config::$tables['groups_list'], // Both, it's normal
				Rights_Config::$prefix.Rights_Config::$tables['groups_rights'],
				Rights_Config::$prefix.Rights_Config::$tables['rights_list'],
				Rights_Config::$prefix.Rights_Config::$tables['context_list'],
				Rights::$user_id,
				$right['from'],
				$right['to'],
				Rights::generateContextNotNullQuery($context, 'ug', $context_strict)
			);
			
		$r_groups = Rights_Config::$sql->query(
			$query_groups
		);
		
		if (!$r_groups) {
			$errorInfo = Rights_Config::$sql->errorInfo();
			
			throw new Rights_Exception(
				sprintf(
					'Unable check right in groups\' rights: %s. Request: %s',
					$errorInfo[2],
					$query_groups
				)
			);	
		}
		
		$in_user_groups = $r_groups->fetch();
		if ($in_user_groups) {
			if ($in_user_groups['grant'] == true) {
				return true;
			}
			else {
				return false;
			}
		}
		
		// He doesn't have this right
		return false;
	}
	
	/**
	 * Generate the little part of SQL wich allow to select a specific context.
	 * 
	 * @param array|integer|string 	$context Description of the context, see {@see check}
	 * @param string 				$label 	 Label of the table in the SQL request
	 * @param boolean 				$strict  Use "=" or "like" ?
	 * 
	 * @return string Part of SQL request
	 * 
	 * @throws Rights_Exception when args aren't valid
	 */
	static function generateContextNotNullQuery ($context, $label, $strict = true)
	{
		if (empty($context)) {
			return '';
		}
		$sql_or = Rights_Config::$requests->orContextNotNull($label);
		
		// Si le context passé est un entier, alors on va chercher non pas le contexte
		// ave ce nom là, mais avec cet ID là.
		if (is_int($context)) {
			// Strict must be "TRUE" if context is an ID
			$strict = true;
			
			return sprintf($sql_or, 
				Rights_Config::$requests->makeDynamic('cl', 'id', $context, $strict)
			);
		}
		// Sinon, si c'est une chaine de caractères, alors on va tout simplement
		// chercher la chaine avec ce nom là.
		else {
			if (is_array($context)) {
				$context = Rights::contextKeysToString($context);
			}
			
			if (is_string($context)) {
				return sprintf($sql_or, 
					Rights_Config::$requests->makeDynamic(
						'cl', 
						'name', 
						$context, 
						$strict
					)
				);
			} else {
				throw new Rights_Exception(
					'Invalid first argument'
				);
			}
		}
	}
	
	/**
	 * Return an associative array which have informations about the right.
	 * 
	 * @param integer|string $right_id_name
	 * 
	 * @return array
	 */
	static function getRightInformations ($right_id_name)
	{
		if (is_string($right_id_name)) {
			$r = Rights_Config::$sql->query(
				sprintf(Rights_Config::$requests->get('select_right_by_name'),
					Rights_Config::$prefix.Rights_Config::$tables['rights_list'],
					$right_id_name
				)
			);
		} else if (is_int($right_id_name)) {
			$r = Rights_Config::$sql->query(
				sprintf(Rights_Config::$requests->get('select_right_by_id'),
					Rights_Config::$prefix.Rights_Config::$tables['rights_list'],
					$right_id_name
				)
			);
		} else {
			throw new Rights_Exception(
				'The first argument (right) must be an integer or a string'
			);
		}
		
		$right = $r->fetch();
		if (!$right) {
			throw new Rights_Exception(
				sprintf('Right #%d(%s) doesn\'t exists', $right_id_name, $right_id_name)
			);
		}
		
		return $right;
	}
	
	/**
	 * Set the user ID.
	 * 
	 * @param integer $user_id
	 */
	static function setUser ($user_id)
	{
		if (!is_int($user_id)) {
			throw new Rights_Exception(
				'The first argument (user id) must be an integer'
			);
		}
		Rights::$user_id = $user_id;
	}
	
	/**
	 * Transform an assoc array into string.
	 * 
	 * @param array $keys_array
	 * 
	 * @return string $keys_string
	 */
	static function contextKeysToString ($keys_array)
	{
		ksort($keys_array);
		$string = '';
		foreach ($keys_array as $key => $value) {
			if ($string != '') {
				$string .= Rights::KEY_PAIRS_DELIMITER;
			}
			$string .= $key.Rights::KEY_VALUES_DELIMITER.$value;
		}
		return $string;
	}
	
	/**
	 * Transform a string into an assoc array.
	 * 
	 * @param string $keys_string
	 * 
	 * @return array $keys_array
	 */
	static function contextKeysFromString ($keys_string)
	{
		$pairs = explode(Rights::KEY_PAIRS_DELIMITER, $keys_string);
		$keys_array = array();
		foreach ($pairs as $pair) {
			$fields = explode(Rights::KEY_VALUES_DELIMITER, $pair);
			// Ajout dans le tableau
			$keys_array[$fields[0]] = $fields[1];
		}
		return $keys_array;
	}
	
}

class Rights_Exception extends Exception {}

?>