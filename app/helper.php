<?php

/**
 * Redirects user to specified page...can be called before or after session_start
 * @author Scott Dover <sdover102@me.com>
 * @param str
 * @return void
 */
function safe_redirect($redirect) {
	if (session_id() == '') {
		header('Location:' . $redirect);
	} else {
		echo '<script type="text/javascript">window.location.href="' . $redirect .'";</script>';
	}
	die();
}

/**
 * Adopted from http://stackoverflow.com/questions/5845732/clean-urls-and-database
 * @author Scott Dover <sdover102@me.com>
 * @param str
 * @return str
 */
function slug($string) {
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}