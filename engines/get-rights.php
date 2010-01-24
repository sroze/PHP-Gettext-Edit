<?php
/**
 * Retourne le status des droits demandés. C'est-à-dire que l'application
 * PHP-Gettext-Edit va lui envoyer une liste de droits et /engines/get-rights.pĥp
 * va retourner une liste de la forme:
 * array(
 * 	array(
 * 		'name' => nom du droit,
 * 		'id' => id du droit,
 * 		'grant' => true/false,
 * 		'from' => 'user'|'group'|''
 * 	)
 * ...
 * )
 */

