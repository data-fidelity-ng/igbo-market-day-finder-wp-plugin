<?php
defined( 'ABSPATH' ) or die( 'No direct script access allowed' );

function imd_get_form_template(){	
	ob_start(); ?>
	
	<style>
		#c-block {position: relative;margin:0 auto; font: 14px/21px "Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif; padding:0;}
		#main-section {position: relative;margin:0 auto;background:#F1F1F1; padding:10px; border-radius: 8px; -webkit-border-radius: 8px;}
		.c-form input{font-size: 14px; height:30px; width:55px; padding:2px; border-radius: 5px; -webkit-border-radius: 5px; text-align: center; box-shadow: 0px 0px 3px #ccc, 0 10px 15px #eee inset;}
		.c-form button{font-size: 14px; height:40px; width:50px; padding:2px; border-radius: 5px; -webkit-border-radius: 5px; text-align: center; background-color: #68b12f;}
		.c-form button:hover {opacity:.85; cursor: pointer;}
		#result-section {background:#FFFFCC; padding:20px; width:100%; border:1px solid #CCFF99}
		.market-day{color:#68b12f; font-weight: bold;}
		.error-notice {background:#FFBABA; color:#D8000C; padding:2px 5px; font-size:11px}
	</style>
	
<!--<div id="main-section">
<h2 class="c-heading">Igbo Market Day Finder</h2>
<div name="imd_form" class="c-form">
<label><span id="error-notice" style="display: none" class="error-notice"> </span> <span id="title">Put In Calendar Date</span></label>
<br/><br/>
<input id="c--year" required autofocus autocomplete="off" placeholder="YYYY" width="60" type="number" maxlength="10" name="c-year"> - 
<input id="c--month" required autocomplete="off" placeholder="MM" width="60" type="number" maxlength="2" name="c-month"> - 
<input id="c--day" required autocomplete="off" placeholder="DD" width="60" type="number" maxlength="2" name="c-day">
&nbsp;
<button id="imd_btn" type="button" name="c-submit" onClick="sendCalRequest()" value="Find">Find</button>
</div>
</div>-->
<br/>




<div id="imd-find-widget" class="mdl-grid">
		<div class="mdl-cell mdl-cell--8-col widget-card-wide mdl-card mdl-shadow--2dp">
			<div class="mdl-card__title  mdl-card--expand">
				<h2 class="mdl-card__title-text">Igbo Market Day Finder</h2>
			</div>
			<div class="mdl-card__title  mdl-card--expand">
				<h2 class="mdl-card__subtitle-text">Find market day for a specific calendar date</h2>
			</div>
			<div class="mdl-card__supporting-text">
				<p id="imd-error" class="imd-error-notice imd-hidden"></p>
				<div class="mdl-grid">
					<div class="mdl-cell mdl-cell--4-col">
						<form action="#">
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input class="mdl-textfield__input" type="number" pattern="^[^-\.]*[0-9]+$" id="c-year">
						<label class="mdl-textfield__label" for="c-year">YYYY</label>
						<span class="mdl-textfield__error">Input must be a positive whole number!</span>
						</div>
						</form>
					</div>
					<div class="mdl-cell mdl-cell--4-col">
						<form action="#">
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input class="mdl-textfield__input" type="number" pattern="^[^-\.]*[0-9]+$" id="c-month">
						<label class="mdl-textfield__label" for="c-month">MM</label>
						<span class="mdl-textfield__error">Input must be a positive whole number!</span>
						</div>
						</form>
					</div>
					<div class="mdl-cell mdl-cell--4-col">
						<form action="#">
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
						<input class="mdl-textfield__input" type="number" pattern="^[^-\.]*[0-9]+$" id="c-day">
						<label class="mdl-textfield__label" for="c-day">DD</label>
						<span class="mdl-textfield__error">Input must be a positive whole number!</span>
						</div>
						</form>
					</div>
				</div>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a onClick="sendCalRequest()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
				Find
				</a>
			</div>
		</div>
	</div>
	<!-- Search result card-->
	<div id="imd-search-result" class="mdl-grid imd-hidden">
		<div class="mdl-cell mdl-cell--8-col widget-card-wide mdl-card mdl-shadow--2dp">
			<div class="mdl-card__title  mdl-card--expand">
				<h2 class="mdl-card__title-text">Igbo Market Day Finder</h2>
			</div>
			<div class="mdl-card__title  mdl-card--expand">
				<h2 class="mdl-card__subtitle-text">Search Result</h2>
			</div>
			<div class="mdl-card__supporting-text">
				<div id="result-section">
				<label id="date"></label><br/>
				<label>The Igbo Market Day Is <span id="market-day" class="market-day"></span></label><br/>
				</div>
			</div>
			<div class="mdl-card__actions mdl-card--border">
				<a onClick="showFindWidget()" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
				Search Again
				</a>
			</div>
		</div>
	</div>
	<!-- Spinner -->
	<div id="loading"></div>
	
	<?php return ob_get_clean();
}
