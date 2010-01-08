<?php 
$menus = array(
	'index' => _('Projets'),
	'configuration' => _('Configuration'),
	'help' => _('Aide')
);

$top_menu_lis = '';
foreach ($menus as $page => $name) {
	$top_menu_lis .= '<li><a href="index.php?page='.$page.'"'.
		(($_GET['page'] == $page) ? ' class="selected"' : '').
		'>'.$name.'</a></li>'."\n";
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>GetTextEdit</title>
<link rel="stylesheet" href="<?php echo LOCAL_PATH; ?>templates/<?php echo $_CONFIG['template']; ?>/styles/site.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo LOCAL_PATH; ?>templates/<?php echo $_CONFIG['template']; ?>/styles/jquery.filetree.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo LOCAL_PATH; ?>templates/<?php echo $_CONFIG['template']; ?>/styles/datagrid.css" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo LOCAL_PATH; ?>templates/<?php echo $_CONFIG['template']; ?>/scripts/jquery-1.3.2.min.js"></script>
</head>
<body>
<div id="header">
	<h1>GetTextEdit</h1>
	<h2><?php echo _('Gérez vos traductions simplement'); ?></h2>
</div>
<ul id="top_menu">
	<?php echo $top_menu_lis; ?>
</ul>