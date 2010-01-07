<?php
if (!isset($language_file)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="contents">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; 
			<a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo $language->getName(); ?></a> &raquo; 
			<a href="index.php?page=language-file&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>"><?php echo $language_file->getName(); ?></a> &raquo; 
			<?php echo _('Supprimer'); ?></h1>
		<?php 
		if (isset($_POST['sure'])) {
			try {
				$language_file->delete();
				
				echo '<div class="message success"><p>'.
					_('Fichier supprimé').'</p>';
				echo '<p><form action="index.php" method="GET">'.
					'<input type="hidden" name="page" value="language" />'.
					'<input type="hidden" name="project" value="'.$project->get('project_id').'" />'.
					'<input type="hidden" name="language" value="'.$language->getCode().'" />'.
					'<input type="submit" value="'._('Retour').'" />'.
					'</form></p>';
				echo '</div>';
			} catch (Exception $e) {
				echo '<div class="message error"><p>'.
					$e->getMessage().
					'</p></div>';
			}
		} else {
		?>
		<div class="message error">
			<p align="center">
				<?php
					echo _('Êtes-vous sûr de vouloir supprimer ce fichier ?').' '.
						_('Cette opération est irréverssible');
				?>
			</p>
			<form action="" method="POST">
				<p align="center">
					<label><input type="checkbox" name="sure" value="yes" /> <?php echo _('Oui'); ?></label>
					<label><input type="submit" value="<?php echo _('Supprimer'); ?>" /></label>
				</p>
			</form>
		</div>
		<?php 
		}
		?>
	</div>
</div>