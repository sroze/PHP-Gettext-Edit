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
		
		$ajax_params_string = '';
		foreach ($ajax_params as $param => $value) {
			if (!empty($ajax_params_string)) {
				$ajax_params_string .= ',';
			}
			$ajax_params_string .= '{name: \''.$param.'\', value: \''.$value.'\'}';
		}
		?><table id="users_datagrid" class="datagrid"></table>
		<a id="users_link"></a>
		<div class="clear"></div>
		<script type="text/javascript">
		$(document).ready(function() {
			$('table#users_datagrid').gteusers(
				'<?php echo $files_start; ?>',
				{
				colNames: [
				   	'<?php echo _('ID'); ?>',
				   	'<?php echo str_replace('\'', '\\\'', _('Nom d\'utilisateur')); ?>',
				   	'<?php echo _('Groupes'); ?>',
				   	'<?php echo _('Droits supplémentaires'); ?>'
				],
				params: [
					<?php echo $ajax_params_string; ?>
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