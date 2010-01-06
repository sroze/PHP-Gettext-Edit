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
			} else if (!preg_match('#^([a-z0-9->_,]*)$#i', $_POST['keywords'])) {
				echo '<div class="form_error">'.
					_('Un ou plusieurs mots de clés sont invalides').
					'</div>';
			} else {
				try {
					Project_Template::create(
						$project,
						$_POST['name'],
						$type,
						$_POST['program_language'],
						explode(',', $_POST['keywords']),
						File::cleanTree($_POST['scan_files']),
						(isset($_POST['delete_old']))
					);
					
					echo '<div class="form_success">'.
						_('Template créé').
						'</div>'; 
					empty($_POST);
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
				<p><label>Type</label>
					<select name="type" onchange="templateTypeChange(this);">
						<option value="LC_MESSAGES">LC_MESSAGES</option>
						<option disabled>------------</option>
						<option value="@other@">Autre</option>
					</select>
					<div class="form_p" id="other_type" style="display: none;">
						<input type="text" name="other_type" value="" />
					</div>
				</p>
			</fieldset>
			<fieldset>
				<legend>Analyse du code</legend>
				<p><label>Language de programmation</label>
					<select name="program_language">
						<?php 
						foreach (Project_Template::$available_languages as $l) {
							$selected = (isset($_POST['program_language']) && $_POST['program_language'] == $l) ? ' selected="selected"' : '';
							echo '<option name="'.$l.'"'.$selected.'>'.$l.'</option>';
						}
						?>
					</select>
				</p>
				<p><label>Supprimer les entrées inutilisées</label>
					<input type="checkbox" name="delete_old" value="yes" />
				</p>
				<p><label>Mots clés supplémentaires</label>
					<input type="text" name="keywords" value="" /><br />
					<em>En temps normal, les mots clés des chaines à traduire sont <code>gettext</code> ou <code>_</code>. S'il y en a d'autres, tapez-les ici, séparés par des virgules</em>
				</p>
				<p><label>Dossiers et fichiers à analyser</label>
					<div class="tree" id="tree_container"></div>
				</p>
			</fieldset>
			<input type="submit" value="Créer" />
		</form>
	</div>
</div>
<script type="text/javascript">
$(document).ready( function() {
    $('#tree_container').fileTree(<?php echo $project->get('project_id'); ?>);
});
</script>