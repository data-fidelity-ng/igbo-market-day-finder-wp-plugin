<?php
/*
Plugin Name: Igbo Market Day Finder
Plugin URI: 
Description: Finds the Igbo Market day for any Gregorian Calendar date.
Author: Silver Ibenye
Version: 1.0
Author URI: http://slybase.com/
Text Domain: imd-finder
 */
defined( 'ABSPATH' ) or die( 'No direct script access allowed' );

define( 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

//Hook for loading functions
add_action( 'init', 'imd_load_functions' );

function imd_load_functions() {
	require_once( PLUGIN_DIR . 'functions.php' );
	require_once( PLUGIN_DIR . 'form.php' );
	require_once( PLUGIN_DIR . 'calendar-form.php' );
}

// Ajax Handler
// both logged in and not logged in users should be able to send this Ajax request
add_action( 'wp_ajax_nopriv_imd_handle_calendar_post_request', 'imd_handle_calendar_post_request' );
add_action( 'wp_ajax_imd_handle_calendar_post_request', 'imd_handle_calendar_post_request' );

function imd_handle_calendar_post_request(){
	// Instantiate WP_Ajax_Response
	$response = new WP_Ajax_Response;

	if (isset($_REQUEST["c_year"]) 
	&& !isset($_REQUEST["c_month"]) 
	&& !isset($_REQUEST["c_day"])) {
		$year = $_REQUEST["c_year"];
		$calendarResponse = imd_look_up_market_days_for_calendar_year($year);
		echo json_encode($calendarResponse);
	} else {
		$year = $_REQUEST["c_year"];
		$month = $_REQUEST["c_month"];
		$day = $_REQUEST["c_day"];
		
		$calendarResponse = imd_look_up_market_day($year, $month, $day);

		echo json_encode($calendarResponse);
	}
	
	// Always exit when doing Ajax
    wp_die();
}

add_action( 'wp_enqueue_scripts', 'enqueue_my_scripts');

function enqueue_my_scripts() {
	wp_register_script('material_min_js', plugin_dir_url(__FILE__) . 'material.min.js', array(), '1.2.1', true);
	wp_register_script('imd_spin', plugin_dir_url(__FILE__) . 'spin.min.js', array(), '2.3.2', true);
	wp_register_script('imd_ajax', plugin_dir_url(__FILE__) . 'imd-ajax.js', array('jquery','imd_spin'), '1.0', true);
	wp_enqueue_script('material_min_js');
	wp_enqueue_script('imd_ajax');

	wp_enqueue_style( 'material_min_css', plugin_dir_url(__FILE__) . 'material.min.css', array(), '1.2.1');
	wp_enqueue_style( 'google_material_icon', plugin_dir_url(__FILE__) . 'font-google-material-icon.css');
	wp_enqueue_style( 'imd_style_css', plugin_dir_url(__FILE__) . 'imd-style.css', array(), '1.0');
	
	// Get the protocol of the current page
	$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
	$params = array(
			// Get the url to the admin-ajax.php file using admin_url()
			'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
		);
	// Print the script to our page
	wp_localize_script( 'imd_ajax', 'imd_params', $params );
}

//view form short code
function imd_view_form_shortcode( $atts ){
	//enqueue_my_scripts();
	return imd_get_form_template();
}

add_shortcode( 'igbo_market_day_finder', 'imd_view_form_shortcode' );

//view calendar short code
function imd_view_calendar_form_shortcode($atts){
	return imd_get_calendar_form_template();
}

add_shortcode( 'igbo_market_day_calendar', 'imd_view_calendar_form_shortcode' );

function imd_igbo_market_day_setup_page()
{
    echo '<h2>Igbo Market Day Finder</h2>
    <p>Copy and paste this shortcode anywhere you want the finder form to display
    &nbsp;&nbsp;<span style="color:purple; font-weight:bold;">[igbo_market_day_finder]</span></p>';

	echo '<p></p>';

	 echo '<h2>Igbo Calendar UI</h2>
    <p>Copy and paste this shortcode anywhere you want the calendar form to display
    &nbsp;&nbsp;<span style="color:purple; font-weight:bold;">[igbo_market_day_calendar]</span></p>';
}
 
function imd_igbo_market_day_setup()
{
    add_options_page('Igbo Market Day Finder', 'Igbo Market Day Finder', 'manage_options',
'igbo_market_day_finder', 'imd_igbo_market_day_setup_page');
}
 
add_action('admin_menu', 'imd_igbo_market_day_setup');

