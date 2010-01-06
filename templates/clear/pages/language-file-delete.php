<?php
if (!isset($language_file)) {
	echo 'Paramètres URL insuffisants';
	exit();
}
?><div id="page">
	<div id="contents">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; 
			<a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo $language->getName(); ?></a> &raquo; 
			<a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>"><?php echo $language_file->getName(); ?></a> &raquo; 
			Supprimer</h1>
		<?php 
		if (isset($_POST['sure'])) {
			try {
				$language_file->delete();
				
				echo '<div class="box large success"><p>'.
					_('Fichier supprimé').'</p><p><a href="index.php?page=project&project='.$project->get('project_id').'&language='.$language->getCode().'">'._('Retour').'</a>'.
					'</p></div>';
			} catch (Exception $e) {
				echo '<div class="box large error"><p>'.
					$e->getMessage().
					'</p></div>';
			}
		} else {
		?>
		<div class="box large warning">
			<p align="center">
				<?php
					echo _('Êtes-vous sûr de vouloir supprimer ce fichier ?').' '.
						_('Cette opération est irréverssible');
				?>
			<p align="center">
				<form action="" method="POST">
					<label><input type="checkbox" name="sure" value="yes" /> Oui</label><br />
					<label><input type="submit" value="Supprimer" /></label>
				</form>
			</p>
		</div>
		<?php 
		}
		?>
	</div>
</div>