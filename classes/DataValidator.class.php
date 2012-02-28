<?php

class DataValidator
{
	private function dateTimeErrors()
	{
		$errs = date_get_last_errors();
		return ($errs['error_count'] > 0) || ($errs['warning_count'] > 0);
	}

	// $date format:
	//	'12/25/2011', '12-25-2011', '12.25.2011', or '12 25 2011'
	// $time format:
	//	'11:00 PM', '1:00 AM', '09:30 PM', etc.
	public function validDateTime($date, $time)
	{
		$dateR = preg_replace('/[-\. ]/', '/', $date);
		$dateString = $dateR . " " . $time;
		$dateObj = date_create_from_format("m/d/Y g:i a", $dateString);

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
