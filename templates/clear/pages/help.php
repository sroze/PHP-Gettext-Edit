<div id="page">
	<div id="contents" class="textual">
		<h1><?php echo _('Aide'); ?></h1>
		<p>La majeure partie des informations concernant les termes utilisés, les conventions et l'utilisation des fichiers est
		présente dans la colonne de droite de nombreuses pages. Cette aide à pour but de vous permettre de bien comprendre comment
		fonctionne PHP-GetText-Edit afin de gérer au mieux vos traductions.</p>
		<ol>
			<li><a href="#gettext">Qu'est-ce que GetText</a>
				<ol>
					<li><a href="#gnu_gettext">GNU GetText</li>
					<li><a href="#gettext_words">Termes</li>
				</ol>
			</li>
			<li><a href="#php-gettext-edit">Traduire un projet avec PHP-Gettext-Edit
			<ol>
				<li><a name="#php-gettext-edit-templates">La création de modèles</a></li>
				<li><a name="#php-gettext-edit-languages">La création de langues</a></li>
				<li><a name="#php-gettext-edit-po">La création de fichier de traduction</a></li>
				<li><a name="#php-gettext-edit-compilation">La compilation des fichiers de traduction</a></li>
			</ol></li>
			<li><a href="#report-bug">Reporter un bug</li>
			<li><a href="#languages_codes">Tableau des codes de langues</li>
		</ol>
		
		<a name="gettext"></a><h2>Qu'est-ce que GetText</h2>
		<a name="gnu_gettext"></a><h3>GNU GetText</h3>
		<p>GetText est le nom de la <a href="http://www.gnu.org/software/gettext/gettext.html">librairie d'internationalisation de GNU</a>, 
		utilisée le plus souvent pour de la traduction d'applications. 
		Elle peut être utilisée par des languages Web comme PHP, utilisé ici mais aussi avec des languages comme C ou Java.</p>
		
		<a name="gettext_words"></a><h3>Termes</h3>
		<p>Voici une liste non exhaustive des termes que vous pouvez rencontrer lors de l'utilisation de PHP-GetText-Edit et de manière
		général de GetText:</p>
		<ul>
			<li><strong>Modèle</strong>: un modèle est un fichier qui contient toutes (du moins essayes) les chaines de caratères à
			traduire de votre programme.</li>
			<li><strong>Fichier de traduction</strong> ou <strong>Fichier .po</strong>: Créé à partir d'un modèle, c'est un fichier qui
			contient lui aussi les traductions mais qui est dédié à une langue. C'est ce fichier que le traducteur va modifier afin de
			faire associer chacune des phrases en leurs homologues traduites.</li>
			<li><strong>Fichier compilé</strong> ou <strong>Fichier .mo</strong>: Créé à partir d'un fichier de traduction, c'est un fichier
			binaire qui contient les chaines ainsi que leurs traductions et qui est utilisé par la librairie Gettext.</li>
		</ul>
		
		<a name="php-gettext-edit"></a><h2>Traduire un projet avec PHP-Gettext-Edit</h2>
		<p>La traduction d'un projet avec PHP-Gettext-Edit se fait en plusieurs étapes:</p>
		<ol>
			<li>La création de modèles</li>
			<li>La création de langues</li>
			<li>La création de fichiers de traduction</li>
			<li>La compilation</li>
		</ol>
		
		<a name="php-gettext-edit-templates"></a><h3>La création de modèles</h3>
		<p>La création de modèle se fait de manière très simple en remplissant un formulaire qui vous permet de configurer le petit
		programme qui va analyser le code source de votre application et récupérer les chaines de caractère présentes. Vous pourrez en effet
		choisir dans quels dossiers et quels fichiers analyser, l'extension des fichiers, les mots clés supplémentaires... Rendez-vous sur
		la page en question pour plus d'informations.</p>
		<a name="php-gettext-edit-languages"></a><h3>La création de langues</h3>
		<p>Avant ou après la création d'un modèle, il faut créer une langue. Concrètement, lors de la création de la langue, PHP-GetText-Edit
		créé un dossier du nom du code de la langue, dans lequel vous pourrez créer les fichiers de traduction .po.</p>
		
		<a name="php-gettext-edit-po"></a><h3>La création de fichier de traduction</h3>
		<p>Une fois un modèle créé, vous pouvez, à partir de celui-ci, créer un fichier de traduction. Ce fichier contient au début presque
		exactement la même chose que le modèle: les chaines à traduire. C'est ce fichier que vous allez pouvoir éditer grâce à l'éditeur
		intégré de fichiers .po de PHP-GetText-Edit.</p>
		<p>Une fois ce fichier édité, vous devriez avoir toutes les correspondances entre les
		chaines de caractères à traduire de votre application et leurs traductions dans ce fichier. C'est à ce moment, qu'il vous faut
		compiler pour terminer le travail de traduction de votre application.</p>
		
		<a name="php-gettext-edit-compilation"></a><h3>La compilation des fichiers de traduction</h3>
		<p>Une fois votre fichier de traduction complété, il faut le compiler en un fichier différent, qui sera plus rapide à lire pour
		la librairie Gettext ou dans un autre format, suivant l'utilisation que vous en faites.</p>
		<ul>
			<li>Dans la plus grande partie des cas, vous aurez besoin de <strong>compiler <em>normalement</em> votre fichier <code>.po</code></strong>, 
			c'est-à-dire de créer un fichier binaire <code>.mo</code> utilisé par les librairies Gettext fournies avec PHP, C...</li>
			<li>Dans d'autres cas, comme pour de l'internationnalisation JavaScript, vous pouvez avec besoin de <strong>compiler votre fichier
			en JSON</strong>. PHP-Gettext-Edit vous permet aussi de le faire.</li>
		</ul>
		<p>Une fois votre fichier transformé (compilé), PHP-Gettext-Edit vous donne l'adresse du fichier et c'est à vous de l'indiquer dans
		votre application, comme il se doit. Si vous ne savez pas comment fonctionne la librairie Gettext de PHP, je vous conseil de rechercher
		sur Google, de nombreux tutoriels éxistes, plus ou moins complets.</p>
		<p><strong>Note:</strong> vous pouvez retrouver tous les fichiers compilés d'une langue en bas de sa page PHP-Gettext-Edit.</p>
		
		<a name="report-bug"></a><h2>Reporter un bug</h2>
		<p>N'hésitez pas à reporter un bug ou à suggerer une fonctionnalité sur le 
		<a href="http://tasks.d-sites.com/projects/show/gettextedit">gestionnaire de bug de PHP-GetText-Edit</a> ou 
		<a href="http://www.d-sites.com/a-propos/contact/">contactez-moi</a> par mail pour toute information supplémentaire.</p>
		
		<a name="languages_codes"></a><h2>Tableau des codes de langues</h2>
		<p>Voici dans le tableau ci-dessous quelques codes de pays et leurs langues:</p>
		<table class="languages">
			<tr>
				<th>Langue</th>
				<th>Code</th>
			</tr>
			<tr>
				<td>Français</td>
				<td><code>fr_FR</code></td>
			</tr>
		</table>
	</div>
</div>