<?php
$additional_rights_list = array(
	'project_access' => array(
		'project_edit',
		'project_delete',
		'project_users_access' => array(
			'project_users_admin'
		),
		'languages_access',
		'templates_access'
	)
);

$ajax_params = array(
	'project' => $project->get('project_id')
);

$files_start = 'project';
?>