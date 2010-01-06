<?php
require_once ROOT_PATH.'includes/classes/Project_Template.php';

if (!isset($project)) {
	echo 'Paramètres URL insuffisants';
	exit();
}
?><div id="page">
	<div id="sidebar">
		<h3><?php echo _('Création du nouveau template'); ?></h3>
		<p>À venir</p>
	</div>
	<div id="contents" class="with_sidebar">
		<h1><a href="index.php?page=project&project=<?php echo $project->get('project_id'); ?>"><?php echo $project->get('project_name'); ?></a> &raquo; Nouveau template</h1>
		<?php
		if (isset($_POST['name'])) {
			if ($_POST['type'] == '@other@') {
				$type = $_POST['other_type'];
			} else {
				$type = $_POST['type'];
			}
				
			if (!preg_match('#^([a-z0-9_-]+)$#i', $_POST['name'])) {
				echo '<div class="form_error">'.
					_('Le nom contient des caractères invalides').
					'</div>';
			} else if (empty($type)) {
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
					
					Project_Template::create(
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
					
					echo '<div class="form_success">'.
						_('Template créé').
						'</div>';
						
					unset($_POST);
				} catch (Exception $e) {
					echo '<div class="form_error">'.
						$e->getMessage().
						'</div>';
				}
			}
		}
		?>
		<form method="POST" action="">
			<fieldset>
				<legend>Général</legend>
				<p><label>Nom</label><input type="text" size="30" name="name"<?php
				if (!empty($_POST['name'])) { echo ' value="'.$_POST['name'].'"'; }
				?> /><br />
					<em>Lettres, chiffres, underscore (_) et tirets autorisés</em>
				</p>
				<?php
				require PAGE_DIR.'specifics/template-general-options.php';
				?>
			</fieldset>
			<?php
			require PAGE_DIR.'specifics/template-code-options.php';
			?>
			<input type="submit" value="Créer" />
		</form>
	</div>
</div>