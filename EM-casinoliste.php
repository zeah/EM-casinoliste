<?php
/*
	Plugin name: EM Casinolist
	Description: List of casino offers
	Version: 0.1.0.2
	GitHub Plugin URI: zeah/EM-casinoliste
*/


defined( 'ABSPATH' ) or die( 'Blank Space' );

require_once 'inc/emc-posttype.php';
require_once 'inc/emc-shortcode.php';
require_once 'inc/emc-customizer.php';


define('EMCASINO_PLUGIN_URL', plugin_dir_url(__FILE__));

function init_emcasinoliste() {

	Emc_Posttype::get_instance();
	Emc_Shortcode::get_instance();
	Emc_Customizer::get_instance();
}

add_action('plugins_loaded', 'init_emcasinoliste');
