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
		if (isset($_POST['action'])) {
			if ($_POST['action'] == 'update-user') {
				$user = (int) $_POST['user'];
				if (empty($user)) {
					echo '<div class="box error"><p>'.
						_('L\'ID utilisateur est invalide').
						'</p></div>';
				} else if (is_array($_POST['rights'])) {
					foreach ($_POST['rights'] as $right => $value) {
						if ($value == 'yes') {
							Rights_Admin::grantUserRights(
								$user,
								$right,
								$_CONTEXT
							);
						} else if ($value == 'no') {
							Rights_Admin::revokeUserRights(
								$user,
								$right,
								$_CONTEXT
							);
						}
					}
					
					echo '<div class="box success"><p>'.
						_('Droits de l\'utilisateur mis à jour').
						'</p></div>';
				}
			}
		}
		?><table id="users_datagrid" class="datagrid"></table>
		<a id="users_link"></a>
		<div class="clear"></div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('table#users_datagrid').gteusers({
		colNames: [
		   	'<?php echo _('ID'); ?>',
		   	'<?php echo str_replace('\'', '\\\'', _('Nom d\'utilisateur')); ?>',
		   	'<?php echo _('Groupes'); ?>',
		   	'<?php echo _('Droits supplémentaires'); ?>'
		],
		params: [
			{name: 'project', value: <?php echo $project->get('project_id'); ?>}
		],
		localpath: '<?php echo LOCAL_PATH; ?>',
		translations: {
			ajouter: '<?php echo _('Ajouter'); ?>',
			supprimer: '<?php echo _('Supprimer'); ?>',
			userdeleteminus: '<?php echo _('Vous devez sélectionner au moins un utilisateur'); ?>',
			userdeletemultiple: '<?php echo _('Êtes-vous sur de vouloir supprimer ces %d utilisateurs ?'); ?>',
			userdeletesingle: '<?php echo _('Êtes-vous sur de vouloir supprimer cet utilisateur ?'); ?>',
			editer: '<?php echo _('Éditer'); ?>',
			selectoneuser: '<?php echo _('Vous devez sélectionner un utilisateur'); ?>',
			utilisateurs: '<?php echo _('Utilisateurs'); ?>'
		}
	});
});
</script>