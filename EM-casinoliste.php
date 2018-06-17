<?php
/*
	Plugin name: EM Casinolist
	Description: List of casino offers
	Version: 0.1.0.2
*/

/*
done:
shortcode
shortcode image
shortcode signup link
meta done with js
added cpt to on-site search
added served css when shortcode present
taxonomy registered

todo:
taxonomy in shortcode
customizer
code comments
adding meta fields
css
mobile css
tablet css
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
