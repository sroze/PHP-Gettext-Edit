<?php
if (!isset($language)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Une langue'); ?></h3>
		<p><?php echo _('Dans PHP-GetText-Edit, une langue peut contenir plusieurs fichiers de traductions.')?></p>
	</div>
	<div id="contents" class="with_sidebar">
		<div class="link right">
			<a class="delete" href="index.php?page=language-delete&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo _('Supprimer'); ?></a>
			<a class="separator"></a>
			<a class="group" href="index.php?page=language-users&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo _('Droits et utilisateurs'); ?></a>
		</div>
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php echo $language->getName(); ?></h1>
		<div class="box little right">
		<h3><?php echo _('Fichiers .po'); ?></h3>
		<?php 
		$files = $language->getFiles();
		$need_compile = false;
		$need_update = false;
		
		if (!empty($files)) {
			echo '<ul>';
			foreach ($files as $file) {
				$file_warnings = $file->getWarnings();
				
				if (!empty($file_warnings)) {
					if (!$need_compile && in_array(Project_Language_File::W_COMPILE, $file_warnings)) {
						$need_compile = true;
					}
					if (!$need_update && in_array(Project_Language_File::W_UPDATE, $file_warnings)) {
						$need_update = true;
					}
					$class = 'invalid';
				} else {
					$class = 'valid';
				}
				
				echo '<li class="'.$class.'"><a href="index.php?page=language-file&project='.$project->get('project_id').'&language='.$language->getCode().'&file='.$file->getName().'">'.
					$file->getName().
					'.po</a></li>';
			}
			echo '</ul>';
		} else {
			echo '<p>'._('Aucun fichier .po n\'est créé').'</p>';
		}
		?>
		</div>
		<ul>
			<li<?php 
			if ($need_compile) {
				echo ' class="important"';
			} else {
				echo ' class="inutile"';
			}
			?>><a href="index.php?page=language-compile-files&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>">
				<?php echo _('Compiler tous les fichiers'); ?>
			</a></li>
			<li<?php 
			if ($need_update) {
				echo ' class="important"';
			} else {
				echo ' class="inutile"';
			}
			?>><a href="index.php?page=language-update-files&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>">
				<?php echo _('Mettre à jour les fichiers depuis leur template'); ?>
			</a></li>
			<li class="spacer"></li>
			<li><a href="index.php?page=language-new-po-file&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>">
				<?php echo _('Créer un nouveau fichier .po'); ?>
			</a></li>
		</ul>
		<div class="clear"></div>
	</div>
</div>