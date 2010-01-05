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
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php echo $language->getName(); ?></h1>
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
		<ul>
			<li><a href="index.php?page=language-new-po-file&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>">
				Créer un nouveau fichier .po
			</a></li>
			<li><a href="index.php?page=language-compile-files&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>">
				Compiler tous les fichiers
			</a></li>
			<li><a href="index.php?page=language-update-files&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>">
				Mettre à jour les fichiers depuis leur template
			</a></li>
		</ul>
		<div class="clear" />
	</div>
</div>