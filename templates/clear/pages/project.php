<?php
if (!isset($project)) {
	echo 'Paramètres URL insuffisants';
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Statistiques'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><?php echo $project->get('project_name'); ?></h1>
		
		<div class="box little right">
		<div class="link right"><a class="add" href="index.php?page=template-new&project=<?php echo $project->get('project_id'); ?>"><?php echo _('Nouveau'); ?></a></div>
		<h3>Templates</h3>
		<?php 
		$templates = $project->getTemplates();
		if (!empty($templates)) {
			echo '<ul>';
			foreach ($templates as $template) {
				echo '<li><a href="index.php?page=template&project='.$project->get('project_id').'&template='.$template.'">'.$template.'</a></li>';
			}
			echo '</ul>';
		} else {
			echo '<p>Aucun template n\'est créé</p>';
		}
		?>
		</div>
		<div class="box little right">
		<div class="link right add"><a href="index.php?page=language-new&project=<?php echo $project->get('project_id'); ?>"><?php echo _('Nouveau'); ?></a></div>
		<h3>Langues</h3>
		<?php 
		$languages = $project->getLanguages();
		if (!empty($languages)) {
			echo '<ul>';
			foreach ($languages as $lang) {
				$language = new Project_Language($project, $lang);
				echo '<li><a href="index.php?page=language&project='.$project->get('project_id').'&language='.$language->getCode().'">'.$language->getName().'</li>';
			}
			echo '</ul>';
		} else {
			echo '<p>Aucune langue n\'est actuellement créée</p>';
		}
		?>
		</div>
		<div class="clear" />
	</div>
</div>