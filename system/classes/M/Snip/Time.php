<?php
/**
 * @package mframe
 */
/**
 * A namespace for housing methods that generate times and dates.
 * 
 * @package mframe
 * @subpackage classes
 * @category Snip
 * @author Matthew Toledo <matthew.toledo@gmail.com>
 */
class M_Snip_Time
{
	
	
	
	/**
	 * Make an associative array where the key and value are 2 digit hours.
	 * 
	 * Valid values for the first argument are '12' and '24'.  Always returns
	 * zero prepended hour values.  Example: 01, 02 ... 12.  Returns an
	 * associative array.  Example: 01 => 01, 02 => 02.  you can always use
	 * array_keys to convert to an indexed array.
	 * 
	 * @param integer $i_hours Most common values are 12 and 24
	 * @param boolean $b_pad_key If true, zero pad the associative array keys
	 * @param integer $i_key_start_at Start at either 0 or 1. Default is 1.
	 * @param boolean $b_pad_value If true, zero pad the associative array values
	 * @param integer $i_value_start_at Start at either 0 or 1. Default is 1.
	 * @return array 
	 */
	public static function get_hours($i_hours = 12, $b_pad_key = true, $i_key_start_at = 1, $b_pad_value = true, $i_value_start_at = 1)
	{
		$a = self::_looper($i_hours, 1, $b_pad_key, $i_key_start_at, $b_pad_value, $i_value_start_at);
		return $a;
	}
	
	
	
	/**
	 * Make an associative array where the key and value are 2 digit minutes.
	 * 
	 * Always returns zero prepended minute values.  Example: 00, 01 ... 59.  
	 * Returns an associative array.  Example: 00 => 00, 01 => 01.  you can 
	 * always use array_keys to convert to an indexed array.
	 * 
	 * If increment is larger than 30, throws an Exception.
	 *
	 * @param integer $i_increment Skip by this amount. Default is 1. Other common values is 5, 15, and 30.
	 * @param boolean $b_pad_key If true, zero pad the associative array keys
	 * @param integer $i_key_start_at Start at either 0 or 1. Default is 0.
	 * @param boolean $b_pad_value If true, zero pad the associative array values
	 * @param integer $i_value_start_at Start at either 0 or 1. Default is 0.
	 * @return array 
	 */
	public static function get_minutes($i_increment = 1, $b_pad_key = true, $i_key_start_at = 0, $b_pad_value = true, $i_value_start_at = 0)
	{
		if ($i_increment > 30) throw new Exception('Increment must be less than 60');
		
		$a = self::_looper(60, $i_increment, $b_pad_key, $i_key_start_at, $b_pad_value, $i_value_start_at);
		return $a;
	}
	
	
	
	/**
	 * Return an associative array of months.
	 * 
	 * You can control zero padding for the associative array key and value. You
	 * are also able to control if January is represented as a zero or a one.
	 * 
	 * @param boolean $b_pad_key Zero pad the associative array keys
	 * @param integer $i_key_start_at First month starts at 0 or 1.  0 returns 0 ... 11.  1 returns 1 ... 12.
	 * @param boolean $b_pad_value Zero pad the associative array values
	 * @param integer $i_value_start_at First month starts at 0 or 1.  0 returns 0 ... 11.  1 returns 1 ... 12.
	 * @return array 
	 */
	public static function get_months_num($b_pad_key = true, $i_key_start_at = 1, $b_pad_value = true, $i_value_start_at = 1)
	{
		$a = self::_looper(12, 1, $b_pad_key, $i_key_start_at, $b_pad_value, $i_value_start_at);
		return $a;
	}
	
	
	public static function get_years($i_start,$i_end,$i_increment=1)
	
	
	
	/**
	 * Loop from zero to any number by an increment and return an associative
	 * array.  This is here to keep the other methods DRY.
	 * 
	 * @param integer $i_max Loops from zero to this value.
	 * @param integer $i_increment Skip in incremental steps by this value.
	 * @param boolean $b_pad_key True means we zero pad the associative array keys.
	 * @param integer $i_key_start_at Start counting from 0 or 1.
	 * @param integer $b_pad_value True means we zero padd the associative array values.
	 * @param integer $i_value_start_at Start counting form 0 or 1.
	 * @return array 
	 */
	private static function _looper($i_max,$i_increment,$b_pad_key,$i_key_start_at,$b_pad_value,$i_value_start_at)
	{
		if ($i_key_start_at < 0 || $i_key_start_at > 1) throw new Exception('Key start arg must be 0 or 1');
		if ($i_value_start_at < 0 || $i_value_start_at > 1) throw new Exception('Value start arg must be 0 or 1');
		
		$a = array();
		for ($i = 0; $i < $i_max; $i+=$i_increment)
		{
			$s_key = $b_pad_key ? sprintf("%02d",$i_key_start_at + $i) : $i_key_start_at + $i;
			$s_value = $b_pad_value ? sprintf("%02d",$i_value_start_at + $i) : $i_value_start_at + $i;
			$a[$s_key] = $s_value;
		}
		return $a;
	}

}
