<?php
if (!isset($project)) {
	echo 'Paramètres URL insuffisants';
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Création du nouveau projet'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; Nouvelle langue</h1>
		<?php
		if (isset($_POST['code'])) {
			if (!preg_match('#^([a-z0-9_-]+)$#i', $_POST['code'])) {
				echo '<div class="form_error">'.
					_('Le code contient des caractères invalides').
					'</div>';
			} else {
				try {
					$language = Project_Language::create($project, $_POST['code']);
					
					if (!empty($_POST['templates'])) {
						foreach ($_POST['templates'] as $template) {
							$language->createFile($template, $template);
						}
					}
					
					echo '<div class="form_success">'.
						_('Langue créé').
						'</div>'; 
					unset($_POST);
				} catch (Exception $e) {
					echo '<div class="form_error">'.
						$e->getMessage().
						'</div>';
				}
			}
		}
		?>
		<form method="POST" action="">
			<p><label>Code</label><input type="text" size="30" name="code"<?php
			if (!empty($_POST['code'])) { echo ' value="'.$_POST['code'].'"'; }
			?> /><br />
				<em>Séquence de deux lettres, puis un underscore (_), puis à nouveau deux lettres. Exemple: en_US (Anglais)</em>
			</p>
			<?php
			$templates = $project->getTemplates();
			if (!empty($templates)) {
			?><p>
				<label>Créer les .po définis par les templates</label>
				<?php
				foreach ($templates as $template) {
					$checked = (isset($_POST['templates'], $_POST['templates'][$template])) ? ' checked="checked"' : '';
					echo '<input type="checkbox" name="templates[]" value="'.$template.'"'.$checked.' /> '.$template.'<br />';
				}
				?>
			</p><?php
			}
			?><input type="submit" value="Créer" />
		</form>
	</div>
</div>