<?php
require_once ROOT_PATH.'includes/classes/Project_File.php';

class Project_Template extends Project_File
{
	/**
	 * Contient l'instance décrivant le projet.
	 * 
	 * @var Project
	 */
	private $project;
	
	// Simple variables
	private $name;
	
	/**
	 * Languages that user can chose for xgettext.
	 * 
	 * @var array
	 */
	static $available_languages = array(
		'PHP', 
		'C', 
		'C++', 
		'Shell', 
		'Python', 
		'Scheme', 
		'Java', 
		'C#', 
		'Perl', 
		'Glade'
	);
	
	/**
	 * Posible file encoding
	 * 
	 * @var array
	 */
	static $available_encoding = array(
		'UTF-8',
		'ISO-8859-1',
		'ASCII'
	);
	
	/**
	 * Files extensions that aren't considered while search for files
	 * with $this->getHighestFileTimestamp
	 * 
	 * @var array
	 */
	private $forbidden_file_extensions = array(
		'po', 'mo', 'pot'
	);
	
	/**
	 * Constructeur.
	 * 
	 * @param Project $project
	 * @param string  $name
	 * 
	 * @return Project_Template
	 */
	public function __construct ($project, $name)
	{
		$this->project = $project;
		$this->name = $name;
		
		$this->file_path = $this->project->get('project_path').$this->project->get('project_languages_path').$this->name.'.pot';
	}
	
	/**
	 * Retourne le nom du template.
	 * 
	 * @return string
	 */
	public function getName ()
	{
		return $this->name;
	}
	
	/**
	 * Get the template type.
	 * 
	 * @return string
	 */
	public function getType ()
	{
		$headers = $this->getHeaders();	
		return $headers['GetTextEdit-type'];
	}
	
	/**
	 * Create a new template.
	 * @param Project $project
	 * @param string  $name 			Name of the template, likes messages
	 * @param string  $type 			Type of template, like LC_MESSAGES
	 * @param string  $language 		In what code, like PHP, C, C++...
	 * @param array   $keywords 		Additionnal keywords
	 * @param array	  $search_files		What type of files we want to search, array like ('*.php', '*.js')
	 * @param array   $files			Files and directories to scan (cleaned by File::cleanTree please)
	 * @param string  $encoding			How files are encoded ?
	 * @param bool    $delete_old		Delete, or not, entries that aren't still used
	 * 
	 * @return Project_Template
	 */
	static function create ($project, $name, $type, $language, $keywords = null, $search_files = array('*.php'), $files = null, $encoding = 'UTF-8', $delete_old = false)
	{
		$template = new Project_Template($project, $name);
		
		if (file_put_contents($template->file_path, '') === false) {
			throw new Project_Template_Exception(
				sprintf(_('Impossible d\'écrire dans le nouveau fichier (%s)'), $template->file_path)
			);
		}
		
		// Now, we'll store configuration in file
		$template->edit(
			$type,
			$language,
			$encoding,
			$keywords,
			$search_files,
			$files
		);
		
		// Generate the file
		$template->update($delete_old);
		
		if (!$template->check()) {
			throw new Project_Template_Exception(
				_('Une erreur inconnue est arrivée')
			);
		} else {
			return $template;
		}
	}

	/**
	 * Re-génère le template
	 * 
	 * @param bool    $delete_old		Delete, or not, entries that aren't still used
	 * 
	 * @return void
	 */
	public function update ($delete_old = false)
	{
		$file_root = $this->project->get('project_path');
		$headers = $this->getHeaders();
		
		$language = $headers['GetTextEdit-language'];
		$encoding = $headers['GetTextEdit-encoding'];
		if (array_key_exists('GetTextEdit-keywords', $headers)) {
			$keywords = explode(',', $headers['GetTextEdit-keywords']);
		}
		if (array_key_exists('GetTextEdit-search-files', $headers)) {
			$search_files = explode(',', $headers['GetTextEdit-search-files']);
		}
		if (array_key_exists('GetTextEdit-files', $headers)) {
			$files = unserialize($headers['GetTextEdit-files']);
		}
		
		if (!in_array($language, self::$available_languages)) {
			throw new Project_Template_Exception(
				_('Le language de programmation n\'est pas valide')
			);
		} else if (!in_array($encoding, self::$available_encoding)) {
			throw new Project_Template_Exception(
				_('L\'encodage n\'est pas valide')
			);
		}
		
		$keywords_string = '';
		if (!empty($keywords)) {
			foreach ($keywords as $keyword) {
				if (trim($keyword) == '') {
					continue;
				}
				$keywords_string .= '--keyword="'.$keyword.'" ';
			}
		}
	
		if (!empty($search_files)) {
			$search_string = '\\( ';
			for ($i = 0; $i < count($search_files); $i++) {
				$search_files[$i] = '-iname "'.trim($search_files[$i]).'"';
			}
			$search_string .= implode(' -o ', $search_files);
			$search_string .= ' \\)';
		} else {
			$search_string = '"*"';
		}
		
		$xgettext_command = 'xgettext '.
				'--force-po '.
				'--add-location '.
				'--sort-output '.
				'--join-existing '.
				'--language="'.$language.'" '.
				'--from-code="'.$encoding.'" '.
				'--output="'.$this->file_path.'" ';
		
		if (substr($file_root, -1) == '/') {
			$file_root = substr($file_root, 0, -1);
		}
		
		if (!empty($files)) {
		
			if (false === file_put_contents($this->file_path, '')) { // Clear file
				throw new Project_Template_Exception(
					_('Impossible de nettoyer le fichier template')
				);
			}
		
			foreach ($files as $file) {
				if (substr($file, 0, 1) != '/') {
					$file = '/'.$file;
				}
				
				if (substr($file, -1) == '/') { // directory
					$command = 'find '.$file_root.$file.' -type f '.$search_string.' | '.$xgettext_command.'-f -';
				} else { // file
					$command = $xgettext_command.'"'.$file_root.$file.'"';
				}
				$exec_result = exec($command);
				//var_dump($command, $exec_result);
			}
		} else {
			throw new Project_Template_Exception(
				_('Aucun fichier/dossier séléctionné')
			);
		}
		
		$headers['GetTextEdit-updated'] = time();
		
		$this->setHeaders(
			array_merge(
				$this->getHeaders(),
				$headers
			)
		);
	}
	
	/**
	 * Edit template informations/configuration
	 * 
	 * @param string  $type
	 * @param string  $language 		In what code, like PHP, C, C++...
	 * @param array   $keywords 		Additionnal keywords
	 * @param array	  $search_files		What type of files we want to search, array like ('*.php', '*.js')
	 * @param array   $files			Files and directories to scan (cleaned by File::cleanTree please)
	 * @param string  $encoding			How files are encoded ?
	 * 
	 * @return bool
	 */
	public function edit ($type, $language, $encoding, $keywords = null, $search_files = null, $files = null)
	{
		$headers = $this->getHeaders();
		$headers['GetTextEdit-type'] = $type;
		$headers['GetTextEdit-language'] = $language;
		$headers['GetTextEdit-encoding'] = $encoding;
		if (!empty($keywords)) {
			$headers['GetTextEdit-keywords'] = implode(',', $keywords);
		}
		if (!empty($search_files)) {
			$headers['GetTextEdit-search-files'] = implode(',', $search_files);
		}
		if (!empty($files)) {
			$headers['GetTextEdit-files'] = serialize($files);
		}
		
		$headers['GetTextEdit-edited'] = time();
		
		return $this->setHeaders($headers);
	}
	
	/**
	 * Delete template.
	 * 
	 * @return bool
	 */
	public function delete ()
	{
		return unlink($this->file_path);
	}
	
	/**
	 * Return the timestamp when the last edited file was edited
	 * 
	 * @return integer
	 */
	public function getLastEditedFileTimestamp ()
	{
		return $this->getHighestFileTimestamp(
			$this->project->get('project_path')
		);
	}
	
	/**
	 * Get the timestamp of the last edited file in a
	 * directory.
	 * 
	 * @param string $directory
	 * 
	 * @return integer
	 */
	private function getHighestFileTimestamp($directory) {
		$highest = 0;
		$highestKnown = 0;
		$handle = opendir($directory);
	     
	    while ($datei = readdir($handle)) {
	    	if (($datei != '.') && ($datei != '..')) {
	        	$file = $directory.$datei;
	        	if (is_dir($file)) {
	            	$highest = $this->getHighestFileTimestamp($file.'/');
	            } else { // file
	            	$file_x = explode('.', $file);
	            	if (!in_array($file_x[count($file_x)-1], $this->forbidden_file_extensions)) {
	                	$highest = filemtime($file);
	            	}
	            }
	            
	            if ($highest > $highestKnown) {
	            	var_dump($file);
	     			$highestKnown = $highest;
	        	}
	    	}
	    }
	    
	    return $highestKnown;
	}
}

class Project_Template_Exception extends Exception {}
?>