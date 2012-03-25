<?php
require_once 'SQL_Intervals_Object.php';

/**
 * This class does an abstraction between an SQL table which is formated
 * as a hierarchy table using intervals.
 * 
 * @package   SQL_Intervals
 * @author    Samuel ROZE <samuel.roze@gmail.com>
 * @copyright 2009 Samuel ROZE
 * @license   LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link      http://www.d-sites.com/projets/librairies/SQL_Intervals
 */
class SQL_Intervals
{
    /**
     * Database object : PDO or an object with the same structure.
     *
     * @var PDO
     */
    static $sql = null;
    
    /**
     * Requests object
     * 
     * @var SQL_Intervals_mysql
     * @var SQL_Intervals_pgsql
     */
    static $requests = null;
    
    /**
     * Type of database. (mysql, sqlite, pgsql, ...)
     * 
     * @var string
     */
    private $database_type = null;
    
    /**
     * Constructor...
     *
     * @param PDO    $sql_object    Database object, like PDO
     * @param string $database_type Type of database
     */
    public function __construct ($sql_object, $database_type = null)
    {
        self::$sql = $sql_object;
        
        if ($database_type == null) {
            $database_type = self::$sql->getAttribute(PDO::ATTR_DRIVER_NAME);
        }
        
        $this->database_type = $database_type;
        
        $className = 'SQL_Intervals_'.$database_type;
        if (!class_exists($className)) {
            throw new SQL_Intervals_Exception('Unable to load '.$className.' class');
        }
        self::$requests = new $className();
    }
    
    /**
     * Add an element in the table
     * 
     * @param string                $table     Name of the table
     * @param SQL_Intervals_Object 	$parent    Object du parent
     * @param array                 $arguments Arguments
     * 
     * @return SQL_Intervals_Object $element
     */
    function add ($table, SQL_Intervals_Object $parent, $arguments)
    {
        $parent->clean();
        
        self::$sql->beginTransaction();
        // Moving objects right bounds from the parent's right bound
        try {
            self::$sql->exec(
                sprintf(self::$requests->get('moveto2right'), $table, $parent->get('to'))
            );
        } catch (PDOException $e) {
            throw new SQL_Intervals_Exception(
                'Unable to move right bounds : '.$e->getMessage()
            );
        }
        // Moving left bounds
        try {
            self::$sql->exec(
                sprintf(self::$requests->get('movefrom2right'), $table, $parent->get('to'))
            );
        } catch (PDOException $e) {
            throw new SQL_Intervals_Exception(
                'Unable to move left bounds : '.$e->getMessage()
            );
        }
        
        // Now, there's free places, we'll add the new object
        $from = $parent->get('to');
        $to = $parent->get('to') + 1;
        $more = self::$requests->more($arguments);
        
        try {
            $r3 = self::$sql->query(
                sprintf(self::$requests->get('insert'), $table, $more[0], $from, $to, $more[1])
            );
        } catch (PDOException $e) {
            throw new SQL_Intervals_Exception(
                'Unable to insert the new object : '.$e->getMessage()
            );
        }
        
        if (!$r3) {
			throw new SQL_Intervals_Exception(
				sprintf(
					'Unable to insert the new object: %s',
					print_r(self::$sql->errorInfo(), true)
				)
			);
		}
        
        if ($this->database_type == 'pgsql') {
        	$query_result = $r3->fetch();
        	$object_informations = (int) $query_result['id'];
        } else {
        	$object_informations = (int) self::$sql->lastInsertId();
        }
        
        self::$sql->commit();
        
        return new SQL_Intervals_Object($table, $object_informations);
    }
    
    /**
     * Remove an object
     * 
     * @param string               $table   Table's name
     * @param SQL_Intervals_Object &$object Object
     * 
     * @return void
     */
    function remove ($table, SQL_Intervals_Object &$object)
    {
        // Start SQL transaction
        self::$sql->beginTransaction();
        
        // Removing all children of the object, and it.
        try {
            self::$sql->exec(
                sprintf(self::$requests->get('delete'), $table, $object->get('from'), $object->get('to'))
            );
        } catch (PDOException $e) {
            throw new SQL_Intervals_Exception(
                'Unable to remove the object : '.$e->getMessage()
            );
        }
        
        // What's the empty place done ? We'll remove it.
        $emptyplaces_size = $object->get('to') - $object->get('from') + 1;
        
        try {
            self::$sql->exec(
                sprintf(self::$requests->get('movefrom2left'), $table, $emptyplaces_size, $object->get('from'))
            );
            self::$sql->exec(
                sprintf(self::$requests->get('moveto2left'), $table, $emptyplaces_size, $object->get('from'))
            );
        } catch (PDOException $e) {
            throw new SQL_Intervals_Exception(
                'Unable to move bounds : '.$e->getMessage()
            );
        }
        
        // No errors, it's OK.
        self::$sql->commit();
        
        // Remove object's instance
        $object = null;
    }
    
    /**
     * Move an object in other one.
     *
     * @param string               $table  Table's name
     * @param SQL_Intervals_Object $object Object to move
     * @param SQL_Intervals_Object $target The future object's parent
     * 
     * @return void
     */
    function move ($table, SQL_Intervals_Object $object, SQL_Intervals_Object $target)
    {
        $object->clean();
        $target->clean();
        
        // Calcul the object's size
        $t_o = $object->get('to') - $object->get('from') + 1;
        
        // Starting an new SQL transaction
        self::$sql->beginTransaction();
        
        // In the first time, we put the object in "stand by" : values are turned
        // to negative values.
        self::$sql->exec(
            sprintf(
                self::$requests->get('gostandby'),
                $table,
                $object->get('from'),
                $object->get('to')
            )
        );

        // If the movement is to the right
        if ($object->get('to') < $target->get('to')) {
            try {
                // We'll move all elements who are between the object and the target to the left
                self::$sql->exec(
                    sprintf(
                        self::$requests->get('movefrom2leftbetweenplaces'),
                        $table,
                        $t_o,
                        $target->get('to'),
                        $object->get('to')
                    )
                );
                // We'll move the target's left bound to the left to make free place to the object
                self::$sql->exec(
                    sprintf(
                        self::$requests->get('moveto2leftbetweenplaces'),
                        $table,
                        $t_o,
                        $object->get('from'),
                        $target->get('from'),
                        $object->get('to'),
                        $target->get('to')
                    )
                );
                // And, at the end of the target, where's free place for object, we'll insert it.
                self::$sql->exec(
                    sprintf(
                        self::$requests->get('leavestandby'),
                        $table,
                        ($target->get('to') - $t_o),
                        ($target->get('to') - 1),
                        (-1 * $object->get('from')),
                        (-1 * $object->get('to'))
                    )
                );
            } catch (PDOException $e) {
                throw new SQL_Intervals_Exception(
                    'Unable to move to the right : '.$e->getMessage()
                );
            }
            // If the movement is to the left
        } else if ($object->get('to') > $target->get('to')) {
            try {
                // We'll move the target's right bound for make free place
                self::$sql->exec(
                    sprintf(
                        self::$requests->get('movefrom2rightbetweenplaces'), 
                        $table,
                        $t_o,
                        $target->get('to'),
                        $object->get('from'),
                        $object->get('from'),
                        $object->get('to'),
                        $target->get('from')
                    )
                );
                // Now, moving elements between the object and the target to the right
                self::$sql->exec(
                    sprintf(
                        self::$requests->get('moveto2rightbetweenplaces'),
                        $table,
                        $t_o,
                        $target->get('to'),
                        $object->get('from')
                    )
                );
                // And, insert the object in the free space
                self::$sql->exec(
                    sprintf(
                        self::$requests->get('leavestandby'),
                        $table,
                        $target->get('to'),
                        ($target->get('to') + $t_o - 1),
                        (-1 * $object->get('from')),
                        (-1 * $object->get('to'))
                    )
                );
            } catch (PDOException $e) {
                throw new SQL_Intervals_Exception(
                    'Unable to move to the left : '.$e->getMessage()
                );
            }
        }
        // All it's OK, commit !
        self::$sql->commit();
    }
}

class SQL_Intervals_Exception extends Exception
{
}

?>