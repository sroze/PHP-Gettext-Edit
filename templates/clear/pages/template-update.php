<?php
if (!isset($template)) {
	echo 'Paramètres URL insuffisants';
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Regénérer le template'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=template&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>">Template <code><?php echo $template->getName(); ?></code></a> &raquo; Regénérer</h1>
		<?php
		if (isset($_POST['action'])) {
			try {
				$template->update(
					isset($_POST['delete_old'])
				);
				
				echo '<div class="form_success">'.
					_('Template re-généré').
					'</div>';
					
				unset($_POST);
			} catch (Exception $e) {
				echo '<div class="form_error">'.
					$e->getMessage().
					'</div>';
			}
		}
		?>
		<form method="POST" action="">
			<p><label>Supprimer les entrées inutilisées</label>
				<input type="checkbox" name="delete_old" value="yes"<?php
			if (isset($_POST['delete_old'])) { echo ' checked'; }
			?> />
			</p>
			<input type="hidden" name="action" value="update" />
			<input type="submit" value="Regénérer" />
		</form>
	</div>
</div>