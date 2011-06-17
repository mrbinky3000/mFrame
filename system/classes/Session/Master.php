<?php
/**
 * @package mFrame
 */
/**
 * Class for importing form data into a session
 */
class Session_Master
{
	
	protected static $s_name_space = 'default';
	
	private static $o_instance;
	
	/**
	 * Class constructor
	 * 
	 * @param string $s_name_space Optional declare a namespace.
	 * @return Session_Master 
	 */
	protected function __construct($s_name_space = null)
	{
		if ($s_name_space) 
		{
			self::set_name_space($s_name_space);
		}
	}
	
	/**
	 * Return or Create and return a singleton instance of this class.
	 * 
	 * @return Session_Master
	 */
    public static function init() 
    {
        if (!isset(self::$o_instance)) 
		{
            $c = __CLASS__;
            self::$o_instance = new $c;
        }

        return self::$o_instance;
    }	
	
	/**
	 * Switch to an active session namespace.
	 * 
	 * Initialize the namespace if it hasn't been used yet.  
	 * 
	 * @param string $s
	 * @return Session_Master 
	 */
	public static function set_name_space($s)
	{
		if (!isset($_SESSION['Session_Master']))
		{
			$_SESSION['Session_Master'] = array();
		}
		
		if (!isset($_SESSION['Session_Master'][$s])) 
		{
			$_SESSION['Session_Master'][$s] = array();
		}
		self::$s_name_space = $s;
	}
	
	
	/**
	 * Get the contents of a session namespace.
	 * 
	 * This does not switch to the active namespace.
	 * 
	 * @param string $s optional session namespace. Default is the current namespace.
	 * @return array
	 */
	public static function get_name_space($s = '')
	{
		$s = $s ? $s : self::$s_name_space;
		$a_return = array();
		if (@is_array($_SESSION['Session_Master'][$s]))
		{
			$a_return = $_SESSION['Session_Master'][$s];
		}
		return $a_return;
	}
	
	/**
	 * Gets the name of the current active name space.
	 * 
	 * @return string
	 */
	public static function get_current_name_space()
	{
		return self::$s_name_space;
	}
	
	
	
	/**
	 * Import an associative array's key/value pairs into the session namespace
	 * 
	 * Note that you can import super globals, like $_POST or $_GET.  If you 
	 * specify a list of keys via the second argument, any keys without values
	 * are unset from the session namespace.
	 * 
	 * @param array $a An associative array.
	 * @param array $a_keys Limit the import of associative array $a to the following array of keys.
	 * @return void
	 */
	public static function import($a, $a_keys = array())
	{
		
		if (!count($a_keys)) 
		{
			$_SESSION['Session_Master'][self::$s_name_space] = array_merge($_SESSION['Session_Master'][self::$s_name_space], $a);
		}
		else
		{
			foreach ($a_keys as $s_key)
			{
				if (isset($a[$s_key])) 
				{
					$_SESSION['Session_Master'][self::$s_name_space][$s_key] = $a[$s_key];
				}
				else
				{
					unset($_SESSION['Session_Master'][self::$s_name_space][$s_key]);
				}
			}
		}
			
	}
	
	/**
	 * Set or delete a single key in the current namespace.
	 * 
	 * @param string $s_key
	 * @param mixed $s_value
	 * @return void
	 */
	public static function set_key($s_key,$s_value)
	{
		if (NULL === $s_value && isset( $_SESSION['Session_Master'][self::$s_name_space][$s_key]) )
		{
			unset($_SESSION['Session_Master'][self::$s_name_space][$s_key]);
		}
		else
		{
			$_SESSION['Session_Master'][self::$s_name_space][$s_key] = $s_value;
		}
	}
	
	/**
	 * 
	 * @param string $s_key
	 * @return mixed
	 */
	public static function get_key($s_key)
	{
		return @$_SESSION['Session_Master'][self::$s_name_space][$s_key];
	}
	
	
	/**
	 * Copy the contents of the session namespace to a superglobal.
	 *
	 * @param array $a_fields optional A list of fields to export.  Blank exports everything.
	 * @param string $s optional Shorthand for a superglobal variable to use as a target.  The default is _POST
	 * @return Session_Master 
	 */
	public static function export_to_superglobal($a_fields = array(), $s = 'post')
	{
		$s_mode = strtolower(trim($s));
		
		$i = count($a_fields);

		
		foreach ($_SESSION['Session_Master'][self::$s_name_space] as $s_key => $s_value)
		{
			
			// If we declared a list of fields to export, skip the session
			// keys that we didn't declare as exportable.
			if ($i > 0) 
			{
				if (!in_array($s_key, $a_fields))
				{
					continue;
				}
			}
			
			switch ($s_mode)
			{
				case '_post[]':
				case '_post':
				case 'post':
					$_POST[$s_key] = $s_value;
					break;
				case '_get[]':
				case '_get':
				case 'get':
					$_GET[$s_key] = $s_value;
					break;
				case '_request[]':
				case '_request':
				case 'request':
					$_REQUEST[$s_key] = $s_value;
					break;
				case '_cookie[]':
				case '_cookie':
				case 'cookie':
					$_COOKIE[$s_key] = $s_value;
					break;
			}
		}		

		
	}
	
	/**
	 * Erase a single key from the current namespace
	 * 
	 * @param string $s
	 * @return void 
	 */
	public static function erase($s)
	{
		if (isset($_SESSION['Session_Master'][self::$s_name_space][$s]))
		{
			unset($_SESSION['Session_Master'][self::$s_name_space][$s]);
	
		}
	}
	
	/**
	 * Reset a particular namespace to a blank array.
	 * @return void
	 */
	public static function clear_name_space()
	{
		$_SESSION['Session_Master'][self::$s_name_space] = array();
	}
	
	/**
	 * Completely destroy and remove all namespaces arrays.
	 * @return void
	 */
	public static function destroy_all()
	{
		$_SESSION['Session_Master'] = array();
	}
	
	/**
	 * Prevent cloning of this singleton object.
	 * 
	 * @return void
	 * @throws Exception
	 */
	public function __clone()
	{
		throw new Exception("You are not permitted to clone this singleton object.");
	}
	
	
}
