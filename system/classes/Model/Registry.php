<?php
/**
 * @package airportdirectlimo
 */
/**
 * Model to access the registry table
 * 
 * @subpackage models
 * @author Matthew Toledo <matthew.toledo@g####.com>
 */
class Model_Registry extends Model
{
	
	public function __construct()
	{
		parent::__construct();
		$this->s_table = 'registry';
		$this->s_primary_col = 'registry_id';
	}
	
	public function get_value($s_key)
	{
		$o = $this->get_by_unique('key',$s_key);
		return $o->value;
	}
	

}
