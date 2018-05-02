<?php 

defined( 'ABSPATH' ) or die( 'Blank Space' );

final class Emc_Customizer {
	/* SINGLETON */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}

	private function wp_hooks() {
		add_action('customize_register', array($this, 'customizer'));
		add_action('customize_preview_init', array($this, 'cd_customizer'), 9999);
	}

	public function cd_customizer() {
		wp_enqueue_script('emcasino_customizer', EMCASINO_PLUGIN_URL . '/assets/js/emcasino-customizer.js', array( 'jquery','customize-preview' ), '', true);
	}

	public function customizer($wp_customizer) {

		$wp_customizer->add_setting('emcasino_css', array(
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'one'

		));

		$wp_customizer->add_section('emcasino_section', array(
			'title' => 'Casino',
			'priority' => 24,
			'capability' => 'edit_theme_options'
		));

		$wp_customizer->add_control('emcasino_css_control', array(
			'type' => 'select',
			'label' => 'Styling',
			'section' => 'emcasino_section',
			'settings' => 'emcasino_css',
			'choices' => array(
				'one' => 'One',
				'two' => 'Two'
			)
		));

	}

}