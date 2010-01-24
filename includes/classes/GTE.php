<?php
/**
 * The GTE (GetTextEdit) class contains some functions that can be used to
 * get some informations/thing independently of a project, file...
 * 
 * @author 	Samuel ROZE <samuel.roze@gmail.com>
 * @link 	http://www.d-sites.com
 */
class GTE
{
	/**
	 * Get users which have the right $right with the context $context
	 * 
	 * @param int|str 		$right
	 * @param int|str|array $context
	 * 
	 * @return array
	 */
	static function getUsersHavingRight ($right, $context)
	{
		$users_id = Rights_Admin::getUsersHavingRight($right, $context);
		
		// Now, we'll get more informations than users' id...
		$query = Database::$sql->query(
			sprintf(
				Database::$requests->get('get_users_informations'),
				Database::$prefix.'users',
				Database::$requests->inAny('id', $users_id)
			)
		);
		
		if (!$query) {
			$sql_error = Database::$sql->errorInfo();
			throw new GTE_Exception(
				sprintf(
					_('Impossible récupérer les informations des utilisateurs: %s'),
					$sql_error[2]
				)
			);
		}
		
		$users = array();
		
		foreach ($query as $line) {
			$users[] = $line;
		}
		
		return $users;
	}
	
	/**
	 * Get the user ID from a user which name is the 1st argument.
	 * 
	 * @param string $user_name
	 * 
	 * @return integer
	 */
	static function getUserIdFromUsername ($user_name)
	{
		$query = Database::$sql->query(
			sprintf(
				Database::$requests->get('get_user_id_from_name'),
				Database::$prefix.'users',
				$user_name
			)
		);
		
		if (!$query) {
			$sql_error = Database::$sql->errorInfo();
			throw new GTE_Exception(
				sprintf(
					_('Impossible récupérer les informations des utilisateurs: %s'),
					$sql_error[2]
				)
			);
		}
		
		$line = $query->fetch();
		if (!$line) {
			throw new GTE_Exception(
				sprintf(
					_('L\'utlisateur "%s" n\'éxiste pas'),
					$user_name
				)
			);
		} else {
			return (int) $line['id'];
		}
	}
	
	/**
	 * Return list of users.
	 * 
	 * @return array
	 */
	static function getUsers ()
	{
		$query = Database::$sql->query(
			sprintf(
				Database::$requests->get('get_users'),
				Database::$prefix.'users'
			)
		);
		
		if (!$query) {
			$sql_error = Database::$sql->errorInfo();
			throw new GTE_Exception(
				sprintf(
					_('Impossible récupérer la liste des utilisateurs: %s'),
					$sql_error[2]
				)
			);
		}
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Return an array of informations about the user #$userid
	 * 
	 * @param integer $userid
	 * 
	 * @return array
	 */
	static function getUserInformations ($userid)
	{
		if (!is_int($userid)) {
			$userid = (int) $userid;
			
			if (empty($userid)) {
				throw new GTE_Exception(
					_('Le premier argument (user ID) doit être un entier')
				);
			}
		}
		
		$query = Database::$sql->query(
			sprintf(
				Database::$requests->get('get_user'),
				Database::$prefix.'users',
				$userid
			)
		);
		
		if (!$query) {
			$sql_error = Database::$sql->errorInfo();
			throw new GTE_Exception(
				sprintf(
					_('Impossible récupérer les informations de l\'utilisateur #%d: %s'),
					$userid,
					$sql_error[2]
				)
			);
		}
		
		$line = $query->fetch(PDO::FETCH_ASSOC);
		if (!$line) {
			throw new GTE_Exception(
				sprintf(
					_('L\'utilisateur #%d n\'éxiste pas'),
					$userid
				)
			);
		} else {
			return $line;
		}
	}
	
	/**
	 * Build a context array from arguments passed to the application.
	 * 
	 * @param array $from
	 * 
	 * @return array
	 */
	static function buildContext ($from = null)
	{
		if ($from == null) {
			$from = $_GET;
		} else if (!is_array($from)) {
			throw new GTE_Exception(
				_('La donnée entrante doit être un tableau')
			);
		}
		
		$context = array();
		$arguments_which_can_build_context = array(
			'project', 'language', 'language_file', 'template'
		);
		
		foreach ($arguments_which_can_build_context as $field) {
			if (array_key_exists($field, $from)) {
				$context[$field] = $from[$field];
			}
		}		
		
		if (empty($context)) {
			return NULL;
		} else {
			return $context;
		}
	}
	
	/**
	 * FIXME Get the name of a right from its in-database name.
	 * 
	 * @param string $right
	 * 
	 * @return string
	 */
	static function getRightName ($right)
	{
		return $right;
	}
}

class GTE_Exception extends Exception{}
?>