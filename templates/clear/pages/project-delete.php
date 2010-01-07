<?php
if (!isset($project)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="contents">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php echo _('Supprimer'); ?></h1>
		<?php 
		if (isset($_POST['sure'])) {
			try {
				$project->delete();
				
				echo '<div class="message success"><p>'.
					_('Projet supprimé').'</p>';
				echo '<p><form action="index.php" method="GET">'.
					'<input type="hidden" name="page" value="index" />'.
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
					echo _('Êtes-vous sûr de vouloir supprimer ce projet ?').' '.
						_('Important: Aucune donnée du projet ne sera supprimée');
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