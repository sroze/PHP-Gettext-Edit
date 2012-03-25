<?php
require_once 'Rights_Config.php';

/**
 * This class help you to manage rights.
 * 
 * @author Samuel ROZE <samuel.roze@gmail.com>
 * @link http://www.d-sites.com/projets/librairies/Rights
 * @version 0.2
 */
class Rights_Admin
{

	/**
	 * Create a database entry in "rights_list" table, that allow you to
	 * assign to users or groups.
	 *
	 * @param string  $name
	 * @param integer $parent_id
	 * 
	 * @return integer $right_id
	 */
	static function createRight ($name, $parent_id = 1)
	{
        $table = Rights_Config::$prefix.Rights_Config::$tables['rights_list'];
	    
		$right = Rights_Config::$intervals->add(
			$table,
			self::Object($table, $parent_id),
			array(
				'name' => $name
			)
		);
		return $right->get('id');
	}
	
	/**
	 * Delete a right.
	 *
	 * @param void
	 */
	static function deleteRight ($id)
	{
	    $table = Rights_Config::$prefix.Rights_Config::$tables['rights_list'];
	    
		Rights_Config::$intervals->remove(
			$table,
			self::Object($table, $id)
		);
	}
	
	/**
	 * Create rights hierachical rights from an array.
	 * 
	 * See the rights installation file of PHP-Gettext-Edit project.
	 * 
	 * @param array   $rights_array
	 * @param integer $parent_right The parent of these rights
	 * 
	 * @return void
	 */
	static function createRightsFromArray ($rights_array, $parent_right = 1)
	{
		foreach ($rights_array as $key => $value) {
			if (is_array($value)) {
				$right = self::createRight($key, $parent_right);
				self::createRightsFromArray($value, $right);
			} else if (is_string($value)) {
				self::createRight($value, $parent_right);
			} else {
				throw new Rights_Exception(
					'Invalid array'
				);
			}
		}
	}
	
	/**
	 * Create a group in the "rights_groups" table. If there isn't any "parent",
	 * the parent is the group #1 (the master group, which have visitor rights).
	 *
	 * @param string  $name		 
	 * @param integer $parent_id 
	 * 
	 * @return int $group_id
	 */
	static function createGroup ($name, $parent_id = 1)
	{
	    $table = Rights_Config::$prefix.Rights_Config::$tables['groups_list'];
	    
		$group = Rights_Config::$intervals->add(
			$table,
			self::Object($table, $parent_id),
			array(
				'name' => $name
			)
		);
		return $group->get('id');
	}
	
	/**
	 * Delete a group, and its sub-groups from Database.
	 *
	 * @param integer $id
	 */
	static function deleteGroup ($id)
	{
		$table = Rights_Config::$prefix.Rights_Config::$tables['groups_list'];
	    
		Rights_Config::$intervals->remove(
			$table,
			self::Object($table, $id)
		);
	}
	
	/**
	 * Get rights groups.
	 * 
	 * @return array
	 */
	static function getGroups ()
	{
		$r = Rights_Config::$sql->query(
			sprintf(Rights_Config::$requests->get('get_groups'),
				Rights_Config::$prefix.Rights_Config::$tables['groups_list']
			)
		);
		if (!$r) {
			throw new Rights_Exception(
				sprintf(
					'Unable to select groups: %s',
					print_r(Rights_Config::$sql->errorInfo(), true)
				)
			);
		} else {
			return $r->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	
	/**
	 * Add rights to a group.
	 *
	 * @param integer $group_ids
	 * @param array   $rights_ids
	 */
	static function addGroupRights ($group_id, $rights_ids)
	{		
		if (!is_array($rights_ids)) {
			$rights_ids = array($rights_ids);
		}
		if (!is_int($group_id)) {
			throw new Rights_Exception(
				'The group id must be an integer'
			);
		}
		
		Rights_Config::$sql->beginTransaction();
			
		for ($i = 0; $i < count($rights_ids); $i++) {
			$right = self::parseRight($rights_ids[$i]);
			$r = Rights_Config::$sql->exec(
				sprintf(Rights_Config::$requests->get('insert_group_right'),
					Rights_Config::$prefix.Rights_Config::$tables['groups_rights'],
					$group_id,
					$right
				)
			);
			if (!$r) {
				throw new Rights_Exception(
					sprintf(
						'Unable to add right #%d to group #%d: %s',
						$right,
						$group_id,
						print_r(Rights_Config::$sql->errorInfo(), true)
					)
				);
			}
		}
		
		Rights_Config::$sql->commit();
	}
	
	/**
	 * Delete rights informations of group.
	 * 
	 * See revokeGroupRights to revoke rights.
	 *
	 * @param integer $group_id
	 * @param array   $rights_ids
	 */
	static function removeGroupRights ($group_id, $rights_ids)
	{
		if (!is_array($rights_ids)) {
			$rights_ids = array($rights_ids);
		}
		if (!is_int($group_id)) {
			throw new Rights_Exception(
				'The group id must be an integer'
			);
		}
		
		Rights_Config::$sql->beginTransaction();
			
		for ($i = 0; $i < count($rights_ids); $i++) {
			$right = self::parseRight($rights_ids[$i]);
			$r = Rights_Config::$sql->exec(
				sprintf(Rights_Config::$requests->get('delete_group_right'),
					Rights_Config::$prefix.Rights_Config::$tables['groups_rights'],
					$group_id,
					$right
				)
			);
			if (!$r) {
				throw new Rights_Exception(
					sprintf(
						'Unable to delete right #%d of group #%d: %s',
						$right,
						$group_id,
						print_r(Rights_Config::$sql->errorInfo(), true)
					)
				);
			}
		}
		
		Rights_Config::$sql->commit();
	}
	
	/**
	 * Get group rights.
	 *
	 * @param integer $group_id
	 * 
	 * @return array $rights
	 */
	static function getGroupRights ($group_id)
	{
		$r = Rights_Config::$sql->query(
			sprintf(Rights_Config::$requests->get('select_group_rights'), 
				Rights_Config::$prefix.Rights_Config::$tables['groups_rights'],
				$group_id
			)
		);
		
		$rights = array();
		foreach ($r as $line) {
			if ($line['grant'] == true) {
				$rights[] = $line['right'];
			}
		}
		
		return $rights;
	}
	
	/**
	 * Grant rights to user.
	 * 
	 * @param integer 		$user_id
	 * @param array   		$rights_ids
	 * @param int|str|array $context
	 * 
	 * @return void
	 */
	static function grantUserRights ($user_id, $rights_ids, $context = null)
	{
		self::addUserRights($user_id, $rights_ids, $context, true);
	}
	
	/**
	 * Revoke a right to a user.
	 * 
	 * See revokeGroupRights to know difference between revoke and remove.
	 * 
	 * @param integer 		$user_id
	 * @param array   		$rights_ids
	 * @param int|str|array $context
	 * 
	 * @return void
	 */
	static function revokeUserRights ($user_id, $rights_ids, $context = null)
	{
		self::addUserRights($user_id, $rights_ids, $context, false);
	}
	
	/**
	 * Add rights to a user
	 *
	 * @param integer 				$user_id
	 * @param array   				$rights_ids
	 * @param integer|string|array 	$context
	 * @param boolean 				$grant 	  	true = Grant - false = Revoke
	 * 
	 * @return void
	 */
	static function addUserRights ($user_id, $rights_ids, $context = null, $grant = true)
	{
		if (!is_array($rights_ids)) {
			$rights_ids = array($rights_ids);
		}
		if (!is_int($user_id)) {
			$user_id = (int) $user_id;
			
			if ($user_id == 0) {
				throw new Rights_Exception(
					'The user id must be an integer'
				);
			}
		}
		
		// Remove last user's rights informations
		self::removeUserRights($user_id, $rights_ids, $context);
		
		if (empty($context)) {
			$context = 'NULL';
		} else if (!is_int($context)) {
			$context = self::getContextId($context);
		}
		
		Rights_Config::$sql->beginTransaction();
			
		for ($i = 0; $i < count($rights_ids); $i++) {
			$right = self::parseRight($rights_ids[$i]);
			$r = Rights_Config::$sql->exec(
				sprintf(Rights_Config::$requests->get('insert_user_right'),
					Rights_Config::$prefix.Rights_Config::$tables['users_rights'],
					$user_id,
					$right,
					$context,
					$grant
				)
			);
			if (!$r) {
				throw new Rights_Exception(
					sprintf(
						'Unable to add right #%d to user #%d: %s',
						$right,
						$user_id,
						print_r(Rights_Config::$sql->errorInfo(), true)
					)
				);
			}
		}
		
		Rights_Config::$sql->commit();
	}
	
	/**
	 * Delete some rights informations of user.
	 *
	 * @param integer 		$user_id
	 * @param array   		$rights_ids
	 * @param int|str|array $context
	 * 
	 * @return void
	 */
	static function removeUserRights ($user_id, $rights_ids, $context = null, $context_strict = true) {
		if (!is_array($rights_ids)) {
			$rights_ids = array($rights_ids);
		}
		if (!is_int($user_id)) {
			$user_id = (int) $user_id;
			
			if ($user_id == 0) {
				throw new Rights_Exception(
					'The user id must be an integer'
				);
			}
		}
		
		Rights_Config::$sql->beginTransaction();
			
		for ($i = 0; $i < count($rights_ids); $i++) {
			$right = self::parseRight($rights_ids[$i]);
			$query = sprintf(Rights_Config::$requests->get('delete_user_right'),
					Rights_Config::$prefix.Rights_Config::$tables['users_rights'],
					$user_id,
					$right,
					self::orContext('ur', $context)
				);
				
			$r = Rights_Config::$sql->exec(
				$query
			);
			if ($r === false) {
				throw new Rights_Exception(
					sprintf(
						'Unable to delete right #%d of user #%d: %s - Request: %s',
						$right,
						$user_id,
						print_r(Rights_Config::$sql->errorInfo(), true),
						$query
					)
				);			
			}
		}
		
		Rights_Config::$sql->commit();
	}
	
	/**
	 * Get user rights.
	 *
	 * @param integer 		$user_id
	 * @param int|str|array $context
	 * 
	 * @return array $rights
	 */
	static function getUserRights ($user_id, $context = null)
	{
		$r = Rights_Config::$sql->query(
			sprintf(Rights_Config::$requests->get('select_user_rights'),
				Rights_Config::$prefix.Rights_Config::$tables['users_rights'],
				Rights_Config::$prefix.Rights_Config::$tables['rights_list'],
				$user_id,
				self::orContext('ur', $context)
			)
		);
		$r->setFetchMode(PDO::FETCH_ASSOC);
		
		$rights = array();
		foreach ($r as $line) {
			if ($line['grant'] == true) {
				$rights[] = $line;
			}
		}
		
		return $rights;
	}
	
	/**
	 * Return an array with informations about the link between this right
	 * and the user in a specific context, or not.
	 * 
	 * @param integer 		$user_id
	 * @param int|str  		$right
	 * @param int|str|array $context
	 * 
	 * @return array
	 */
	static function getUserRightEffectiveInformations ($user_id, $right, $context = null)
	{
		// Get rights informations
		$right = Rights::getRightInformations($right);		
		
		// We will look at user's rights, to check if he has it.
		$query_rights = sprintf(Rights_Config::$requests->get('in_user_rights'),
			Rights_Config::$prefix.Rights_Config::$tables['users_rights'],
			Rights_Config::$prefix.Rights_Config::$tables['rights_list'],
			Rights_Config::$prefix.Rights_Config::$tables['context_list'],
			$user_id,
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
				return array('grant' => true, 'from' => 'user');
			} else {
				return array('grant' => false, 'from' => 'user');
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
				$user_id,
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
				return array('grant' => true, 'from' => 'group');
			}
			else {
				return array('grant' => false, 'from' => 'group');
			}
		}
		
		// He doesn't have this right
		return array('grant' => false, 'from' => '');
	}
	
	/**
	 * Put user into a group.
	 *
	 * @param integer $user_id
	 * @param array   $groups_ids
	 * @param integer $context_id
	 * @param bool	  $grant
	 * 
	 * @return void
	 */
	static function addUserGroups ($user_id, $groups_ids, $context = null, $grant = true)
	{
		if (is_int($groups_ids)) {
			$groups_ids = array($groups_ids);
		}
		if (!is_int($user_id)) {
			throw new Rights_Exception(
				'The user id must be an integer'
			);
		}
		if (empty($context)) {
			$context_id = 'NULL';
		} else {
			$context_id = self::getContextId($context);
		}
		
		Rights_Config::$sql->beginTransaction();
			
		for ($i = 0; $i < count($groups_ids); $i++) {
			$group = $groups_ids[$i];
			$r = Rights_Config::$sql->exec(
				sprintf(Rights_Config::$requests->get('insert_user_group'),
					Rights_Config::$prefix.Rights_Config::$tables['users_groups'],
					$user_id,
					$group,
					$context_id,
					$grant
				)
			);
			if (!$r) {
				throw new Rights_Exception(
					sprintf(
						'Unable to add (grant? %d) user #%d in group #%d with context "%s": %s',
						$grant,
						$user_id,
						$group,
						$context_id,
						print_r(Rights_Config::$sql->errorInfo(), true)
					)
				);	
			}
		}
		
		Rights_Config::$sql->commit();
	}
	
	/**
	 * Remove user from groups.
	 *
	 * @param integer $user_id
	 * @param array   $groups_ids
	 * @param integer $context_id
	 * 
	 * @return void
	 */
	static function removeUserGroups ($user_id, $groups_ids, $context_id = null, $context_strict = true)
	{
		if (is_int($groups_ids)) {
			$groups_ids = array($groups_ids);
		}
		if (!is_int($user_id)) {
			throw new Rights_Exception(
				'The user id must be an integer'
			);
		}
		
		Rights_Config::$sql->beginTransaction();
			
		for ($i = 0; $i < count($groups_ids); $i++) {
			$group = $groups_ids[$i];
			$r = Rights_Config::$sql->exec(
				sprintf(Rights_Config::$requests->get('delete_user_group'),
					Rights_Config::$prefix.Rights_Config::$tables['users_groups'],
					$user_id,
					$group,
					self::orContext('ug', $context)
				)
			);
			if (!$r) {
				throw new Rights_Exception(
					sprintf(
						'Unable to remove user #%d from group #%d with context "%s": %s',
						$user_id,
						$group,
						$context,
						print_r(Rights_Config::$sql->errorInfo(), true)
					)
				);	
			}
		}
		
		Rights_Config::$sql->commit();
	}
	
	/**
	 * Get user's grops.
	 *
	 * @param integer 		$user_id
	 * @param int|str|array $context
	 * 
	 * @return array $groups
	 */
	static function getUserGroups ($user_id, $context = null)
	{
		$r = Rights_Config::$sql->query(
			sprintf(Rights_Config::$requests->get('select_user_groups'),
				Rights_Config::$prefix.Rights_Config::$tables['users_groups'],
				Rights_Config::$prefix.Rights_Config::$tables['groups_list'],
				$user_id,
				self::orContext('ug', $context)
			)
		);
		$r->setFetchMode(PDO::FETCH_ASSOC);
		
		$groups = array();
		foreach ($r as $line) {
			$groups[] = $line;
		}
		
		return $groups;
	}
	
	/**
	 * Create a context.
	 * 
	 * @return integer $context_id
	 */
	static function createContext ()
	{
		$query = Rights_Config::$sql->query(
			sprintf(Rights_Config::$requests->get('insert_context'),
				Rights_Config::$prefix.Rights_Config::$tables['context_list']
			)
		);
		
		if (!$query) {
			throw new Rights_Exception(
				sprintf(
					'Unable to create a context: %s', 
					print_r(Rights_Config::$sql->errorInfo(), true)
				)
			);
		}
		
		if (Rights_Config::$sql_type == 'pgsql') {
			$query_result = $query->fetch();
			$id = (int) $query_result['id'];
		} else {
			$id = (int) Rights_Config::$sql->lastInsertId();
		}
		
		return $id;
	}
	
	/**
	 * Delete a context
	 * 
	 * @param integer $context_id
	 * 
	 * @return void
	 */
	static function removeContext ($id)
	{
		$query = Rights_Config::$sql->query(
			sprintf(Rights_Config::$requests->get('delete_context'),
				Rights_Config::$prefix.Rights_Config::$tables['context_list'],
				$id
			)
		);
		if ($query->rowCount() == 0) {
			throw new Rights_Exception(
				sprintf('Context #%d doesn\'t exists', $id)
			);
		}
	}
	
	/**
	 * Set a couple key/value to a context. If the key doesn't exists, it is created,
	 * else it is remplaced.
	 * 
	 * @param integer $context_id Context ID
	 * @param string  $key_name   Key name
	 * @param mixed   $value      Value
	 * 
	 * @return void 
	 */
	static function setContextKey ($context_id, $key_name, $value)
	{
		$keys = self::getContextKeys($context_id);
		// Ajoute la valeur
		$keys[$key_name] = $value;
		// New string
		$keys_string = Rights::contextKeysToString($keys);
		// Save string
		self::setContextName($context_id, $keys_string);
	}
	
	/**
	 * Used by setContextKey, this function save directly the content of
	 * 2nd argument in the database's "name" field of the context
	 * #{1st arg}
	 * 
	 * @param integer 	$context_id
	 * @param string 	$context_name
	 * 
	 * @return void
	 */
	static function setContextName ($context_id, $context_name)
	{
		$query = Rights_Config::$sql->query(
			sprintf(Rights_Config::$requests->get('set_context_keys'),
				Rights_Config::$prefix.Rights_Config::$tables['context_list'],
				$context_name,
				$context_id
			)
		);
		
		if (!$query) {
			throw new Rights_Exception(
				sprintf(_('Unable to set context keys: %s'), print_r(Rights_Config::$sql->errorInfo(), true))
			);
		}
	}
	
	/**
	 * Get context couples (key/value)
	 * 
	 * @param integer $context_id
	 * 
	 * @return array $keys_array
	 */
	static function getContextKeys ($context_id)
	{
		$query = Rights_Config::$sql->query(
			sprintf(Rights_Config::$requests->get('get_context_keys'),
				Rights_Config::$prefix.Rights_Config::$tables['context_list'],
				$context_id
			)
		);
		
		if (!$query) {
			throw new Rights_Exception(
				sprintf(
					'Unable to get context #%d keys: %s', 
					$context_id, 
					print_r(Rights_Config::$sql->errorInfo(), true)
				)
			);
		}
		
		$result = $query->fetch();
		// Transforme la chaine de caractères en tableau
		if (empty($result['name'])) {
			return array();
		}
		$keys_array = Rights::contextKeysFromString($result['name']);
		// On retourne le tableau
		return $keys_array;
	}
	
	/**
	 * Parse right...
	 * 
	 * @param integer|string $right
	 * 
	 * @return integer Right ID
	 */
	static function parseRight ($right)
	{
		if (is_int($right)) {
			return $right;
		} else if (is_string($right)) {
			$r = Rights_Config::$sql->query(
				sprintf(Rights_Config::$requests->get('get_right_id_by_name'),
					Rights_Config::$prefix.Rights_Config::$tables['rights_list'],
					$right
				)
			);
			if (!$r) {
				$sql_error = Rights_Config::$sql->errorInfo();
				throw new Rights_Exception(
					sprintf(
						'Unable to find right which name is "%s": %s',
						$right,
						$sql_error[2]
					)
				);
			} else {
				$query_fetch = $r->fetch();
				if (!$query_fetch) {
					$sql_error = Rights_Config::$sql->errorInfo();
					throw new Rights_Exception(
						sprintf(
							'Unable to find right which name is "%s": %s',
							$right,
							$sql_error[2]
						)
					);
				}
				return (int) $query_fetch['id'];
			}
		} else {
			throw new Rights_Exception(
				'Invalid right type'
			);
		}
	}
	
	/**
	 * Get context ID.
	 * 
	 * @param string $context_str
	 * 
	 * @return integer
	 */
	static function getContextId ($context)
	{
		if (is_int($context)) {
			return $context;
		} else if (is_string($context)) {
			$r = Rights_Config::$sql->query(
				sprintf(Rights_Config::$requests->get('get_context_id_by_name'),
					Rights_Config::$prefix.Rights_Config::$tables['context_list'],
					$context
				)
			);
			if (!$r) {
				$sql_error = Rights_Config::$sql->errorInfo();
				throw new Rights_Exception(
					sprintf(
						'Unable to find context which name is "%s": %s',
						$context,
						$sql_error[2]
					)
				);
			} else {
				$query_fetch = $r->fetch();
				if (!$query_fetch) {
					$new_context_id = self::createContext();
					self::setContextName($new_context_id, $context);
					return (int) $new_context_id;
				} else {
					return (int) $query_fetch['id'];
				}
			}
		} else if (is_array($context)) {
			return self::getContextId(
				Rights::contextKeysToString($context)
			);
		} else {
			throw new Rights_Exception(
				'Invalid context type'
			);
		}
	}
	
	/**
	 * Return an array of users which have the right $right in
	 * the context $context
	 * 
	 * @param int|str 		$right
	 * @param int|str|array $context
	 * 
	 * @return array
	 */
	static function getUsersHavingRight ($right, $context = null, $context_strict = true)
	{
		$users_id = array();
		
		// Get rights informations
		$right = Rights::getRightInformations($right);		
		
		// We will look at user's rights, to check if he has it.
		$query_rights = sprintf(Rights_Config::$requests->get('in_rights'),
			Rights_Config::$prefix.Rights_Config::$tables['users_rights'],
			Rights_Config::$prefix.Rights_Config::$tables['rights_list'],
			Rights_Config::$prefix.Rights_Config::$tables['context_list'],
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
		} else {
			foreach ($r_rights as $ur_line) {
				$users_id[] = (int) $ur_line['user'];
			}
		}
		
		// Now, we will check groups rights
		$query_groups = sprintf(Rights_Config::$requests->get('in_groups'),
				Rights_Config::$prefix.Rights_Config::$tables['users_groups'],
				Rights_Config::$prefix.Rights_Config::$tables['groups_list'], // 
				Rights_Config::$prefix.Rights_Config::$tables['groups_list'], // Both, it's normal
				Rights_Config::$prefix.Rights_Config::$tables['groups_rights'],
				Rights_Config::$prefix.Rights_Config::$tables['rights_list'],
				Rights_Config::$prefix.Rights_Config::$tables['context_list'],
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
		} else {
			foreach ($r_groups as $gr_line) {
				$users_id[] = (int) $gr_line['user'];
			}
		}
		
		return array_values(
			array_unique($users_id)
		);
	}
	
	/**
	 * Return the SQL which select the context.
	 * 
	 * @param string		$label
	 * @param int|str|array $context
	 * 
	 * @return string
	 */
	static function orContext ($label, $context, $context_strict = true)
	{
		if (empty($context)) {
			return '';
		} else {
			$context_id = self::getContextId($context);
		
			$sql = 'OR ';
			$sql .= Rights_Config::$requests->makeDynamic($label, 'context', $context_id, $context_strict);

			return $sql;
		}
	}
	
	/**
	 * Create an SQL_Intervals_Object.
	 * 
	 * @param string $table
	 * @param array  $informations
	 * 
	 * @return SQL_Intervals_Object
	 */
	static function Object ($table, $informations)
	{
	    return new SQL_Intervals_Object($table, $informations);
	}
}

?>