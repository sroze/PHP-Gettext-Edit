<?php
Rights_Config::init($sql, $_POST['sql-prefix'].'rights_');

// Creation of users groups
$group_admin = Rights_Admin::createGroup('Administrateur');
$group_trans = Rights_Admin::createGroup('Traducteur');

// Creation of rights
$right_admin = Rights_Admin::createRight('administration');
Rights_Admin::createRight('create_user', $right_admin);
Rights_Admin::createRight('delete_user', $right_admin);
Rights_Admin::createRight('edit_user', $right_admin);

$right_project = Rights_Admin::createRight('project');
Rights_Admin::createRight('create_project', $right_project);
Rights_Admin::createRight('edit_project', $right_project);
Rights_Admin::createRight('delete_project', $right_project);

// Grant rights to groups
Rights_Admin::addGroupRights($group_admin, $right_admin);

?>