<?php
if (!isset($project)) {
	echo _('Paramètres URL insuffisants');
	exit();
} else if (!Rights::check('project_access', array(
		'project' => (int)$_PROJECT->get('project_id')
	))) {
	throw new GTE_Exception(
		_('Vous n\'avez pas les autorisations nécessaires')
	);
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Traductions du projet'); ?></h3>
		<p><?php echo _('Grâce aux modèles que vous aurez préparez, vous pourrez des créer des fichiers de traduction pour chaque langue '.
		'de votre application.'), ' ', _('Ainsi, il vous suffira de traduire les phrases grâce à l\'éditeur de PHP-GetText-Edit et de '.
		'compiler pour voir votre application se doter d\'une nouvelle langue!'); ?></p>
		<p><?php echo _('<strong>Note:</strong> Le necessaire doit être fait au niveau de votre application.'); ?></p>
	</div>
	<div id="contents" class="with_sidebar">
		<div class="link right">
		<?php 
		if (Rights::check('project_users')) {
			echo '<a class="group" href="index.php?page=project-users&project='.
			$project->get('project_id').'">'._('Droits et utilisateurs').'</a>'.
			'<a class="separator"></a>';
		}
		if (Rights::check('project_delete')) {
			echo '<a class="delete" href="index.php?page=project-delete&project='.
			$project->get('project_id').'">'._('Supprimer').'</a>';
		}
		if (Rights::check('project_edit')) {
			echo '<a class="edit" href="index.php?page=project-edit&project='.
			$project->get('project_id').'">'._('Editer').'</a>';
		}
		?>
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
				$template_name = $template->getName();
				$last_edited_files = $template->getEditedFiles();
				
				if (!empty($last_edited_files)) {
					$class = 'invalid';
				} else {
					$class = 'valid';
				}
				
				echo '<li class="'.$class.'"><a href="index.php?page=template&project='.$project->get('project_id').'&template='.$template_name.'">'.$template_name.'</a></li>';
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
		$languages_files = array();
		if (!empty($languages)) {
			echo '<ul>';
			foreach ($languages as $language) {
				$languages_files[$language->getName()] = array();
				foreach ($language->getFiles() as $language_file) {
					$last_bracket = strrpos(
						$language_file->file_path, 
						'/', 
						-1*(strlen($language_file->file_path)-strrpos($language_file->file_path, '/')+1)
					);
					$languages_files[$language->getName()][] = substr($language_file->file_path, $last_bracket);
				}
				
				$language_warnings = $language->getWarnings();
				if (!empty($language_warnings)) {
					$class = 'invalid';
				} else {
					$class = 'valid';
				}
				echo '<li class="'.$class.'"><a href="index.php?page=language&project='.$project->get('project_id').'&language='.$language->getCode().'">'.$language->getName().'</a></li>';
			}
			echo '</ul>';
		} else {
			echo '<p>'._('Aucune langue n\'est actuellement créée').'</p>';
		}
		?>
		</div>
		<?php
		// Check if each languages have the same .po files
		foreach ($languages_files as $language_name => $files) {
			foreach ($files as $file) {
				foreach ($languages_files as $other_language_name => $other_language_files) {
					if (!in_array($file, $other_language_files)) {
						echo '<div class="box error"><p>'.
							sprintf(
								_('La langue <strong>%s</strong> n\'a pas le fichier <strong>%s</strong>'),
								$other_language_name,
								$file
							).
							'</p></div>';
					}
				}
			}
			//array_shift($languages_files);
		}
		?>
		<ul>
		<?php
		echo '<li><a href="index.php?page=project-new-po-file&project='.
			$project->get('project_id').'">'._('Créer un même fichier .po par langue').'</a></li>';
		?>
		</ul>
		
		<div class="clear"></div>
	</div>
</div>