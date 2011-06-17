<?php
/**
 * @package mFrame
 * @subpackage helpers
 * @author Matthew Toledo
 */

/**
 * Fetch a POST var without incurring a PHP warning if it is not set.
 * @param string $s
 * @return mixed A value or NULL if not set.
 */
function fp($s) { return isset($_POST[$s]) ? $_POST[$s] : NULL; }

/**
 * Fetch a GET var without incurring a PHP warning if it is not set.
 * @param string $s
 * @return mixed A value or NULL if not set.
 */
function fg($s) { return isset($_GET[$s]) ? $_GET[$s] : NULL; }

/**
 * Fetch a REQUEST var without incurring a PHP warning if it is not set.
 * @param string $s
 * @return mixed A value or NULL if not set.
 */
function fr($s) { return isset($_REQUEST[$s]) ? $_REQUEST[$s] : NULL; }

/**
 * Fetch a SESSION var without incurring a PHP warning if it is not set.
 * @param string $s
 * @return mixed A value or NULL if not set.
 */
function fs($s) { return isset($_SESSION[$s]) ? $_SESSION[$s] : NULL; }

/**
 * Fetch a COOKIE var without incurring a PHP warning if it is not set.
 * @param string $s
 * @return mixed A value or NULL if not set.
 */
function fc($s) { return isset($_COOKIE[$s]) ? $_COOKIE[$s] : NULL; }

/**
 * Fetch a value from any associative array without incurring a PHP warning if
 * the array key is not set.
 * @param string $s
 * @param array $a
 * @return mixed Any value including 0 or FALSE.  Returns NULL key was not set.
 */
function fa($s,$a) { return isset($a[$s]) ? $a[$s] : NULL; }


if (!function_exists('money_format')) 
{

	/**
	 * An implementation of the function money_format for the platforms that do 
	 * not have it (windows, for example). 
	 *
	 * The function accepts to same string of format accepts for the
	 * original function of the PHP. 
	 * 
	 * (Sorry. my writing in English is very bad) 
	 * 
	 * The function is tested using PHP 5.1.4 in Windows XP and Apache WebServer.
	 * @link http://us.php.net/manual/en/function.money-format.php#89060
	 * @author Rafael M. Salvioni
	 */
	function money_format($format, $number)
	{
		$regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
				  '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
		if (setlocale(LC_MONETARY, 0) == 'C') {
			setlocale(LC_MONETARY, '');
		}
		$locale = localeconv();
		preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
		foreach ($matches as $fmatch) {
			$value = floatval($number);
			$flags = array(
				'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
							   $match[1] : ' ',
				'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
				'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
							   $match[0] : '+',
				'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
				'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
			);
			$width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
			$left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
			$right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
			$conversion = $fmatch[5];

			$positive = true;
			if ($value < 0) {
				$positive = false;
				$value  *= -1;
			}
			$letter = $positive ? 'p' : 'n';

			$prefix = $suffix = $cprefix = $csuffix = $signal = '';

			$signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
			switch (true) {
				case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
					$prefix = $signal;
					break;
				case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
					$suffix = $signal;
					break;
				case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
					$cprefix = $signal;
					break;
				case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
					$csuffix = $signal;
					break;
				case $flags['usesignal'] == '(':
				case $locale["{$letter}_sign_posn"] == 0:
					$prefix = '(';
					$suffix = ')';
					break;
			}
			if (!$flags['nosimbol']) {
				$currency = $cprefix .
							($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
							$csuffix;
			} else {
				$currency = '';
			}
			$space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';

			$value = number_format($value, $right, $locale['mon_decimal_point'],
					 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
			$value = @explode($locale['mon_decimal_point'], $value);

			$n = strlen($prefix) + strlen($currency) + strlen($value[0]);
			if ($left > 0 && $left > $n) {
				$value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
			}
			$value = implode($locale['mon_decimal_point'], $value);
			if ($locale["{$letter}_cs_precedes"]) {
				$value = $prefix . $currency . $space . $value . $suffix;
			} else {
				$value = $prefix . $value . $space . $currency . $suffix;
			}
			if ($width > 0) {
				$value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
						 STR_PAD_RIGHT : STR_PAD_LEFT);
			}

			$format = str_replace($fmatch[0], $value, $format);
		}
		return $format;
	} 
	
	
}