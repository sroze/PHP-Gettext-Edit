<?php
if (!isset($_CONFIG['database'])) {
	throw new Exception(
		_('La configuration de la base de données est introuvable')
	);
}

$sql = new PDO(
	$_CONFIG['database']['type'].':'.
		'dbname='.$_CONFIG['database']['dbname'].';'.
		'host='.$_CONFIG['database']['host'].';'.
		'port='.$_CONFIG['database']['port'],
	$_CONFIG['database']['user'],
	$_CONFIG['database']['password']
);

if ($sql === false) {
	$sql_error = $sql->errorInfo();
	throw new Exception(
		sprintf(_('Impossible de se connecter à la base de données: %s'), $sql_error[2])
	);
}
?>