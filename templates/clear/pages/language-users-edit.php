<div id="contents">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo $language->getName(); ?></a> &raquo; 
		<?php
		echo _('Utilisateurs'); ?> &raquo; <?php
		$user = (int) isset($_POST['user']) ? $_POST['user'] : $_GET['user'];
		$informations = GTE::getUserInformations($user);
		
		echo $informations['username'];
		?></h1>
	<input type="hidden" name="user" id="user_field" value="<?php echo $user; ?>" />
	<?php
	require PAGE_DIR.'specifics/rights/language.php';
	require PAGE_DIR.'specifics/user-rights-editor.php';
	?>
</div>