#mFrame

mFrame is an "anti" MVC PHP Framework.   Every other PHP framework out there (as far as I know) uses a central core script paired with server-side redirection to make it look as if there are different URLS when, in actuality, everything goes through the core.  Models, views and partials are pulled into a controller and the core outputs the final HTML.

mFrame is different in that the "view" portion takes precedence and there is no need for the web server to perform redirection. Every view is it's own traditional, old-school php file. It is well suited to retro-fitting old sites.  It is also allows folks who deal with graphic designers or hands-on clients to approach the front end in a more traditional manner--and by "traditional" I mean "super old school".  Basically, your designers and clients can create and edit web pages just like they did back in the day, without having to wrap their head around traditional MVC concepts.  They can just ignore the PHP bits as they go about editing CSS and JavaScript.

Is this as efficient as a traditional MVC?  Almost.  Is it easier for the less savvy to understand and edit?  Arguably so.

##How To Use
1. Create a stand-alone PHP page just like you did back when everyone partied like it was 1999.  This is now the view.
2. Include the mFrame core and a controller class at the top of this page before anything else.
3. The mFrame core calls a controller, model, and other resources as needed.
4. Each page controller is a PHP5 class.  Output is stored in static class properties of the controller.
5. Stick the class properties into the stand-alone page ("the view") where needed.

##Requires
- PHP5.3 (Needed for late static binding and autoloading)
- Apache 


##EXAMPLE

###The "View"
Here is your standard, Dreamweaver-or-whatever-friendly PHP page.  Front-end developers will like it because the whole DOM is in one spot and they can write and debug JavaScript easily.  Some clients will like it because they can open this file in their editor and tweak it without having to understand MVC frameworks, they just see something very familiar with minimal PHP.

```php
<?php

// init and start up the mFrame core
require_once('system/init_system.php');

// call the controller for this page
require_once('system/controllers/index_c.php');
$o_index = new indexController();

// All the stuff below is the "view"
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>mFrame Example</title>
</head>
<body>
<p>mFrame Example - <?php echo indexController::$s_today_date ?></p>
<p><?php echo indexController::$s_another_example; ?> This is an example of a page previously designed without mFrame. I've stripped
	out all the usual PHP from this file, and moved it to a controller. Now, the
	only PHP in this file is used to display template variables.</p>
	<form id="form1" name="form1" method="post" action="">
		<table border="0" cellspacing="1" cellpadding="3">
			<tr>
				<td><label>Login</label></td>
				<td><input name="login" type="text" id="login" size="30" maxlength="64" value="<?php echo fp('login') ?>"/></td>
			</tr>
			<tr>
				<td><label>Password</label></td>
				<td><input name="password" type="password" id="password" size="30" maxlength="128" value="<?php echo fp('password') ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" id="submit" value="Submit" /></td>
			</tr>
		</table>
	</form>
	<p>Todo, improve this demo.</p>
</body>
</html>
```



###The Controller
Notice the static class properties.  See how we used them in the "view" above.  The constructor does
much of the heavy lifting.  The core makes dozens of helper objects, static methods, global functions, and
static properties available to your controller (I still need to document all these).

For instance, stuff like "Session_Master" is used to repopulate an HTML form after a PRG (post -> redirect -> get).  "fp()" is used to fetch a $_POST key and value without littering your error logs with warnings.  "Form_Validation" is a class that does just that--provide common types of form validations.  Folks who use CodeIgniter will be familiar with the usage.

```php
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
 * @author Matthew Toledo
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
```


##About
This anti-framework was written to test a theory of mine.  It's currently in alpha status but I've used it on my own for
fun projects.  I'm continuing to refine stuff and automating common code tasks by creating new core classes and methods.
I would love it if you would help the project along by forking and improving mFrame.

##Todo
- Autoload a controller who's name is based on a pattern derived from the name of the current view.  So bob.php auto-loads a controller called bob_c.php
- Document the heck out of everything
