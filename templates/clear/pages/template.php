<?php
if (!isset($template)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
$t1 = microtime(true);
$last_edited = $template->getLastEditedFileTimestamp();
echo 'Last edited file time got in '.(microtime(true)-$t1).' s';

$template_headers = $template->getHeaders();
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Statistiques'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<div class="link right">
			<a class="delete" href="index.php?page=template-delete&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>"><?php echo _('Supprimer'); ?></a>
			<a class="edit" href="index.php?page=template-edit&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>"><?php echo _('Editer'); ?></a>
		</div>
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php echo _('Modèle'); ?> <code><?php echo $template->getName(); ?></code></h1>
		<div class="box little right">
		<h3><?php echo _('Informations'); ?></h3>
		<ul>
			<?php
			if (array_key_exists('GetTextEdit-edited', $template_headers)) {
				echo '<li>'._('Dernière édition le').' '.date('d/m/Y H:i:s', (int) $template_headers['GetTextEdit-edited']).'</li>';
			}
			if (array_key_exists('GetTextEdit-updated', $template_headers)) {
				echo '<li>'._('Mis à jour le').' '.date('d/m/Y H:i:s', (int) $template_headers['GetTextEdit-updated']).'</li>';
			}
			if (array_key_exists('GetTextEdit-compiled', $template_headers)) {
				echo '<li>'._('Dernier fichier du projet modifié le').' '.date('d/m/Y H:i:s', $last_edited).'</li>';
			}
			?>
		</ul>
		</div>
		<ul>
		<?php 
		if ($last_edited > (int) $template_headers['GetTextEdit-updated']) {
			echo '<div class="box error">'.
				_('Un ou plusieurs fichiers du projet ont étés modifiés depuis la création la dernière mise à jour du modèle').
				'</div>';
		}
		?>
			<li><a href="index.php?page=template-update&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>">
				<?php echo _('Mettre à jour le modèle'); ?>
			</a></li>
		</ul>
	</div>
</div>