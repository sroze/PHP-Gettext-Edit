<?php
require_once ROOT_PATH.'includes/classes/Project_File.php';

class Project_Language_File extends Project_File
{
	// Warnings const
	const W_COMPILE = 1;
	const W_UPDATE = 2;
	
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
			$command = 'msginit --input="'.$template->file_path.'" --output-file="'.$file_path.'" --no-translator --locale="'.$language->getCode().'"';
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
	}
	
	/**
	 * Compile the .po file into an .mo file.
	 * 
	 * @param bool $use_fuzzy
	 * 
	 * @return string $output_file_path
	 */
	public function compile ($use_fuzzy = false)
	{
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
			
			return $output_file_path;
		} else {
			throw new Project_Language_File_Exception(
				sprintf(_('La compilation vers le fichier "%s" a échoué.'), $output_file_path)
			);
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

		if (!array_key_exists('GetTextEdit-updated', $language_file_headers) OR
			(int) $template_headers['GetTextEdit-updated'] > (int) $language_file_headers['GetTextEdit-updated']) {
			$warnings[] = self::W_UPDATE;
		}
		if (!array_key_exists('GetTextEdit-compiled', $language_file_headers) OR
		(int) $language_file_headers['GetTextEdit-edited'] > (int) $language_file_headers['GetTextEdit-compiled']) {
			$warnings[] = self::W_COMPILE;
		}
		
		return $warnings;
	}
}

class Project_Language_File_Exception extends Exception {}
?>