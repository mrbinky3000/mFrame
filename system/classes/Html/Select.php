<?php
/**
 * @package mframe
 */
/**
 * This class can be used to create and display an xhtml-formatted SELECT dropdown
 * form element.  Supply it with an array of options, selected options, and 
 * tag attributes and it will put it all together for you.
 * 
 * <code>
 * // Example
 * $a_options = array(
 *	'A' => 'This is A',
 *  'B' => 'This is B',
 *	'C' => 'This is C',
 * );
 *
 * // simulate us submitting a post value for this example
 * $_POST['letter'] = 'C';
 *
 * // Make a new object
 * $o_folder_select = new Html_Select($_POST['letter'], $a_options , 'letter');
 * </code>
 *
 * Outputs the following HTML
 *
 * <code>
 * 	<select name="letter" id="letter">
 *		<option value="">Pick One</option>
 *		<option value="A">Letter A</option>
 *		<option value="B">Letter B</option>
 *		<option value="C" selected="selected">Letter C</option>
 *	</select>
 * </code>
 *
 * @author Matthew Toledo <matthew.toledo@gmail.com>
 * @subpackage classes
 */
class Html_Select
{
	
	/**
	 * An associative array where the array key is an XTHML tag attribute and 
	 * the array value is that attribute's value. 
	 * @var array
	 */
	public $a_attributes = array();
	
	/**
	 * An associative array where the array key is an HTML option value and
	 * the array value is the HTML option label.  For example:
	 * <code>
	 *	<option value="[KEY]">[VALUE]</option>
	 * </code>
	 * @var array
	 */
	public $a_select_options = array();
	
	/**
	 * A regular indexed array containing option values that should be pre-selected
	 * in the dropdown list that is generated.
	 * @var array
	 */
	public $a_selected = array();
	
	
	/**
	 * When true, a "Pick One" option is added to the list of $a_select_options
	 * @see $a_select_options
	 * @var bool
	 */
	public $b_pick_one = TRUE;
	
	/**
	 * Integer that decides where the "Pick One" select option goes.  0 = top
	 * of the list. 1 = bottom of the list.
	 * @var integer
	 */
	public $i_pick_one_location = 0;
	
	/**
	 * You can replace the default "Pick One" label with this text string.
	 * @var string
	 */
	public $s_pick_one_label = "Pick One";
	
	/**
	 * You can replace the defalut "Pick One" value with this text string.
	 * @var string
	 */
	public $s_pick_one_value = "";
	
	
	/**
	 * Class constructor
	 * 
	 * Supply the constructor with zero to three optional arguments.
	 * 
	 * Most Common Usages
	 * ------------------
	 *
	 * Supply the constructor with two args:
	 *  - The first is an associative array that is used for the select options 
	 *  - second is a string that holds the ID and NAME attributes.	
	 * 
	 * Supply the constructor three args:
	 * - The first argument contains the option values that are to be pre-selected. 
	 *   It can be either a string or an array.
	 * - The second argument contains the options for the dropdown list.  The 
	 *   value attribute is the array key, the label is the array key value.
	 * - The third argument holds attributes for the opening SELECT tag.  It can
	 *   be an asociative array of key/value pairs. Or a single string.  If it 
	 *   is a string, the string value is assigned the id and name attributes.
	 * 
	 * This method is chainable.
	 * 
	 * @param mixed Optional array or string. String = selected values.  NULL or a string of '' =  no selected values
	 * @param array Optional associative array with select options where key => value :: option => label or a string for the ID and NAME attributes
	 * @param mixed Optional array or string.  If an array, its an html attribute associative array where key => value :: attribute => value. If it is a string, set name and id to the striing.
	 * @return Html_Select
	 */
	public function __construct()
	{
		
		if (func_num_args())
		{
			$a_args = func_get_args();
			
			// pre selected option values
			if (is_null($a_args[0]) || '' == $a_args[0])
			{
				$this->a_selected = array();
			}
			if (!is_array($a_args[0]))
			{
				$this->a_selected[] = $a_args[0];
			}
			if (is_array($a_args[0]))
			{
				$this->a_selected = array_values($a_args[0]);
			}
			
			
			// select options
			if (is_array($a_args[1]))
			{
				$this->a_select_options = $a_args[1];
			}
			
			
			// attributes
			if (is_array($a_args[2]))
			{
				$this->a_attributes = $a_args[2];
			}
			
			if (is_string($a_args[2]))
			{
				$this->a_attributes['id'] = $a_args[2];
				$this->a_attributes['name'] = $a_args[2];
			}
		
		}
		
		return $this;
	}
	
	
	/**
	 * Outputs an XHTML compatible SELECT list with options. Any pre-selected
	 * options will get the "selected" attribute.
	 * 
	 * @return string
	 */
	public function display()
	{
		$a = array();
		$a[] = '<select '.$this->get_attribute_string().'>';
		$a = array_merge($a, $this->make_options_array());
		$a[] = '</select>';
		return implode("\n",$a);
	}
	


	/**
	 * Generate the HTML for each option tag.
	 * 
	 * Examines the a_selected_options property of this class, factors in other
	 * class properties like $i_pick_one_location, $b_pick_one, $s_pick_one_value
	 * and creates an array where each cell represents a row of HTML code.  It
	 * consists of all the "option" tag elements.
	 *
	 * @return array
	 */
	public function make_options_array()
	{
		$a_option = array();
		$i_selected_count = count($this->a_selected);
		
		if ($this->b_pick_one && 0 === $this->i_pick_one_location)
		{
			$s_selected = (!$i_selected_count) ? ' selected="selected"': '';
			$a_option[] = '<option value="'.$this->s_pick_one_value.'"'.$s_selected.'>'.$this->s_pick_one_label.'</option>';
		}
		foreach ($this->a_select_options as $s_value => $s_label)
		{
			$s_selected = (in_array($s_value,$this->a_selected)) ?' selected="selected"': '';
			$a_option[] = '<option value="'.$s_value.'"'.$s_selected.'>'.$s_label.'</option>';			
		}
		if ($this->b_pick_one && 1 === $this->i_pick_one_location)
		{
			$s_selected = (!$i_selected_count) ? ' selected="selected"': '';
			$a_option[] = '<option value="'.$this->s_pick_one_value.'"'.$s_selected.'>'.$this->s_pick_one_label.'</option>';
		}
		return $a_option;
	}
		
	/**
	 * Examines the a_attributes property of the class and converts it
	 * into a string that represents XHTML-compatible tag attributes.
	 *
	 * <code>
	 * $this->a_attributes = array('id'=>'fred','name'=>'fred');
	 * $s_attributes = $this->get_attribute_string();
	 * // $s_attributes becomes:
	 * // id="fred" name="fred"
	 * </code>
	 *
	 * @return string
	 */
	public function get_attribute_string()
	{
		
		$s_return = '';
		$a_bits = array();
		
		// escape attribute values to prevent XSS
		$a_escape = array(
			'&' => '&amp;', 
			'<' => '&lt;', 
			'>' => '&gt;', 
			'"' => '&quot;',
			"'" => '&#39;'
		);

		foreach ($this->a_attributes as $s_key => $s_value)
		{
			$s_value = strtr($s_value,$a_escape);
			$s_key = strtolower(trim($s_key));						
			$a_bits[] = $s_key.'="'.$s_value.'"';
		
		}
		$s_return = implode(' ',$a_bits);
		return $s_return;
		
	}
	
	
	/**
	 * Set an attribute for the select tag
	 * 
	 * A utility method to append values to the a_attributes property. This is
	 * used to apply XHTML-compatible attributes to the "SELECT" tag.
	 * This can have one argument or two.
	 * 
	 * This method is chainable.
	 * 
	 * <code>
	 * $this->set_attribute('fred');  // sets the id and name attributes to 'fred'
	 * $this->set_attribute('target','_new') // first argument is attribute name, second is attribute value
	 * $this->set_attribute($a_attribute_value); // reads an associative array of attribute-value pairs.
	 * </code>
	 * 
	 * @param mixed	Either A string or an associatve array of attribute name-value pairs.
	 * @param mixed An optional string representing an attribute value
	 * @return Html_Select 
	 */
	public function set_attribute()
	{
		if (func_num_args())
		{
			$a_args = func_get_args();
			
			if ( 2 == count($a_args) && is_string($a_args[0]) && is_string($a_args[1]) )
			{
				$this->a_attributes[$a_args[0]] = $a_args[1];
			}
			
			if ( 1 == count($a_args) && is_array($a_args[0]) )
			{
				foreach($a_args[0] as $s_attr_name => $s_attr_value)
				{
					$this->a_attributes[$s_attr_name] = $s_attr_value;
				}
			}

			if ( 1 == count($a_args) && is_string($a_args[0]) )
			{
				$this->a_attributes['id'] = $a_args[0];
				$this->a_attributes['name'] = $a_args[0];
			}	
		}
		
		return $this;
	}
	
}