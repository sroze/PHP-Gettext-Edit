<div id="page">
	<div id="sidebar">
		<h3><?php echo _('Statistiques'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><?php echo $project->get('project_name'); ?></h1>
		
		<div class="box little"><h3>Templates</h3>
		<div class="right_link"><a href="index.php?page=new-template&project=<?php echo $project->get('project_id'); ?>"><?php echo _('Créer un nouveau template'); ?></a></div>
		<?php 
		$templates = $project->getTemplates();
		if (empty($templates)) {
			echo '<ul>';
			foreach ($templates as $template) {
				echo '<li>'.$template.'</li>';
			}
			echo '</ul>';
		} else {
			echo '<p>Aucun template n\'est créé</p>';
		}
		?>
		</div>
		<div class="box little"><h3>Langues</h3>
		<div class="right_link"><a href="index.php?page=new-language&project=<?php echo $project->get('project_id'); ?>"><?php echo _('Créer un nouvelle langue'); ?></a></div>
		<?php 
		$languages = $project->getLanguages();
		if (empty($languages)) {
			echo '<ul>';
			foreach ($languages as $lang) {
				echo '<li>'.$lang.'</li>';
			}
			echo '</ul>';
		} else {
			echo '<p>Aucune langue n\'est actuellement créée</p>';
		}
		?>
		</div>
	</div>
</div>