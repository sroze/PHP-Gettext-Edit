<?php 
if (!CONNECTED) {
	echo _('Vous êtes déjà déconnecté');
	exit();
}
?>
<div id="page">
	<div id="contents">
		<?php
		session_unset();
		setcookie('user', '', 0);
		?><div class="box success"><?php echo _('Vous avez bien été déconnecté'); ?></div>
	</div>
</div>