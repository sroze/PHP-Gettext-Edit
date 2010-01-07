<?php
if (!isset($language)) {
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
			<a class="delete" href="index.php?page=language-delete&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo _('Supprimer'); ?></a>
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
				$language_file = new Project_Language_File($language, $file);
				$language_file_headers = $language_file->getHeaders();
				$template = $language_file->getTemplate();
				$template_headers = $template->getHeaders();
				
				$class = ' class="valid"';
				
				if (!array_key_exists('GetTextEdit-updated', $language_file_headers) OR
					(int) $template_headers['GetTextEdit-updated'] > (int) $language_file_headers['GetTextEdit-updated']) {
					$need_update = true;
					$class = ' class="invalid"';
				}
				if (!array_key_exists('GetTextEdit-compiled', $language_file_headers) OR
				(int) $language_file_headers['GetTextEdit-updated'] > (int) $language_file_headers['GetTextEdit-compiled']) {
					$need_compile = true;
					$class = ' class="invalid"';
				}
				
				echo '<li'.$class.'><a href="index.php?page=language-file&project='.$project->get('project_id').'&language='.$language->getCode().'&file='.$file.'">'.
					$file.
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
		<div class="clear" />
	</div>
</div>