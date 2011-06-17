<?php
/**
 * @package mFrame
 */
/**
 * Master model class.
 * 
 * @subpackage models
 * @author Matthew Toledo <matthew.toledo@g####.com>
 */
class Model
{
	/**
	 * The name of the current table
	 * @var string 
	 */
	protected $s_table;
	
	/**
	 * The name of the primary column in the current table
	 * @var string
	 */
	protected $s_primary_col;
	
	/**
	 * Database Connection
	 * @var MattSQL
	 */
	protected $o_db;
	
	
	/**
	 * Main constructor
	 */
	public function __construct()
	{
		$this->o_db = MattSQL::getInstance()->setMode('OBJECT');
	}
	
	/**
	 * Fetch results by a unique column
	 * 
	 * @param string $s_col
	 * @param string $s_val
	 * @return stdClass 
	 */
	public function get_by_unique($s_col,$s_val)
	{
		$o = $this->o_db->q("SELECT * FROM `{$this->s_table}` WHERE `{$s_col}` = '{$s_val}'")->fetchRow();
		return $o;
	}
	
	/**
	 * Return the entire contents of the current table as a JSON object.
	 * 
	 * @return string
	 */
	public function export_json()
	{
		$o = new stdClass();
		
		$a = $this->o_db->setMode('OBJECT')->q("SELECT * FROM `{$this->s_table}`")->fetchAllRows();
		foreach ($a as $o)
		{
			$a_return[$o->{$this->s_primary_col}] = $o;
		}
		return json_encode($a_return);
		
	}
}
