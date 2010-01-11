<?php
require_once ROOT_PATH.'includes/classes/Project_File.php';

class Project_Language_File extends Project_File
{
	// Warnings const
	const W_UPDATE = 1;
	const W_COMPILE = 2;
	const W_COMPILE_JSON = 3;
	
	/**
	 * List of possible compiled files' extensions.
	 * 
	 * @var array
	 */
	private $possible_compiled_file_extension = array(
		'mo', 'json'
	);
	
	// Vars
	private $language;
	private $name;
	
	/**
	 * Constructeur.
	 * 
	 * @param Project_Language $language
	 * @param string $name
	 * 
	 * @return Project_Language_File
	 */
	public function __construct ($language, $name)
	{
		$this->language = $language;
		$this->name = $name;
		
		$this->file_path = $this->language->directory_path.$this->name.'.po';
	}	
	
	/**
	 * Return the name of the language.
	 * 
	 * @return string
	 */
	public function getName ()
	{
		return $this->name;
	}
	
	/**
	 * Create a new .po file.
	 * 
	 * @param Project_Language 	$language
	 * @param string			$name
	 * @param Project_Template	$template
	 * 
	 * @return Project_Language_File
	 */
	static function create ($language, $name, $template)
	{
		if (empty($template)) {
			throw new Project_Language_Exception(
				_('Pour initialiser un fichier .po, il faut un template')
			);
		} else if (is_string($template)) {
			$template = new Project_Template($language->project, $template);
		} else if (is_object($template)) {
			if (get_class($template) == 'Project_Template') {
				// Perfect!
			} else {
				throw new Project_Language_Exception(
					sprintf(_('Type de template inconnu: %s'), get_class($template))
				);
			}
		}
		
		$directory_path = $language->directory_path.$template->getType().'/';
		if (!is_dir($directory_path)) {
			if (!mkdir($directory_path)) {
				throw new Project_Language_Exception(
					sprintf(_('Impossible de créer le dossier du type: %s'), $directory_path)
				);
			}
		}
		
		$file_path = $directory_path.$name.'.po';
		
		if (is_file($file_path)) {
			throw new Project_Language_Exception(
				_('Le fichier existe déjà')
			);
		} else if (file_put_contents($file_path, '') === false) {
			throw new Project_Language_Exception(
				sprintf(_('Impossible d\'écrire le fichier: %s'), $file_path)
			);
		}
		
		if (!empty($template)) {
			$command = 'msginit --input="'.$template->file_path.'" --output-file="'.$file_path.'" --no-translator --locale="fr_FR"';
			// --locale="'.$language->getCode().'"
			// Don't specifiy the locale of the new .po file because if locale is en_US, it copies msgid into
			// msgstr, but not on others...
			$exec_result = exec($command);
			//var_dump($command, $exec_result);
		}
		
		$language_file = new Project_Language_File($language, $template->getType().'/'.$name);
		$language_file_headers = $language_file->getHeaders();
		$language_file_headers['GetTextEdit-template'] = $template->getName();
		unset( // remove template headers
			$language_file_headers['GetTextEdit-language'],
			$language_file_headers['GetTextEdit-encoding'],
			$language_file_headers['GetTextEdit-keywords'],
			$language_file_headers['GetTextEdit-search-files'],
			$language_file_headers['GetTextEdit-files']
		);
		$language_file->setHeaders($language_file_headers);
		
		return $language_file;
	}
	
	/**
	 * Update file from its template.
	 * 
	 * @return void
	 */
	public function update ()
	{
		$template = $this->getTemplate();
		
		$command = 'msgmerge '.
			'--update '.
			'--sort-output '.
			'--quiet '.
			'"'.$this->file_path.'" "'.$template->file_path.'"';
		$exec_result = exec($command);
		
		$headers = $this->getHeaders();
		$headers['GetTextEdit-updated'] = time();
		$this->setHeaders($headers);
		//var_dump($command, $exec_result);
		
		return $this->file_path;
	}
	
	/**
	 * Compile the .po file into an .mo file.
	 * 
	 * @param string $type		Type of compilation: normal/json
	 * @param bool   $use_fuzzy
	 * 
	 * @return string $output_file_path
	 */
	public function compile ($type = 'normal', $use_fuzzy = false)
	{
		if ($type == 'json') {
			$output_file_path = $this->toJSON($use_fuzzy);
		} else if ($type == 'normal') {
			$output_file_path = substr($this->file_path, 0, -2).'mo'; // Remplace .po by .mo
			$command = 'msgfmt '.
				($use_fuzzy ? '--use-fuzzy ' : '').
				'--output-file="'.$output_file_path.'" '.
				'"'.$this->file_path.'"';
			$exec_result = exec($command);
			
			if (is_file($output_file_path)) {
				$headers = $this->getHeaders();
				$headers['GetTextEdit-compiled'] = time();
				$this->setHeaders($headers);
			} else {
				throw new Project_Language_File_Exception(
					sprintf(_('La compilation vers le fichier "%s" a échoué.'), $output_file_path)
				);
			}
		} else {
			throw new Project_Language_File_Exception(
				sprintf(_('Type de compilation "%s" inconnu.'), $type)
			);
		}
		
		if (is_file($this->file_path.'~')) {
			unlink($this->file_path.'~');
		}
		
		return $output_file_path;
	}
	
	/**
	 * Transform a .po file into a JSON file.
	 * 
	 * @param bool $use_fuzzy
	 * 
	 * @return string $output_file_path
	 */
	public function toJSON ($use_fuzzy = false)
	{
		$json_string = array();
		$json_file_path = substr($this->file_path, 0, -2).'json';
		$messages = $this->getMessages();
		
		foreach ($messages as $msgid => $message_informations) {
			if ($use_fuzzy || !$message_informations['fuzzy']) {
				$json_strings[] = '"'.
					str_replace('"', '\\"', $msgid).
					'": "'.
					str_replace('"', '\\"', $message_informations['msgstr']).
					'"';
			}
		}
		
		$puts = file_put_contents(
			$json_file_path,
			'{'.implode(',', $json_strings).'}'
		);
		
		if ($puts === false) {
			throw new Project_Language_File_Exception(
				sprintf(_('Impossible d\'écrire dans le fichier "%s".'), $json_file_path)
			);
		} else if (!is_file($json_file_path)) {
			throw new Project_Language_File_Exception(
				sprintf(_('La compilation en JSON vers le fichier "%s" a échoué.'), $json_file_path)
			);
		} else {
			$headers = $this->getHeaders();
			$headers['GetTextEdit-compiledJSON'] = time();
			$this->setHeaders($headers);
			
			return $json_file_path;
		}
	}
	
	/**
	 * Delete the file.
	 * 
	 * @return bool
	 */
	public function delete ()
	{
		if (!unlink($this->file_path)) {
			throw new Project_Language_File_Exception(
				sprintf(_('Impossible de supprimer le fichier: %s'), $this->file_path)
			);
		} else {
			return true;
		}
	}
	
	/**
	 * Return the name of the file template
	 * 
	 * @return string
	 */
	public function getTemplateName ()
	{
		$template = $this->getTemplate();
		return $template->getName();
	}
	
	/**
	 * Return the template instance of file.
	 * 
	 * @return Project_Template
	 */
	public function getTemplate ()
	{
		$headers = $this->getHeaders();
		
		if (!array_key_exists('GetTextEdit-template', $headers)) {
			throw new Project_Language_Exception(
				_('Impossible d\'identifier le template de ce fichier')
			);
		} else {
			return new Project_Template(
				$this->language->project,
				trim($headers['GetTextEdit-template'])
			);
		}
	}
	
	/**
	 * Return warnings.
	 * 
	 * @return array
	 */
	public function getWarnings ()
	{
		$warnings = array();
		
		$headers = $this->getHeaders();
		$template = $this->getTemplate();
		$template_headers = $template->getHeaders();

		if (!array_key_exists('GetTextEdit-updated', $headers) OR
			(int) $template_headers['GetTextEdit-updated'] > (int) $headers['GetTextEdit-updated']) {
			$warnings[] = self::W_UPDATE;
		}
		if (!array_key_exists('GetTextEdit-compiled', $headers) ||
			(int) $headers['GetTextEdit-edited'] > (int) $headers['GetTextEdit-compiled']) {
			$warnings[] = self::W_COMPILE;
		}
		if (array_key_exists('GetTextEdit-compiledJSON', $headers) &&
			(int) $headers['GetTextEdit-edited'] > (int) $headers['GetTextEdit-compiledJSON']) {
			$warnings[] = self::W_COMPILE_JSON;
		}
		
		return $warnings;
	}
	
	/**
	 * Return a list of files which have the same name but with an
	 * extension .mo or .json
	 * 
	 * @return array
	 */
	public function getCompiledFiles ()
	{
		$result = array();
		$vierge_file_path = substr($this->file_path, 0, -2);
		
		foreach ($this->possible_compiled_file_extension as $possible_extension) {
			$file = $vierge_file_path.$possible_extension;
			
			if (is_file($file)) {
				$result[] = $file;
			}
		}
		
		return $result;
	}
	
	/**
	 * Remove a key from an array.
	 * 
	 * @return array
	 */
	private function array_remove_key ()
	{
		$args = func_get_args();
		
		return array_diff_key(
			$args[0],
			array_flip(
				array_slice(
					$args,
					1
				)
			)
		);
	}
}

class Project_Language_File_Exception extends Exception {}
?>