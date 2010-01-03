<?php
$project = new Project((int) $_GET['project']);
try {
	$project->get('project_name');
} catch (Exception $e) {
	echo '<div class="form_error">'.
		$e->getMessage();
		'</div>';
	exit();
}
?>
<div id="page">
	<div id="sidebar">
		<h3><?php echo _('Statistiques'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><?php echo $project->get('project_name'); ?></h1>
		
	</div>
</div>