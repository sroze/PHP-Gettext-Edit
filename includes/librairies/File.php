<?php
class File
{
	/**
	 * Remove file, or directory.
	 * 
	 * @param string $directory_or_file
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
}

class File_Exception extends Exception {}
?>