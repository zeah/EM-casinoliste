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
		wp_enqueue_script('emcasino_customizer', EMCASINO_PLUGIN_URL . '/assets/js/emcasino-customizer.js?v=1.0.0.1', array( 'jquery','customize-preview' ), '', true);
	}

	public function customizer($wp_customizer) {

		$wp_customizer->add_section('emcasino_section', array(
			'title' => 'Casino',
			'priority' => 700,
			'capability' => 'edit_theme_options'
		));

		$wp_customizer->add_setting('emcasino_layout', array(
			'type' => 'option',
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'one'

		));

		$wp_customizer->add_control('emcasino_css_control', array(
			'type' => 'select',
			'label' => 'Styling',
			'description' => 'Need to update before changes are visible.',
			'section' => 'emcasino_section',
			'settings' => 'emcasino_layout',
			'choices' => array(
				'one' => 'One',
				// 'two' => 'Two',
				'three' => 'Three',
				// 'four' => 'Four'
			)
		));

		$color_args = [
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color'
		];

		$text_args = [
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field'
		];


	
		$wp_customizer->add_setting('emcasino_color[background]', $color_args);
		$wp_customizer->get_setting('emcasino_color[background]')->default = '#f5f5f5'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_background_c', array(
				'label' => 'Container Background',
				'description' => '',
				'settings' => 'emcasino_color[background]',
				'section' => 'emcasino_section'
		)));

		$wp_customizer->add_setting('emcasino_color[border]', $color_args);
		$wp_customizer->get_setting('emcasino_color[border]')->default = '#aabbaa'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_border_c', array(
				'label' => 'Container Border Color',
				'description' => '',
				'settings' => 'emcasino_color[border]',
				'section' => 'emcasino_section'
		)));


		$wp_customizer->add_setting('emcasino_color[nr]', $color_args);
		$wp_customizer->get_setting('emcasino_color[nr]')->default = '#66cc66'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_nr_c', array(
				'label' => 'Numbering background',
				'description' => '',
				'settings' => 'emcasino_color[nr]',
				'section' => 'emcasino_section'
		)));
		

		$wp_customizer->add_setting('emcasino_color[nr_text]', $color_args);
		$wp_customizer->get_setting('emcasino_color[nr_text]')->default = '#ffffff'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_nr_text_c', array(
				'label' => 'Numbering text color',
				'description' => '',
				'settings' => 'emcasino_color[nr_text]',
				'section' => 'emcasino_section'
		)));
		

		$wp_customizer->add_setting('emcasino_color[bonus]', $color_args);
		$wp_customizer->get_setting('emcasino_color[bonus]')->default = '#5555ff'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_bonus_c', array(
				'label' => 'Bonus text',
				'description' => '',
				'settings' => 'emcasino_color[bonus]',
				'section' => 'emcasino_section'
		)));

		$wp_customizer->add_setting('emcasino_color[freespins]', $color_args);
		$wp_customizer->get_setting('emcasino_color[freespins]')->default = '#cc55cc'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_freespins_c', array(
				'label' => 'Freespins text',
				'description' => '',
				'settings' => 'emcasino_color[freespins]',
				'section' => 'emcasino_section'
		)));
		
		$wp_customizer->add_setting('emcasino_text[playhere]', $text_args);
		$wp_customizer->get_setting('emcasino_text[playhere]')->default = 'Play Now'; 
		$wp_customizer->add_control('emcasino_text_playhere_c', array(
			'type' => 'input',
			'label' => 'Play here text',
			'description' => '',
			'settings' => 'emcasino_text[playhere]',
			'section' => 'emcasino_section'
		));

		$wp_customizer->add_setting('emcasino_color[playhere]', $color_args);
		$wp_customizer->get_setting('emcasino_color[playhere]')->default = '#33cc33'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_playhere_c', array(
				'label' => 'Play here background',
				'description' => '',
				'settings' => 'emcasino_color[playhere]',
				'section' => 'emcasino_section'
		)));

		$wp_customizer->add_setting('emcasino_color[playhere_hover]', $color_args);
		$wp_customizer->get_setting('emcasino_color[playhere_hover]')->default = '#33ff33'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_playhere_hover_c', array(
				'label' => 'Play here hover',
				'description' => '',
				'settings' => 'emcasino_color[playhere_hover]',
				'section' => 'emcasino_section'
		)));
		
		$wp_customizer->add_setting('emcasino_color[playhere_text]', $color_args);
		$wp_customizer->get_setting('emcasino_color[playhere_text]')->default = '#ffffff'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_playhere_text_c', array(
				'label' => 'Play here text color',
				'description' => '',
				'settings' => 'emcasino_color[playhere_text]',
				'section' => 'emcasino_section'
		)));
		
		$wp_customizer->add_setting('emcasino_color[playhere_border]', $color_args);
		$wp_customizer->get_setting('emcasino_color[playhere_border]')->default = '#aabbaa'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_playhere_border_c', array(
				'label' => 'Play here border',
				'description' => '',
				'settings' => 'emcasino_color[playhere_border]',
				'section' => 'emcasino_section'
		)));

		$wp_customizer->add_setting('emcasino_text[readmore]', $text_args);
		$wp_customizer->get_setting('emcasino_text[readmore]')->default = 'Read More'; 
		$wp_customizer->add_control('emcasino_text_readmore_c', array(
			'type' => 'input',
			'label' => 'Read more text',
			'description' => '',
			'settings' => 'emcasino_text[readmore]',
			'section' => 'emcasino_section'
		));

		$wp_customizer->add_setting('emcasino_color[readmore]', $color_args);
		$wp_customizer->get_setting('emcasino_color[readmore]')->default = '#aacccc'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_readmore_c', array(
				'label' => 'Read more background',
				'description' => '',
				'settings' => 'emcasino_color[readmore]',
				'section' => 'emcasino_section'
		)));

		$wp_customizer->add_setting('emcasino_color[readmore_hover]', $color_args);
		$wp_customizer->get_setting('emcasino_color[readmore_hover]')->default = '#aaffff'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_readmore_hover_c', array(
				'label' => 'Read more hover',
				'description' => '',
				'settings' => 'emcasino_color[readmore_hover]',
				'section' => 'emcasino_section'
		)));

		$wp_customizer->add_setting('emcasino_color[readmore_text]', $color_args);
		$wp_customizer->get_setting('emcasino_color[readmore_text]')->default = '#ffffff'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_readmore_text_c', array(
				'label' => 'Read more text',
				'description' => '',
				'settings' => 'emcasino_color[readmore_text]',
				'section' => 'emcasino_section'
		)));
		
		$wp_customizer->add_setting('emcasino_color[readmore_border]', $color_args);
		$wp_customizer->get_setting('emcasino_color[readmore_border]')->default = '#aabbaa'; 
		$wp_customizer->add_control(new WP_Customize_Color_Control( 
			$wp_customizer, 'emcasino_readmore_border_c', array(
				'label' => 'Read more border',
				'description' => '',
				'settings' => 'emcasino_color[readmore_border]',
				'section' => 'emcasino_section'
		)));
		
		

	}

}