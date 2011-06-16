<?php
/**
 * @package mFrame
 */
/**
 * Registry
 * 
 * The registry design pattern stores and retrieves references to objects, and 
 * works in a similar way to a telephone directory: storing and retrieving 
 * contacts. We will use it to store these core objects, system-wide settings, 
 * and later, any other data or information which needs to be shared across the 
 * system.
 * 
 * Because we are storing this information centrally, we only ever want one 
 * instance of this registry object to be available within our framework, if 
 * more than one were available, then we would have problems where we were 
 * expecting a certain piece of data, or a certain object to be in the registry, 
 * when it was in fact stored within another instance of the Registry object. 
 * To solve this problem, our Registry object will also implement the Singleton 
 * design pattern, which prevents more than a single instance of the object 
 * being available.
 * 
 * @author Matthew Toledo <matthew.toledo@gmail.com>, Michael Peacock
 * @version 0.1
 * @link http://www.talkphp.com/advanced-php-programming/1304-how-use-singleton-design-pattern.html
 */
class MF_registry
{
	
	private static $a_objects;
	
	private static $a_settings;
	
	private static $s_framework_name;
	
	private static $o_registry_instance;
	
	
	/**
	 * Class constructor
	 * 
	 * By making the constructor private we have prohibited objects of the class
	 * from being instantiated from outside the class. So for example the 
	 * following no longer works outside the class:
	 * 
	 * <code>
	 * $o_registry = new Registry()
	 * <code>
	 * 
	 * @link http://www.talkphp.com/advanced-php-programming/1304-how-use-singleton-design-pattern.html
	 */
	private function __construct()
	{
		self::$a_objects = array();
		self::$a_settings = array();
		self::$s_framework_name = 'mFrame version 0.1';
	}
	
	/**
	 * 
	 * @return type 
	 */
	public static function singleton()
	{
		if ( !isset( self::$o_registry_instance )) 
		{
			$o = __CLASS__;
			self::$o_registry_instance = new $o;
		}
		
		return self::$o_registry_instance;
	}
	
	/**
	 * Prevent the cloning of this object.
	 */
	public function __clone() 
	{
		// todo : throw exception instead?
		trigger_error('Cloning the registry is not permitted', E_USER_ERROR);
	}
	
	/**
	 * Stores a class object in the registry
	 * 
	 * @param object $s_object_name The name of the object (class)
	 * @param string $s_key The associative array key where we will store the object as a value.
	 * @return void
	 */
	public function store_object($s_object_name,$s_key)
	{
		require_once('objects/' . $s_object_name . '.class.php');
		self::$a_objects[$s_key] = new $s_object_name(self::$o_registry_instance);
	}
	
	/**
	 * Gets an object from the object registry
	 * 
	 * @param string $s_key The associative array key that contains the value we wish to return
	 * @return object
	 */
	public function get_object($s_key)
	{
		if (is_object(self::$a_objects[$s_key])) 
		{
			return self::$a_objects[$s_key];
		}
	}
	
	
	/**
	 * Stores a setting in the registry
	 * 
	 * Note: what is a setting?
	 * 
	 * @param string $s_data
	 * @param string $s_key 
	 * @return void
	 */
	public function store_setting($s_data, $s_key)
	{
		self::$a_settings[$s_key] = $s_data;
	}
	
	public function get_setting($s_key)
	{
		return self::$a_settings[$s_key];
	}
	
	public function get_version()
	{
		return self::$s_framework_name;
				
	}
}