<?php
if (!isset($language_file)) {
	echo _('Paramètres URL insuffisants');
	exit();
}

$warnings = $language_file->getWarnings();
$file_headers = $language_file->getHeaders();
$template = $language_file->getTemplate();

?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Statistiques'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<div class="link right">
			<a class="delete" href="index.php?page=language-file-delete&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>"><?php echo _('Supprimer'); ?></a>
		</div>
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo $language->getName(); ?></a> &raquo; <?php echo $language_file->getName(); ?></h1>
		<div class="box little right">
		<h3><?php echo _('Informations'); ?></h3>
		<ul>
			<li><?php echo sprintf(_('Issu du template <strong>%s</strong>'), $template->getName()); ?></li>
			<?php
			if (array_key_exists('GetTextEdit-edited', $file_headers)) {
				echo '<li>'._('Dernière édition le').' '.date('d/m/Y H:i:s', (int) $file_headers['GetTextEdit-edited']).'</li>';
			}
			if (array_key_exists('GetTextEdit-updated', $file_headers)) {
				echo '<li>'._('Mis à jour le').' '.date('d/m/Y H:i:s', (int) $file_headers['GetTextEdit-updated']).'</li>';
			}
			if (array_key_exists('GetTextEdit-compiled', $file_headers)) {
				echo '<li>'._('Compilé le').' '.date('d/m/Y H:i:s', (int) $file_headers['GetTextEdit-compiled']).'</li>';
			}
			?>
		</ul>
		</div>
		<ul><li<?php			
			if (in_array(Project_Language_File::W_UPDATE, $warnings)) {
				echo ' class="important"';
			} else {
				echo ' class="inutile"';
			}
			?>><a href="index.php?page=language-file-update&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>">
				<?php echo _('Recharger depuis le template'); ?>
			</a></li>
			<?php 
			if (in_array(Project_Language_File::W_COMPILE_JSON, $warnings)) {
				$class_compile_json = ' class="important"';
			} else if (array_key_exists('GetTextEdit-compileJSON', $file_headers)) {
				$class_compile_json = ' class="inutile"';
			} else {
				$class_compile_json = '';
			}
			
			if (in_array(Project_Language_File::W_COMPILE, $warnings)) {
				$class_compile = ' class="important"';
			} else if ($class_compile_json != ' class="inutile"') {
				$class_compile = ' class="inutile"';
			} else {
				$class_compile = '';
			}
			?>
			<li<?php echo $class_compile; ?>><a href="index.php?page=language-file-compile&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>">
				<?php echo _('Compiler'); ?>
			</a></li>
			<li<?php echo $class_compile_json; ?>><a href="index.php?page=language-file-compile-json&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>">
				<?php echo _('Compiler en JSON'); ?>
			</a></li>
			<li class="spacer"></li>
			<li><a href="index.php?page=language-file-editor&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>">
				<?php echo _('Editer le contenu'); ?>
			</a></li>
		</ul>
		<h2><?php echo _('Compilations'); ?></h2>
		<p><?php echo _('Voici le ou les fichiers compilés issus de ce fichier:'); ?></p>
		<ul>
		<?php
		$compiled_files = $language_file->getCompiledFiles();
		
		if (empty($compiled_files)) {
			echo '<li>'._('Aucun fichier compilé').'</li>';
		} else {
			foreach ($compiled_files as $file_path) {
				echo '<li><strong>'.$file_path.'</strong> <code>'.date('d/m/Y H:i:s', filemtime($file_path)).'</code></li>';
			}
		}
		?>
		</ul>
		<div class="clear"></div>
	</div>
</div>