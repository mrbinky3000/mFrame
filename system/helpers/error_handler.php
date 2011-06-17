<?php
/**
 * @package mFrame
 * @subpackage helpers
 */
/**
 * Convert PHP error's into Exception objects that can be caught.
 * 
 * @param integer $errno
 * @param string $errstr
 * @param string $errfile
 * @param integer $errline
 * @param array $errcontext
 * @return type 
 */
function e2e_handle_error($errno, $errstr, $errfile, $errline, array $errcontext)
{
	// error was suppressed with the @-operator
	if (0 === error_reporting()) {
		return false;
	}

	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);

}

// set_error_handler('e2e_handle_error');
