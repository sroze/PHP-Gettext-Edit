			<fieldset>
				<legend><?php echo _('Analyse du code'); ?></legend>
				<p><label><?php echo _('Language de programmation'); ?></label>
					<select name="program_language">
						<?php 
						foreach (Project_Template::$available_languages as $l) {
							$selected = (isset($_POST['program_language']) && $_POST['program_language'] == $l) ? ' selected="selected"' : '';
							echo '<option name="'.$l.'"'.$selected.'>'.$l.'</option>';
						}
						?>
					</select>
				</p>
				<p><label><?php echo _('Encodage'); ?></label>
					<select name="encoding">
						<?php 
						foreach (Project_Template::$available_encoding as $l) {
							$selected = (isset($_POST['encoding']) && $_POST['encoding'] == $l) ? ' selected="selected"' : '';
							echo '<option name="'.$l.'"'.$selected.'>'.$l.'</option>';
						}
						?>
					</select>
				</p>
				<p><label><?php echo _('Supprimer les entrées inutilisées'); ?></label>
					<input type="checkbox" name="delete_old" value="yes"<?php
				if (isset($_POST['delete_old'])) { echo ' checked'; }
				?> />
				</p>
				<p><label><?php echo _('Mots clés supplémentaires'); ?></label>
					<input type="text" name="keywords" value="<?php
				if (!empty($_POST['keywords'])) { echo $_POST['keywords']; }
				?>" /><br />
					<em><?php echo _('En général, les mots clés des chaines à traduire sont <code>gettext</code> ou <code>_</code>.');
					echo _('S\'il y en a d\'autres, tapez-les ici, séparés par des virgules'); ?></em>
				</p>
				<p><label><?php echo _('Dossiers et fichiers à analyser'); ?></label>
					<div class="tree" id="tree_container"></div>
				</p>
				<p><label><?php echo _('Types de fichiers à rechercher'); ?></label>
					<input type="text" name="search_files" value="<?php
				if (!empty($_POST['search_files'])) { echo $_POST['search_files']; } else { echo '*.php'; }
				?>" /><br />
					<em><?php echo _('Séparés par des virgules.');
					echo _('Exemple:'); ?> <code>*.php,*.myphpext,language_*</code></em>
				</p>
			</fieldset>
			<script type="text/javascript">
			$(document).ready( function() {
			    $('#tree_container').fileTree(<?php echo $project->get('project_id'); ?>);
			});
			</script>