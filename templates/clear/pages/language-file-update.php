<?php
if (!isset($language_file)) {
	echo 'Paramètres URL insuffisants';
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
			Mettre à niveau</h1>
		<?php 
		if (isset($_POST['action'])) {
			try {
				$language_file->update();
				
				echo '<div class="form_success">'.
					_('Fichier mis à niveau').' - <a href="index.php?page=language-file&project='.$project->get('project_id').'&language='.$language->getCode().'&file='.$language_file->getName().'">'._('Retour').'</a>'.
					'</div>';
			} catch (Exception $e) {
				echo '<div class="form_error">'.
					$e->getMessage().
					'</div>';
			}
		}
		?>
		<form action="" method="POST">
			<p><label>Template</label><?php
			echo $language_file->getTemplateName();
			?></p>
			<input type="hidden" name="action" value="update" />
			<p><input type="submit" value="Mettre à niveau" /></p>
		</form>
	</div>
</div>