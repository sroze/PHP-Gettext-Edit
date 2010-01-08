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
			<li><a href="#php-gettext-edit">Traduire un projet avec PHP-Gettext-Edit</li>
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
		<ul>
			<li>La création de modèles</li>
			<li>La création de langues</li>
			<li>La création de fichiers de traduction</li>
		</ul>
		
		<a name="report-bug"></a><h2>Reporter un bug</h2>
		<p>N'hésitez pas à reporter un bug ou à suggerer une fonctionnalité sur le 
		<a href="http://tasks.d-sites.com/projects/show/gettextedit">gestionnaire de bug de PHP-GetText-Edit</a> ou 
		<a href="http://www.d-sites.com/a-propos/contact/">contactez-moi</a> par mail pour toute information supplémentaire.</p>
		
		<a name="languages_codes"></a><h2>Tableau des codes de langues</h2>
		<p>Voici dans le tableau ci-dessous quelques codes de pays et leurs langues:</p>
		<table class="languages">
			<th>
				<td>Langue</td>
				<td>Code</td>
			</th>
			<tr>
				<td>Français</td>
				<td><code>fr_FR</code></td>
			</tr>
		</table>
	</div>
</div>