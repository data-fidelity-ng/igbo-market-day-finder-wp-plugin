<?php
defined( 'ABSPATH' ) or die( 'No direct script access allowed' );

function imd_get_market_day_by_index($index){
	$marketDays =["AFO", "NKWO", "EKE", "ORIE"];
	return $marketDays[$index];
}

function imd_get_gregorian_day_by_index($index){
	$gregorianDays = ["SUNDAY", "MONDAY", "TUESDAY", "WEDNESDAY", "THURSDAY", "FRIDAY", "SATURDAY"];
	return $gregorianDays[$index];
}

function imd_get_abbreviated_gregorian_day_by_index($index){
	$gregorianDays = ["SUN", "MON", "TUE", "WED", "THUR", "FRI", "SAT"];
	return $gregorianDays[$index];
}

function imd_get_market_day_index($day){
	$marketDaysIndex = [
		"AFO" => 0,
		"NKWO" => 1,
		"EKE" => 2,
		"ORIE" => 3
	];
	
	return $marketDaysIndex[$day];
}

function imd_get_gregorian_day_index($day){
	$gregorianDaysIndex = [
		"SUNDAY" => 0, 
		"MONDAY" => 1, 
		"TUESDAY" => 2, 
		"WEDNESDAY" => 3, 
		"THURSDAY" => 4, 
		"FRIDAY" => 5, 
		"SATURDAY" => 6
	];
	
	return $gregorianDaysIndex[$day];
}

function imd_get_month_name($monthIndex){
	$monthMappings = [
		1 => "January",
		2 => "February",
		3 => "March",
		4 => "April",
		5 => "May",
		6 => "June",
		7 => "July",
		8 => "August",
		9 => "September",
		10 => "October",
		11 => "November",
		12 => "December"
	];
	
	return $monthMappings[$monthIndex];
}

function imd_get_number_of_days_in_month($monthInt, $isLeapYear=false){
	$monthDaysMappings = [
		1 => 31,
		2 => $isLeapYear ? 29:28,
		3 => 31,
		4 => 30,
		5 => 31,
		6 => 30,
		7 => 31,
		8 => 31,
		9 => 30,
		10 => 31,
		11 => 30,
		12 => 31
	];

	return $monthDaysMappings[$monthInt];
}

function imd_is_leap_year($baseYear){
	return (($baseYear % 100) == 0) ? (($baseYear % 400) == 0) : (($baseYear % 4) == 0);
}

function imd_get_first_day_of_year($year) {
	if ($year < 1) {
		return null;
	}
	return date('l',strtotime(date($year.'-01-01')));
}

function imd_get_base_year_for_non_leap_year($key) {
	$keyMappings = [
		"Monday" => array(1, "NKWO", "MONDAY"),
		"Tuesday" => array(2, "EKE", "TUESDAY"),
		"Wednesday" => array(3, "ORIE", "WEDNESDAY"),
		"Thursday" => array(9, "AFO", "THURSDAY"),
		"Friday" => array(10, "NKWO", "FRIDAY"),
		"Saturday" => array(5, "EKE", "SATURDAY"),
		"Sunday" => array(6, "ORIE", "SUNDAY"),
	];

	if ($key == null) {
		return null;
	}

    return $keyMappings[$key];
}

function imd_get_base_year_for_leap_year($key) {
	$keyMappings = [
		"Monday" => array(24, "EKE", "MONDAY"),
		"Tuesday" => array(8, "ORIE", "TUESDAY"),
		"Wednesday" => array(20, "AFO", "WEDNESDAY"),
		"Thursday" => array(4, "NKWO", "THURSDAY"),
		"Friday" => array(16, "EKE", "FRIDAY"),
		"Saturday" => array(28, "ORIE", "SATURDAY"),
		"Sunday" => array(12, "AFO", "SUNDAY"),
	];

	if ($key == null) {
		return null;
	}

    return $keyMappings[$key];
}

function imd_get_base_year($year){
	$key = imd_get_first_day_of_year($year);
	$isLeapYear = false;
	$isLeapYear = imd_is_leap_year($year);

	if ($isLeapYear) {
		return imd_get_base_year_for_leap_year($key);
	} else {
		return imd_get_base_year_for_non_leap_year($key);
	}
}

/*
  Validates the request from form submission  
 */
function imd_validate_request($year, $month = NULL, $day = NULL){
	
	if (!is_numeric($year) || (intval($year) != floatval($year)) || intval($year) <= 0){
		$error = "Year Should Be A Positive Non-Zero Whole Number";
		throw new Exception($error);
	}

	if($month == NULL && $day != NULL){
		$error = "Month can not be null when day has value";
		throw new Exception($error);
	}
	
	if ($month != NULL && (!is_numeric($month) || intval($month) != floatval($month) || intval($month) <= 0)){
		$error = "Month Should Be A Positive Non-Zero Whole Number";
		throw new Exception($error);
	}
	
	if ($day != NULL && (!is_numeric($day) || intval($day) != floatval($day) || intval($day) <= 0)){
		$error = "Day Should Be A Positive Non-Zero Whole Number";
		throw new Exception($error);
	}
	
	if ($month != NULL && intval($month) > 12){
		$error = "Invalid Month";
		throw new Exception($error);
	}

	$isLeapYear = (intval($year) % 4) == 0;
	
	if ($day != NULL && (intval($month) == 2) && $isLeapYear && intval($day) > 29){
		$error = "Invalid Day";
		throw new Exception($error);
	}
	
	if ($day != NULL && (intval($month) == 2) && !$isLeapYear && intval($day) > 28){
		$error = "Invalid Day";
		throw new Exception($error);
	}
	
	$thirtyDayMonths = array(4,6,9,11);
	if ($day != NULL && in_array(intval($month), $thirtyDayMonths) && intval($day) > 30){
		$error = "Invalid Day";
		throw new Exception($error);
	}
	
	$thirtyOneDayMonths = array(1,3,5,7,8,10,12);
	if ($day != NULL && in_array(intval($month), $thirtyOneDayMonths) && intval($day) > 31){
		$error = "Invalid Day";
		throw new Exception($error);
	}
	
	return;
}

/*
Gets the numbers of days
from the beginning of the year passed in
to the day of the month passed in
*/
function imd_get_number_of_days($baseYear, $monthInt, $dayInt){
	$isLeapYear = false;
	if (imd_is_leap_year($baseYear)){
		$isLeapYear = true;
	}
	
	switch ($monthInt) {
		case 1: #January
			return $dayInt;
			break;
		case 2: #February
			return 31 + $dayInt;
			break;
		case 3: #March
			return ($isLeapYear) ? 60 + $dayInt : 59 + $dayInt;
			break;
		case 4: #April
			return ($isLeapYear) ? 91 + $dayInt : 90 + $dayInt;
			break;
		case 5: #May
			return ($isLeapYear) ? 121 + $dayInt : 120 + $dayInt;
			break;
		case 6: #June
			return ($isLeapYear) ? 152 + $dayInt : 151 + $dayInt;
			break;
		case 7: #July
			return ($isLeapYear) ? 182 + $dayInt : 181 + $dayInt;
			break;
		case 8: #August
			return ($isLeapYear) ? 213 + $dayInt : 212 + $dayInt;
			break;
		case 9: #September
			return ($isLeapYear) ? 244 + $dayInt : 243 + $dayInt;
			break;
		case 10: #October
			return ($isLeapYear) ? 274 + $dayInt : 273 + $dayInt;
			break;
		case 11: #November
			return ($isLeapYear) ? 305 + $dayInt : 304 + $dayInt;
			break;
		case 12: #December
			return ($isLeapYear) ? 335 + $dayInt : 334 + $dayInt;
			break;
		
	}
}

function imd_get_single_market_day($yearIntValue, $monthIntValue, $dayIntValue){
    $baseYearProperties = imd_get_base_year($yearIntValue);
    if ($baseYearProperties == null){
		$error = "No Matching Calendar Year for year:".$yearIntValue;
		throw new Exception($error);
    }
	
	#the base year with which the passed in year shares Calendar
	$baseYear = $baseYearProperties[0];
	#first igbo market day of that year
	$firstMarketDay = $baseYearProperties[1];
	#first gregorian day of that year
	$firstGregorianDay = $baseYearProperties[2];
	
	#index of the first igbo market day
	#echo "\nfirstMarketDay: ". $firstMarketDay;
	$firstMarketDayIndex = imd_get_market_day_index($firstMarketDay);
	#index of the first gregorian day
	$firstGregorianDayIndex = imd_get_gregorian_day_index($firstGregorianDay);
    #get the total numbers of days so far, starting from the beginning of the year
	$numberOfDays = imd_get_number_of_days($baseYear, $monthIntValue, $dayIntValue);
	
	#subtract 1 from total number of days
	#add to day index
	#then get the modulus of dividing by 4
	#echo "\nfirstMarketDayIndex: ".$firstMarketDayIndex;
	#echo "\nnumberOfDays: ".$numberOfDays;
	$resultingMarketDayIndex = (($numberOfDays - 1) + $firstMarketDayIndex) % 4;
	$resultingGregorianDayIndex = (($numberOfDays - 1) + $firstGregorianDayIndex) % 7;
	
	#use the indices found to get the names of those days
	$resultingMarketDay = imd_get_market_day_by_index($resultingMarketDayIndex);
	$resultingGregorianDay = imd_get_gregorian_day_by_index($resultingGregorianDayIndex);
	$resultingAbbreviatedGregorianDay = imd_get_abbreviated_gregorian_day_by_index($resultingGregorianDayIndex);

	$isCurrentDay = false;
	$currentDate = getDate();
	if($currentDate["mday"] == $dayIntValue 
	&& $currentDate["mon"] == $monthIntValue 
	&& $currentDate["year"] == $yearIntValue){
		$isCurrentDay = true;
	}
	
	$result = [
		"market_day" => $resultingMarketDay,
		"gregorian_day" => $resultingGregorianDay,
		"abbr_gregorian_day" => $resultingAbbreviatedGregorianDay,
		"digit_day" => $dayIntValue,
		"is_current_day" => $isCurrentDay
	];

	return $result;
}

function imd_get_market_days_in_month($yearIntValue, $monthIntValue){

	$content = array();
	$isLeapYear = false;
	if (imd_is_leap_year($yearIntValue)){
		$isLeapYear = true;
	}

	$numberOfDaysInMonth = imd_get_number_of_days_in_month($monthIntValue, $isLeapYear);

	for($i=0; $i < $numberOfDaysInMonth; $i++){
		$dayIntValue = $i + 1;
		$searchResult = imd_get_single_market_day($yearIntValue, $monthIntValue, $dayIntValue);
		$content[$i] = $searchResult;
	}

	$isCurrentMonth = false;
	$currentDate = getDate();
	if($currentDate["mon"] == $monthIntValue 
	&& $currentDate["year"] == $yearIntValue){
		$isCurrentMonth = true;
	}

	$result["is_current_month"] = $isCurrentMonth;
	$result["content"] = $content;

	return $result;
}

function imd_get_market_days_in_year($yearIntValue){

	$content = array();

	for($i=0; $i < 12; $i++){
		$monthIntValue = $i + 1;
		$monthName = imd_get_month_name($monthIntValue);
		$content[$monthName] = imd_get_market_days_in_month($yearIntValue, $monthIntValue);
	}
	
	$result = array();
	$isCurrentYear = false;
	$currentDate = getDate();
	if($currentDate["year"] == $yearIntValue){
		$isCurrentYear = true;
	}
	$result["is_current_year"] = $isCurrentYear;
	$result["content"] = $content;

	return $result;
}

/**
 * Processes form submission for 
 * single market day look up
 */
function imd_look_up_market_day($year, $month, $day){
    #validate request
	try{
		imd_validate_request($year, $month, $day);
	}catch (Exception $e){
		return $response = ["error" => $e->getMessage()];
	}

	$yearIntValue = intval($year);
    $monthIntValue = intval($month);
    $dayIntValue = intval($day);

	try{
		$searchResult = imd_get_single_market_day($yearIntValue, $monthIntValue, $dayIntValue);
		$monthName = imd_get_month_name($monthIntValue);
		$response = [
			"Date" => $searchResult["gregorian_day"] ." ". $monthName . " " . $dayIntValue . ", " . $yearIntValue,
			"IgboDay" => $searchResult["market_day"]
		];

    	return $response;
	} catch (Exception $e){
		return $response = ["error" => $e->getMessage()];
	}    
}

/**
 * Processes form submission for 
 * calender year market days look up
 */
function imd_look_up_market_days_for_calendar_year($year){
	#validate request
	try{
		imd_validate_request($year, NULL, NULL);
	}catch (Exception $e){
		return $response = ["error" => $e->getMessage()];
	}

	$yearIntValue = intval($year);

	try {
		$response = imd_get_market_days_in_year($yearIntValue);
		return $response;
	} catch(Exception $e) {
		return $response = ["error" => $e->getMessage()];
	}
}

//echo json_encode(imd_get_market_days_in_year(2016));
//echo json_encode(imd_look_up_market_day(2100, 03, 24));
