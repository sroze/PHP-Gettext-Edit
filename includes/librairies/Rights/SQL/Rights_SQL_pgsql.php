<?php
require_once 'Rights_SQL.php';

/**
 * SQL requests for PostgreSQL for the Rights library.
 *
 * @author Samuel ROZE <samuel.roze@gmail.com>
 * @link http://www.d-sites.com/projets/librairies/Rights
 * @version 0.2
 */
class Rights_SQL_pgsql extends Rights_SQL
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
	
		// Start: Rights_Admin
		
		'insert_group_right' => 'INSERT INTO %s ("group", "right") VALUES (%d, %d)',
		'delete_group_right' => 'DELETE FROM %s WHERE "group" = %d AND "right" = %d',
	
		'select_group_rights' => 'SELECT "right", "grant" FROM %s WHERE "group" = %d',
		'get_groups' => 'SELECT * FROM %s',
	
		'insert_user_right' => 'INSERT INTO %s ("user", "right", "context", "grant") VALUES (%d, %d, %s, %d::boolean)', // #4 must be %s
		'delete_user_right' => 'DELETE FROM %s ur WHERE "user" = %d AND "right" = %d 
			AND (
				(ur."context" IS NULL)
				%s
			)',
	
		'select_user_rights' => 'SELECT ur."right" , ur."grant", rl."name"
			FROM %s ur
				LEFT OUTER JOIN %s rl ON rl.id = ur.right
			WHERE "user" = %d
			AND (
				(ur."context" IS NULL)
				%s
			)',
	
		'insert_user_group' => 'INSERT INTO %s ("user", "group", "context", "grant") VALUES (%d, %d, %s, %d::boolean)', // #4 must be %s
		'delete_user_group' => 'DELETE FROM %s ug WHERE "user" = %d AND "group" = %d 
			AND (
				(ug."context" IS NULL)
				%s
			)',
	
		'select_user_groups' => 'SELECT gl."id", gl."name" 
		FROM %s ug
			LEFT OUTER JOIN %s as gl ON gl.id = ug.group
		WHERE ug."user" = %d 
			AND (
				(ug."context" IS NULL)
				%s
			)',
	
		'insert_context' => 'INSERT INTO %s DEFAULT VALUES RETURNING id',
		'delete_context' => 'DELETE FROM %s WHERE id = %d',
		'set_context_keys' => 'UPDATE %s SET "name" = \'%s\' WHERE id = %d',
		'get_context_keys' => 'SELECT "name" FROM %s WHERE id = %d',
		'get_context_id_by_name' => 'SELECT "id" FROM %s WHERE "name" = \'%s\'',
	
		'get_right_id_by_name' => 'SELECT id FROM %s WHERE name = \'%s\'',
	
		'in_rights' => 'SELECT ur."user" FROM %s as ur
			LEFT OUTER JOIN %s as rl
				ON rl.id = ur."right"
			LEFT OUTER JOIN %s as cl
				ON cl.id = ur."context"
			WHERE rl."from" >= %d
				AND rl."to" <= %d
				AND (
				    (ur."context" IS NULL)
				    %s
				)
				AND ur."grant" = TRUE',
	
		'in_groups' => 'SELECT ug."user" FROM %s as ug
			LEFT OUTER JOIN %s as gl
				ON gl.id = ug."group"
			LEFT OUTER JOIN %s as gl2
				ON (gl2."from" <= gl."from" AND gl2."to" >= gl."to")
			LEFT OUTER JOIN %s as gr
				ON gr."group" = gl2.id
			LEFT OUTER JOIN %s as rl 
				ON rl.id = gr."right"
			LEFT OUTER JOIN %s as cl
				ON cl.id = ug."context"
			WHERE gr."right" IS NOT NULL
				AND rl."from" >= %d
				AND rl."to" <= %d
				AND (
					(ug."context" IS NULL)
					%s
				)
				AND ug."grant" = TRUE',
	
		// End: Rights_Admin
		// Start: Rights
	
		'select_right_by_id' => 'SELECT * FROM %s WHERE id = %d',
		'select_right_by_name' => 'SELECT * FROM %s WHERE name = \'%s\'',
	
		'in_user_rights' => 'SELECT ur."grant" FROM %s as ur
			LEFT OUTER JOIN %s as rl
				ON rl.id = ur."right"
			LEFT OUTER JOIN %s as cl
				ON cl.id = ur."context"
			WHERE ur."user" = %d
				AND rl."from" >= %d
				AND rl."to" <= %d
				AND (
				    (ur."context" IS NULL)
				    %s
				)
			ORDER BY rl."from", ur."grant" ASC
			LIMIT 1',
	
		'in_user_groups' => 'SELECT ug."grant" FROM %s as ug
			LEFT OUTER JOIN %s as gl
				ON gl.id = ug."group"
			LEFT OUTER JOIN %s as gl2
				ON (gl2."from" <= gl."from" AND gl2."to" >= gl."to")
			LEFT OUTER JOIN %s as gr
				ON gr."group" = gl2.id
			LEFT OUTER JOIN %s as rl 
				ON rl.id = gr."right"
			LEFT OUTER JOIN %s as cl
				ON cl.id = ug."context"
			WHERE ug."user" = %d
				AND gr."right" IS NOT NULL
				AND rl."from" >= %d
				AND rl."to" <= %d
				AND (
					(ug."context" IS NULL)
					%s
				)
			ORDER BY rl."from", ug."grant" ASC
			LIMIT 1',
	
		// End: Rights
	);
	
	/**
	 * Check if $value is at the start of $field
	 * 
	 * @param string $field
	 * @param string $value
	 * 
	 * @return string
	 */
	public function startOfString ($field, $value)
	{
		$value = preg_replace(
			'#\'#',
			'\\\'',
			$value
		);
		
		return 'position(\''.$value.'\' in '.$field.') = 1';
	}
}

?>