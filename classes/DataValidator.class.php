<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

class DataValidator
{
	private function dateTimeErrors()
	{
		$errs = date_get_last_errors();
		return ($errs['error_count'] > 0) || ($errs['warning_count'] > 0);
	}


	public function validDateTime($datetime)
	{
		$datetimeR = preg_replace('/[-\.]/', '/', $datetime);
		$dateObj = date_create_from_format("m/d/Y g:i a", $datetimeR);

		if ($dateObj && !$this->dateTimeErrors())
			return date_timestamp_get($dateObj);

		return false;
	}


	public function checkRange($min, $max, $val)
	{
		return $val >= $min && $val <= $max;
	}
}

?>
