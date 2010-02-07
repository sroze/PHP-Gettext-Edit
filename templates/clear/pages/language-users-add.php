<div id="contents">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo $language->getName(); ?></a> &raquo; 
		<?php echo _('Utilisateurs'); ?> &raquo; <?php echo _('Nouveau'); ?></h1>
	<p><?php echo _('Vous pouvez ajouter des autorisations à un utilisateur grâce au formulaire ci-dessous.'); ?></p>
	<p><strong><?php echo _('Utilisateur concerné:'); ?></strong><br />
	<select id="user_field" name="user"><?php 
	$users = GTE::getUsers();
	
	foreach ($users as $informations) {
		echo '<option value="'.$informations['id'].'">'.$informations['username'].'</option>';
	}
	?></select></p>
	<?php
	require PAGE_DIR.'specifics/rights/language.php';
	require PAGE_DIR.'specifics/user-rights-editor.php';
	?>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('select#user_field').change(function(){
		reloadInformations($(this).val());
	});
});
</script>