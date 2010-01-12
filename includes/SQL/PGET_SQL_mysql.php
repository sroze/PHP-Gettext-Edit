<?php
require_once 'PGET_SQL.php';

/**
 * SQL requests for MySQL for the PHP-Gettext-Edit
 *
 * @author Samuel ROZE <samuel.roze@gmail.com>
 */
class PGET_SQL_mysql extends PGET_SQL
{
	/**
	 * Character which is used to delimit fields in an SQL request
	 * 
	 * @var string(1)
	 */
	public $fields_delimitor = '`';
	
	/**
	 * Associative array for each requests.
	 * 
	 * @var array
	 */
	public $requests = array(
		'create_user' => 'INSERT INTO %s (username, password, email) VALUES (\'%s\', \'%s\', \'%s\')',
	
		'create_project' => 'INSERT INTO %s (project_name, project_path, project_languages_path)
			VALUES (\'%s\', \'%s\', \'%s\')',
		'update_project' => 'UPDATE %s SET 
			project_name = \'%s\', 
			project_path = \'%s\', 
			project_languages_path = \'%s\'
			WHERE project_id = %d',
		'delete_project' => 'DELETE FROM %s WHERE project_id = %d',
		'get_projects' => 'SELECT project_id, project_name FROM %s',
		'get_project' => 'SELECT * FROM %s WHERE project_id = %d',
		'connect_user' => 'SELECT id, name, email FROM %s WHERE username = \'%s\' AND password = \'%s\''
	);
}

?>