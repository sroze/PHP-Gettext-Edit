<?php
if (!isset($template)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Editer le template'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=template&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>"><?php echo _('Modèle'); ?> <code><?php echo $template->getName(); ?></code></a> &raquo; <?php echo _('Editer'); ?></h1>
		<?php
		if (isset($_POST['program_language'])) {
			if ($_POST['type'] == '@other@') {
				$type = $_POST['other_type'];
			} else {
				$type = $_POST['type'];
			}
			
			if (empty($type)) {
				echo '<div class="message error">'.
					_('Le type est vide').
					'</div>';
			} else if (!in_array($_POST['program_language'], Project_Template::$available_languages)) {
				echo '<div class="message error">'.
					_('Le language de programmation est invalide').
					'</div>';
			} else if (!in_array($_POST['encoding'], Project_Template::$available_encoding)) {
				echo '<div class="message error">'.
					_('L\'encodage est invalide').
					'</div>';
			} else if (!preg_match('#^([a-z0-9->_,]*)$#i', $_POST['keywords'])) {
				echo '<div class="message error">'.
					_('Un ou plusieurs mots de clés sont invalides').
					'</div>';
			} else if (!preg_match('#^([a-z0-9*\.,_-]*)$#i', $_POST['search_files'])) {
				echo '<div class="message error">'.
					_('La chaine des fichiers à rechercher n\'est pas correcte').
					'</div>';
			} else {
				try {
					$search_files = (!empty($_POST['search_files'])) ? explode(',', $_POST['search_files']) : null;
					
					$template->edit(
						$type,
						$_POST['program_language'],
						$_POST['encoding'],
						explode(',', $_POST['keywords']),
						$search_files, 
						File::cleanTree($_POST['files'])
					);
					
					echo '<div class="message success"><p>'.
						_('Modèle édité').'</p>';
					echo '<p><form action="index.php" method="GET">'.
						'<input type="hidden" name="page" value="template" />'.
						'<input type="hidden" name="project" value="'.$project->get('project_id').'" />'.
						'<input type="hidden" name="template" value="'.$template->getName().'" />'.
						'<input type="submit" value="'._('Retour').'" />'.
						'</form></p>';
					echo '</div>';
				} catch (Exception $e) {
					echo '<div class="message error">'.
						$e->getMessage().
						'</div>';
				}
			}
		} else {
			$headers = $template->getHeaders();
			$_POST['program_language'] = $headers['GetTextEdit-language'];
			$_POST['encoding'] = $headers['GetTextEdit-encoding'];
			if (array_key_exists('GetTextEdit-keywords', $headers)) {
				$_POST['keywords'] = $headers['GetTextEdit-keywords'];
			}
			if (array_key_exists('GetTextEdit-search-files', $headers)) {
				$_POST['search_files'] = $headers['GetTextEdit-search-files'];
			}
			if (array_key_exists('GetTextEdit-files', $headers)) {
				$_POST['files'] = unserialize($headers['GetTextEdit-files']);
			}
			$type = $headers['GetTextEdit-type'];
			if ($type == 'LC_MESSAGES') {
				$_POST['type'] = $type;
			} else {
				$_POST['type'] = '@other@';
				$_POST['other_type'] = $type;
			}
		}
		?>
		<form method="POST" action="" class="formatted">
			<fieldset>
				<legend><?php echo _('Général'); ?></legend>
				<p><label><?php echo _('Nom'); ?></label><input type="text" size="30" name="name" value="<?php
				echo $template->getName();
				?>" disabled /><br />
				</p>
				<?php 
				require PAGE_DIR.'specifics/template-general-options.php';
				?>
			</fieldset>
			<?php
			require PAGE_DIR.'specifics/template-code-options.php';
			?>
			<input type="submit" value="<?php echo _('Editer'); ?>" />
		</form>
	</div>
</div>