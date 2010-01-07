<?php
if (!isset($project)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Création du nouveau projet'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php echo _('Nouvelle langue'); ?></h1>
		<?php
		if (isset($_POST['code'])) {
			if (!preg_match('#^([a-z0-9_-]+)$#i', $_POST['code'])) {
				echo '<div class="message error">'.
					_('Le code contient des caractères invalides').
					'</div>';
			} else {
				try {
					$language = Project_Language::create($project, $_POST['code']);
					
					if (!empty($_POST['templates'])) {
						foreach ($_POST['templates'] as $template_name) {
							$template = new Project_Template($project, $template_name);
							Project_Language_File::create($language, $template_name, $template);
						}
					}
					
					echo '<div class="message success"><p>'.
						_('Langue créée').'</p>';
					echo '<p><form action="index.php" method="GET">'.
						'<input type="hidden" name="page" value="language" />'.
						'<input type="hidden" name="project" value="'.$project->get('project_id').'" />'.
						'<input type="hidden" name="language" value="'.$language->getCode().'" />'.
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
			<p><label><?php echo _('Code'); ?></label><input type="text" size="30" name="code"<?php
			if (!empty($_POST['code'])) { echo ' value="'.$_POST['code'].'"'; }
			?> /><br />
				<em><?php echo _('Séquence de deux lettres, puis un underscore (_), puis à nouveau deux lettres.');
				echo _('Exemple:');
				echo ' en_US ('._('Anglais').')'; ?></em>
			</p>
			<?php
			$templates = $project->getTemplates();
			if (!empty($templates)) {
			?><p>
				<label><?php echo _('Créer les .po définis par les templates'); ?></label>
				<?php
				foreach ($templates as $template) {
					$template_name = $template->getName();
					
					$checked = (isset($_POST['templates'], $_POST['templates'][$template_name])) ? ' checked="checked"' : '';
					echo '<input type="checkbox" name="templates[]" value="'.$template_name.'"'.$checked.' /> '.$template_name.'<br />';
				}
				?>
			</p><?php
			}
			?><input type="submit" value="<?php echo _('Créer'); ?>" />
		</form>
	</div>
</div>