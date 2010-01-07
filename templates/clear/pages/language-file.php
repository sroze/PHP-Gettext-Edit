<?php
if (!isset($language_file)) {
	echo _('Paramètres URL insuffisants');
	exit();
}

$template = $language_file->getTemplate();
$template_headers = $template->getHeaders();
$file_headers = $language_file->getHeaders();

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
			if (!array_key_exists('GetTextEdit-updated', $file_headers) OR
				(int) $template_headers['GetTextEdit-updated'] > (int) $file_headers['GetTextEdit-updated']) {
				echo ' class="important"';
			} else if ((int) $template_headers['GetTextEdit-updated'] <= (int) $file_headers['GetTextEdit-updated']) {
				echo ' class="inutile"';
			}
			?>><a href="index.php?page=language-file-update&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>">
				<?php echo _('Recharger depuis le template'); ?>
			</a></li>
			<li<?php 
			if (!array_key_exists('GetTextEdit-compiled', $file_headers) OR
				(int) $file_headers['GetTextEdit-updated'] > (int) $file_headers['GetTextEdit-compiled']) {
				echo ' class="important"';
			} else if ((int) $file_headers['GetTextEdit-updated'] <= (int) $file_headers['GetTextEdit-compiled']) {
				echo ' class="inutile"';
			}
			?>><a href="index.php?page=language-file-compile&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>">
				<?php echo _('Compiler'); ?>
			</a></li>
			<li class="spacer"></li>
			<li><a href="index.php?page=language-file-editor&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>">
				<?php echo _('Editer le contenu'); ?>
			</a></li>
		</ul>
		<div class="clear"></div>
	</div>
</div>