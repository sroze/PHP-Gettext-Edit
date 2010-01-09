<?php
require_once ROOT_PATH.'includes/librairies/File_INI.php';
require_once ROOT_PATH.'includes/classes/Database.php';

$config_ini = new File_INI(ROOT_PATH.'includes/configuration/configuration.ini');
$_CONFIG = $config_ini->read();

define('TEMPLATE_DIR', ROOT_PATH.'templates/'.$_CONFIG['template'].'/');

if (!is_dir(TEMPLATE_DIR)) {
	echo sprintf(_('Template %s doesn\'t exists'), $_CONFIG['template']);
}

define('PAGE_DIR', TEMPLATE_DIR.'pages/');

$sql = new Database(
	ROOT_PATH.'includes/configuration/gettextedit.db'
);

if (!isset($_COOKIE['GetTextEdit-language'])) {
	$langs = array();

	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
	    // break up string into pieces (languages and q factors)
	    preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
	
	    if (count($lang_parse[1])) {
	        // create a list like "en" => 0.8
	        $langs = array_combine($lang_parse[1], $lang_parse[4]);
	    	
	        // set default to 1 for any without q factor
	        foreach ($langs as $lang => $val) {
	            if ($val === '') $langs[$lang] = 1;
	        }
	
	        // sort list based on value	
	        arsort($langs, SORT_NUMERIC);
	    }
	}
	
	// look through sorted list and use first one that matches our languages
	foreach ($langs as $lang => $val) {
		if (strpos($lang, 'fr') === 0) {
			define('LANGUAGE', 'fr_FR');
		} else if (strpos($lang, 'en') === 0) {
			define('LANGUAGE', 'en_US');
		} 
	}
	
	if (defined('LANGUAGE')) {
		setcookie('language', LANGUAGE, time()+3600*24*360);
	}
}

if (!defined('LANGUAGE')) {
	define('LANGUAGE', 'fr_FR');
}

setlocale(LC_ALL, LANGUAGE);
bindtextdomain('gettextedit', ROOT_PATH.'includes/locales');
textdomain('gettextedit');
?>