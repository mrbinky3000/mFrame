<?php
/**
 * @package mframe
 */
/**
 * Basic Form Validation
 * 
 * Emulates how Code Igniter runs tests and applies filters on data + some new
 * improvements.
 * 
 * @author Matthew Toledo <matthew.toledo@g####.com>
 * @subpackage classes
 */
class Form_Validation
{
	
	/**
	 * Holds a multi-dimensonal array with our tests.
	 * @var array
	 */
	public $a_rules = array();
	
	
	/**
	 * Holds the all the possible error messages that are displayed when a field 
	 * fails a rule.
	 * @var array 
	 */
	public $a_error_messages = array();
	
	/**
	 * Holds form validation error messages for errors encountered on the last 
	 * run.
	 * @var array 
	 */
	public $a_errors = array();
	
	/**
	 * Holds general error messages encountered during the lifespan of this 
	 * object.  Must manually clear out the general error's array.
	 * @var array
	 */
	public static $a_general_errors = array();
	
	/**
	 * Post prefix for post vars.
	 * @deprecated
	 * @var type 
	 */
	public $s_post_prefix = '';
	
	/**
	 * A user-defined label prefixed to all tests and error messages.  It allows
	 * the programmer to use the same object several times without having results
	 * collide / overwrite each other.
	 * 
	 * @var string
	 * @see Form_Validation::set_error_name_space()
	 */
	protected $s_error_name_space = 'default';
	
	/**
	 * Allows us to pick which PHP superglobal array to use.  Possible values
	 * are post, get, request, cookie, and session.  Must be lowercase, no
	 * spaces.
	 * 
	 * @var string
	 * @see Form_Validation::set_superglobal() 
	 */
	protected $s_superglobal = 'post';
	
	/**
	 * Lets us choose between appending form validation errors for each field to
	 * that field's list, or having new validation erros overwrite previous error
	 * messages.
	 * 
	 * @var boolean
	 * @see Form_Validation::set_append_error()
	 */
	protected $b_append_errors = TRUE;
	
	
	/**
	 * Class constructor allows you to optionally declare a name space.
	 * 
	 * The default name space is 'default'.  Also, this method is chainable.
	 * 
	 * @param string $s_error_name_space
	 * @return Form_Validation 
	 */
	public function __construct($s_error_name_space = NULL) 
	{
		if (is_string($s_error_name_space))
		{
			$this->set_error_name_space($s_error_name_space);
		}
		$this->set_default_error_messages();
		return $this;
	}
	
	/**
	 * Set default error messages for built-in tests.
	 * 
	 * This method is chainable.
	 * 
	 * @return Form_Validation
	 */
	public function set_default_error_messages()
	{
		$this->set_error_message('required', FORM_VALIDATION_REQUIRED);
		$this->set_error_message('min_value', FORM_VALIDATION_MIN_VALUE);
		$this->set_error_message('max_value', FORM_VALIDATION_MAX_VALUE);
		$this->set_error_message('exact_value', FORM_VALIDATION_EXACT_VALUE);
		$this->set_error_message('min_length', FORM_VALIDATION_MIN_LENGTH);
		$this->set_error_message('max_length', FORM_VALIDATION_MAX_LENGTH);
		$this->set_error_message('exact_length', FORM_VALIDATION_EXACT_LENGTH);
		$this->set_error_message('equal_to_field', FORM_VALIDATION_EQUAL_TO_FIELD);
		$this->set_error_message('integer', FORM_VALIDATION_INTEGER);
		$this->set_error_message('float', FORM_VALIDATION_FLOAT);
		$this->set_error_message('money_us', FORM_VALIDATION_MONEY_US);
		$this->set_error_message('alpha_underscore', FORM_VALIDATION_ALPHA_UNDERSCORE);
		$this->set_error_message('date', FORM_VALIDATION_DATE);
		return $this;
	}
	
	/**
	 * Declare and use a name space to store form validation error messages.
	 * 
	 * This method is chainable.
	 * 
	 * @param string $s_error_name_space
	 * @return Form_Validation 
	 */
	public function set_error_name_space($s_error_name_space)
	{
		if (is_string($s_error_name_space))
		{
			$this->s_error_name_space = $s_error_name_space;
		}
		if (!is_array($this->a_errors[$s_error_name_space])) 
		{
			$this->a_errors[$s_error_name_space] = array();
		}
		return $this;
	}
	
	/**
	 * Declare tests and filters for a form field.
	 * 
	 * The function takes three parameters as input:
	 * o The field name - the exact name you've given the form field.
	 * o A "human" name for this field, which will be inserted into the error 
	 *   message. For example, if your field is named "user" you might give it a
	 *   human name of "Username".
	 * o The validation rules for this form field.
	 * 
	 * Validation rules look something like:
	 * trim|required|min_length=6|max_length=20|custom_method=valid_password_characters,arg1,arg2
	 * 
	 * @param string $s_field_name
	 * @param string $s_label
	 * @param string $s_rules 
	 */
	public function set_rules($s_field_name, $s_label, $s_rules)
	{
		$this->a_rules[$this->s_error_name_space][$s_field_name] = array(
			's_label' => $s_label,
			's_rules' => $s_rules,
		);
	}
	
	/**
	 * State which superglobal variable is the target for our form validation
	 * tests and data filters.
	 * 
	 * This method is chainable.
	 * 
	 * @param type $s_superglobal
	 * @return Form_Validation 
	 */
	public function set_superglobal($s_superglobal)
	{
		$s_superglobal = preg_replace("/[^a-z]/",'',trim(strtolower($s_superglobal)));
		if (!in_array($s_superglobal, array('post','get','request','cookie','session'))) 
		{
			throw new Exception("Superglobal needs to be post, get, request, cookie, or session.");
		}
		$this->s_superglobal = $s_superglobal;
		return $this;
	}
	
	/**
	 * Declare if you want errors for a particular field to be appended to a list
	 * or have new errors overwrite old errors.
	 * 
	 * @param boolean $b
	 * @return Form_Validation 
	 */
	public function set_append_errors($b)
	{
		if (!is_bool($b)) 
		{
			throw new Exception("First argument must be boolean");
		}
		$this->b_append_errors = $b;
		return $this;
	}
	
	/**
	 * Run a series of tests and apply filters to user-supplied data in one of
	 * the PHP superglobals.
	 * 
	 * <code>
	 *	$a_test['login'] = 'trim|required|min=4|max=20';
	 *	$a_test['password'] = 'trim|required|min=6|max=20|custom_method=valid_password_characters,arg1,arg2";
	 * </code>
	 * 
	 * When writing your own functions and methods for validating date, please
	 * note that the $s_field_name is always the first argument, followed by
	 * any other arguments if any.
	 * 
	 * @return boolean 
	 */
	public function run()
	{
	
		// clear some vars
		$this->a_errors[$this->s_error_name_space]  = array();
				
		$a_tests_to_run = $this->a_rules[$this->s_error_name_space];
		
		// assume victory!
		$b_return = TRUE;
		
		if (!is_array($a_tests_to_run)) throw new Exception ("First argument must be a hash array.");

		// rules look like "required|trim|min=8|max=32|custom_method=my_method,arg1,arg2";
		foreach ($a_tests_to_run as $s_field_name => $a)
		{
			
			$s_label = @$a['s_label'];
			$s_rules = @$a['s_rules'];
			
			$s_field_name = $this->s_post_prefix.$s_field_name;
			
			$a_rules = explode('|',$s_rules);
			
			// skip if not required and the value is blank
			if (!in_array('required',$a_rules) && !$this->get_field($s_field_name)) continue;
			
			foreach($a_rules as $s_test)
			{
				// $m_arguments:
				// delcared "mixed" because it can be a single argument or an 
				// array of arguments
				// 
				// example 1: 
				//		"min=8" becomes 
				//		$s_test_name = min
				//		$m_arguments = 8
				//		
				// example 2: 
				//		"custom_method=method_name,arg1,arg2" becomes 
				//		$s_test_name = custom_method
				//		$m_arguments = array('method_name','arg1','arg2')
				// 
				// example of escaping commas:  
				//		"custom_method=method_name,hello there, badgers\, we don't need no stinkin badgers!" becomes
				//		method_name("hello there","badgers\, we don't need no stinkin badgers!");
				
				$m_arguments = NULL;
				
				@list($s_test_name,$m_arguments) = explode('=',$s_test);
				
				
				if ($m_arguments && preg_match('/(?<!\\\\),/',$m_arguments))
				{				
					// unescape commas
					$m_arguments = preg_split('/(?<!\\\\),/',$m_arguments);
				}
				
				switch ($s_test_name)
				{
					case 'required'			: $this->test_required($s_field_name); break;
					case 'in'				: $this->test_in($s_field_name,$m_arguments);break;
					case 'min_value'		: $this->test_min_value($s_field_name,$m_arguments); break;
					case 'max_value'		: $this->test_max_value($s_field_name,$m_arguments); break;
					case 'equal_value'		: $this->test_equal_value($s_field_name,$m_arguments); break;
					case 'min_length'		: $this->test_min_length($s_field_name,$m_arguments); break;
					case 'max_length'		: $this->test_max_length($s_field_name,$m_arguments); break;
					case 'exact_length'		: $this->test_exact_length($s_field_name,$m_arguments); break;
					case 'equal_to_field'	: $this->test_equal_to_field($s_field_name,$m_arguments); break;
					
					case 'integer'			: $this->test_integer($s_field_name); break;
					case 'alpha_underscore'	: $this->test_alpha_underscore($s_field_name); break;
					case 'float'			: $this->test_float($s_field_name); break;
					case 'money_us'			: $this->test_money_us($s_field_name); break;
					case 'email_format'		: $this->test_email_format($s_field_name); break;
					case 'date'				: $this->test_date($s_field_name); break;
					case 'cc_number'		: $this->test_cc_number($s_field_name); break;
					
					case 'xss'				: $this->filter_xss($s_field_name); break;
				
					case 'custom_method'	:

						if(is_string($m_arguments))
						{
							$b_result = call_user_func(array($this, $m_arguments),$s_field_name);
						}
						
						if(is_array($m_arguments))
						{
							
							// get the name of the method
							$s_method = array_shift($m_arguments);
							
							// put the field name first in the list of arguments to pass to the custom method
							array_unshift($m_arguments,$s_field_name);
								
							// unescape any escaped commas
							for($i = 0; $i < count($m_arguments); $i++)
							{
								$m_arguments[$i] = preg_replace("/\\,/",',',$m_arguments[$i]);
							}

							$b_result = call_user_func_array(array($this, $s_method), $m_arguments); 

						}
						break;
					case 'custom_function'	: 					
						
						if(is_string($m_arguments))
						{
							$b_result = call_user_func($m_arguments, $s_field_name);
						}
						
						if (is_array($m_arguments)) 
						{
							
							// get the name of the function
							$s_function = array_shift($m_arguments);
							
							// put the field name first in the list of arguments to pass to the custom method
							array_unshift($s_field_name,$m_arguments);
								
							// unescape any escaped commas
							for($i = 0; $i < count($m_arguments); $i++)
							{
								$m_arguments[$i] = preg_replace("/\\,/",',',$m_arguments[$i]);
							}

							$b_result = call_user_func_array($s_function, $m_arguments); 
							
						}
						break;
					default:
						
						// Before we give up, see if there is already a PHP
						// function that exists.  If so, use it as a FILTER
						// 
						// By default we send the current field as the first 
						// variable, but you can use the term "var" to place
						// the current field in any argument position.  See
						// below.
						// 
						// example:
						//		"trim" becomes
						//		$_POST[$s_field_name] = trim(get_field($s_field_name))
						//
						// example: multiple args (var is substituted out)
						//		"substr=var,3,9"
						//		$_POST[$s_field_name] = substr(get_field($s_field_name),3,9);
						//
						// example: multiple args (var is substituted out)
						//		"sprintf=There are %d monkeys in the %s,var,tree)" becomes
						//		$_POST[$s_field_name] = sprintf('There are %d monkeys in the %s',get_field($s_field_name),'tree');
						//		
						
						if (function_exists($s_test_name)) 
						{
						
							
							$a_arguments = array();
							if (is_string($m_arguments))
							{
								$a_arguments[] = $m_arguments;
							}
							if (is_array($m_arguments)) 
							{
								$a_arguments = $m_arguments;
							}
							
							// user supplied arguments
							if (count($a_arguments))
							{
								// unescape any escaped commas
								// substitue out "var" for the superglobal value
								for($i = 0; $i < count($a_arguments); $i++)
								{
									$a_arguments[$i] = preg_replace("/\\,/",',',$a_arguments[$i]);

									if ('var' == $a_arguments[$i])
									{
										$a_arguments[$i] = $this->get_field($s_field_name);
									}
								}
							}
							else
							{
								$a_arguments[] = $this->get_field($s_field_name);
							}

							/*
							FB::group('predefined function');
							FB::log($s_test_name,'$s_test_name');
							FB::log($a_arguments,'$a_arguments');
							FB::groupEnd();
							*/
							
							$m_result = call_user_func_array($s_test_name,$a_arguments);
							
							$this->set_field($s_field_name, $m_result);
							
						}
						
						// there is no built in or user defined php function
						// with the name of $s_test_name
						else
						{
							throw new Exception ("There is no defined test called '$s_test_name'.");
						}
				} // end: switch ($s_test_name)
			} // end: foreach ($a_tests as $s_test)
		} // end: foreach ($a_tests_to_run as $s_field_name => $s_tests)
		
		if (!is_bool($b_return))
		{
			throw new Exception ("Tests must return a TRUE or FALSE boolean.");
		}
		
		return $b_return;
	}
	
	/**
	 * Fetch the value of a superglobal.
	 * 
	 * Unfortunately, PHP will not let you access superglobal variables via
	 * variable variables.  So, I have to use an un-DRY switch statement.
	 * For example, the following is not allowed.
	 * 
	 * <code>
	 *  // this will not work.
	 *  $superglobal = '_POST';
	 *	$$superglobal['bob'] = 'blarg';
	 * </code>
	 * 
	 * @param string $s_field_name
	 * @return mixed
	 * @see Form_Validation::set_field()
	 * @see Form_Validation::$s_superglobal
	 */
	public function get_field($s_field_name)
	{
		
		$m_return = NULL;

		switch ($this->s_superglobal)
		{
			case 'post':
				$m_return = fp($s_field_name);
				break;
			case 'get':
				$m_return = fg($s_field_name);
				break;
			case 'request':
				$m_return = fr($s_field_name);
				break;
			case 'cookie':
				$m_return = fc($s_field_name);
				break;
			case 'session':
				$m_return = fs($s_field_name);
				break;
		}
		return $m_return;
	}
	
	/**
	 * Set a superglobal value.
	 * 
	 * This method is chainable.
	 * 
	 * @param string $s_field_name
	 * @param mixed $m_value
	 * @return Form_Validation 
	 * @see Form_Validation::get_field()
	 * @see Form_Validation::$s_superglobal
	 */
	public function set_field($s_field_name, $m_value)
	{
		switch ($this->s_superglobal)
		{
			case 'post':
				$_POST[$s_field_name] = $m_value;
				break;
			case 'get':
				$_GET[$s_field_name] = $m_value;
				break;
			case 'request':
				$_REQUEST[$s_field_name] = $m_value;
				break;
			case 'cookie':
				$_COOKIE[$s_field_name] = $m_value;
				break;
			case 'session':
				$_SESSION[$s_field_name] = $m_value;
				break;
		}		
		return $this;
	}
	
	/**
	 * Add Error
	 * 
	 * Adds an error message to the current error name space.  Also, this method
	 * is chainable.
	 * 
	 * @param string $s_field_name
	 * @param string $s_rule
	 * @param array $a_substitutions optional An associative array. "{key}" is swapped out for the value
	 * @return Form_Validation
	 * @see Form_Validation::set_append_errors() 
	 */
	public function ae($s_field_name,$s_rule,$a_substitutions = array())
	{
		
		// rules are the method/function names.  We remove 'test_' and 'filter_'
		// from the beginning of the rule names.
		$s_rule = preg_replace('/^(test_|filter_)/','',$s_rule);
		
		// find the human friendly label for this field
		$s_label = @$this->a_rules[$this->s_error_name_space][$s_field_name]['s_label'];
		
		// get the official error message for this rule.
		// insert human friendly label if given.
		$s_error_message = $this->get_error_message($s_rule, $s_label);
		
		foreach ($a_substitutions as $s_key => $s_value)
		{
			$s_error_message = preg_replace('/\{'.$s_key.'\}/',$s_value,$s_error_message);
		}
		
		// add it to the list of errors for this run.
		if (TRUE === $this->b_append_errors)
		{
			$this->a_errors[$this->s_error_name_space][$s_field_name][] = $s_error_message;
		}
		else
		{
			$this->a_errors[$this->s_error_name_space][$s_field_name] = array($s_error_message);
		}
		
		return $this;
		
	}
	
	/**
	 * Append to General Errors array.
	 * 
	 * @param string $s 
	 */
	public static function age($s)
	{
		self::$a_general_errors[] = $s;
	}
	
	/**
	 * Get the Total Number of Errors.
	 * 
	 * By default, it includes all the errors in the current name space, plus
	 * all the general errors.
	 * 
	 * @param boolean $b_include_general_errors optional Set to FALSE to exclude general errors from the count.
	 */
	public function gne($b_include_general_errors = TRUE)
	{
		$i = count($this->a_errors[$this->s_error_name_space]);
		if ($b_include_general_errors) 
		{
			$i += count(self::$a_general_errors);
		}
		return $i;
	}
	
	/**
	 * Show errors for a particular field name
	 * 
	 * @param string $s_field_name 
	 */
	public function se($s_field_name)
	{
		$s_return = '';
		$m = @$this->a_errors[$this->s_error_name_space][$s_field_name];
		if (count($m) > 1) 
		{
			$s_return = '<ul>';
			foreach ($m as $s)
			{
				$s_return .= '<li>'.$s.'</li>';
			}
			$s_return .= '</ul>';
		}
		else
		{
			$s_return = $m[0];
		}
	}
	
	/**
	 * Show any general errors
	 * @return string
	 */
	public static function sge()
	{
		$s_return = '';
		if (count(self::$a_general_errors) > 1) 
		{
			$s_return = '<ul>';
			foreach (self::$a_general_errors as $s)
			{
				$s_return .= '<li>'.$s.'</li>';
			}
			$s_return .= '</ul>';
		}
		else
		{
			$s_return = self::$a_general_errors[0];
		}
		return $s_return;
	}
	
	/**
	 * Get the Number of General Errors
	 * 
	 * Return an integer representing the total number of general errors
	 * 
	 * @return integer
	 */
	public static function gnge()
	{
		return count(self::$a_general_errors);
	}
	
	public function get_error_message($s_rule_method, $s_label)
	{
		$s_return = '';
		$s_rule = preg_replace('/^test_/','',$s_rule_method);
		if (isset($this->a_error_messages[$s_rule])) 
		{
			$s_return = preg_replace('/\{label\}/',$s_label,$this->a_error_messages[$s_rule]);
		}
		return $s_return;
	}
	
	public function set_error_message($s_rule,$s)
	{
		$this->a_error_messages[$s_rule] = $s;
		return $this;
	}


	/**
	 * Test to ensure that a required field is not blank.
	 * 
	 * @param string $s_field_name
	 * @return boolean 
	 */
	public function test_required($s_field_name)
	{
		$m = $this->get_field($s_field_name);
		$b_return = (NULL === $m || '' == $m) ? FALSE : TRUE;
		$this->ae($s_field_name, $this->get_error_message($s_field_name,__FUNCTION__));
		return $b_return;
	}
	
	/**
	 * Test to see if a field's value can be found in a list of acceptable 
	 * possibilities.
	 *
	 * @param type $s_field_name
	 * @param type $a_haystack
	 * @return type 
	 */
	public function test_in($s_field_name, $a_haystack)
	{
		$m = $this->get_field($s_field_name);
		return in_array($m, (array)$a_haystack);
	}
	
	/**
	 * Test to see if a field's value is greater than or equal to $m_min.
	 * 
	 * @param string $s_field_name
	 * @param integer|string $m_min Usually an integer, but you can do string comparison as well.
	 * @return boolean
	 */
	public function test_min_value($s_field_name, $m_min)
	{
		$m = $this->get_field($s_field_name);
		$b_return = ($m < $m_min) ? FALSE : TRUE;
		return $b_return;
	}
	
	/**
	 * Test to see if a field's value is less than or equal to $m_max.
	 * 
	 * @param string $s_field_name
	 * @param integer|string $m_min Usually an integer, but you can do string comparison as well.
	 * @return boolean
	 */
	public function test_max_value($s_field_name, $m_max)
	{
		$m = $this->get_field($s_field_name);
		$b_return = ($m > $m_max) ? FALSE : TRUE;
		return $b_return;
	}

	/**
	 * Test to see if a field's value is exactly equal or identical to $m_exact.
	 * 
	 * The default type of comparison operator used is PHP's equal operator (==).
	 * Set $s_mode to 'identical' to use PHP's identical operator (==).
	 * 
	 * @param string $s_field_name
	 * @param mixed $m_value Usually an integer or string.
	 * @param string $s_mode optional Must be either 'equal' (==) or 'identical' (===)
	 * @return boolean
	 */
	public function test_exact_value($s_field_name, $m_value, $s_mode = 'equal')
	{
		$b_return = FALSE;
		$m = $this->get_field($s_field_name);
		if ('equal' == $s_mode) 
		{
			$b_return = ($m == $m_value) ? TRUE : FALSE;
		}
		if ('identical' == $s_mode) 
		{
			$b_return = ($m === $m_value) ? TRUE : FALSE;
		}
		return $b_return;
	}
	

	/**
	 * Test to see if a field's length is greater than or equal to $i.
	 * 
	 * @param string $s_field_name
	 * @param integer $i
	 * @return boolean
	 */
	public function test_min_length($s_field_name, $i)
	{
		$m = strlen($this->get_field($s_field_name));
		$b_return = ($m < (int)$i) ? FALSE : TRUE;
		if (!$b_return) $this->ae($s_field_name, __FUNCTION__, array('i'=>$i));
		return $b_return;
	}

	/**
	 * Test to see if a field's length is less than or equal to $i.
	 * 
	 * @param string $s_field_name
	 * @param integer $i
	 * @return boolean
	 */
	public function test_max_length($s_field_name, $i)
	{
		$m = strlen($this->get_field($s_field_name));
		$b_return = ($m > (int)$i) ? FALSE : TRUE;
		if (!$b_return) $this->ae($s_field_name, __FUNCTION__, array('i'=>$i));
		return $b_return;
	}

	/**
	 * Test to see if a field's length is exactly equal to $i.
	 * 
	 * @param string $s_field_name
	 * @param integer $i
	 * @return boolean
	 */
	public function test_exact_length($s_field_name, $i)
	{
		$m = strlen($this->get_field($s_field_name));
		$b_return = ($m == (int)$i) ? TRUE : FALSE;
		if (!$b_return) $this->ae($s_field_name, __FUNCTION__, array('i'=>$i));
		return $b_return;
	}

	/**
	 * Test to see if a field's value is exactly equal to another field's value.
	 * 
	 * If both fields are empty, they are equal. Make sure to test if they are 
	 * required if they need to be non blank.
	 * 
	 * @param string $s_field_name
	 * @param string $s
	 * @return boolean
	 */
	public function test_equal_to_field($s_field_name, $s)
	{
		$b_return = ($this->get_field($s_field_name) == $this->get_field($s)) ? TRUE : FALSE;
		if (!$b_return) $this->ae($s_field_name, __FUNCTION__, array('s'=>$i));
		return $b_return;
	}
	
	/**
	 * Test to see if the field is an integer value.
	 * 
	 * @param type $s_field_name
	 * @return boolean 
	 */
	public function test_integer($s_field_name)
	{
		$b_return = preg_match('/^[-+]?\d+$/',$this->get_field($s_field_name)) ? TRUE : FALSE;
		if (!$b_return) $this->ae($s_field_name, __FUNCTION__);
		return $b_return;
	}
	
	/**
	 * Test to see if the field is a float value.
	 * 
	 * @param type $s_field_name
	 * @return type 
	 */
	public function test_float($s_field_name)
	{
		$b_return = preg_match('/^[-+]?[0-9]+(\.[0-9]+)?$/',$this->get_field($s_field_name)) ? TRUE : FALSE;
		if (!$b_return) $this->ae($s_field_name, __FUNCTION__);
		return $b_return;
	}
	
	/**
	 * Test to see if the field properly formatted us currency.
	 * 
	 * - NOTE that optional commas ARE allowed.  1,000,000.00 is legal.
	 * - Must have 2 decimal places
	 * - no dollar sign permitted
	 * 
	 * @param type $s_field_name
	 * @return type 
	 */
	public function test_money_us($s_field_name)
	{
		$b_return = preg_match('/^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$/',$this->get_field($s_field_name)) ? TRUE : FALSE;
		return $b_return;
	}
	
	/**
	 * Test to ensure that a field contains letters numbers and underscores only.
	 * 
	 * Note, if the field is blank, it will pass the test.  Make sure to use
	 * required if this is a required element.
	 * 
	 * @param type $s_field_name 
	 * @return boolean
	 */
	public function test_alpha_underscore($s_field_name)
	{
		$b_return = preg_match('/^[a-zA-Z0-9_]*$/', $this->get_field($s_field_name)) ? TRUE : FALSE;
		if (!$b_return) $this->ae($s_field_name, __FUNCTION__);
		return $b_return;
	}
	
	/**
	 * Test that the field is a properly formatted email address
	 * 
	 * Notes:
	 * - RFC 2822 (simplified)
	 * - Matches a normal email address.  
	 * - Does not check the top-level domain.
	 * - Does not check if domain is reachable via the internet.
	 * 
	 * @param type $s_field_name
	 * @return boolean 
	 */
	public function test_email_format($s_field_name)
	{
		$b_return = (preg_match('/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i', $this->get_field($s_field_name))) ? TRUE : FALSE;
		if (!$b_return) $this->ae($s_field_name, __FUNCTION__);
		return $b_return;		
	}
	
	/**
	 * Check if the supplied string can be parsed as a date by PHP strtotime
	 * 
	 * @param string $s_field_name
	 * @return boolean 
	 */
	public function test_date($s_field_name)
	{
		$b_return = FALSE;
		$m = strtotime($this->get_field($s_field_name));	
		if (FALSE === $m) 
		{
			$this->ae($s_field_name, __FUNCTION__);
		}
		else
		{
			$b_return = TRUE;
		}
		return $b_return;		
	}

	/**
	 * Test that a credit card number is the proper format
	 * 
	 * Notes:
	 * - Does not check if the card is valid.
	 * - Numbers can be grouped into 4's like on the card itself.
	 * 
	 * @param type $s_field_name
	 * @return boolean 
	 */
	public function test_cc_number($s_field_name)
	{
		$b_return = (preg_match('/^(?:4\d{3}[ -]*\d{4}[ -]*\d{4}[ -]*\d(?:\d{3})?|5[1-5]\d{2}[ -]*\d{4}[ -]*\d{4}[ -]*\d{4}|6(?:011|5[0-9]{2})[ -]*\d{4}[ -]*\d{4}[ -]*\d{4}|3[47]\d{2}[ -]*\d{6}[ -]*\d{5}|3(?:0[0-5]|[68][0-9])\d[ -]*\d{6}[ -]*\d{4}|(?:2131|1800)[ -]*\d{6}[ -]*\d{5}|35\d{2}[ -]*\d{4}[ -]*\d{4}[ -]*\d{4})$/', $this->get_field($s_field_name))) ? TRUE : FALSE;
		if (!$b_return) $this->ae($s_field_name, __FUNCTION__);
		return $b_return;		
	}	
	
	/**
	 * Filter XSS from a field.
	 * 
	 * @todo Replace with ci's XSS filter, or use HTML Purifier when I have time.
	 * @param string $s_field_name
	 * @return Form_Validation 
	 */
	public function filter_xss($s_field_name)
	{
		$m = $this->get_field($s_field_name);
		$m = filter_var($m, FILTER_SANITIZE_STRING);
		$this->set_field($s_field_name, $m);
		return $this;
	}
	
	/**
	 * Removes all non-integer characters from a string.
	 * 
	 * @param string $s_field_name
	 * @return Form_Validation 
	 */
	public function filter_non_integers($s_field_name)
	{
		$m = $this->get_field($s_field_name);
		$m = preg_replace("/[^0-9]",'',$m);
		$this->set_field($s_field_name, $m);
		return $this;
	}
	
}