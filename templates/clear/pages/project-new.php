<?php 
if (!Rights::check('project_create')) {
	throw new GTE_Exception(
		_('Vous n\'avez pas les autorisations nécessaires')
	);
}
?>
<div id="page">
	<div id="sidebar">
		<h3><?php echo _('Créer un projet'); ?></h3>
		<p><?php echo _('Créez un nouveau projet afin de gérer les différentes traductions '.
			'de celui-ci dans PHP-Gettext-Edit '); ?></p>
		<?php
		require PAGE_DIR.'specifics/sidebar/project.php';
		?>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><?php echo _('Nouveau projet'); ?></h1>
		<?php
		if (isset($_POST['name'])) {
			if (!preg_match('#^([a-z0-9-]+)$#i', $_POST['name'])) {
				echo '<div class="message error">'.
					_('Le nom contient des caractères invalides').
					'</div>';
			} else if (empty($_POST['path_app'])) {
				echo '<div class="message error">'.
					_('Adresse d\'application invalide').
					'</div>';
			} else { 
				try {
					$project_id = Project::create($_POST['name'], $_POST['path_app'], $_POST['path_lang']);
					
					echo '<div class="message success"><p>'.
						_('Projet créé').'</p>';
					echo '<p><form action="index.php" method="GET">'.
						'<input type="hidden" name="page" value="project" />'.
						'<input type="hidden" name="project" value="'.$project_id.'" />'.
						'<input type="submit" value="'._('Continuer').'" />'.
						'</form></p>';
					echo '</div>';
					
					define('CREATED', true);
					unset($_POST);
				} catch (Exception $e) {
					echo '<div class="message error"><p>'.$e->getMessage().'</p></div>';
				}
			}
		}
		
		if (!defined('CREATED')) {
		?>
		<form method="POST" action="" class="formatted"><?php 
		require PAGE_DIR.'specifics/project-form.php';
		?>
			<input type="submit" value="<?php echo _('Créer'); ?>" />
		</form>
		<?php 
		}
		?>
	</div>
</div>