<?php
if (!isset($language_file)) {
	echo 'Paramètres URL insuffisants';
	exit();
}
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
		<h3>Informations</h3>
		<p>À venir</p>
		</div>
		<ul>
			<li><a href="index.php?page=language-file-update&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>">
				Recharger depuis le template
			</a></li>
			<li><a href="index.php?page=language-file-editor&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>">
				Editer le contenu
			</a></li>
			<li><a href="index.php?page=language-file-compile&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>">
				Compiler
			</a></li>
		</ul>
		<div class="clear" />
	</div>
</div>