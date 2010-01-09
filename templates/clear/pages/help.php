<div id="page">
	<div id="contents" class="textual">
		<h1><?php echo _('Aide'); ?></h1>
		<p><?php echo _('La majeure partie des informations concernant les termes utilisés, les conventions et l\'utilisation des fichiers est '.
		'présente dans la colonne de droite de nombreuses pages.'), ' ', _('Cette aide à pour but de vous permettre de bien comprendre comment '.
		'fonctionne PHP-GetText-Edit afin de gérer au mieux vos traductions.'); ?></p>
		<ol>
			<li><a href="#gettext"><?php echo _('Qu\'est-ce que GetText'); ?></a>
				<ol>
					<li><a href="#gnu_gettext">GNU GetText</a></li>
					<li><a href="#gettext_words"><?php echo _('Termes'); ?></a></li>
				</ol>
			</li>
			<li><a href="#php-gettext-edit"><?php echo _('Traduire un projet avec PHP-Gettext-Edit'); ?></a>
			<ol>
				<li><a href="#php-gettext-edit-templates"><?php echo _('La création de modèles'); ?></a></li>
				<li><a href="#php-gettext-edit-languages"><?php echo _('La création de langues'); ?></a></li>
				<li><a href="#php-gettext-edit-po"><?php echo _('La création de fichier de traduction'); ?></a></li>
				<li><a href="#php-gettext-edit-compilation"><?php echo _('La compilation des fichiers de traduction'); ?></a></li>
			</ol></li>
			<li><a href="#report-bug"><?php echo _('Reporter un bug'); ?></a></li>
			<li><a href="#languages_codes"><?php echo _('Tableau de codes de langues'); ?></a></li>
		</ol>
		
		<a name="gettext"></a><h2><?php echo _('Qu\'est-ce que GetText'); ?></h2>
		<a name="gnu_gettext"></a><h3>GNU GetText</h3>
		<p><?php echo _('GetText est le nom de la <a href="http://www.gnu.org/software/gettext/gettext.html">librairie d\'internationalisation '.
		'de GNU</a>, utilisée le plus souvent pour de la traduction d\'applications.'); ?> 
		<?php echo _('Elle peut être utilisée par des languages Web comme PHP, utilisé ici mais aussi avec des languages comme C ou Java.'); ?></p>
		
		<a name="gettext_words"></a><h3><?php echo _('Termes'); ?></h3>
		<p><?php echo _('Voici une liste non exhaustive des termes que vous pouvez rencontrer lors de l\'utilisation de PHP-GetText-Edit et '.
		'de manière générale de GetText:'); ?></p>
		<ul>
			<li><strong><?php echo _('Modèle'); ?></strong>: <?php echo _('un modèle est un fichier qui contient toutes (du moins essayes) '.
		'les chaines de caratères à	traduire de votre programme.'); ?></li>
			<li><strong><?php echo _('Fichier de traduction'); ?></strong> <?php echo _('ou'); ?> <strong><?php echo _('Fichier .po'); ?></strong>: 
		<?php echo _('Créé à partir d\'un modèle, c\'est un fichier qui contient lui aussi les traductions mais qui est dédié à une langue.'); ?> 
		<?php echo _('C\'est ce fichier que le traducteur va modifier afin de faire associer chacune des phrases en leurs homologues traduites.'); ?></li>
			<li><strong><?php echo _('Fichier compilé'); ?></strong> <?php echo _('ou'); ?> <strong><?php echo _('Fichier .mo'); ?></strong>: 
		<?php echo _('Créé à partir d\'un fichier de traduction, c\'est un fichier binaire qui contient les chaines ainsi que '.
		'leurs traductions et qui est utilisé par la librairie Gettext.'); ?></li>
		</ul>
		
		<a name="php-gettext-edit"></a><h2><?php echo _('Traduire un projet avec PHP-Gettext-Edit'); ?></h2>
		<p><?php echo _('La traduction d\'un projet avec PHP-Gettext-Edit se fait en plusieurs étapes:'); ?></p>
		<ol>
			<li><?php echo _('La création de modèles'); ?></li>
			<li><?php echo _('La création de langues'); ?></li>
			<li><?php echo _('La création de fichiers de traduction'); ?></li>
			<li><?php echo _('La compilation'); ?></li>
		</ol>
		
		<a name="php-gettext-edit-templates"></a><h3><?php echo _('La création de modèles'); ?></h3>
		<p><?php echo _('La création de modèle se fait de manière très simple en remplissant un formulaire qui vous permet de configurer le petit '.
		'programme qui va analyser le code source de votre application et récupérer les chaines de caractère présentes.'); ?> 
		<?php echo _('Vous pourrez en effet	choisir dans quels dossiers et quels fichiers analyser, l\'extension des fichiers, '
		.'les mots clés supplémentaires...'); ?> 
		<?php echo _('Rendez-vous sur la page en question pour plus d\'informations.'); ?></p>
		<a name="php-gettext-edit-languages"></a><h3><?php echo _('La création de langues'); ?></h3>
		<p><?php echo _('Avant ou après la création d\'un modèle, il faut créer une langue.'); ?> 
		<?php echo _('Concrètement, lors de la création de la langue, PHP-GetText-Edit '.
		'créé un dossier du nom du code de la langue, dans lequel vous pourrez créer les fichiers de traduction .po.'); ?></p>
		
		<a name="php-gettext-edit-po"></a><h3><?php echo _('La création de fichier de traduction'); ?></h3>
		<p><?php echo _('Une fois un modèle créé, vous pouvez, à partir de celui-ci, créer un fichier de traduction.'); ?> 
		<?php echo _('Ce fichier contient au début presque exactement la même chose que le modèle: les chaines à traduire.'); ?> 
		<?php echo _('C\'est ce fichier que vous allez pouvoir éditer grâce à l\'éditeur intégré de fichiers .po de PHP-GetText-Edit.'); ?></p>
		<p><?php echo _('Une fois ce fichier édité, vous devriez avoir toutes les correspondances entre les '.
		'chaines de caractères à traduire de votre application et leurs traductions dans ce fichier.'); ?>
		<?php echo _('C\'est à ce moment, qu\'il vous faut compiler pour terminer le travail de traduction de votre application.'); ?></p>
		
		<a name="php-gettext-edit-compilation"></a><h3><?php echo _('La compilation des fichiers de traduction'); ?></h3>
		<p><?php echo _('Une fois votre fichier de traduction complété, il faut le compiler en un fichier différent, qui sera plus '.
		'rapide à lire pour la librairie Gettext ou dans un autre format, suivant l\'utilisation que vous en faites.'); ?></p>
		<ul>
			<li><?php echo _('Dans la plus grande partie des cas, vous aurez besoin de <strong>compiler <em>normalement</em> votre fichier '.
			'<code>.po</code></strong>, c\'est-à-dire de créer un fichier binaire <code>.mo</code> utilisé par les librairies Gettext '.
			'fournies avec PHP, C...'); ?></li>
			<li><?php echo _('Dans d\'autres cas, comme pour de l\'internationnalisation JavaScript, vous pouvez avec besoin de '.
			'<strong>compiler votre fichier en JSON</strong>. PHP-Gettext-Edit vous permet aussi de le faire.'); ?></li>
		</ul>
		<p><?php echo _('Une fois votre fichier transformé (compilé), PHP-Gettext-Edit vous donne l\'adresse du fichier et c\'est à vous '.
		'de l\'indiquer dans votre application, comme il se doit.'); ?> 
		<?php echo _('Si vous ne savez pas comment fonctionne la librairie Gettext de PHP, je vous conseil de rechercher '.
		'sur Google, de nombreux tutoriels éxistes, plus ou moins complets.'); ?></p>
		<p><strong><?php echo _('Note:'); ?></strong> <?php echo _('vous pouvez retrouver tous les fichiers compilés d\'une langue en bas de sa page PHP-Gettext-Edit.'); ?></p>
		
		<a name="report-bug"></a><h2><?php echo _('Reporter un bug'); ?></h2>
		<p><?php echo sprintf(_('N\'hésitez pas à reporter un bug ou à suggerer une fonctionnalité sur le '.
		'<a href="%s">gestionnaire de bug de PHP-GetText-Edit</a> ou '.
		'<a href="%s">contactez-moi</a> par mail pour toute information supplémentaire.</p>'),
		'http://tasks.d-sites.com/projects/show/gettextedit',
		'http://www.d-sites.com/a-propos/contact/'); ?></p>
		
		<a name="languages_codes"></a><h2><?php echo _('Tableau de codes de langues'); ?></h2>
		<p><?php echo _('Voici dans le tableau ci-dessous quelques codes de pays et leurs langues:'); ?></p>
		<table class="languages">
			<tr>
				<th><?php echo _('Langue'); ?></th>
				<th><?php echo _('Code'); ?></th>
			</tr>
			<tr>
				<td><?php echo _('Français'); ?></td>
				<td><code>fr_FR</code></td>
			</tr>
			<tr>
				<td><?php echo _('Anglais'); ?></td>
				<td><code>en_US</code></td>
			</tr>
			<tr>
				<td><?php echo _('Allemand'); ?></td>
				<td><code>de_DE</code></td>
			</tr>
			<tr>
				<td><?php echo _('Espagnol'); ?></td>
				<td><code>es_ES</code></td>
			</tr>
			<tr>
				<td><?php echo _('Italien'); ?></td>
				<td><code>it_IT</code></td>
			</tr>
		</table>
	</div>
</div>