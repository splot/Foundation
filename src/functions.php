<?php
/**
 * Few global functions that SplotFoundation registers for ease of use.
 * 
 * @package SplotFoundation
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2013, Michał Dudek
 * @license MIT
 */

use Splot\Foundation\Debug\Debugger;
use Splot\Foundation\Debug\Logger;

/**
 * Friendly output of variables. It will print them out in <pre class="md-dump"> tag.
 * 
 * @param object $variable Variable to be dumped.
 * @param bool $toString [optional] Should return a string instead of echoing the output? Default: false.
 * @return string
 */
function dump($variable, $toString = false) {
	return Debugger::dump($variable, $toString);
}

/**
 * Log the specified message.
 * 
 * @param string $message Message to log.
 * @param string $category [optional] Category to put the item in.
 * @param array $additional [optional] Additional data in a form of array.
 * @param string $tags [optional] Tags for this log item as string, separated by comma.
 * @return null|bool Returns (bool) false if log is disabled.
 */
function log_message($message, $category = 'General', array $additional = array(), $tags = null) {
	return Logger::log($message, $category, $additional, $tags);
}


// a fix for apache_request_headers() if it's not available
if(!function_exists('apache_request_headers')) {
	/**
	 * Returns request headers.
	 * 
	 * @return array
	 */
	function apache_request_headers() {
		$headers = array();
		foreach($_SERVER as $key => $value) {
			if(substr($key, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
			}
		}
		return $headers;
	}
}