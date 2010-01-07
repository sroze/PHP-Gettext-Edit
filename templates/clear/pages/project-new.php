<div id="page">
	<div id="contents">
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
						
					unset($_POST);
				} catch (Exception $e) {
					echo '<div class="message error"><p>'.$e->getMessage().'</p></div>';
				}
			}
		}
		?>
		<form method="POST" action="" class="formatted"><?php 
		require PAGE_DIR.'specifics/project-form.php';
		?>
			<input type="submit" value="<?php echo _('Créer'); ?>" />
		</form>
	</div>
</div>