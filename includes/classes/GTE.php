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
				Database::$requests->inAny('id', array($users_id))
			)
		);
		
		if (!$query) {
			$sql_error = Database::$sql->errorInfo();
			throw new User_Exception(
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
}

class GTE_Exception extends Exception{}
?>