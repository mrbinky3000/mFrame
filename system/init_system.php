<?php
/**
 * Load Required PHP / MySQL Resources
 * 
 * This is mini PHP "anti-framework"
 * 
 * @package mFrame
 * @author Matthew Toledo
 */

// Immediately initialize PHP session variables.
session_start();

// Then, start output buffering so that we can make header calls later if we 
// want without messing up the PHP session.
ob_start();

// this is the location of the system folder
define('BASE_DIR', dirname(__FILE__).'/system');

// pre-load required resources
require_once(BASE_DIR.'/config/config.php');
require_once(BASE_DIR.'/helpers/common.php');
require_once(BASE_DIR.'/helpers/error_handler.php');

// Add multiple search paths where the autoloader can look for classes. No trailing slashes.
$a_paths = array(
	PEAR_PATH,
	BASE_DIR.'/classes'
);

$s_my_path = implode(PATH_SEPARATOR, $a_paths);

// tell PHP to scan the default include path AND your include path
set_include_path(get_include_path() . PATH_SEPARATOR . $s_my_path);

// autoload other resources from the classes folder and from PEAR library
function __autoload($s_class_name)
{


	// name your classes and filenames with underscores, i.e., 
	// Class Html_Select stored in /classes/Html
	// Class Model_Registry stored in /classes/Model 
	$s_classfile = str_replace("_", "/", $s_class_name) . ".php";
	
	require_once($s_classfile);
}

// load the language file
require_once(BASE_DIR.'/languages/'.LANGUAGE.'/lang.php');


// Attempt to make a connection to the database.
// TODO: send an email if the database is down.
try
{
	MattSQL::init()
		->newConnection('HOST', 'USERNAME', 'PASSWORD', 'DATABASE')
		->setMode('HASH');
}
catch(Exception $o_exception)
{
	die("I encountered a problem while establishing a connection to the database.");
}
