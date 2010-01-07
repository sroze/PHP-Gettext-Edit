<?php
if (!isset($language_file)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="contents">
		<div class="link right">
			<a class="compile" href="index.php?page=language-file-compile&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>"><?php echo _('Compiler'); ?></a>
		</div>
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; 
			<a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo $language->getName(); ?></a> &raquo; 
			<a href="index.php?page=language-file&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>"><?php echo $language_file->getName(); ?></a> &raquo; 
			<?php echo _('Édition'); ?></h1>
		<?php
		require PAGE_DIR.'specifics/po-editor.php';
		?>
	</div>
</div>