<?php
defined( 'ABSPATH' ) or die( 'No direct script access allowed' );

function imd_get_calendar_form_template(){	
	ob_start(); ?>
	<script>
		window.onload = function() {
       //when the document is finished loading,
		
		postCalendarYearRequest(focus_year, focus_month);
		} 

	</script>
	<!--- Calendar Months -->
	<div id="imd-months" class="mdl-grid">
		<div class="mdl-cell mdl-cell--8-col calendar-card-wide mdl-card mdl-shadow--2dp">
			<div class="mdl-card__title  mdl-card--expand">
				<button onclick="requestPreviousCalendarYear()" class="imd-left mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
					<i class="material-icons">fast_rewind</i>
				</button>
				<h2 id="declare_year" class="mdl-card__title-text"></h2>
				<button onclick="requestNextCalendarYear()" class="imd-right mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
					<i class="material-icons">fast_forward</i>
				</button>
			</div>
			<div class="mdl-card__supporting-text">
				<table id="imd-month-table" class="imd-table">
					<tbody id="imd-month-table-body">
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!--- Drill down to Calendar Dates -->
	<div id="imd-dates" class="mdl-grid imd-hidden">
		<div class="mdl-cell mdl-cell--8-col calendar-card-wide mdl-card mdl-shadow--2dp">
			<div class="mdl-card__title  mdl-card--expand">
				<button onclick="requestPreviousCalendarYear()" class="imd-left mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
					<i class="material-icons">fast_rewind</i>
				</button>
				<h2 id="declare_month_year" class="mdl-card__title-text"></h2>
				<button onclick="requestNextCalendarYear()" class="imd-right mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
					<i class="material-icons">fast_forward</i>
				</button>
			</div>
			<div class="mdl-card__title  mdl-card--expand">
				<button onclick="buildPreviousMonthCalendarDates()" class="imd-left mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
					<i class="material-icons">navigate_before</i>
				</button>
				<h2 id="declare_month" class="mdl-card__subtitle-text"></h2>
				<button onclick="buildNextMonthCalendarDates()" class="imd-right mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
					<i class="material-icons">navigate_next</i>
				</button>
			</div>
			<div class="mdl-card__supporting-text">
				<table id="imd-date-table" class="mdl-data-table mdl-js-data-table imd-table">
					<thead>
						<tr>
							<th class="mdl-data-table__cell--non-numeric">EKE
							</th>
							<th class="mdl-data-table__cell--non-numeric">ORIE
							</th>
							<th class="mdl-data-table__cell--non-numeric">AFO
							</th>
							<th class="mdl-data-table__cell--non-numeric">NKWO
							</th>
						</tr>
					</thead>
					<tbody id="imd-date-table-body">
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- spinner -->
	<div id="loading"></div>
	
	<?php return ob_get_clean();
}
