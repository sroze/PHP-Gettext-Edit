<?php
if (!isset($project)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="contents">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php echo _('Éditer'); ?></h1>
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
					$project->edit($_POST['name'], $_POST['path_app'], $_POST['path_lang']);
					
					echo '<div class="message success"><p>'.
						_('Projet édité').'</p>';
					echo '<p><form action="index.php" method="GET">'.
						'<input type="hidden" name="page" value="project" />'.
						'<input type="hidden" name="project" value="'.$project->get('project_id').'" />'.
						'<input type="submit" value="'._('Continuer').'" />'.
						'</form></p>';
					echo '</div>';
						
					unset($_POST);
				} catch (Exception $e) {
					echo '<div class="message error"><p>'.$e->getMessage().'</p></div>';
				}
			}
		} else {
			
		}
		?>
		<form method="POST" action=""><?php 
		require PAGE_DIR.'specifics/project-form.php';
		?>
			<input type="submit" value="<?php echo _('Créer'); ?>" />
		</form>
	</div>
</div>