<?php
Rights_Config::init($sql, $_POST['sql-prefix'].'rights_');

Rights_Admin::createRightsFromArray(
	array(
		'admin_access' => array(
			'admin_template_update'
		),
		'projects_access' => array(
			'project_create',
			'project_access' => array(
				'project_edit',
				'project_create',
				'project_delete',
				'project_users_access' => array(
					'project_users_admin'
				),
				'languages_access' => array(
					'language_create',
					'language_delete',
					'language_access' => array(
						'language_edit',
						'language_delete',
						'language_users_access' => array(
							'language_users_admin'
						),
						'language_files_access' => array(
							'language_file_create',
							'language_file_access' => array(
								'language_file_delete',
								'language_file_edit',
								'language_file_compile',
								'language_file_update',
								'language_file_users_access' => array(
									'language_file_users_admin'
								)
							)
						)
					)
				),
				'templates_access' => array(
					'template_create',
					'template_access' => array(
						'template_delete',
						'template_edit',
						'template_update',
						'template_users_access' => array(
							'template_users_admin'
						),
					)
				)
			)
		)
	)
);

// Create groups
$group_admin = Rights_Admin::createGroup('Administrateur');

// Grant rights to groups
// The rest of rights will be granted on project creation
Rights_Admin::addGroupRights($group_admin, array(
	'admin_template_update',
	'project_create'
));

?>