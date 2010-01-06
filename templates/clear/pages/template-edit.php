<?php
if (!isset($template)) {
	echo 'Paramètres URL insuffisants';
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Editer le template'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <a href="index.php?page=template&project=<?php echo $project->get('project_id'); ?>&template=<?php echo $template->getName(); ?>">Template <code><?php echo $template->getName(); ?></code></a> &raquo; Editer</h1>
		<?php
		if (isset($_POST['program_language'])) {
			if ($_POST['type'] == '@other@') {
				$type = $_POST['other_type'];
			} else {
				$type = $_POST['type'];
			}
			
			if (empty($type)) {
				echo '<div class="form_error">'.
					_('Le type est vide').
					'</div>';
			} else if (!in_array($_POST['program_language'], Project_Template::$available_languages)) {
				echo '<div class="form_error">'.
					_('Le language de programmation est invalide').
					'</div>';
			} else if (!in_array($_POST['encoding'], Project_Template::$available_encoding)) {
				echo '<div class="form_error">'.
					_('L\'encodage est invalide').
					'</div>';
			} else if (!preg_match('#^([a-z0-9->_,]*)$#i', $_POST['keywords'])) {
				echo '<div class="form_error">'.
					_('Un ou plusieurs mots de clés sont invalides').
					'</div>';
			} else if (!preg_match('#^([a-z0-9*\.,_-]*)$#i', $_POST['search_files'])) {
				echo '<div class="form_error">'.
					_('La chaine des fichiers à rechercher n\'est pas correcte').
					'</div>';
			} else {
				try {
					$search_files = (!empty($_POST['search_files'])) ? explode(',', $_POST['search_files']) : null;
					
					$template->edit(
						$type,
						$_POST['program_language'],
						explode(',', $_POST['keywords']),
						$search_files,
						File::cleanTree($_POST['files']),
						$_POST['encoding'],
						(isset($_POST['delete_old']))
					);
					
					echo '<div class="form_success">'.
						_('Template édité').
						'</div>';
						
					unset($_POST);
				} catch (Exception $e) {
					echo '<div class="form_error">'.
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
		<form method="POST" action="">
			<fieldset>
				<legend>Général</legend>
				<p><label>Nom</label><input type="text" size="30" name="name" value="<?php
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
			<input type="submit" value="Editer" />
		</form>
	</div>
</div>