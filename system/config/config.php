<?php
/**
 * Configuration File for mFrame anti-framework
 * 
 * Set up a simple PHP environment.
 *
 * @package mFrame
 * @author Matthew Toledo
 */

//-----------------------------------------------------------------------------
// System and Plugin Configuration Options
//-----------------------------------------------------------------------------

// setup debugging output
require_once(BASE_DIR.'/plugins/FirePHPCore/fb.php');

// uncomment next line to force debugging on or off, even on the production server
// define('DEBUG', TRUE);


if (!defined(DEBUG))
{
	// If this is the development server, I turn on FirePHPCore.  I always set up 
	// my local developement server to use virtual hosts and have them all end in 
	// ".local" Feel free to change how you determine the server is the dev server.
	if (preg_match('/\.local/',$_SERVER['HTTP_HOST'])) 
	{	
		define('DEBUG',TRUE);

	}
	else
	{
		define('DEBUG',FALSE);
	}
}

if (DEBUG)
{
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	error_reporting(E_ALL);
	FB::setEnabled(TRUE)
}
else
{ 
	ini_set('display_errors', 0);
	ini_set('log_errors', 0);
	error_reporting(E_NONE);
	FB::setEnabled(FALSE);
}

// default language must have a folder in the language directory
define('LANGUAGE', 'english');

// if FALSE, Auth won't use SOAP. (rare to do so anyhow)
define('AUTH_SOAP_CLIENT',FALSE); 


// Use the Authorize.Net sandbox when in debug mode
if (DEBUG)
{
	define('AUTH_NET_API_LOGIN_ID', 'ADDYOURAPIKEY');
	define('AUTH_NET_API_TRANSACTION_KEY', 'ADDYOURTRANSACTIONKEY');
	define('AUTH_NET_SANDBOX',TRUE); // True means we don't charge cards, false means we do. Kinda backwards, but that's how their SDK does it.
}
// Send transactions to the payment gateway for processing when debug is off.
else
{
	define('AUTH_NET_API_LOGIN_ID', 'ADDYOURAPILOGIN');
	define('AUTH_NET_API_TRANSACTION_KEY', 'ADDYOURAPITRANSKEY');
	define('AUTH_NET_SANDBOX',FALSE); // False means we REALLY charge the cards. 	
}


//-----------------------------------------------------------------------------
// Begin User Defined Config Options Below
//-----------------------------------------------------------------------------

// location of root of PEAR repository. No trailing slash. Used by autoloader to search for PEAR packages.
define('PEAR_PATH','FULL-PATH-TO-PEAR'); 

// set the money locale
setlocale(LC_MONETARY, 'en_US');
