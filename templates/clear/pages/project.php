<?php
if (!isset($project)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Statistiques'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<div class="link right">
			<a class="delete" href="index.php?page=project-delete&project=<?php echo $project->get('project_id'); ?>"><?php echo _('Supprimer'); ?></a>
			<a class="edit" href="index.php?page=project-edit&project=<?php echo $project->get('project_id'); ?>"><?php echo _('Editer'); ?></a>
		</div>
		<h1><?php echo $project->get('project_name'); ?></h1>
		
		<div class="box little right">
		<div class="link right"><a class="add" href="index.php?page=template-new&project=<?php echo $project->get('project_id'); ?>"><?php echo _('Nouveau'); ?></a></div>
		<h3><?php echo _('Modèles'); ?></h3>
		<?php 
		$templates = $project->getTemplates();
		if (!empty($templates)) {
			echo '<ul>';
			foreach ($templates as $template) {
				echo '<li><a href="index.php?page=template&project='.$project->get('project_id').'&template='.$template.'">'.$template.'</a></li>';
			}
			echo '</ul>';
		} else {
			echo '<p>'._('Aucun template n\'est créé').'</p>';
		}
		?>
		</div>
		<div class="box little right">
		<div class="link right"><a class="add" href="index.php?page=language-new&project=<?php echo $project->get('project_id'); ?>"><?php echo _('Nouveau'); ?></a></div>
		<h3><?php echo _('Langues'); ?></h3>
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
			echo '<p>'._('Aucune langue n\'est actuellement créée').'</p>';
		}
		?>
		</div>
		<div class="clear" />
	</div>
</div>