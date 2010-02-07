<div id="page">
	<div id="contents">
		<?php
		if (array_key_exists('language_file', $_CONTEXT)) {
			$right = 'language_file_users_';
		} else if (array_key_exists('language', $_CONTEXT)) {
			$right = 'language_users_';
		} else if (array_key_exists('template', $_CONTEXT)) {
			$right = 'template_users_';
		} else if (array_key_exists('project', $_CONTEXT)) {
			$right = 'project_users_';
		}
		
		if (count(GTE::getUsersHavingRight($right.'admin', $_CONTEXT)) == 0) {
			Rights_Admin::grantUserRights($_USER->get('id'), array($right.'admin'), $_CONTEXT);
			echo '<div class="box success"><p>'._('Droits accordés.').'</p></div>';
		} else {
			echo '<div class="box error"><p>'._('Erreur').'</p></div>';
		}
		
		?>
	</div>
</div>