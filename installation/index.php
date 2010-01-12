<?php
define('ROOT_PATH', realpath(dirname(__FILE__).'/../').'/');
require_once ROOT_PATH.'installation/includes/configuration.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>PHP-GetText-Edit</title>
<link rel="stylesheet" href="../templates/clear/styles/site.css" type="text/css" media="all" />
</head>
<body>
<div id="header">
	<h1>PHP-GetText-Edit</h1>
	<h2><?php echo _('Installation de l\'application'); ?></h2>
</div>
<div id="page">
	<div id="sidebar">
		<h3><?php echo _('Installation rapide'); ?></h3>
		<p><?php echo _('Dans une petite minute, vous aurez fini d\'installer GetTextEdit.'); ?></p>
	</div>
	<div id="contents" class="with_sidebar">
<?php
if (isset($_POST['install'])) {
	
}
if (!defined('INSTALLED')) {
?>
<h1>Installation</h1>
<?php 
if (!is_writable(INI_FILE_PATH)) {
	echo '<div class="box error"><p>', _('Le fichier INI de configuration n\'est pas accessible en écriture par PHP-Gettext-Edit.'), ' ',
	_('Le fichier se trouve à cette adresse:'), '</p><p>', INI_FILE_PATH, '</p></div>';
}
?><form method="POST" action="">
<fieldset>
	<legend><?php echo _('Base de données'); ?></legend>
	<p><label><?php echo _('Type de base de données'); ?></label><select name="sql-type">
		<option value="pgsql">PostgreSQL</option>
		<option value="mysql">MySQL</option>
	</select></p>
	<p><label><?php echo _('Adresse de votre serveur SQL'); ?></label>
		<input type="text" name="sql-host" value="localhost" />
	</p>
	<p><label><?php echo _('Port de connexion'); ?></label>
		<input type="text" name="sql-port" value="" />
	</p>
	<p><label><?php echo _('Nom d\'utilisateur'); ?></label>
		<input type="text" name="sql-user" value="" />
	</p>
	<p><label><?php echo _('Mot de passe'); ?></label>
		<input type="text" name="sql-password" value="" />
	</p>
	<p><label><?php echo _('Nom de la base de données'); ?></label>
		<input type="text" name="sql-dbname" value="" />
	</p>
	<p><label><?php echo _('Préfix des tables'); ?></label>
		<input type="text" name="sql-prefix" value="gte_" />
	</p>
</fieldset>

<fieldset>
	<legend><?php echo _('Administrateur'); ?></legend>
	<p><?php echo _('Un utilisateur administrateur doit être créé, remplissez les informations ci-dessous:'); ?></p>
	<p><label><?php echo _('Nom d\'utilisateur'); ?></label>
		<input type="text" name="admin-user" value="" />
	</p>
	<p><label><?php echo _('Mot de passe'); ?></label>
		<input type="text" name="admin-password" value="" />
	</p>
	<p><label><?php echo _('Adresse email'); ?></label>
		<input type="text" name="admin-email" value="" />
	</p>
</fieldset>

	<input type="hidden" name="install" value="yes" />
	<input type="submit" name="submit" value="<?php echo _('Installation !'); ?>" />
</form>
<?php 
}
?>
</div>
</div>
<div id="footer"><a href="http://www.d-sites.com/projets/gettextedit/">GetTextEdit</a> &copy; 2009 <a href="http://www.d-sites.com">Samuel ROZE</a></div>
</body>
</html>