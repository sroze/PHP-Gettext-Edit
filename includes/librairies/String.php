<?php
require_once 'utf8.php';

/**
 * Cette classe permet de vérifier ou d'appliquer des filtre sur nombre de 
 * données.
 * 
 * @author Samuel ROZE <samuel.roze@gmail.com>
 */
class String
{
	
	const REGEX_HOSTNAME = '#^([a-z0-9_\.-]+)\.([a-z]{2,4})(/~?([a-z0-9_/-]+))?$#i';
	const REGEX_BASE64 = '#^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?$#';
	const REGEX_INTEGER = '#^([0-9]+)$#';
	const REGEX_EMAIL = '#^([a-z0-9\._-]+)@([a-z0-9-_\.]+)\.([a-z]{2,4})#';
	const REGEX_USERNAME = '#^([a-z0-9_\.-]+)$#i';
	const REGEX_IPV4 = '#^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$#';
	const REGEX_STRICT_STRING = '#^[a-z]+$#i';
	const REGEX_STRING = '#^[a-z0-9_\.-]+$#i';
	const REGEX_PASSWORD = '#^[a-z0-9/\\\(\)@!\?\.-]+$#i';
	const REGEX_URL = '#https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?#i';
	
	/**
	 * Génère une chaine de caractère aléatoire.
	 * 
	 * @param integer $string_length
	 * @param boolean $tiret
	 * 
	 * @return string
	 */
	static function random ($string_length, $tiret = true) {
		$chaine = 'abcdefghijklmnpqrstuvwxy01234567898';
		if ($tiret) {
			$chaine .= '-';
		}
		srand((double)microtime()*1000000);
		//---
		$string = '';
		for($i = 0 ; $i < $string_length; $i++) {
			$string .= $chaine[rand()%strlen($chaine)];
		}
		return $string;
	}
	
	/**
	 * Crypte une chaine de caractères.
	 * 
	 * @param string $string
	 * 
	 * @return string
	 */
	static function crypt ($string) {
		return sha1($string);
	}
	
	/**
	 * DEPRECATED: Analyse et parse une chaine de caractère venant de l'extérieur.
	 * 
	 * @param string  $string
	 * @param boolean $toHTML
	 * 
	 * @return string
	 */
	static public function external ($string, $toHTML = false) {
		$string = urldecode($string);
		$string = str_replace('<', '&lt;', $string);
		$string = str_replace('>', '&gt;', $string);
		if ($toHTML) {
			$string = str_replace('\'', '&acute;', $string);
			$string = str_replace('"', '&quot;', $string);
		}
		else if (stripslashes($string) == $string) {
			$string = addslashes($string);
		}
		$string = utf8::parse($string);
		
		return $string;
	}
	
	static function is ($string) {
	    return preg_match(self::REGEX_STRING, $string);
	}
	
	static function is_hostname ($string) {
		return preg_match(String::REGEX_HOSTNAME, $string);
	}
	
	static function is_base64 ($string) {
		return preg_match(String::REGEX_BASE64, $string);
	}
	
	static function is_int ($string) {
		return preg_match(String::REGEX_INTEGER, $string);
	}
	
	static function is_email ($string) {
		return preg_match(String::REGEX_EMAIL, $string);
	}
	
	static function is_username ($string) {
		return preg_match(String::REGEX_USERNAME, $string);
	}
	
    static function is_ipv4 ($ip) {
        return preg_match(self::REGEX_IPV4, $ip);
    }

    static function is_strict ($string) {
        return preg_match(self::REGEX_STRICT_STRING, $string);
    }
    
    static function is_password ($string) {
        return preg_match(self::REGEX_PASSWORD, $string);
    }
    
    static function is_url ($string) {
        return preg_match(self::REGEX_URL, $string);
    }
	
}