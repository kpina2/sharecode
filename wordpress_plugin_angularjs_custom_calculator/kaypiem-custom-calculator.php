<?php
/*
Plugin Name: Kaypiem Custom Calculator
Plugin URI: http://www.kaypiem.com/
Version: 0.4
Author: Kevin McHenry
Description: Affiliation Profitability Calculator
*/
defined( 'ABSPATH' ) or die( '' );

if(!defined('FWA_CALC_DIR')) {
    define('FWA_CALC_DIR', plugin_dir_path( __FILE__ ));
}
if(!defined('FWA_CALC_URL')) {
    define('FWA_CALC_URL', plugin_dir_url( __FILE__ ));
}

include_once( FWA_CALC_DIR . 'includes/functions.php' );
include_once( FWA_CALC_DIR . 'includes/scripts.php' );
include_once( FWA_CALC_DIR . 'includes/shortcodes.php' );
include_once( FWA_CALC_DIR . 'includes/posttype.php' );

// Options Menu in Admin
add_action( 'admin_menu', 'fwa_calc_admin_menu' );
function fwa_calc_admin_menu() {
        remove_shortcode( "fwa_calculator" ); // for a bug that was causing our template to show up
	add_options_page( 
            'Freedom Wealth Allicance Calculator', 
            'FWA Calculator', 
            'manage_options', 
            'fwa-calculator', 
            'fwa_calc_admin_options'
        );
}

function fwa_calc_admin_options() {
    if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    echo '<div class="wrap">';
    echo '<p>Here is where the form will go if we extend this plugin.</p>';
    echo '</div>';
}
?>