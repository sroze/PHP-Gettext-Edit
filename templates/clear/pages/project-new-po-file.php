<?php
if (!isset($project)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Création d\'un même fichier .po par langue'); ?></h3>
		<p><?php echo _('Au lieu de créer à la main un même fichier .po pour chaque langue, cette page vous permet de créer un fichier .po '.
		'viege issu d\'un même modèle pour toutes les langues.'); ?></p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a>  &raquo; <?php echo _('Nouveaux fichiers .po'); ?></h1>
		<?php 
		if (isset($_POST['name'])) {
			if (!preg_match('#^([a-z0-9_-]+)$#i', $_POST['name'])) {
				echo '<div class="message error">'.
					_('Le nom contient des caractères invalides').
					'</div>';
			} else if (empty($_POST['languages'])) {
				echo '<div class="message error">'.
					_('Aucune langue n\'est séléctionnée').
					'</div>';
			} else {
				try {
					$template = new Project_Template($project, $_POST['template']);
					$template->check();
				} catch (Exception $e) {
					echo '<div class="message error">'.
						$e->getMessage().
						'</div>';
				}
				
				$name = $_POST['name'];
				
				foreach ($_POST['languages'] as $language_name) {
					$language = new Project_Language($project, $language_name);
					
					try {
						$language_file = Project_Language_File::create($language, $name, $template);
						
						echo '<div class="box success"><p>'.
							_('Fichier .po créé').' - '.
							'<a href="index.php?page=language-file&project='.$project->get('project_id').'&language='.$language->getCode().'&file='.$language_file->getName().'">'.
							_('Continuer').
							'</a></p></div>';
						
						unset($language_file);
					} catch (Exception $e) {
						echo '<div class="message error">'.
							$e->getMessage().
							'</div>';
					}
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
						foreach ($project->getTemplates() as $template) {
							$template_name = $template->getName();
							
							$selected = (isset($_POST['template']) && $_POST['template'] == $template_name) ? ' selected="selected"' : '';
							echo '<option name="'.$template_name.'"'.$selected.'>'.$template_name.'</option>';
						}
						?>
					</select>
				</p>
				<p>
					<label>Langues concernées</label>
					<?php 
					foreach ($project->getLanguages() as $language) {
						$language_name = $language->getName();
						$language_code = $language->getCode();
						
						$checked = (isset($_POST['languages']) && in_array($language_code, $_POST['languages'])) ? ' checked' : '';
						echo '<label class="clean"><input type="checkbox" name="languages[]" value="'.$language_code.'"'.$checked.' /> '.$language_name.'</label><br />';
					}
					?>
				</p>
			</fieldset>
			<input type="submit" value="<?php echo _('Créer'); ?>" />
		</form>
	</div>
</div>