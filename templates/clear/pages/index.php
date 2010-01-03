<?php 
$_PROJECT = new Project();
$liste = $_PROJECT->getList();

$projects_list = '';
foreach ($liste as $informations) {
	$projects_list .= '<li><a href="index.php?page=project&project='.$informations['project_id'].'">'.$informations['project_name'].'</a>';
	$projects_list .= '<p>'._('Aucune information sur les traductions').'</p>';
	$projects_list .= '</li>'."\n";
}

if (empty($projects_list)) {
	$projects_list = _('Aucun projet créé pour le moment.');
}

?>
<div id="page">
	<div id="sidebar">
		<h3><?php echo _('Vos projets'); ?></h3>
		<p><?php echo _('Vous pouvez créer différents projets, qui pourront contenir leurs propre configuration, ce qui vous permettera de gérer de manière automatisée, une fois configurés, les traductions.'); ?></p>
	</div>
	<div id="contents" class="with_sidebar">
		<div class="right_link"><a href="index.php?page=new-project"><?php echo _('Créer un nouveau projet'); ?></a></div>
		<h1><?php echo _('Projets'); ?></h1>
		<ul class="projects_list">
			<?php echo $projects_list; ?>
		</ul>
	</div>
</div>