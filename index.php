<?php
/**
 * mFrame
 * 
 * Framework loader - a single point of access for the framework.
 * 
 * The plan:
 * - Load constants
 * - Start output bufering with ob_start()
 * - Start sessions with session_start()
 * - Set up our own means of handling PHP errors.
 * - Set up our own means of catching uncaught exceptions.
 * 
 * @package mFrame
 * @version 0.1
 * @author Matthew Toledo <matthew.toledo@gmail.com>, Michael Peacock.
 */

session_start();
ob_start();

/**
 * The application root path.
 */
define('APP_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

/**
 * Simple security method to prevent direct linking from other scripts outside
 * the framework
 */
define('MFRAME', TRUE);


/**
 * Magically autoload controller classes
 * 
 * Used to automagically include the approprate controller file when they are 
 * needed.
 * @param string $s_class_name 
 */
function __autoload($s_class_name)
{
	require_once('system/controllers/' . $s_class_name . '/' . $s_class_name . '.php');
}

// require our registry
require_once('system/core/registry.class.php');
$o_registry = MF_registry::singleton();

print $o_registry->get_version();

exit();