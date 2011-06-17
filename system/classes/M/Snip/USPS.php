<?php
/**
 * @package mframe
 */
/**
 * A simple namespace to place common arrays that deal with the United States
 * Postal Service
 * 
 * @package mframe
 * @subpackage classes
 * @category Snips
 * @author Matthew Toledo <matthew.toledo@gmail.com>
 */
class M_Snip_USPS
{
	
	/**
	 * Offical list of USPS 2 letter abbreviations.
	 * 
	 * @link http://www.usps.com/ncsc/lookups/usps_abbreviations.html
	 * @var array
	 */
	public static $a_usps_state_abbreviations = array(
		'AL'=>'AL', 'AK'=>'AK', 'AS'=>'AS', 'AZ'=>'AZ', 'AR'=>'AR', 'CA'=>'CA', 
		'CO'=>'CO', 'CT'=>'CT', 'DE'=>'DE', 'DC'=>'DC', 'FM'=>'FM', 'FL'=>'FL', 
		'GA'=>'GA', 'GU'=>'GU', 'HI'=>'HI', 'ID'=>'ID', 'IL'=>'IL', 'IN'=>'IN', 
		'IA'=>'IA', 'KS'=>'KS', 'KY'=>'KY', 'LA'=>'LA', 'ME'=>'ME', 'MH'=>'MH', 
		'MD'=>'MD', 'MA'=>'MA', 'MI'=>'MI', 'MN'=>'MN', 'MS'=>'MS', 'MO'=>'MO', 
		'MT'=>'MT', 'NE'=>'NE', 'NV'=>'NV', 'NH'=>'NH', 'NJ'=>'NJ', 'NM'=>'NM', 
		'NY'=>'NY', 'NC'=>'NC', 'ND'=>'ND', 'MP'=>'MP', 'OH'=>'OH', 'OK'=>'OK', 
		'OR'=>'OR', 'PW'=>'PW', 'PA'=>'PA', 'PR'=>'PR', 'RI'=>'RI', 'SC'=>'SC', 
		'SD'=>'SD', 'TN'=>'TN', 'TX'=>'TX', 'UT'=>'UT', 'VT'=>'VT', 'VI'=>'VI', 
		'VA'=>'VA', 'WA'=>'WA', 'WV'=>'WV', 'WI'=>'WI', 'WY'=>'WY', 'AE'=>'AE', 
		'AA'=>'AA', 'AE'=>'AE', 'AE'=>'AE', 'AE'=>'AE', 'AP'=>'AP'	
	);
	
	/**
	 * Official list of USPS states where the key is the full name and the value
	 * is the abbreviation.
	 * 
	 * @link http://www.usps.com/ncsc/lookups/usps_abbreviations.html
	 * @var array
	 */
	public static $a_usps_states_1 = array(
		'ALABAMA' => 'AL', 'ALASKA' => 'AK', 'AMERICAN SAMOA' => 'AS', 'ARIZONA' => 
		'AZ', 'ARKANSAS' => 'AR', 'CALIFORNIA' => 'CA', 'COLORADO' => 'CO', 
		'CONNECTICUT' => 'CT', 'DELAWARE' => 'DE', 'DISTRICT OF COLUMBIA' => 'DC', 
		'FEDERATED STATES OF MICRONESIA' => 'FM', 'FLORIDA' => 'FL', 'GEORGIA' => 'GA', 
		'GUAM' => 'GU', 'HAWAII' => 'HI', 'IDAHO' => 'ID', 'ILLINOIS' => 'IL', 'INDIANA' 
		=> 'IN', 'IOWA' => 'IA', 'KANSAS' => 'KS', 'KENTUCKY' => 'KY', 'LOUISIANA' => 
		'LA', 'MAINE' => 'ME', 'MARSHALL ISLANDS' => 'MH', 'MARYLAND' => 'MD', 
		'MASSACHUSETTS' => 'MA', 'MICHIGAN' => 'MI', 'MINNESOTA' => 'MN', 'MISSISSIPPI' 
		=> 'MS', 'MISSOURI' => 'MO', 'MONTANA' => 'MT', 'NEBRASKA' => 'NE', 'NEVADA' => 
		'NV', 'NEW HAMPSHIRE' => 'NH', 'NEW JERSEY' => 'NJ', 'NEW MEXICO' => 'NM', 'NEW 
		YORK' => 'NY', 'NORTH CAROLINA' => 'NC', 'NORTH DAKOTA' => 'ND', 'NORTHERN 
		MARIANA ISLANDS' => 'MP', 'OHIO' => 'OH', 'OKLAHOMA' => 'OK', 'OREGON' => 'OR', 
		'PALAU' => 'PW', 'PENNSYLVANIA' => 'PA', 'PUERTO RICO' => 'PR', 'RHODE ISLAND' 
		=> 'RI', 'SOUTH CAROLINA' => 'SC', 'SOUTH DAKOTA' => 'SD', 'TENNESSEE' => 'TN', 
		'TEXAS' => 'TX', 'UTAH' => 'UT', 'VERMONT' => 'VT', 'VIRGIN ISLANDS' => 'VI', 
		'VIRGINIA' => 'VA', 'WASHINGTON' => 'WA', 'WEST VIRGINIA' => 'WV', 'WISCONSIN' 
		=> 'WI', 'WYOMING' => 'WY', 'Armed Forces Africa' => 'AE', 'Armed Forces 
		Americas (except Canada)' => 'AA', 'Armed Forces Canada' => 'AE', 'Armed Forces 
		Europe' => 'AE', 'Armed Forces Middle East' => 'AE', 'Armed Forces Pacific' => 
		'AP'
	);
	
	/**
	 * Official list of USPS states where the key is the abbreviation and the 
	 * value is the full name.
	 *
	 * @link http://www.usps.com/ncsc/lookups/usps_abbreviations.html
	 * @var array 
	 */
	public static $a_usps_states_2 = array(
		'AL' => 'ALABAMA' , 'AK' => 'ALASKA' , 'AS' => 'AMERICAN SAMOA' , 'AZ' => 
		'ARIZONA' , 'AR' => 'ARKANSAS' , 'CA' => 'CALIFORNIA' , 'CO' => 'COLORADO' , 
		'CT' => 'CONNECTICUT' , 'DE' => 'DELAWARE' , 'DC' => 'DISTRICT OF COLUMBIA' , 
		'FM' => 'FEDERATED STATES OF MICRONESIA' , 'FL' => 'FLORIDA' , 'GA' => 'GEORGIA' 
		, 'GU' => 'GUAM' , 'HI' => 'HAWAII' , 'ID' => 'IDAHO' , 'IL' => 'ILLINOIS' , 
		'IN' => 'INDIANA' , 'IA' => 'IOWA' , 'KS' => 'KANSAS' , 'KY' => 'KENTUCKY' , 
		'LA' => 'LOUISIANA' , 'ME' => 'MAINE' , 'MH' => 'MARSHALL ISLANDS' , 'MD' => 
		'MARYLAND' , 'MA' => 'MASSACHUSETTS' , 'MI' => 'MICHIGAN' , 'MN' => 'MINNESOTA' 
		, 'MS' => 'MISSISSIPPI' , 'MO' => 'MISSOURI' , 'MT' => 'MONTANA' , 'NE' => 
		'NEBRASKA' , 'NV' => 'NEVADA' , 'NH' => 'NEW HAMPSHIRE' , 'NJ' => 'NEW JERSEY' , 
		'NM' => 'NEW MEXICO' , 'NY' => 'NEW YORK' , 'NC' => 'NORTH CAROLINA' , 'ND' => 
		'NORTH DAKOTA' , 'MP' => 'NORTHERN MARIANA ISLANDS' , 'OH' => 'OHIO' , 'OK' => 
		'OKLAHOMA' , 'OR' => 'OREGON' , 'PW' => 'PALAU' , 'PA' => 'PENNSYLVANIA' , 'PR' 
		=> 'PUERTO RICO' , 'RI' => 'RHODE ISLAND' , 'SC' => 'SOUTH CAROLINA' , 'SD' => 
		'SOUTH DAKOTA' , 'TN' => 'TENNESSEE' , 'TX' => 'TEXAS' , 'UT' => 'UTAH' , 'VT' 
		=> 'VERMONT' , 'VI' => 'VIRGIN ISLANDS' , 'VA' => 'VIRGINIA' , 'WA' => 
		'WASHINGTON' , 'WV' => 'WEST VIRGINIA' , 'WI' => 'WISCONSIN' , 'WY' => 'WYOMING' 
		, 'AE' => 'Armed Forces Africa' , 'AA' => 'Armed Forces Americas (except 
		Canada)' , 'AE' => 'Armed Forces Canada' , 'AE' => 'Armed Forces Europe' , 'AE' 
		=> 'Armed Forces Middle East' , 'AP' => 'Armed Forces Pacific' 
	);
	

}