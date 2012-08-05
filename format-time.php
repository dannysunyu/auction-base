<?php
	

function FormatTime($timestamp, $currentTime)
{
	$timestamp = strtotime($timestamp);
	
	// Get time difference and setup arrays
	$difference = strtotime($currentTime) - $timestamp;
	
	$periods = array("second", "minute", "hour", "day", "week", "month", "years");
	$lengths = array("60","60","24","7","4.35","12");
 
	// Past or present
	if ($difference >= 0) 
	{
		$ending = "ago";
	}
	else
	{
		$difference = -$difference;
		$ending = "to go";
	}
 
	// Figure out difference by looping while less than array length
	// and difference is larger than lengths.
	$arr_len = count($lengths);
	for($j = 0; $j < $arr_len && $difference >= $lengths[$j]; $j++)
	{
		$difference /= $lengths[$j];
	}
 
	// Round up		
	$difference = round($difference);
 
	// Make plural if needed
	if($difference != 1) 
	{
		$periods[$j].= "s";
	}
 
	// Default format
	$text = "$difference $periods[$j] $ending";
 
 
	// over 24 hours
	if($j > 2)
	{
		// future date over a day formate with year
		if($ending == "to go")
		{
			if($j == 3 && $difference == 1)
			{
				$text = "tomorrow at ". date("g:i a", $timestamp);
			}
			else
			{
				$text = date("F j, Y \a\\t g:i a", $timestamp);
			}
			return $text;
		}
 
		if($j == 3 && $difference == 1) // Yesterday
		{
			$text = "yesterday at ". date("g:i a", $timestamp);
		}
		else if($j == 3) // Less than a week display -- Monday at 5:28pm
		{
			$text = date("l \a\\t g:i a", $timestamp);
		}
		else if($j < 6 && !($j == 5 && $difference == 12)) // Less than a year display -- June 25 at 5:23am
		{
			$text = date("F j \a\\t g:i a", $timestamp);
		}
		else // if over a year or the same month one year ago -- June 30, 2010 at 5:34pm
		{
			$text = date("F j, Y \a\\t g:i a", $timestamp);
		}
	}
 
	return $text;
}
	
?>