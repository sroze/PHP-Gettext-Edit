<?php
/**
 * Cette librairie permet de convertir des chaines en UTF-8
 * 
 * @author Samuel ROZE <samuel.roze@gmail.com>
 */
class utf8
{
	/**
	 * Encode une chaine en UTF-8.
	 * 
	 * @param string $string
	 * 
	 * @return string
	 */
	static function encode ($string) {
		return utf8_encode($string);
	}
	
	/**
	 * Vérifie qu'une chaine de caractère est encodée en UTF-8.
	 * 
	 * @param string $string
	 * 
	 * @return boolean
	 */
	static function is ($string) {
	    return (utf8_encode(utf8_decode($string)) == $string);
	}
	
	/**
	 * Transforme une chaine de caractère, si elle ne l'est pas déjà, 
	 * en UTF-8.
	 * 
	 * @param string $string
	 * 
	 * @return string
	 */
	static function parse ($string) {
		if (!utf8::is($string)) {
			return utf8::encode($string);
		}
		else {
			return $string;
		}
	}
}

?>