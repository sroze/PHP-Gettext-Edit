<?php
define('ROOT_PATH', realpath(dirname(__FILE__).'/../').'/');
require_once ROOT_PATH.'includes/configuration.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>GetTextEdit</title>
<link rel="stylesheet" href="../templates/clear/styles/site.css" type="text/css" media="all" />
</head>
<body>
<div id="header">
	<h1>GetTextEdit</h1>
	<h2><?php echo _('Installation de l\'application'); ?></h2>
</div>
<div id="page">
	<div id="sidebar">
		<h3><?php echo _('Installation rapide'); ?></h3>
		<p><?php echo _('Dans quelques secondes, vous aurez fini d\'installer GetTextEdit.'); ?></p>
	</div>
	<div id="contents" class="with_sidebar">
<?php
if (isset($_POST['install'])) {
	$fopen = fopen($sql->filename, 'a+');
	if (!$fopen) {
		echo '<div class="form_error">'.
			sprintf(_('Impossible d\'ouvrir le fichier de base de données. <em>Chmodez</em> et <em>Chownez</em> comme il faut pour que %s soit accessible en lecture et écriture'), $sql->filename).
			'</div>';
	} else {
		fclose($fopen);
		
		$sql->query('CREATE TABLE projects (
			project_id INTEGER PRIMARY KEY AUTOINCREMENT,
			project_name TEXT,
			project_path TEXT,
			project_languages_path TEXT
		)');
		$_CONFIG['installed'] = 1;
		$config_ini->write($_CONFIG);
		
		echo '<h2 class="ok">'._('Application installée avec succès').'</h2>';
	}
} else {
?>
<form method="POST" action="">
	<input type="hidden" name="install" value="1" />
	<input type="submit" name="submit" value="Installation !" />
</form>
<?php 
}
?>
	</div>
</div>
<div id="footer"><a href="http://www.d-sites.com/projets/gettextedit/">GetTextEdit</a> &copy; 2009 <a href="http://www.d-sites.com">Samuel ROZE</a></div>
</body>
</html>