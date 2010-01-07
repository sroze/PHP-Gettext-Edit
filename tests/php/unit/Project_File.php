<?php
define('ROOT_PATH', realpath(dirname(__FILE__)).'/');
require_once 'Aspects/Errors/UnitTest.php';
$unittest = new UnitTest();

require_once '../../../includes/classes/Project_File.php';

$file_path = ROOT_PATH.'file_test.po';
$file_contents = <<<EOF
msgid ""
msgstr ""
"Content-Type: text/plain; charset=UTF-8\n"
"Last-Translator: Samuel ROZE <samuel.roze@gmail.com>\n"

msgid "test2"
msgstr "translation2"

# Welcome
#:file.php:12
msgid "test"
msgstr "translation"

msgid "longue, très longue traduction"
msgstr ""
"Ça c'est du jamais vu!"
"Parce que elle est très très longur"
"voir plus..."
EOF;
$puts = file_put_contents(
	$file_path,
	$file_contents
);
$unittest->setTestTitle('Initialisation du fichier .po');
$unittest->mustBeEqual($puts, strlen($file_contents));

class Project_File_Test extends Project_File {
	public function __construct ($file_path) {
		$this->file_path = $file_path;
	}
}
$file = new Project_File_Test($file_path);

$unittest->setTestTitle('getHeaders');
$unittest->mustBeEqual($file->getHeaders(), array(
	'Content-Type' => 'text/plain; charset=UTF-8',
	'Last-Translator' => 'Samuel ROZE <samuel.roze@gmail.com>'
));


echo '<pre>';
print_r(file($file_path));
echo '</pre>';

$unittest->setTestTitle('setHeaders');
$file->setHeaders(array(
	'Content-Type' => 'text/plain; charset=UTF-8',
	'Edited-width' => 'Project_File TEST'
));
$unittest->mustBeEqual($file->getHeaders(), array(
	'Content-Type' => 'text/plain; charset=UTF-8',
	'Edited-width' => 'Project_File TEST'
));


echo '<pre>';
print_r(file($file_path));
echo '</pre>';

$unittest->setTestTitle('getMessages');
$unittest->mustBeEqual($file->getMessages(), array(
	'test2' => array(
		'msgstr' => 'translation2',
		'references' => array(),
		'fuzzy' => false,
		'comments' => ''
	),
	'test' => array(
		'msgstr' => 'translation',
		'references' => array(
			'file.php:12'
		),
		'fuzzy' => false,
		'comments' => 'Welcome'
	),	
	'longue, très longue traduction' => array(
		'msgstr' => 'Ça c\'est du jamais vu!
Parce que elle est très très longur
voir plus...',
		'references' => array(),
		'fuzzy' => false,
		'comments' => ''
	)
));


$unittest->setTestTitle('editMessage');

echo '<pre>';
print_r(file($file_path));
echo '</pre>';
$file->editMessage('test', 'translation3');
$unittest->mustBeEqual($file->getMessages(), array(
	'test2' => array(
		'msgstr' => 'translation2',
		'references' => array(),
		'fuzzy' => false,
		'comments' => ''
	),
	'test' => array(
		'msgstr' => 'translation3',
		'references' => array(
			'file.php:12'
		),
		'fuzzy' => false,
		'comments' => 'Welcome'
	),	
	'longue, très longue traduction' => array(
		'msgstr' => 'Ça c\'est du jamais vu!
Parce que elle est très très longur
voir plus...',
		'references' => array(),
		'fuzzy' => false,
		'comments' => ''
	)
));
echo '<pre>';
print_r(file($file_path));
echo '</pre>';

$unittest->setTestTitle('editMessage (delete)');
$file->editMessage('test2', false);
$unittest->mustBeEqual($file->getMessages(), array(
	'test' => array(
		'msgstr' => 'translation3',
		'references' => array(
			'file.php:12'
		),
		'fuzzy' => false,
		'comments' => 'Welcome'
	),	
	'longue, très longue traduction' => array(
		'msgstr' => 'Ça c\'est du jamais vu!
Parce que elle est très très longur
voir plus...',
		'references' => array(),
		'fuzzy' => false,
		'comments' => ''
	)
));

