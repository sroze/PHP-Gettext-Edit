<?php
require_once ROOT_PATH.'includes/classes/Project_Template.php';

if (!isset($project)) {
	echo _('Paramètres URL insuffisants');
	exit();
}
?><div id="page">
	<div id="sidebar">
		<?php
		require PAGE_DIR.'specifics/sidebar/template.php';
		?>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; <?php echo _('Nouveau template'); ?></h1>
		<?php
		if (isset($_POST['name'])) {
			if ($_POST['type'] == '@other@') {
				$type = $_POST['other_type'];
			} else {
				$type = $_POST['type'];
			}
				
			if (!preg_match('#^([a-z0-9_-]+)$#i', $_POST['name'])) {
				echo '<div class="message error">'.
					_('Le nom contient des caractères invalides').
					'</div>';
			} else if (empty($type)) {
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
					
					$template = Project_Template::create(
						$project,
						$_POST['name'],
						$type,
						$_POST['program_language'],
						explode(',', $_POST['keywords']),
						$search_files,
						File::cleanTree($_POST['files']),
						$_POST['encoding'],
						(isset($_POST['delete_old']))
					);
					
					echo '<div class="message success"><p>'.
						_('Modèle créé').'</p>';
					echo '<p><form action="index.php" method="GET">'.
						'<input type="hidden" name="page" value="template" />'.
						'<input type="hidden" name="project" value="'.$project->get('project_id').'" />'.
						'<input type="hidden" name="template" value="'.$template->getName().'" />'.
						'<input type="submit" value="'._('Continuer').'" />'.
						'</form></p>';
					echo '</div>';
					
					unset($_POST);
				} catch (Exception $e) {
					echo '<div class="message error">'.
						$e->getMessage().
						'</div>';
				}
			}
		}
		?>
		<form method="POST" action="" class="formatted">
			<fieldset>
				<legend><?php echo _('Général'); ?></legend>
				<p><label><?php echo _('Nom'); ?></label><input type="text" size="30" name="name"<?php
				if (!empty($_POST['name'])) { echo ' value="'.$_POST['name'].'"'; }
				?> /><br />
					<em><?php echo _('Lettres, chiffres, underscore (_) et tirets autorisés'); ?></em>
				</p>
				<?php
				require PAGE_DIR.'specifics/template-general-options.php';
				?>
			</fieldset>
			<?php
			require PAGE_DIR.'specifics/template-code-options.php';
			?>
			<input type="submit" value="<?php echo _('Créer'); ?>" />
		</form>
	</div>
</div>