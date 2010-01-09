<?php
if (!isset($template)) {
	echo _('Paramètres URL insuffisants');
	exit();
}

$last_edited_files = $template->getEditedFiles();
$template_headers = $template->getHeaders();
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Modèle'); ?></h3>
		<p><?php echo _('Un modèle, c\'est un fichier qui contient toutes les phrases et tous les mots qui sont à traduire.'), ' ',
		_('Il se créé de manière automatique en analysant les sources de votre application et permet par la suite de créer des '.
		'fichiers de traductions (ou fichier <code>.po</code>) qui pourront être compilés pour servir à votre application.')?></p>
	</div>
	<div id="contents" class="with_sidebar">
		<div class="link right">
			<a class="delete" href="index.php?page=template-delete&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>"><?php echo _('Supprimer'); ?></a>
			<a class="edit" href="index.php?page=template-edit&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>"><?php echo _('Editer'); ?></a>
		</div>
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php echo _('Modèle'); ?> <code><?php echo $template->getName(); ?></code></h1>
		<?php
		$file_edited_information = '';
		if (!empty($last_edited_files)) {
			$last_edited = 0;
			
			$file_edited_information .= '<div class="box error"><p>';
			if (count($last_edited_files) == 1) {
				$file_edited_information .= _('Un fichier du projet a été modifié depuis la dernière mise à jour du modèle');
			} else {
				$file_edited_information .= sprintf(_('%d fichiers du projet ont étés modifiés depuis la dernière mise à jour du modèle'), count($last_edited_files));
			}
			
			$file_edited_information .= '</p><p><a id="show_hide_files" href="javascript:;"></a></p><ul id="file_list" style="display: none;">';
			
			foreach ($last_edited_files as $file) {
				$filemtime = filemtime($file);
				if ($filemtime > $last_edited) {
					$last_edited = $filemtime;
				}
				$file = str_replace($project->get('project_path'), '/', $file);
				
				$file_edited_information .= '<li><code>'.date('d/m/Y H:i:s', $filemtime).'</code> <span>'.$file.'</span></li>';
			}
			$file_edited_information .= '</ul></div>';
			$class = 'important';
		} else {
			$last_edited = $template_headers['GetTextEdit-updated'];
			$class = 'inutile';
		}
		?>
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
			echo '<li>'._('Dernier fichier du projet modifié le').' '.date('d/m/Y H:i:s', $last_edited).'</li>';
			?>
		</ul>
		</div>
		<?php 
		echo $file_edited_information;
		?><ul>
			<li class="<?php echo $class; ?>"><a href="index.php?page=template-update&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>">
				<?php echo _('Mettre à jour le modèle'); ?>
			</a></li>
		</ul>
		<div class="clear"></div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var see_string = '<?php echo _('Voir la liste'); ?>';
	var hide_string = '<?php echo _('Cacher la liste'); ?>';
	
	$('a#show_hide_files').text(see_string);
	$('a#show_hide_files').click(function (){
		if ($('ul#file_list').css('display') != 'block') {
			$('ul#file_list').slideDown();
			$('a#show_hide_files').text(hide_string);
		} else {
			$('ul#file_list').slideUp();
			$('a#show_hide_files').text(see_string);
		}
	});
});
</script>