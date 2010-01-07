<div id="page">
	<div id="contents">
		<h2>Configuration</h2>
		<?php
		if (isset($_POST['update_conf'])) {
			$_CONFIG['template'] = $_POST['template'];
			$config_ini->write($_CONFIG);
			
			echo '<div class="message success"><p>'.
				_('Paramètres mis à jour').
				'</p></div>'; 
		}
		?>
		<form method="POST" action="">
			<p><label>Style</label><select name="template">
				<?php
				$template_dir = ROOT_PATH.'templates/';
				$template_dir_opened = opendir($template_dir);
				while (false !== ($entry = readdir($template_dir_opened))) {
					if (substr($entry, 0, 1) != '.' && is_dir($template_dir.$entry)) {
						echo '<option value="'.$entry.'">'.$entry.'</option>';
					}
				}
				closedir($template_dir_opened);
				?>
			</select>
			</p>
			<input type="submit" name="update_conf" value="Mettre à jour" />
		</form>
	</div>
</div>