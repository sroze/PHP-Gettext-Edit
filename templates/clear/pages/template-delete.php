<?php
if (!isset($template)) {
	echo 'Paramètres URL insuffisants';
	exit();
}
?><div id="page">
	<div id="contents">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=template&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>">Template <code><?php echo $template->getName(); ?></code></a> &raquo; Supprimer</h1>
		<?php 
		if (isset($_POST['sure'])) {
			try {
				$template->delete();
				
				echo '<div class="box large success"><p>'.
					_('Template supprimée').'</p><p><a href="index.php?page=project&project='.$project->get('project_id').'">'._('Retour').'</a>'.
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
					echo _('Êtes-vous sûr de vouloir supprimer ce template ?').' '.
						_('Cette opération supprimera toutes les données associées de manière irréverssible');
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