<?php
if (!isset($language)) {
	echo 'Paramètres URL insuffisants';
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Création d\'un fichier .po'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo $language->getName(); ?></a> &raquo; Nouveau fichier .po</h1>
		<?php 
		if (isset($_POST['name'])) {
			if (!preg_match('#^([a-z0-9_-]+)$#i', $_POST['name'])) {
				echo '<div class="form_error">'.
					_('Le nom contient des caractères invalides').
					'</div>';
			} else {
				try {
					$template = new Project_Template($project, $_POST['template']);
					$template->check();
					
					$language_file = $language->createFile($_POST['name'], $template);
					
					echo '<div class="form_success">'.
						_('Fichier .po créé').
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
			<fieldset>
				<legend>Général</legend>
				<p><label>Nom</label><input type="text" size="30" name="name" value="" /></p>
				<p><label>À partir du template</label>
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
			<input type="submit" value="Créer" />
		</form>
	</div>
</div>