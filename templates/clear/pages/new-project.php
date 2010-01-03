<div id="page">
	<div id="contents">
		<h1><?php echo _('Nouveau projet'); ?></h1>
		<?php
		if (isset($_POST['name'])) {
			if (!preg_match('#^([a-z0-9-]+)$#i', $_POST['name'])) {
				echo '<div class="form_error">'.
					_('Le nom contient des caractères invalides').
					'</div>';
			} else if (empty($_POST['path_app']) || !$_POST['path_app'] = realpath($_POST['path_app'])) {
				echo '<div class="form_error">'.
					_('Impossible d\'accèder à ce chemin depuis cette application').
					'</div>';
			} else {
				$_POST['path_app'] = str_replace('"', '\\"', $_POST['path_app']);
				$_POST['path_lang'] = str_replace('"', '\\"', $_POST['path_lang']);
				$sql->query('INSERT INTO projects (project_name, project_path, project_languages_path) VALUES
					("'.$_POST['name'].'", "'.$_POST['path_app'].'", "'.$_POST['path_lang'].'")
				');
				echo '<div class="form_success">'.
					_('Projet créé').
					'</div>'; 
			}
		}
		?>
		<form method="POST" action="">
			<p><label>Nom</label><input type="text" size="30" name="name" /><br />
				<em>Lettres, chiffres et tirets autorisés</em>
			</p>
			<p><label>Répertoire du projet</label><input type="text" size="50" name="path_app" /><br />
				<em>Doit être un chemin complet. Exemple pour l\'application actuelle: <?php echo ROOT_PATH; ?></em>
			</p>
			<p><label>Répertoire des langues</label><input type="text" size="30" name="path_lang" /><br />
				<em>Au sein de projet, quel est le répertoire des langues ? Exemple pour l\'application actuelle: locales/</em>
			</p>
			<input type="submit" value="Créer" />
		</form>
	</div>
</div>