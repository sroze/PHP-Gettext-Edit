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
		'create_user' => 'INSERT INTO %s (username, password, email) VALUES (\'%s\', \'%s\', \'%s\') RETURNING id'
	);
}

?>