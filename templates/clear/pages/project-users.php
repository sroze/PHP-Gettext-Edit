<?php
if (!isset($project)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<?php 
		include PAGE_DIR.'specifics/sidebar/users.php';
		?>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php 
		echo _('Utilisateurs'); ?></h1>
		<?php
		require PAGE_DIR.'specifics/rights/project.php';
		require PAGE_DIR.'specifics/users-editor.php';
		?>
	</div>
</div>