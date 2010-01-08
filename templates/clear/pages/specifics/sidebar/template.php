		<h2><?php echo _('Nom du modèle'); ?></h2>
		<p><?php echo _('Le nom du modèle permet de différencier plusieurs modèles entre eux mais seul le nom du fichier '.
		'de traduction est important pour votre application.'); ?></p>
		<h2><?php echo _('Type de modèle'); ?></h2>
		<p><?php echo _('Très souvent <code>LC_MESSAGES</code>, le type de modèle permet de définir dans quelles conditions '.
		'les traductions des fichiers de traductions issus de ce modèle seront utilisées.'), ' ', _('Avec la fonction <code>gettext</code> '.
		'de PHP, il est impératif que le type soit <code>LC_MESSAGES</code>.'); ?></p>
		<h2><?php echo _('Language de programmation'); ?></h2>
		<p><?php echo _('Pour extraire les chaines de caractères à traduire du code source, il faut préciser le language de '.
		'programmation afin que le programme puisse récupérer toutes les chaines utilisées'); ?></p>
		<p><?php echo _('Le language JavaScript n\'étant pas supporté comme tel, vous pouvez choisir le mode Python qui '.
		'donnera le résultat le plus concluant.')?></p>
		<h2><?php echo _('Mots clés supplémentaires'); ?></h2>
		<p><?php echo _('La fonction <code>gettext</code> et son alias <code>_</code> est utilisée pour traduire une chaine.'), ' ',
		_('Si vous utilisez vos propres fonctions, alors précisez-les.'); ?></p>
		<h2><?php echo _('Dossiers à analyser'); ?></h2>
		<p><?php echo _('L\'essentiel de la création d\'un modèle est la recherche de chaines de caractère à traduire.'), ' ',
		_('Vous pouvez donc choisir dans quels dossiers et fichiers souhaitez-vous rechercher ces chaines.'); ?></p>
		<h2><?php echo _('Types de fichiers'); ?></h2>
		<p><?php echo _('Dans les dossiers de recherche que vous avez spécifié, précisez quels sont le format de nom des '.
		'fichiers à rechercher.'); ?></p>