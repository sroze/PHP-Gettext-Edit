<?php
define('ROOT_PATH', realpath(dirname(__FILE__).'/../').'/');
require_once ROOT_PATH.'installation/includes/configuration.php';
require_once ROOT_PATH.'includes/librairies/String.php';
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
		<h3><?php echo _('Port de connexion'); ?></h3>
		<p><?php echo _('Le port de connexion à la base de données est défini par son administrateur.'), ' ',
		_('Pour MySQL, le port par défaut est <code>3306</code> et pour PostgreSQL <code>5432</code>.'); ?></p>
		<h3><?php echo _('Préfix des tables'); ?></h3>
		<p><?php echo _('Si dans une même base de données vous stokez plusieurs applications, vous pouvez préciser un préfix pour le nom des tables '.
		'de PHP-Gettext-Edit.'), ' ', _('Si vous utilisez une base de données qui supporte les schéma, vous pouvez mettre <code>mon_schema.</code> '.
		'comme préfix, ce qui aura pour conséquence de mettre les tables dans le schéma <code>mon_schema</code>.'); ?></p>
		<h3><?php echo _('Administrateur'); ?></h3>
		<p><?php echo _('Il faut toujours avoir un administrateur pour une application: c\'est celui qui donne les droits et qui peut configurer '.
		'toutes les options de l\'application.'); ?></p>
	</div>
	<div id="contents" class="with_sidebar">
	<h1><?php echo _('Installation'); ?></h1>
<?php
if ((int) $_CONFIG['installed']) {
	echo '<div class="box error"><p>'.
		_('PHP-Gettext-Edit est déjà installé').
		'</p></div>';
	define('INSTALLED', true);
} else if (!is_writable(INI_FILE_PATH)) {
	echo '<div class="box error"><p>', _('Le fichier INI de configuration n\'est pas accessible en écriture par PHP-Gettext-Edit.'), ' ',
	_('Le fichier se trouve à cette adresse:'), '</p><p><strong>', INI_FILE_PATH, '</strong></p></div>';
} else if (isset($_POST['install'])) {
	// Test for fields
	if (!in_array($_POST['sql-type'], $available_databases)) {
		echo '<div class="box error"><p>'.
			_('Base de données non supportée').
			'</p></div>';
	} else if (empty($_POST['sql-host'])
		OR empty($_POST['sql-port']) OR empty($_POST['sql-user'])
		OR empty($_POST['sql-password']) OR empty($_POST['sql-dbname'])
	) {
		echo '<div class="box error"><p>'.
			_('Les informations concernant votre base de données ne sont pas complètes').
			'</p></div>';
	} else if (!String::is_username($_POST['admin-user'])) {
		echo '<div class="box error"><p>'.
			_('Nom d\'utilisateur administrateur invalide').
			'</p></div>';
	} else if (!String::is_password($_POST['admin-password'])) {
		echo '<div class="box error"><p>'.
			_('Mot de passe administrateur invalide').
			'</p></div>';
	} else if (!String::is_email($_POST['admin-email'])) {
		echo '<div class="box error"><p>'.
			_('Adresse email invalide').
			'</p></div>';
	} else {
		// Test database connection
		$sql = new PDO($_POST['sql-type'].':dbname='.$_POST['sql-dbname'].';host='.$_POST['sql-host'].';port='.$_POST['sql-port'], $_POST['sql-user'], $_POST['sql-password']);
		
		if (!$sql) {
			echo '<div class="box error"><p>'.
				_('Impossible de se connecter à la base de données avec ces paramètres').
				'</p></div>';
		} else {
			// Creation of tables
			$sql->beginTransaction();
			
			$sql_contents = file_get_contents(ROOT_PATH.'installation/includes/SQL/'.$_POST['sql-type'].'/php-gettext-edit.sql');
			$sql_contents .= file_get_contents(ROOT_PATH.'installation/includes/SQL/'.$_POST['sql-type'].'/rights.sql');
			// Add prefixes
			$sql_contents = str_replace(
				array(
					'CREATE TABLE ',
					'DROP TABLE IF EXISTS ',
					'INSERT INTO '
				),
				array(
					'CREATE TABLE '.$_POST['sql-prefix'],
					'DROP TABLE IF EXISTS '.$_POST['sql-prefix'],
					'INSERT INTO '.$_POST['sql-prefix']
				),
				$sql_contents
			);
			
			$sql_queries = explode(';', $sql_contents);
			foreach ($sql_queries as $query) {
				if (trim($query) == '') {
					continue;
				}
				
				if ($sql->exec($query) === false) {
					$sql_error = $sql->errorInfo();
					echo '<div class="box error"><p>'.
						_('La requête n\'a pas été éxécutée correctement:').
						'</p><p>'.
						'<strong>'._('Requête').':</strong> '.$query.
						'</p><p>'.
						'<strong>'._('Erreur').':</strong> '.$sql_error[2].
						'</p></div>';
					
					$rollback = $sql->rollBack();
				}
			}
			
			if (!isset($rollback)) {
				$sql->commit();
			}
			
			// Fix it in INI file
			$_CONFIG['database'] = array(
				'type' => $_POST['sql-type'],
				'host' => $_POST['sql-host'],
				'port' => $_POST['sql-port'],
				'user' => $_POST['sql-user'],
				'password' => $_POST['sql-password'],
				'dbname' => $_POST['sql-dbname'],
				'prefix' => $_POST['sql-prefix']
			);
			
			// Then, create rights and groups
			require ROOT_PATH.'installation/includes/SQL/create-rights.php';
			
			Database::init($sql, $_POST['sql-prefix']);
			
			// Create admin user and grant it rights
			$admin_id = User::create($_POST['admin-user'], $_POST['admin-password'], $_POST['admin-email']);
			Rights_Admin::addUserGroups($admin_id, $group_admin);
			
			// Save INI
			$_CONFIG['installed'] = true;
			$config_ini->write($_CONFIG);
			
			echo '<div class="box success"><p>'.
				_('PHP-Gettext-Edit a été installé avec succès').
				' - <a href="../">'._('Continuer').'</a>'.
				'</p></div>';
			define('INSTALLED', true);
		}
	}
}
if (!defined('INSTALLED')) {
?><form method="POST" action="" class="formatted">
	<fieldset>
		<legend><?php echo _('Base de données'); ?></legend>
		<p><label><?php echo _('Type de base de données'); ?></label><select name="sql-type">
			<option value="pgsql"<?php 
			if (isset($_POST['sql-type']) && $_POST['sql-type'] == 'pgsql') {
				echo ' selected="selected"';
			} ?>>PostgreSQL</option>
			<option value="mysql"<?php 
			if (isset($_POST['sql-type']) && $_POST['sql-type'] == 'mysql') {
				echo ' selected="selected"';
			} ?>>MySQL</option>
		</select></p>
		<p><label><?php echo _('Adresse de votre serveur SQL'); ?></label>
			<input type="text" name="sql-host" value="<?php 
			if (isset($_POST['sql-host'])) {
				echo $_POST['sql-host'];
			} else {
				echo '127.0.0.1';
			} ?>" />
		</p>
		<p><label><?php echo _('Port de connexion'); ?></label>
			<input type="text" name="sql-port" value="<?php 
			if (isset($_POST['sql-port'])) {
				echo $_POST['sql-port'];
			} ?>" />
		</p>
		<p><label><?php echo _('Nom d\'utilisateur'); ?></label>
			<input type="text" name="sql-user" value="<?php 
			if (isset($_POST['sql-user'])) {
				echo $_POST['sql-user'];
			} ?>" />
		</p>
		<p><label><?php echo _('Mot de passe'); ?></label>
			<input type="password" name="sql-password" value="<?php 
			if (isset($_POST['sql-password'])) {
				echo $_POST['sql-password'];
			} ?>" />
		</p>
		<p><label><?php echo _('Nom de la base de données'); ?></label>
			<input type="text" name="sql-dbname" value="<?php 
			if (isset($_POST['sql-dbname'])) {
				echo $_POST['sql-dbname'];
			} ?>" />
		</p>
		<p><label><?php echo _('Préfix des tables'); ?></label>
			<input type="text" name="sql-prefix" value="<?php 
			if (isset($_POST['sql-prefix'])) {
				echo $_POST['sql-prefix'];
			} else {
				echo 'gte_';
			} ?>" />
		</p>
	</fieldset>
	
	<fieldset>
		<legend><?php echo _('Administrateur'); ?></legend>
		<p><label><?php echo _('Nom d\'utilisateur'); ?></label>
			<input type="text" name="admin-user" value="<?php 
			if (isset($_POST['admin-user'])) {
				echo $_POST['admin-user'];
			} ?>" />
		</p>
		<p><label><?php echo _('Mot de passe'); ?></label>
			<input type="password" name="admin-password" value="<?php 
			if (isset($_POST['admin-password'])) {
				echo $_POST['admin-password'];
			} ?>" />
		</p>
		<p><label><?php echo _('Adresse email'); ?></label>
			<input type="text" name="admin-email" value="<?php 
			if (isset($_POST['admin-email'])) {
				echo $_POST['admin-email'];
			} ?>" />
		</p>
	</fieldset>
	
	<p>
		<input type="hidden" name="install" value="yes" />
		<input type="submit" name="submit" value="<?php echo _('Installation !'); ?>" />
	</p>
</form>
<?php 
}
?>
</div>
</div>
<div id="footer"><a href="http://www.d-sites.com/projets/gettextedit/">GetTextEdit</a> &copy; 2009 <a href="http://www.d-sites.com">Samuel ROZE</a></div>
</body>
</html>