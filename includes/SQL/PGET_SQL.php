<?php
/**
 * Abstract class for SQL requests.
 *
 * @author Samuel ROZE <samuel.roze@gmail.com>
 */
abstract class PGET_SQL
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
			throw new PGET_SQL_Exception(
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
	 * Get the SQL code which check if field is in any values which is
	 * in the array given as the 2nd arg.
	 * 
	 * @param string $field
	 * @param array	 $values
	 * 
	 * @return string
	 */
	abstract public function inAny ($field, $values);
}

class PGET_SQL_Exception extends Exception {}
?>