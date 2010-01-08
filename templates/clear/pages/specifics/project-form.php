			<p><label><?php echo _('Nom'); ?></label><input type="text" size="30" name="name"<?php
			if (!empty($_POST['name'])) { echo ' value="'.$_POST['name'].'"'; }
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