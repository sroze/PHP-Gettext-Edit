<?php
if (!isset($language)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Création d\'un fichier .po'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo $language->getName(); ?></a> &raquo; <?php echo _('Nouveau fichier .po'); ?></h1>
		<?php 
		if (isset($_POST['name'])) {
			if (!preg_match('#^([a-z0-9_-]+)$#i', $_POST['name'])) {
				echo '<div class="message error">'.
					_('Le nom contient des caractères invalides').
					'</div>';
			} else {
				try {
					$template = new Project_Template($project, $_POST['template']);
					$template->check();
					
					$language_file = Project_Language_File::create($language, $_POST['name'], $template);
					
					echo '<div class="message success"><p>'.
						_('Fichier .po créé').'</p>';
					echo '<p><form action="index.php" method="GET">'.
						'<input type="hidden" name="page" value="language-file" />'.
						'<input type="hidden" name="project" value="'.$project->get('project_id').'" />'.
						'<input type="hidden" name="language" value="'.$language->getCode().'" />'.
						'<input type="hidden" name="file" value="'.$language_file->getName().'" />'.
						'<input type="submit" value="'._('Continuer').'" />'.
						'</form></p>';
					echo '</div>';
					unset($_POST);
				} catch (Exception $e) {
					echo '<div class="message error">'.
						$e->getMessage().
						'</div>';
				}
			}
		}
		?>
		<form method="POST" action="" class="formatted">
			<fieldset>
				<legend><?php echo _('Général'); ?></legend>
				<p><label><?php echo _('Nom'); ?></label><input type="text" size="30" name="name" value="" /></p>
				<p><label><?php echo _('À partir du modèle'); ?></label>
					<select name="template">
						<?php 
						foreach ($project->getTemplates() as $template_name) {
							$selected = (isset($_POST['template']) && $_POST['template'] == $template_name) ? ' selected="selected"' : '';
							echo '<option name="'.$template_name.'"'.$selected.'>'.$template_name.'</option>';
						}
						?>
					</select>
				</p>
			</fieldset>
			<input type="submit" value="<?php echo _('Créer'); ?>" />
		</form>
	</div>
</div>