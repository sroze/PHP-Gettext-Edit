<?php
if (!isset($template)) {
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
			<a class="delete" href="index.php?page=template-delete&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>"><?php echo _('Supprimer'); ?></a>
			<a class="edit" href="index.php?page=template-edit&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>"><?php echo _('Editer'); ?></a>
		</div>
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php echo _('Modèle'); ?> <code><?php echo $template->getName(); ?></code></h1>
		<ul>
			<li><a href="index.php?page=template-update&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>">
				<?php echo _('Regénérer le template'); ?>
			</a></li>
		</ul>
	</div>
</div>