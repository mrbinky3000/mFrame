<?php
/**
 * @package mFrame
 */
/**
 * Controller for the mFrame example. You may want to delete this file.
 * 
 * Keeps all the view templates variables and logic a single name space and out 
 * of the global name space so there is no chance of collisions.
 * 
 * @subpackage controllers
 * @author Matthew Toledo <matthew.toledo@g####.com>
 */
class exampleController
{
	
	// -------------------------------------------------------------------------
	// Static variables used by the view.
	// -------------------------------------------------------------------------
	public static $s_today_date;
	public static $s_another_example;	
	// -------------------------------------------------------------------------
	

	/**
	 * Class constructor 
	 * 
	 * - Set all view's variables.  
	 * - Handle the view's form actions.
	 * @return void
	 */
	public function __construct()
	{

		Session_Master::init();
		Session_Master::set_name_space('example_c');
		
		// handle form submit
		if (fp('submit')) 
		{

			Session_Master::import($_POST, array(
				'login'
			));
			
			
			$o = new Form_Validation();
			$o->set_rules('login', "Login", 'required|trim|xss');
			$o->set_rules('password', "Password", 'required|trim|xss');

			
			if (TRUE == $o->run()) 
			{
				
				// PRG pattern
				header('Location: /example_loggedin.php',true,303);

			}

		}
		// first time viewing quote form
		else
		{
			// load previous form state (if any)
			Session_Master::export_to_superglobal(array(
				'login'
			));
		}
		
		///////////////////////////////////
		// Initialize the view
		///////////////////////////////////
		
		self::$s_today_date = date("m/d/Y");
		self::$s_another_example = "Hello!";
		
	}
	
}


