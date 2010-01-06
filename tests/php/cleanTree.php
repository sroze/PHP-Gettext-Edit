<?php
function cleanTree ($directories_or_files)
{
	$result = array();
	
	foreach ($directories_or_files as $object) {
		$parent = parentIn($object, $directories_or_files);
		
		if ($parent != false) {
			continue;
		} else if (!in_array($object, $result)) {
			$result[] = $object;
		}
	}
	
	return $result;
}

function parentIn ($object, $object_list)
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

$tree = array(
	'/includes/',
	'/tests/dir2/file.php',
	'/tests/',
	'/.project',
	'/includes/keys'
);

var_dump(
	$tree,
	cleanTree($tree)
);
?>