<?php
require_once 'PGET_SQL.php';

/**
 * SQL requests for PostgreSQL for the PHP-Gettext-Edit
 *
 * @author Samuel ROZE <samuel.roze@gmail.com>
 */
class PGET_SQL_pgsql extends PGET_SQL
{
	/**
	 * Character which is used to delimit fields in an SQL request
	 * 
	 * @var string(1)
	 */
	public $fields_delimitor = '"';
	
	/**
	 * Associative array for each requests.
	 * 
	 * @var array
	 */
	public $requests = array(
		'create_user' => 'INSERT INTO %s (username, password, email) VALUES (\'%s\', \'%s\', \'%s\') RETURNING id',
	
		'create_project' => 'INSERT INTO %s (project_name, project_path, project_languages_path)
			VALUES (\'%s\', \'%s\', \'%s\') RETURNING project_id',
		'update_project' => 'UPDATE %s SET 
			project_name = \'%s\', 
			project_path = \'%s\', 
			project_languages_path = \'%s\'
			WHERE project_id = %d',
		'delete_project' => 'DELETE FROM %s WHERE project_id = %d',
		'get_projects' => 'SELECT project_id, project_name FROM %s',
		'get_project' => 'SELECT * FROM %s WHERE project_id = %d',
		'connect_user' => 'SELECT id, username, email FROM %s WHERE username = \'%s\' AND password = \'%s\'',
		'get_user_id_from_name' => 'SELECT id FROM %s WHERE username = \'%s\'',
		'get_users_informations' => 'SELECT id, username FROM %s WHERE %s ORDER BY username DESC',
		'get_users' => 'SELECT id, username FROM %s ORDER BY username DESC',
		'get_user' => 'SELECT * FROM %s WHERE id = %d'
	);
	
	/**
	 * @see includes/SQL/PGET_SQL#inAny($field, $values)
	 */
	public function inAny ($field, $values)
	{
		$sql = $field.' IN (';
		$i = 0;
		foreach ($values as $value) {
			if ($i > 0) {
				$sql .= ',';
			}
			if (is_int($value)) {
				$sql .= $value;
			} else {
				$sql .= '\''.
					str_replace('\'', '\\\'', $value).
					'\'';
			}
			$i++;
		}
		$sql .= ')';
		
		return $sql;
	}
}

?>