<?php
class Database
{
	/**
	 * Prefix of tables.
	 * 
	 * @var string
	 */
	static $prefix;
	
	/**
	 * PDO instance.
	 * 
	 * @var PDO
	 */
	static $sql;
	
	/**
	 * PGTE_SQL instance.
	 * 
	 * @var PGTE_SQL
	 */
	static $requests;
	
	/**
	 * Type of database.
	 * 
	 * @var string
	 */
	static $database_type;
	
	/**
	 * Set $sql and $requests
	 * 
	 * @param PDO 	 $sql
	 * @param string $prefix
	 * 
	 * @return Database
	 */
	static function init (PDO $sql, $prefix = '')
	{
        $database_type = $sql->getAttribute(PDO::ATTR_DRIVER_NAME);
        
		$className = 'PGET_SQL_'.$database_type;
		if (!class_exists($className)) {
		  	@include ROOT_PATH.'includes/SQL/'.$className.'.php';
			    	
		   	if (!class_exists($className)) {
		        throw new Rights_Exception(
		        	'Unable to find '.$className.' requests class'
		        );
		   	}
		}
		
		self::$database_type = $database_type;
		self::$prefix = $prefix;
		self::$sql = $sql;
		self::$requests = new $className();
	}
}
?>