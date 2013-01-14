<?php
/*
Plugin Name: ALO Browse Happy Around The Blog
Description: The plugin checks the browser and opens a Browse Happy modal if the browser is old and insecure
Version: 1.0
Author: Alessandro Massasso
*/

/*  Copyright 2011
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



define( "ALO_BH_PLUGIN_DIR", basename( dirname(__FILE__) ) );
define( "ALO_BH_PLUGIN_URL", WP_PLUGIN_URL ."/" . ALO_BH_PLUGIN_DIR );
define( "ALO_BH_PLUGIN_ABS", WP_PLUGIN_DIR . "/". ALO_BH_PLUGIN_DIR );



/**
 * Load scripts & styles on Frontend
 */
function alo_bh_load_scripts() {
	global $alo_bh_hide_popup;
	if ( $alo_bh_hide_popup ) return;
	
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');	
	wp_enqueue_script('jquery-ui-dialog');

	// Set up a different JqueryUI theme: http://stackoverflow.com/questions/1348559/are-there-hosted-jquery-ui-themes-anywhere
	wp_enqueue_style('jquery-ui-theeme', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/blitzer/jquery-ui.css');

	if ( @file_exists ( TEMPLATEPATH.'/alo-browse-happy.css' ) ) {
		wp_enqueue_style ('alo-browse-happy', get_bloginfo('template_directory') .'/alo-browse-happy.css' );
	} else {
		wp_enqueue_style ('alo-browse-happy', ALO_BH_PLUGIN_URL.'/alo-browse-happy.css' );
	}
	
}
add_action('wp_enqueue_scripts', 'alo_bh_load_scripts');


/**
 * Set cookie
 */
function alo_bh_set_cookie () {
	global $alo_bh_hide_popup;
	$alo_bh_hide_popup = true;

    $alo_bh_cookie = "alo_bh_hide_popup";
 	if ( !isset($_COOKIE[ $alo_bh_cookie ]) ) {
		setcookie ( $alo_bh_cookie, "hide", 0);
		$alo_bh_hide_popup = false;
	} 
}

add_action('init', 'alo_bh_set_cookie' );



/**
 * Open modal
 */
function alo_bh_banner() {
	global $wp_version, $alo_bh_hide_popup;
	if ( $alo_bh_hide_popup ) return;
	if ( version_compare ( $wp_version , '3.2', '<' ) ) return;
	
	if ( !function_exists('wp_dashboard_browser_nag') ) require_once( ABSPATH . '/wp-admin/includes' .'/dashboard.php' );

	$response = wp_check_browser_version();
	if ( $response && empty($response['insecure'] ) ) return; // show only if insecure

	if ( !empty($response['insecure'] ) )
	{
		$title =  __( 'You are using an insecure browser!' );
		$class_insecure = 'browser-insecure';
	}
	else
	{
		$title =  __( 'Your browser is out of date!' );
		$class_insecure = '';
	}
	
	echo '<div id="browser-happy-frontend" title="'. esc_attr($title) .'" class="'. $class_insecure .'" >';
	wp_dashboard_browser_nag();
	echo '</div>';

	echo '
<script type="text/javascript"> 
//<![CDATA[
	var $bh = jQuery.noConflict();
   	$bh(document).ready(function(){
		$bh("#browser-happy-frontend").dialog({
			autoOpen: true,
			zIndex: 9999,
			modal: true,
			width: 650,
			height: 300
		})
	});
	$bh("#browser-happy-frontend .dismiss").live( "click", function(e) {
		e.preventDefault();
		$bh("#browser-happy-frontend").dialog( "close" );
	});	
	
//]]>
</script> ';
}
add_action( 'wp_footer', 'alo_bh_banner' );

