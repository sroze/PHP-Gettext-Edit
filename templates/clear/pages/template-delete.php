<?php
if (!isset($template)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="contents">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=template&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>"><?php echo _('Modèle'); ?> <code><?php echo $template->getName(); ?></code></a> &raquo; Supprimer</h1>
		<?php 
		if (isset($_POST['sure'])) {
			try {
				$template->delete();
				
				echo '<div class="message success"><p>'.
					_('Modèle supprimé').'</p>';
				echo '<p><form action="index.php" method="GET">'.
					'<input type="hidden" name="page" value="project" />'.
					'<input type="hidden" name="project" value="'.$project->get('project_id').'" />'.
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
					echo _('Êtes-vous sûr de vouloir supprimer ce template ?').' '.
						_('Cette opération supprimera toutes les données associées de manière irréverssible');
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