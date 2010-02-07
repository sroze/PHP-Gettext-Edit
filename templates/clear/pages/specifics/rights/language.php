<?php
$additional_rights_list = array(
	'language_access' => array(
		'language_edit',
		'language_delete',
		'language_files_access',

		'language_users_access' => array(
			'language_users_admin'
		),
	)
);

$ajax_params = $_CONTEXT;
$files_start = 'language';
$form_action = 'index.php?page=language-users&project='.$project->get('project_id').'&language='.$language->getCode();
?>