<?php
defined( 'ABSPATH' ) or die( 'No direct script access allowed' );

/*
add_action( 'init', 'imd_handle_post' );

function imd_handle_post(){
	
}*/

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
	$isLeapYear = false;
	if (($baseYear % 4) == 0){
		$isLeapYear = true;
	}
	return $isLeapYear;
}

function imd_get_base_year($key){
	$keyMappings = [
		"0.00893" => array(1, "AFO", "SUNDAY"),
		"0.01786" => array(2, "NKWO", "MONDAY"),
		"0.02679" => array(3, "EKE", "TUESDAY"),
		"0.03571" => array(4, "ORIE", "WEDNESDAY"),
		"0.04464" => array(5, "NKWO", "FRIDAY"),
		"0.05357" => array(6, "EKE", "SATURDAY"),
		"0.06250" => array(7, "ORIE", "SUNDAY"),
		"0.07143" => array(8, "AFO", "MONDAY"),
		"0.08036" => array(9, "EKE", "WEDNESDAY"),
		"0.08929" => array(10, "ORIE", "THURSDAY"),
		"0.09821" => array(11, "AFO", "FRIDAY"),
		"0.10714" => array(12, "NKWO", "SATURDAY"),
		"0.11607" => array(13, "ORIE", "MONDAY"),
		"0.12500" => array(14, "AFO", "TUESDAY"),
		"0.13393" => array(15, "NKWO", "WEDNESDAY"),
		"0.14286" => array(16, "EKE", "THURSDAY"),
		"0.15179" => array(17, "AFO", "SATURDAY"),
		"0.16071" => array(18, "NKWO", "SUNDAY"),
		"0.16964" => array(19, "EKE", "MONDAY"),
		"0.17857" => array(20, "ORIE", "TUESDAY"),
		"0.18750" => array(21, "NKWO", "THURSDAY"),
		"0.19643" => array(22, "EKE", "FRIDAY"),
		"0.20536" => array(23, "ORIE", "SATURDAY"),
		"0.21429" => array(24, "AFO", "SUNDAY"),
		"0.22321" => array(25, "EKE", "TUESDAY"),
		"0.23214" => array(26, "ORIE", "WEDNESDAY"),
		"0.24107" => array(27, "AFO", "THURSDAY"),
		"0.25000" => array(28, "NKWO", "FRIDAY"),
		"0.25893" => array(29, "ORIE", "SUNDAY"),
		"0.26786" => array(30, "AFO", "MONDAY"),
		"0.27679" => array(31, "NKWO", "TUESDAY"),
		"0.28571" => array(32, "EKE", "WEDNESDAY"),
		"0.29464" => array(33, "AFO", "FRIDAY"),
		"0.30357" => array(34, "NKWO", "SATURDAY"),
		"0.31250" => array(35, "EKE", "SUNDAY"),
		"0.32143" => array(36, "ORIE", "MONDAY"),
		"0.33036" => array(37, "NKWO", "WEDNESDAY"),
		"0.33929" => array(38, "EKE", "THURSDAY"),
		"0.34821" => array(39, "ORIE", "FRIDAY"),
		"0.35714" => array(40, "AFO", "SATURDAY"),
		"0.36607" => array(41, "EKE", "MONDAY"),
		"0.37500" => array(42, "ORIE", "TUESDAY"),
		"0.38393" => array(43, "AFO", "WEDNESDAY"),
		"0.39286" => array(44, "NKWO", "THURSDAY"),
		"0.40179" => array(45, "ORIE", "SATURDAY"),
		"0.41071" => array(46, "AFO", "SUNDAY"),
		"0.41964" => array(47, "NKWO", "MONDAY"),
		"0.42857" => array(48, "EKE", "TUESDAY"),
		"0.43750" => array(49, "AFO", "THURSDAY"),
		"0.44643" => array(50, "NKWO", "FRIDAY"),
		"0.45536" => array(51, "EKE", "SATURDAY"),
		"0.46429" => array(52, "ORIE", "SUNDAY"),
		"0.47321" => array(53, "NKWO", "TUESDAY"),
		"0.48214" => array(54, "EKE", "WEDNESDAY"),
		"0.49107" => array(55, "ORIE", "THURSDAY"),
		"0.50000" => array(56, "AFO", "FRIDAY"),
		"0.50893" => array(57, "EKE", "SUNDAY"),
		"0.51786" => array(58, "ORIE", "MONDAY"),
		"0.52679" => array(59, "AFO", "TUESDAY"),
		"0.53571" => array(60, "NKWO", "WEDNESDAY"),
		"0.54464" => array(61, "ORIE", "FRIDAY"),
		"0.55357" => array(62, "AFO", "SATURDAY"),
		"0.56250" => array(63, "NKWO", "SUNDAY"),
		"0.57143" => array(64, "EKE", "MONDAY"),
		"0.58036" => array(65, "AFO", "WEDNESDAY"),
		"0.58929" => array(66, "NKWO", "THURSDAY"),
		"0.59821" => array(67, "EKE", "FRIDAY"),
		"0.60714" => array(68, "ORIE", "SATURDAY"),
		"0.61607" => array(69, "NKWO", "MONDAY"),
		"0.62500" => array(70, "EKE", "TUESDAY"),
		"0.63393" => array(71, "ORIE", "WEDNESDAY"),
		"0.64286" => array(72, "AFO", "THURSDAY"),
		"0.65179" => array(73, "EKE", "SATURDAY"),
		"0.66071" => array(74, "ORIE", "SUNDAY"),
		"0.66964" => array(75, "AFO", "MONDAY"),
		"0.67857" => array(76, "NKWO", "TUESDAY"),
		"0.68750" => array(77, "ORIE", "THURSDAY"),
		"0.69643" => array(78, "AFO", "FRIDAY"),
		"0.70536" => array(79, "NKWO", "SATURDAY"),
		"0.71429" => array(80, "EKE", "SUNDAY"),
		"0.72321" => array(81, "AFO", "TUESDAY"),
		"0.73214" => array(82, "NKWO", "WEDNESDAY"),
		"0.74107" => array(83, "EKE", "THURSDAY"),
		"0.75000" => array(84, "ORIE", "FRIDAY"),
		"0.75893" => array(85, "NKWO", "SUNDAY"),
		"0.76786" => array(86, "EKE", "MONDAY"),
		"0.77679" => array(87, "ORIE", "TUESDAY"),
		"0.78571" => array(88, "AFO", "WEDNESDAY"),
		"0.79464" => array(89, "EKE", "FRIDAY"),
		"0.80357" => array(90, "ORIE", "SATURDAY"),
		"0.81250" => array(91, "AFO", "SUNDAY"),
		"0.82143" => array(92, "NKWO", "MONDAY"),
		"0.83036" => array(93, "ORIE", "WEDNESDAY"),
		"0.83929" => array(94, "AFO", "THURSDAY"),
		"0.84821" => array(95, "NKWO", "FRIDAY"),
		"0.85714" => array(96, "EKE", "SATURDAY"),
		"0.86607" => array(97, "AFO", "MONDAY"),
		"0.87500" => array(98, "NKWO", "TUESDAY"),
		"0.88393" => array(99, "EKE", "WEDNESDAY"),
		"0.89286" => array(100, "ORIE", "THURSDAY"),
		"0.90179" => array(101, "NKWO", "SATURDAY"),
		"0.91071" => array(102, "EKE", "SUNDAY"),
		"0.91964" => array(103, "ORIE", "MONDAY"),
		"0.92857" => array(104, "AFO", "TUESDAY"),
		"0.93750" => array(105, "EKE", "THURSDAY"),
		"0.94643" => array(106, "ORIE", "FRIDAY"),
		"0.95536" => array(107, "AFO", "SATURDAY"),
		"0.96429" => array(108, "NKWO", "SUNDAY"),
		"0.97321" => array(109, "ORIE", "TUESDAY"),
		"0.98214" => array(110, "AFO", "WEDNESDAY"),
		"0.99107" => array(111, "NKWO", "THURSDAY"),
		"0" => array(112, "EKE", "FRIDAY")
	];
	
    if ($key == null){
        return null;
    }
    return $keyMappings[$key];
}

/**
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

/**
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
	$key = "0";

    if(($yearIntValue % 112) != 0){
        $floatValue = round($yearIntValue/112, 5);
        $whole = floor($floatValue);
        $key = sprintf("%.5f", ($floatValue - $whole));
    }

	#echo "\nKey: ". $key;
    $baseYearProperties = imd_get_base_year($key);
    if ($baseYearProperties == null){
		$error = "No Matching Calendar Year";
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