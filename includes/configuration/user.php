<?php
// We need session
session_start();

if (!empty($_SESSION['user_informations'])) {
	$_USER = new User($_SESSION['user_informations']);
} else if (!empty($_COOKIE['user'])) {
	$_USER = User::fromCookie($_COOKIE['user']);
}

if (!defined('CONNECTED')) {
	define('CONNECTED', false);
}
if (Rights::$user_id == 0) {
	Rights::setUser((int) $_CONFIG['anonymous_id']);
}
?>