<?php 
if (CONNECTED) {
	echo _('Vous êtes déjà connecté');
	exit();
}
?>
<div id="page">
	<div id="sidebar">
		<h3><?php echo _('Connexion'); ?></h3>
		<p><?php echo _('Si vous avez un compte PHP-GetText-Edit sur cette installation, connectez-vous '.
		'pour pouvoir manipuler les traductions et gérer les projets.'); ?></p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><?php echo _('Connexion'); ?></h1>
		<?php
		if (isset($_POST['name'])) {
			if (!String::is_username($_POST['username'])) {
				echo '<div class="box error"><p>'.
					_('Le nom d\'utilisateur est invalide').
					'</p></div>';
			} else if (!String::is_password($_POST['password'])) {
				echo '<div class="box error"><p>'.
					_('Le mot de passe est invalide').
					'</p></div>';
			} else { 
				try {
					User::connect($_POST['username'], String::crypt($_POST['password']), isset($_POST['cookie']));
					
					echo '<div class="box success"><p>'.
						_('Connexion éffectuée').
						' - <a href="index.php">'.
						_('Continuer').
						'</a></p><div>';
				} catch (Exception $e) {
					echo '<div class="box error"><p>'.$e->getMessage().'</p></div>';
				}
			}
		}
		
		if (!defined('CONNECTED')) {
		?>
		<form method="POST" action="" class="formatted">
			<p><label><?php echo _('Nom d\'utilisateur'); ?></label>
				<input type="text" name="username" value="" />
			</p>
			<p><label><?php echo _('Mot de passe'); ?></label>
				<input type="password" name="password" value="" />
			</p>
			<p><label><?php echo _('Rester connecté'); ?></label>
				<input type="checkbox" name="cookie" value="yes" />
			</p>
			<p><input type="submit" value="<?php echo _('Connexion'); ?>" /></p>
		</form>
		<?php 
		}
		?>
	</div>
</div>