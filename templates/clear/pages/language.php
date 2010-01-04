<?php
if (!isset($language)) {
	echo 'Paramètres URL insuffisants';
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Statistiques'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<div class="link right">
			<a class="delete" href="index.php?page=language-delete&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo _('Supprimer'); ?></a>
		</div>
		<h1><?php echo $project->get('project_name'); ?> &raquo; <?php echo $language->getName(); ?></h1>
		<ul>
			<li>Créer un nouveau fichier .po</li>
			<li>Compiler tous les fichiers</li>
			<li>Mettre à jour les fichiers depuis leur template</li>
		</ul>
		<div class="box little right">
		<h3>Fichiers .po</h3>
		<?php 
		$files = $language->getFiles();
		if (!empty($files)) {
			echo '<ul>';
			foreach ($files as $file) {
				echo '<li><a href="index.php?page=language-file&project='.$project->get('project_id').'&language='.$language->getCode().'&file='.$file.'">'.$file.'.po</a></li>';
			}
			echo '</ul>';
		} else {
			echo '<p>Aucun fichier .po n\'est créé</p>';
		}
		?>
		</div>
	</div>
</div>