<?php
/**
 * Abstract class for SQL requests.
 *
 * @author Samuel ROZE <samuel.roze@gmail.com>
 * @link http://www.d-sites.com/projets/librairies/Rights
 * @version 0.2
 */
abstract class Rights_SQL
{
	/**
	 * Character which is used to delimit fields in an SQL request
	 * 
	 * @var string(1)
	 */
	public $fields_delimitor = '"';
	
	/**
	 * An assoc array with requests keys and requests contents.
	 * 
	 * @var array
	 */
	public $requests;
	
	/**
	 * Return a request, or if it doesn't exists, throw an error.
	 * 
	 * @param string $name
	 * 
	 * @return string $request
	 */
	public function get ($name)
	{
		if (isset($this->requests[$name]))
			return $this->requests[$name];
		else {
			throw new Rights_SQL_Exception(
				sprintf('Request "%s" doesn\'t exists', $name)
			);
		}
	}
	
	/**
	 * Concat more arguments
	 * 
	 * @param array $arguments
	 * 
	 * @return array [array $names, array $values]
	 */
	public function more ($arguments)
	{
		$more = array('', '');
		foreach ($arguments as $title => $value) {
			$more[0] .= ', '.$this->fields_delimitor.$title.$this->fields_delimitor.'"';
			$more[1] .= ', \''.$value.'\'';
		}
		return $more;
	}
	
	/**
	 * Create a dynamic part of requests.
	 * 
	 * @param string 		 $label
	 * @param string 		 $field
	 * @param string|integer $value
	 * @param boolean	 	 $strict
	 * 
	 * @return string
	 */
	public function makeDynamic ($label, $field, $value, $strict = true)
	{
		// Parse label
		if ($label == null) {
			$label = '';
		} else {
			$label .= '.';
		}
		
		// Parse field
		$field = $label.$this->fields_delimitor.$field.$this->fields_delimitor;
		
		if (!is_int($value)) {
			return $this->startOfString($field, $value);
		} else if (!$strict) {
			throw new Rights_SQL_Exception(
				'Strict (arg #4) couldn\'t be false for integer values (arg #3)'
			);
		} else {
			return $field.' = '.$value;	
		}
	}
	
	/**
	 * Make a part of SQL request, about the context.
	 * 
	 * @param string $label
	 * 
	 * @return string
	 */
	public function orContextNotNull ($label)
	{
		return 'OR ('.$label.'.'.$this->fields_delimitor.'context'.$this->fields_delimitor.' IS NOT NULL
			AND %s
		)';
	}
	
	// Functon startOfString must be extended
	abstract public function startOfString ($field, $value);
}

class Rights_SQL_Exception extends Exception {}
?>