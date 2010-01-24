<div id="contents" class="with_sidebar">
	<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php 
		echo _('Utilisateurs'); ?> &raquo; <?php echo _('Nouveau'); ?></h1>
	<p><strong><?php echo _('Utilisateur'); ?>:</strong><br />
	<select id="user" name="user"><?php 
	$users = GTE::getUsers();
	
	foreach ($users as $informations) {
		echo '<option value="'.$informations['id'].'">'.$informations['username'].'</option>';
	}
	?></select></p>
	<?php 
	require PAGE_DIR.'specifics/user-rights-editor.php';
	?>
</div>