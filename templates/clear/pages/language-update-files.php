<?php
if (!isset($language)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Compiler tous les fichiers'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<div class="link right">
			<a class="delete" href="index.php?page=language-delete&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo _('Supprimer'); ?></a>
		</div>
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo $language->getName(); ?></a> &raquo; 
		<?php echo _('Compiler tous les fichiers'); ?></h1>
		<?php 
		if (isset($_POST['files'])) {
			foreach ($_POST['files'] as $file) {
				try {
					$language_file = new Project_Language_File($language, $file);
					$output_file_path = $language_file->update();
						
					echo '<div class="box success">'.
						'<p>'.sprintf(_('Fichier mis à jour: <strong>%s</strong>'), $output_file_path).' - <a href="index.php?page=language-file&project='.$project->get('project_id').'&language='.$language->getCode().'&file='.$language_file->getName().'">'._('Continuer').'</a></p>'.
						'</div>';
				} catch (Exception $e) {
					echo '<div class="box error">'.
						'<p>'.$e->getMessage().'</p>'.
						'</div>';
				}
			}
		}
		?>
		<div class="box">
			<p><?php echo _('Sélectionnez les fichiers que vous souhaitez mettre à jour:'); ?></p>
			<?php 
			$files = $language->getFiles();
			if (!empty($files)) {
			?><form action="" method="POST">
				<ul>
					<?php
					foreach ($files as $file) {
						$file_warnings = $file->getWarnings();
						
						echo '<li class="'.(!in_array(Project_Language_File::W_UPDATE, $file_warnings) ? 'valid' : 'invalid').'"><label><input type="checkbox" name="files['.$file->getName().']" value="yes"'.
							(isset($_POST['files'], $_POST['files'][$file->getName()]) ?
								' checked' : '')
							.' /> '.$file->getName().' depuis <strong>'.$file->getTemplateName().'</strong></label></li>';
					}
					?>
				</ul>
				<p><input type="submit" name="compile" value="<?php echo _('Mettre à jour'); ?>" /></p>
			</form>
			<?php
			} else {
				echo '<ul><li>'._('Aucun fichier').'</li></ul>';
			}
			?>
		</div>
	</div>
</div>