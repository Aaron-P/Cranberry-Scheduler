<?php
/**
 * Defines the DataValidator class.
 * @file      DataValidator.class.php
 * @author    Aaron Papp
 * @author    Shawn LeMaster
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

/**
 * Handles validating user data that comes off website forms.
 * @class DataValidator
 */
class DataValidator
{
	/**
     * Checks if any errors occured when calling date_create_from_format
     * in the validDateTime function below. This is a workaround for PHP's
     * excessive permissiveness in allowing and attempting to correct
     * invalid dates/times.
     */
	private function dateTimeErrors()
	{
		$errs = date_get_last_errors();
		return ($errs["error_count"] > 0) || ($errs["warning_count"] > 0);
	}


	/**
     * Validates that the string passed in represents a valid date and time
     * to be used for scheduling a meeting.
     * @param[in] $datetime A date and time as a string in the format e.g. "12/25/2012 1:30 PM"
     */
	public function validDateTime($datetime)
	{
		$datetimeR = preg_replace("/[-\.]/", "/", $datetime);
		$dateObj = date_create_from_format("m/d/Y g:i a", $datetimeR);

		if ($dateObj && !$this->dateTimeErrors())
			return date_timestamp_get($dateObj);

		return false;
	}


	/**
     * Validates that a value falls within a certain range.
     * @param[in] $min The minimum bound of the range
     * @param[in] $max The maximum bound of the range
     * @param[in] $val The value to check
     */
	public function checkRange($min, $max, $val)
	{
		return $val >= $min && $val <= $max;
	}
}

?>
