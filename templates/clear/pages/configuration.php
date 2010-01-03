<div id="page">
	<div id="contents">
		<h2>Configuration</h2>
		<?php
		if (isset($_POST['install'])) {
			$_CONFIG['template'] = $_POST['template'];
			$config_ini->write($_CONFIG);
			
			echo '<h2 class="ok">'._('Paramètre mis à jour').'</h2>';
		}
		?>
		<form method="POST" action="">
			<p><label>Style</label><select name="template">
				<?php
				$template_dir = ROOT_PATH.'templates/';
				$template_dir_opened = opendir($template_dir);
				while ($entry = @readdir($template_dir_opened) && substr($entry, 0, 1) != '.' && is_dir($template_dir.$entry)) {
					echo '<option value="'.$entry.'">'.$entry.'</option>';
				}
				?>
			</select>
			</p>
			<input type="submit" value="Mettre à jour" />
		</form>
	</div>
</div>