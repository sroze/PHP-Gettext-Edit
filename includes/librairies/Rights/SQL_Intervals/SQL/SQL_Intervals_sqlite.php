<?php

/**
 * Contains SQL_Intervals's resquests for SQLite.
 * 
 * @package   SQL_Intervals
 * @author    Samuel ROZE <samuel.roze@gmail.com>
 * @copyright 2009 Samuel ROZE
 * @license   LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link      http://www.d-sites.com/projets/librairies/SQL_Intervals
 */
class SQL_Intervals_SQLite
{
    /**
     * Array which contains requests
     * 
     * @var array
     */
    private $_requests = array(
    
        'moveto2right' => 'UPDATE %s SET "to" = "to" + 2 WHERE "to" >= %d',
        'movefrom2right' => 'UPDATE %s SET "from" = "from" + 2 WHERE "from" > %d',
    
        'moveto2left' => 'UPDATE %s SET "to" = "to" - %d WHERE "to" >= %d',
        'movefrom2left' => 'UPDATE %s SET "from" = "from" - %d WHERE "from" > %d',
        
        'insert' => 'INSERT INTO %s ("from", "to"%s) VALUES (%d, %d%s)',
        'delete' => 'DELETE FROM %s WHERE "from" >= %d AND "to" <= %d',
    
        'select' => 'SELECT * FROM %s WHERE id = %d',
    
        'gostandby' => 'UPDATE %s
            SET "from" = (-1) * "from" , "to" = (-1) * "to"
            WHERE "from" = %d AND "to" = %d',
        'leavestandby' => 'UPDATE %s
            SET "from" = %d , "to" = %d
            WHERE "from" = %d AND "to" = %d',
    
        'movefrom2leftbetweenplaces' => 'UPDATE %s SET "from" = "from" - %d
            WHERE "to" <= %d
                AND "from" > %d',
        'moveto2leftbetweenplaces' => 'UPDATE %s SET "to" = "to" - %d
            WHERE (
                %d BETWEEN "from" AND "to"
                AND %d NOT BETWEEN "from" AND "to"
            )
            OR (
                "from" > %d
                AND "to" < %d
            )',
    
        'movefrom2rightbetweenplaces' => 'UPDATE %s SET "from" = "from" + %d
            WHERE (
                "from" > %d
                AND "to" < %d
            )
            OR (
                "from" < %d
                AND "to" > %d
                AND %d NOT BETWEEN "from" AND "to"
            )',
        'moveto2rightbetweenplaces' => 'UPDATE %s SET "to" = "to" + %d
            WHERE "to" >= %d
                AND "to" < %d',
    
    );
    
    /**
     * Return the query.
     * 
     * @param string $name Tag name of the request
     * 
     * @return string $requete SQL Request
     * @throws SQL_Intervals_Exception when the request doesn't exist
     */
    public function get ($name)
    {
        if (isset($this->_requests[$name])) {
            return $this->_requests[$name];
        } else {
            throw new SQL_Intervals_Exception(
                'The request "'.$name.'" does not exist'
            );
        }
    }
    
    /**
     * Implode more arguments to put them into an other request
     * 
     * @param array $arguments Associative array of fields
     * 
     * @return array [array $fields, array $values]
     */
    public function more ($arguments)
    {
        $more = array('', '');
        foreach ($arguments as $title => $value) {
            $more[0] .= ', "'.$title.'"';
            $more[1] .= ', \''.$value.'\'';
        }
        return $more;
    }
    
}

?>