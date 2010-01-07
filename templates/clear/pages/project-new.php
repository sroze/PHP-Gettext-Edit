<div id="page">
	<div id="contents">
		<h1><?php echo _('Nouveau projet'); ?></h1>
		<?php
		if (isset($_POST['name'])) {
			if (!preg_match('#^([a-z0-9-]+)$#i', $_POST['name'])) {
				echo '<div class="message error">'.
					_('Le nom contient des caractères invalides').
					'</div>';
			} else if (empty($_POST['path_app']) || !$_POST['path_app'] = realpath($_POST['path_app'])) {
				echo '<div class="message error">'.
					_('Impossible d\'accèder à ce chemin depuis cette application').
					'</div>';
			} else {
				$_POST['path_app'] = str_replace('"', '\\"', $_POST['path_app']);
				if (substr($_POST['path_app'], -1) != '/') {
					$_POST['path_app'] .= '/';
				}
				
				$_POST['path_lang'] = str_replace('"', '\\"', $_POST['path_lang']);
				if (substr($_POST['path_lang'], -1) != '/') {
					$_POST['path_lang'] .= '/';
				}
				
				if (!is_dir($_POST['path_app'].$_POST['path_lang'])) {
					echo '<div class="message error">'.
						sprintf(_('Le dossier %s n\'éxiste pas au sein du projet'), $_POST['path_lang']).
						'</div>';
				} else {
					$sql->query('INSERT INTO projects (project_name, project_path, project_languages_path) VALUES
						("'.$_POST['name'].'", "'.$_POST['path_app'].'", "'.$_POST['path_lang'].'")
					');
					
					echo '<div class="message success"><p>'.
						_('Projet créé').'</p>';
					echo '<p><form action="index.php" method="GET">'.
						'<input type="hidden" name="page" value="project" />'.
						'<input type="hidden" name="project" value="'.$sql->lastInsertId().'" />'.
						'<input type="submit" value="'._('Continuer').'" />'.
						'</form></p>';
					echo '</div>';
						
					unset($_POST);
				}
			}
		}
		?>
		<form method="POST" action="">
			<p><label><?php echo _('Nom'); ?></label><input type="text" size="30" name="name"<?php
			if (!empty($_POST['nom'])) { echo ' value="'.$_POST['nom'].'"'; }
			?> /><br />
				<em><?php echo _('Lettres, chiffres et tirets autorisés'); ?></em>
			</p>
			<p><label><?php echo _('Répertoire du projet'); ?></label><input type="text" size="50" name="path_app"<?php
			if (!empty($_POST['path_app'])) { echo ' value="'.$_POST['path_app'].'"'; }
			?> /><br />
				<em><?php echo _('Doit être un chemin complet.'); ?> 
				<?php echo _('Exemple pour l\'application actuelle:'); ?> <?php echo ROOT_PATH; ?></em>
			</p>
			<p><label><?php echo _('Répertoire des langues'); ?></label><input type="text" size="30" name="path_lang"<?php
			if (!empty($_POST['path_lang'])) { echo ' value="'.$_POST['path_lang'].'"'; }
			?> /><br />
				<em><?php echo _('Au sein de projet, quel est le répertoire des langues ?'); ?> 
				<?php echo _('Exemple pour l\'application actuelle:'); ?> locales/</em>
			</p>
			<input type="submit" value="<?php echo _('Créer'); ?>" />
		</form>
	</div>
</div>