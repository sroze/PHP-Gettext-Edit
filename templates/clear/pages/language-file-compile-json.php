<?php
if (!isset($language_file)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="contents">
		<div class="link right">
			<a class="edit" href="index.php?page=language-file-editor&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>"><?php echo _('Editer'); ?></a>
		</div>
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; 
			<a href="index.php?page=language&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>"><?php echo $language->getName(); ?></a> &raquo; 
			<a href="index.php?page=language-file&project=<?php echo $project->get('project_id'); ?>&language=<?php echo $language->getCode(); ?>&file=<?php echo $language_file->getName(); ?>"><?php echo $language_file->getName(); ?></a> &raquo; 
			<?php echo _('Compiler en JSON'); ?></h1>
		<?php 
		if (isset($_POST['compile'])) {
			try {
				$output_file_path = $language_file->compile(
					'json',
					isset($_POST['with-fuzzy'])
				);
				
				echo '<div class="box success">'.
					'<p>'.sprintf(_('Fichier compilé en JSON: <strong>%s</strong>'), $output_file_path).' - <a href="index.php?page=language-file&project='.$project->get('project_id').'&language='.$language->getCode().'&file='.$language_file->getName().'">'._('Retour').'</a></p>'.
					'</div>';
			} catch (Exception $e) {
				echo '<div class="box error">'.
					'<p>'.$e->getMessage().'</p>'.
					'</div>';
			}
		}
		?>
		<div class="box">
			<p><?php echo sprintf(_('Pour compiler votre fichier <code>.po</code> de traduction en fichier JSON, et ainsi pouvoir l\'utiliser juste après '.
			'dans votre application JavaScript par exemple, il vous suffit de cliquer sur le button "%s".'), _('Compiler en JSON')); ?> <?php echo _('Voici quelques informations '.
			'concernenant la version actuelle de votre fichier JSON:'); ?></p>
			<ul>
			<?php 
			$headers = $language_file->getHeaders();
			if (!array_key_exists('GetTextEdit-compiledJSON', $headers)) {
				echo '<li>'._('Le fichier n\'éxiste pas encore').'</li>';
			} else {
				echo '<li>'._('Compilé en JSON le').' '.date('d/m/Y H:i:s', (int) $headers['GetTextEdit-compiledJSON']);
			}
			?>
			</ul>
			<form action="" method="POST">
				<p><label><input type="checkbox" name="with-fuzzy" value="yes" /> <?php echo _('Inclure les valeurs qualifiées de <code>fuzzy</code>'); ?></label></p>
				<p><input type="submit" name="compile" value="<?php echo _('Compiler en JSON'); ?>" /></p>
			</form>
		</div>
	</div>
</div>