<?php

/**
 * This class describe an element of the SQL table, with its ID or its bounds.
 * 
 * @package   SQL_Intervals
 * @author    Samuel ROZE <samuel.roze@gmail.com>
 * @copyright 2009 Samuel ROZE
 * @license   LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link      http://www.d-sites.com/projets/librairies/SQL_Intervals
 */
class SQL_Intervals_Object
{
    /**
     * Informations about the object
     * 
     * @var array
     */
    private $_informations = array();
    
    /**
     * Name of the table
     * 
     * @var string
     */
    private $_table;
    
    /**
     * Informations are there loaded ?
     * 
     * @var boolean
     */
    private $_loaded = false;
    
    /**
     * Create the abstract database object.
     * 
     * @param string $table        Name of the table
     * @param array  $informations Some informations about the object
     * if it's necessary.
     * 
     * @return SQL_Intervals_Object
     */
    public function __construct ($table, $informations)
    {
        $this->_table = $table;
        
        if (is_int($informations)) {
            $this->_informations['id'] = $informations;
        } else if (is_array($informations)) {
            if (isset($informations['from']) && isset($informations['to'])) {
                $this->_informations = array_merge($this->_informations, $informations);
            } else if (!empty($informations['id'])) {
                $this->_informations['id'] = $informations['id'];
            } else {
                throw new SQL_Intervals_Exception(
                    'To identificate an object, it must have its ID or its bounds in the array'
                );
            }
        } else {
            throw new SQL_Intervals_Exception(
                'The information type is not supported'
            );
        }
    }
    
    /**
     * Clean the object's informations
     * 
     * @return void
     */
    public function clean ()
    {
        $new_informations = array();
        if (isset($this->_informations['id'])) {
            $new_informations['id'] = $this->_informations['id'];
        }
        $this->_informations = $new_informations;
        $this->_loaded = false;
    }
    
    /**
     * Get some information about this object
     * 
     * @param string $field Field name
     * 
     * @return mixed
     */
    public function get ($field)
    {
        if (!$this->_loaded && !isset($this->_informations[$field])) {
            $this->_loadInformations();
        }
        
        if (isset($this->_informations[$field])) {
            return $this->_informations[$field];
        }
        return false;
    }
    
    /**
     * Set a field.
     * 
     * @param string    $field    Name of the field
     * @param mixed     $value    Value
     * @param string(1) $operator Operator between field and value
     * 
     * @throws SQL_Intervals_Exception when the operator isn't valid
     * @return void
     */
    public function set ($field, $value, $operator = '=')
    {
        if ($operator == '=') {
            $this->_informations[$field] = $value;
        } else if ($operator == '+') {
            $this->_informations[$field] += (int) $value;
        } else if ($operator == '-') {
            $this->_informations[$field] -= (int) $value;
        } else if ($operator == '*') {
            $this->_informations[$field] *= (int) $value;
        } else {
            throw new SQL_Intervals_Exception(
                'Invalid operator'
            );
        }
    }
    
    /**
     * Load objet's informations from the Database
     * 
     * @return void
     */
    private function _loadInformations ()
    {
        if (SQL_Intervals::$sql == null) {
            throw new SQL_Intervals_Exception(
                'SQL_Intervals SQL object not defined'
            );
        } else if (empty($this->_informations['id'])) {
            throw new SQL_Intervals_Exception(
                'Object ID not defined'
            );
        }
        try {
            $query = SQL_Intervals::$sql->query(
                sprintf(SQL_Intervals::$requests->get('select'), $this->_table, $this->_informations['id'])
            );
        }
        catch (PDOException $e) {
            throw new SQL_Intervals_Exception(
                'Unable to get informations from database: '.$e->getMessage()
            );
        }
        
        if (!$query) {
        	$sql_error = SQL_Intervals::$sql->errorInfo();
        	throw new SQL_Intervals_Exception(
                'Unable to get informations from database: '.$sql_error[2]
            );
        }
        
        foreach ($query as $informations) {
            $this->_informations = array_merge($this->_informations, $informations);
        }
        $this->_loaded = true;
    }
}
?>