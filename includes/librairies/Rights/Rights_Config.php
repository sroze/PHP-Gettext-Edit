<?php
/**
 * This class is a repository of Rights configurations.
 * 
 * @author Samuel ROZE <samuel.roze@gmail.com>
 * @link http://www.d-sites.com/projets/librairies/Rights
 * @version 0.2
 */
class Rights_Config
{
	
	/**
	 * List of Database tables.
	 *
	 * @var array
	 */
	static $tables = array(
		// For rights
		'rights_list' => 'rights_list',
		'groups_list' => 'groups_list',
		// For relations with rights
		'groups_rights' => 'groups_rights',
		'users_rights' => 'users_rights',
		// For relation with users
		'users_groups' => 'users_groups',
		// For contexts
		'context_list' => 'context_list'
	);
	
	/**
	 * Table prefix.
	 *
	 * @var string
	 */
	static $prefix = '';
	
	/**
	 * Active dynamic rights.
	 * 
	 * @var boolean
	 */
	static $dynamic_rights = true;
	
	/**
	 * "PDO" class
	 *
	 * @var PDO
	 */
	static $sql = null;
	
	/**
	 * Type of Database. (mysql, sqlite, pgsql...)
	 * 
	 * @var string
	 */
	static $sql_type = null;
	
	/**
	 * An Rights_SQL sub-class which contains every request for the type
	 * of Database used.
	 * 
	 * @var Rights_SQL
	 */
	static $requests = null;
	
	/**
	 * Class "SQL_Intervals" created
	 *
	 * @var SQL_Intervals
	 */
	static $intervals = null;
	
	/**
	 * Separator of data in a to-string-array.
	 *
	 * @var string
	 */
	static $database_array_separator = ';';
	
	/**
	 * Set a config value.
	 *
	 * @param string $name
	 * 
	 * @param mixed $value
	 */
	static public function set ()
	{
		$num_args = func_num_args();
		if ($num_args < 2) {
			throw new Droits_Exception(
				'Arg number to low'
			);
		}
		
		for ($i = 0; $i < ($num_args-1); $i++) {
			$arg = func_get_arg($i);
			if (!isset($variable)) {
				$variable = &Rights_Config::${$arg};
			} else if (isset($variable[$arg]) || $i == ($num_args-2)) {
				$variable = &$variable[$arg];
			} else if ($arg == '[]') {
				$variable = &$variable[];
			} else {
				throw new Droits_Exception(
					sprintf('Lap #%d: "%s" undefined', $i, $arg)
				);
			}
		}
		$variable = func_get_arg($i);
	}
	
	/**
	 * Return a config value.
	 * 
	 * More of one argument return values in an assoc array.
	 * 
	 * @param string $name
	 * @param string $name
	 * @param string ...
	 * 
	 * @return mixed
	 */
	static public function get ()
	{
		$num_args = func_num_args();
		if ($num_args == 0) {
			throw new Droits_Exception(
				'No argument'
			);
		}
		for ($i = 0; $i < $num_args; $i++) {
			$arg = func_get_arg($i);
			
			if (!isset($variable)) {
				$variable = Rights_Config::${$arg};
			} else if (isset($variable[$arg])) {
				$variable = $variable[$arg];
			} else {
				throw new Droits_Exception(
					sprintf('Lap #%d: "%s" undefined', $i, $arg)
				);
			}
		}
		
		return $variable;
	}
	
	/**
	 * Build the in-classes
	 * 
	 * @param PDO    $sql_object PDO object or like
	 * @param string $prefix     Tables prefix
	 * 
	 * @return void
	 */
	static function init ($sql_object, $prefix = '')
	{
	    self::set('sql', $sql_object);
	    // Create the requests class
	    $database_type = self::$sql->getAttribute(PDO::ATTR_DRIVER_NAME);
	    self::$sql_type = $database_type;
	    
	    $className = 'Rights_SQL_'.$database_type;
	    if (!class_exists($className)) {
	    	@include 'SQL/'.$className.'.php';
	    	
	    	if (!class_exists($className)) {
		        throw new Rights_Exception(
		        	'Unable to find '.$className.' requests class'
		        );
	    	}
	    }
	    self::set('requests', new $className());
	    // Create the intervals instance
	    if (!class_exists('SQL_Intervals')) {
	        throw new Rights_Exception(
	        	'Unable to find SQL_Intervals class'
	        );
	    }
	    self::set('intervals', new SQL_Intervals($sql_object));
	    // Fix prefix
	    self::set('prefix', $prefix);
	}
}

?>