//fetch year response
var month_map = {};
month_map['January'] = {'next':'February'};
month_map['February'] = {'next':'March', 'previous':'January'};
month_map['March'] = {'next':'April', 'previous':'February'};
month_map['April'] = {'next':'May', 'previous':'March'};
month_map['May'] = {'next':'June', 'previous':'April'};
month_map['June'] = {'next':'July', 'previous':'May'};
month_map['July'] = {'next':'August', 'previous':'June'};
month_map['August'] = {'next':'September', 'previous':'July'};
month_map['September'] = {'next':'October', 'previous':'August'};
month_map['October'] = {'next':'November', 'previous':'September'};
month_map['November'] = {'next':'December', 'previous':'October'};
month_map['December'] = {'previous':'November'};

var month_name_map = {};
month_name_map['January'] = 'JAN';
month_name_map['February'] = 'FEB';
month_name_map['March'] = 'MAR';
month_name_map['April'] = 'APR';
month_name_map['May'] = 'MAY';
month_name_map['June'] = 'JUN';
month_name_map['July'] = 'JUL';
month_name_map['August'] = 'AUG';
month_name_map['September'] = 'SEPT';
month_name_map['October'] = 'OCT';
month_name_map['November'] = 'NOV';
month_name_map['December'] = 'DEC';

var month_index_map = [
'January',
'February',
'March',
'April',
'May',
'June',
'July',
'August',
'September',
'October',
'November',
'December'];


var currentDate = new Date();
var current_year = currentDate.getFullYear();
var current_month_index = currentDate.getMonth();
var current_month = month_index_map[current_month_index];
var focus_year = current_year;
var focus_month = current_month;
var year_response = '';
var validCaptcha = false;

jQuery(document).ready(function() {
	imd_resetMathCaptcha();
});

function showFindWidget() {
	jQuery('#imd-search-result').addClass('imd-hidden');
	jQuery('#imd-find-widget').removeClass('imd-hidden');

}

function imd_resetMathCaptcha() {
	jQuery("#imd-math-captcha-result").val("");

	var postData = {
			'action': 'imd_generate_math_captcha',
			'reset' : 'true'
		};

	jQuery.post( imd_params.ajaxurl, postData).done(function( res ) {
		var result = JSON.parse(res);
		var randNum1 = result.randNumber1;
		var randNum2 = result.randNumber2;

		var labelText = randNum1 + ' + ' + randNum2;
		jQuery("#imd-math-captcha-result-label").html(labelText);

		//jQuery("#imd-rand-num1").text(randNum1);
		//jQuery("#imd-rand-num2").text(randNum2);

	});

}

function sendCalRequest() {
	//disable submit button
	//document.getElementById('imd_btn').disabled = true;
	jQuery('#imd-error').addClass('imd-hidden')
	postSingleCalendarDateRequest(
		processResponse // handle response
	);
	return false;
}

// handles the response for postSingleCalendarDateRequest(), and adds the html
function processResponse(response, errorMessage, spinner) { 
	
	var date_label = document.getElementById('date');
	var marketDay_span = document.getElementById('market-day');
	
	//stop loading spinner
	if (spinner) {
		spinner.stop();
	}
	
	if (errorMessage){

		error_element = document.getElementById('imd-error');
		error_element.innerHTML = errorMessage ? errorMessage : 'Bummer: there was an error! Please try again.';
		jQuery('#imd-error').removeClass('imd-hidden')
		
	} else {		
		window.scrollTo(0,0);
		jQuery('#imd-find-widget').addClass('imd-hidden');
		date_label.innerHTML = response['date'];
		marketDay_span.innerHTML = response['igboDay'];
		jQuery('#imd-search-result').removeClass('imd-hidden');
		
	}
			
}

function imd_captchaResultExists() {
	if (document.getElementById('imd-math-captcha-result') === undefined) {
		console.error("No element with id 'imd-math-captcha-result'");
		return false;
	} else {
		var captchaResult = document.getElementById('imd-math-captcha-result').value

		if (captchaResult === '' || captchaResult === null || captchaResult === undefined) {
			console.error("'imd-math-captcha-result' is empty")
			return false;
		} else {
			return true;
		}
	}
}

function postSingleCalendarDateRequest(processResponse){
	
	if (typeof processResponse != 'function') processResponse = function () {};
		
	var calendarPostData = {
			'action': 'imd_handle_calendar_post_request',
			'c_year': document.getElementById('c-year').value,
			'c_month': document.getElementById('c-month').value,
			'c_day': document.getElementById('c-day').value
	};

	//start loading spinner
	var target = document.getElementById('loading');
	var spinner = new Spinner(spinOptions()).spin(target);

	//verify recaptcha
	if (!imd_captchaResultExists()) {
		//return
		processResponse(null, 'Invalid Math Captcha', spinner);
		
	} else {
		var captchaResult = document.getElementById('imd-math-captcha-result').value
		var captchaVerifyPostData = {
				'action': 'imd_verify_math_captcha',
				'mathCaptchaTotal' : captchaResult
			};

		jQuery.post( imd_params.ajaxurl, captchaVerifyPostData).done(function( data ) {
				
				var result = JSON.parse(data);

				if (result.success === true){
					jQuery.post( imd_params.ajaxurl, calendarPostData, function( res ) {
			
						var result = JSON.parse(res);

						if (result.error){
							var errorMessage = result.error;
							processResponse(null, errorMessage, spinner);
						} else {
							var response = { 'date': result.Date,
											'igboDay': result.IgboDay
											};
							processResponse(response, null, spinner);
						}
					});
				} else {
					validCaptcha = false;
					var errorMessage = "Captcha result is invalid";
					processResponse(null, errorMessage, spinner);
					console.error(errorMessage);
					
				}
			}).fail(function(){
				validCaptcha = false;
				var errorMessage = "Error verifying Captcha result";
				processResponse(null, errorMessage, spinner);
				console.error(errorMessage);
					
			}).always(function(){
				//reset captcha
 				imd_resetMathCaptcha();
			});
		
	}
}

function postCalendarYearRequest(yearRequested, monthRequested = '') {

	var postData = {
			'action': 'imd_handle_calendar_post_request',
			'c_year': yearRequested
	};

	//start loading spinner
	var target = document.getElementById('loading');
	var spinner = new Spinner(spinOptions()).spin(target);

	// Post to the server
	jQuery.post( imd_params.ajaxurl, postData, function( data ) {
		
		var result = JSON.parse(data);

		if (result.error){
			var errorMessage = result.error;
			
		} else {
			year_response = result;
			buildCalendarMonths(year_response, yearRequested, monthRequested);
		}
		//stop loading spinner
		spinner.stop();
	});
}

function requestNextCalendarYear(month_requested) {
	if (focus_year < 8064) {
		var year_requested = focus_year + 1;
		postCalendarYearRequest(year_requested, month_requested);
	}
}

function requestPreviousCalendarYear(month_requested) {
	if (focus_year > 1) {
		var year_requested = focus_year - 1;
		postCalendarYearRequest(year_requested, month_requested);
	}
}

function buildNextMonthCalendarDates() {
	if (month_map[focus_month].next) {
		var month_requested = month_map[focus_month].next;
		buildCalendarDates(year_response, month_requested);
	} else {
		//this means that user is gotten to the end of month in the year
		//there take user to beginning of the next year.
		var month_requested = 'January';
		requestNextCalendarYear(month_requested)
	}

}

function buildPreviousMonthCalendarDates() {
	if (month_map[focus_month].previous) {
		month_requested = month_map[focus_month].previous;
		buildCalendarDates(year_response, month_requested);
	} else {
		//this means that user is gotten to the begiining of month in the year
		//there take user to end of the previous year.
		var month_requested = 'December';
		requestPreviousCalendarYear(month_requested);
	}

}

/**
 * Builds the table UI that shows 
 * all dates of a single month
 *  in 4 columns and 3 rows.
 */
function buildCalendarDates(year_response, month_requested) {
	focus_month = month_requested;
	document.getElementById("declare_month_year").innerHTML='Igbo Calendar '.concat(focus_year);
	document.getElementById("declare_month").innerHTML=focus_month.concat(' ', focus_year);
	var month_response = year_response.content[month_requested];
	var table_body_element = document.getElementById("imd-date-table-body");
	//first remove all child nodes
	while (table_body_element.firstChild) {
		table_body_element.removeChild(table_body_element.firstChild);
	}
	buildDateRows(month_response, table_body_element);
	jQuery("#imd-months").addClass("imd-hidden");
	jQuery("#imd-dates").removeClass("imd-hidden");

}

/**
 * Build rows that will show consecutive cells each 
 * representing a single unit date.
 * Each row it builds will contain four date cells.
 */
function buildDateRows(month_response, table_body_element){
	var table_row = document.createElement("tr");

	for (i = 0; i < month_response.content.length; i++) { 
		table_row = buildDateCells(month_response.content[i], table_row, month_response.content.length);
		if (table_row.childNodes.length == 4) {
			table_body_element.appendChild(table_row);
			table_row = document.createElement("tr");
		} 
	}

	return table_body_element;
}
/**
 * Appropriately places the date cells under their corresponding
 * cell headers like 'EKE', 'ORIE' etc
 */
function buildDateCells(month_date, row_element, days_in_month) {
	switch(month_date.market_day) {
		case 'EKE':
			row_element = buildEKECell(month_date, row_element, days_in_month);		
			break;
		case 'ORIE':
			row_element = buildORIECell(month_date, row_element, days_in_month);
			break;
		case 'AFO':
			row_element = buildAFOCell(month_date, row_element, days_in_month);
			break;
		case 'NKWO':
			row_element = buildNKWOCell(month_date, row_element, days_in_month);
			break;
	}

	return row_element;
}

/**
 * Build a date cell under EKE cell header
 */
function buildEKECell(month_date, row_element, days_in_month) {

	row_element = buildDateCell(month_date, row_element);

	//check if it is last day of the month
	if (month_date.digit_day == days_in_month) {
		//grey out the remaining cells
		row_element = buildGreyDateCell(row_element); 
		row_element = buildGreyDateCell(row_element); 
		row_element = buildGreyDateCell(row_element);
	}

	return row_element;
}

/**
 * Build a date cell under ORIE cell header
 */
function buildORIECell(month_date, row_element, days_in_month) {
	//check if it is the first day of the month
	if (month_date.digit_day == 1) {
		//grey out the cell prior to ORIE cell
		row_element = buildGreyDateCell(row_element);
	}
	row_element = buildDateCell(month_date, row_element);

	//check if it is last day of the month
	if (month_date.digit_day == days_in_month) {
		//grey out the remaining cells
		row_element = buildGreyDateCell(row_element); 
		row_element = buildGreyDateCell(row_element);
	}

	return row_element;
}

/**
 * Build a date cell under AFO cell header
 */
function buildAFOCell(month_date, row_element, days_in_month) {
	//check if it is the first day of the month
	if (month_date.digit_day == 1) {
		//grey out the cell prior to AFO cell
		row_element = buildGreyDateCell(row_element);
		row_element = buildGreyDateCell(row_element); 
	}
	row_element = buildDateCell(month_date, row_element);

	//check if it is last day of the month
	if (month_date.digit_day == days_in_month) {
		//grey out the remaining cells
		row_element = buildGreyDateCell(row_element);
	}

	return row_element;
}

/**
 * Build a date cell under NKWO cell header
 */
function buildNKWOCell(month_date, row_element, days_in_month) {
	//check if it is the first day of the month
	if (month_date.digit_day == 1) {
		//grey out the cell prior to NKWO cell
		row_element = buildGreyDateCell(row_element);
		row_element = buildGreyDateCell(row_element);
		row_element = buildGreyDateCell(row_element);
	}
	row_element = buildDateCell(month_date, row_element);

	return row_element;
}

/**
 * Builds a date cell.
 * A cell containing a unit date.
 * @example '3 MON'
 */
function buildDateCell(month_date, row_element) {
	var cell_data = document.createElement("td");
	var div1 = document.createElement("div");
	var div2 = document.createElement("div");

	var div1_node = document.createTextNode(month_date.digit_day);
	var div2_node = document.createTextNode(month_date.abbr_gregorian_day);

	div1.appendChild(div1_node);
	div2.appendChild(div2_node);

	cell_data.appendChild(div1);
	cell_data.appendChild(div2);

	if (month_date.is_current_day) {
		cell_data.style.color = "white";
		cell_data.style.background = "#7C4DFF";
	}

	row_element.appendChild(cell_data);

	return row_element;
}

/**
 * Builds a grey/disabled date cell.
 */
function buildGreyDateCell(row_element) {
	var cell_data = document.createElement("td");
	cell_data.setAttribute("class", "imd-opacity");
	row_element.appendChild(cell_data);
	return row_element;
}

/**
 * Builds the table UI that shows 
 * all months in 4 columns and 3 rows.
 */
function buildCalendarMonths(year_response, year_requested, month_requested = '') {
	focus_year = year_requested;
	document.getElementById("declare_year").innerHTML='Igbo Calendar '.concat(focus_year);
	if (month_requested) {
		//if a particular month has been requested then just drill down 
		//and build the calendar dates for that month.
		buildCalendarDates(year_response, month_requested);
	} else {
		//no specific month is requested
		//so just display all months for that year.
		var table_body_element = document.getElementById("imd-month-table-body");
		//first remove all child nodes
		while (table_body_element.firstChild) {
			table_body_element.removeChild(table_body_element.firstChild);
		}
		buildMonthRows(year_response, table_body_element);
		jQuery("#imd-dates").addClass("imd-hidden");
		jQuery("#imd-months").removeClass("imd-hidden");
	}
	
}

/**
 * Build rows that will show consecutive cells each 
 * representing a single month.
 * Each row it builds will contain four month cells.
 */
function buildMonthRows(year_response, table_body_element) {
	var table_row = document.createElement("tr");

	for (month_name in year_response.content) { 
		table_row = buildMonthCell(table_row, year_response, month_name);
		if (table_row.childNodes.length == 4) {
			table_body_element.appendChild(table_row);
			table_row = document.createElement("tr");
		} 
	}

	return table_body_element;
}
/**
 * Builds a cell that would show a single month
 * @example 'JAN'
 */
function buildMonthCell(row_element, year_response, month_name) {
	var cell_data = document.createElement("td");
	var button_node = document.createElement("button");
	var click_function = "buildCalendarDates(year_response, '".concat(month_name, "')");
	button_node.setAttribute("onclick", click_function);

	if (year_response.is_current_year && year_response.content[month_name].is_current_month) {
		button_node.setAttribute("class", "mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored");
	} else {
		button_node.setAttribute("class", "mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect");
	}
	var text_node = document.createTextNode(month_name_map[month_name]);
	button_node.appendChild(text_node);
	cell_data.appendChild(button_node);
	row_element.appendChild(cell_data);
	return row_element;
}

function spinOptions(){
	var options = {
		  lines: 13 // The number of lines to draw
		, length: 20 // The length of each line
		, width: 5 // The line thickness
		, radius: 20 // The radius of the inner circle
		, scale: 1 // Scales overall size of the spinner
		, corners: 1 // Corner roundness (0..1)
		, color: '#000' // #rgb or #rrggbb or array of colors
		, opacity: 0.25 // Opacity of the lines
		, rotate: 0 // The rotation offset
		, direction: 1 // 1: clockwise, -1: counterclockwise
		, speed: 1 // Rounds per second
		, trail: 60 // Afterglow percentage
		, fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
		, zIndex: 2e9 // The z-index (defaults to 2000000000)
		, className: 'spinner' // The CSS class to assign to the spinner
		, top: '50%' // Top position relative to parent
		, left: '50%' // Left position relative to parent
		, shadow: false // Whether to render a shadow
		, hwaccel: false // Whether to use hardware acceleration
		, position: 'absolute' // Element positioning
	};
	
	return options;
}
