<?php
/**
 * This class helps to manage INI files
 * 
 * @author  Samuel ROZE <samuel.roze@gmail.com>
 * @link    http://www.d-sites.com/projets/librairies/File_INI/
 * @link	http://tasks.d-sites.com/projects/show/php-file-ini
 * @version 1.0
 */
class File_INI
{
    /**
     * The file path
     * 
     * @var string
     */
    public $filename;
    
    /**
     * Constructor.
     * 
     * @param string $filename File path
     * 
     * @return File_INI
     */
    public function __construct ($filename)
    {
        $this->filename = $filename;
    }
    
    /**
     * Return the INI as a multi-dimentional Array
     * 
     * @return array
     */
    public function read ()
    {
        $array = parse_ini_file($this->filename, true);
        if (!$array) {
            throw new File_INI_Exception(
                _('Unable to read the file')
            );
        }
        return $array;
    }
    
    /**
     * Write into the ini file, from a multi-dimensional Array
     * 
     * @param array $array The array which will transformed into INI
     * 
     * @return void
     */
    public function write ($array)
    {
        $contents = $this->addArray($array, null, 0);
        // Opening file
        $f = fopen($this->filename, 'w');
        if (!$f) {
            throw new File_INI_Exception('Unable to open the file with writing rights');
        }
        
        // Write & Close
        $puts = fputs($f, $contents);
        if (!$puts) {
            throw new File_INI_Exception('Writing failed');
        }
        
        @fclose($f);
    }
    
    /**
     * Parse a Array to INI
     * 
     * @param array   $array The Array
     * @param string  $group The groupe name
     * @param integer $level The grade into the array/ini
     * 
     * @return string
     */
    private function addArray ($array, $group, $level)
    {
        if ($level >= 2) {
            throw new File_INI_Exception(
                'Just two array levels are allowed'
            );
        }
        
        $output = ''; $groups = '';
        
        if ($group != null) {
            $output .= "\n".'['.$group.']';
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $groups .= $this->addArray($value, $key, $level + 1);
            } else if (is_string($value) || is_int($value) || is_bool($value)) {
                $output .= $this->addValue($key, $value);
            }
        }
        return $output.$groups;
    }
    
    /**
     * Parse a key/value couple to INI
     * 
     * @param string $key Key
     * @param mixed  $val Value
     * 
     * @return string
     */
    private function addValue ($key, $val)
    {
        if (!is_string($key)) {
            throw new File_INI_Exception('The key must be a string');
        }
        $output = "\n".$key.' = ';
        if (is_int($val)) {
            $output .= (string) $val;
        } else {
            $val = str_replace('"', '\"', $val);
            $output .= '"'.(string) $val.'"';
        }
        return $output;
    }    
}

class File_INI_Exception extends Exception
{
}
?>