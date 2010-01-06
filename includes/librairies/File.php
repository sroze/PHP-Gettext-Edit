<?php
class File
{
	/**
	 * Remove file, or directory.
	 * 
	 * @param string $directory_or_file
	 * 
	 * @return void
	 */
	static function rm ($directory_or_file)
	{
		if (is_file($directory_or_file)) {
			return unlink($directory_or_file);
		} else if (is_dir($directory_or_file)) {
			if (substr($directory_or_file, -1) != '/') {
				$directory_or_file .= '/';
			}
			
			$dh = @opendir($directory_or_file);
			
			if (!$dh) {
		        return $dh;
		    } else {
			    while (false !== ($obj = readdir($dh))) {
			        if($obj == '.' || $obj == '..') {
			            continue;
			        }
			
			        $rm = self::rm($directory_or_file.$obj);
			        
			        if (!$rm) {
			        	return $rm;
			        }
			    }
			
			    closedir($dh);
			    rmdir($directory_or_file);
		    }
		} else {
			throw new File_Exception(
				sprintf(_('Bad type of data: %s'), $directory_or_file)
			);
		}
		
		return true;
	}
	
	/**
	 * Clean a list of directories/files: remove files which are contained in
	 * a selected directory for instance.
	 * 
	 * @param array $directories_or_files
	 * 
	 * @return array
	 */
	static function cleanTree ($directories_or_files)
	{
		$result = array();
		
		foreach ($directories_or_files as $object) {
			
			
			if (self::parentIn($object, $directories_or_files) != false) {
				continue;
			} else if (!in_array($object, $result)) {
				$result[] = $object;
			}
		}
		
		return $result;
	}
	
	/**
	 * Search if a directory or a file have a parent in the list of
	 * objects.
	 * 
	 * @param string $object
	 * @param array  $object_list
	 * 
	 * @return string|false The parent or bool(false)
	 */
	static function parentIn ($object, $object_list)
	{
		$x = explode('/', substr($object, 1));
		array_pop($x);
		if (substr($object, -1) == '/') { // Directory
			array_pop($x);
		}
		
		$actual_string = '/';
		for ($i = 0; $i < count($x); $i++) {
			$actual_string .= $x[$i].'/';
			
			if (in_array($actual_string, $object_list)) {
				return $actual_string;
			}
		}
		
		return false;
	}
}

class File_Exception extends Exception {}
?>