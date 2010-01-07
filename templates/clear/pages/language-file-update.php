<?php
if (!isset($language_file)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Mise à niveau'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; 
			<a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo $language->getName(); ?></a> &raquo; 
			<a href="index.php?page=language-file&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>"><?php echo $language_file->getName(); ?></a> &raquo; 
			<?php echo _('Mettre à niveau'); ?></h1>
		<?php 
		if (isset($_POST['action'])) {
			try {
				$language_file->update();
				
				echo '<div class="message success"><p>'.
					_('Fichier mis à niveau').'</p>';
				echo '<p><form action="index.php" method="GET">'.
					'<input type="hidden" name="page" value="language-file" />'.
					'<input type="hidden" name="project" value="'.$project->get('project_id').'" />'.
					'<input type="hidden" name="language" value="'.$language->getCode().'" />'.
					'<input type="hidden" name="file" value="'.$language_file->getName().'" />'.
					'<input type="submit" value="'._('Retour').'" />'.
					'</form></p>';
				echo '</div>';
			} catch (Exception $e) {
				echo '<div class="message error">'.
					$e->getMessage().
					'</div>';
			}
		}
		?>
		<form action="" method="POST">
			<p><label><?php echo _('Modèle'); ?></label><?php
			echo $language_file->getTemplateName();
			?></p>
			<input type="hidden" name="action" value="update" />
			<p><input type="submit" value="<?php echo _('Mettre à niveau'); ?>" /></p>
		</form>
	</div>
</div>