<?php
if (!isset($template)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Regénérer le template'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=template&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>"><?php echo _('Modèle'); ?> <code><?php echo $template->getName(); ?></code></a> &raquo; <?php echo _('Regénérer'); ?></h1>
		<?php
		if (isset($_POST['action'])) {
			try {
				$template->update(
					isset($_POST['delete_old'])
				);
				
					
				echo '<div class="message success"><p>'.
					_('Modèle regénéré').'</p>';
				echo '<p><form action="index.php" method="GET">'.
					'<input type="hidden" name="page" value="template" />'.
					'<input type="hidden" name="project" value="'.$project->get('project_id').'" />'.
					'<input type="hidden" name="template" value="'.$template->getName().'" />'.
					'<input type="submit" value="'._('Retour').'" />'.
					'</form></p>';
				echo '</div>';
					
				unset($_POST);
			} catch (Exception $e) {
				echo '<div class="form_error">'.
					$e->getMessage().
					'</div>';
			}
		}
		?>
		<form method="POST" action="">
			<p><label><?php echo _('Supprimer les entrées inutilisées'); ?></label>
				<input type="checkbox" name="delete_old" value="yes"<?php
			if (isset($_POST['delete_old'])) { echo ' checked'; }
			?> />
			</p>
			<input type="hidden" name="action" value="update" />
			<input type="submit" value="<?php echo _('Regénérer'); ?>" />
		</form>
	</div>
</div>