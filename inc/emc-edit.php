<?php 

defined( 'ABSPATH' ) or die( 'Blank Space' );

final class Emc_Edit {
	/* SINGLETON */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}

	/* wp hooks .. */
	private function wp_hooks() {
		add_action('admin_enqueue_scripts', array($this, 'enqueue_script'));
		add_action('add_meta_boxes_emcasino', array($this, 'create_meta'));
		add_action('save_post', array($this, 'save'));

	}

	/* adding script and styling to casino admin page */
	public function enqueue_script() {
		$screen = get_current_screen();
		if ($screen->id == 'emcasino') {
			wp_enqueue_style('emcasin_admin_style', EMCASINO_PLUGIN_URL . '/assets/css/emcasino-edit.css', array(), false);
			wp_enqueue_script('emcasino_meta', EMCASINO_PLUGIN_URL . '/assets/js/emcasino-edit.js', array(), false, true);
		}
	}

	/*
		registers meta box
	*/
	public function create_meta() {
		add_meta_box('emcasino_meta', 'Casino Info', array($this,'create_meta_box'), 'emcasino');
	}

	/*
		creates the container for meta input
		adds json for javascript to create input fields
	*/
	public function create_meta_box($post) {
		wp_nonce_field('em'.basename(__FILE__), 'em_nonce');

		$meta = get_post_meta($post->ID, 'emcasino');
		$sort = get_post_meta($post->ID, 'emcasino_sort');
		$json = [
			'meta' => isset($meta[0]) ? $this->sanitize($meta[0]) : '',
			'sort' => isset($sort[0]) ? $this->sanitize($sort[0]) : '0'
		];

		wp_localize_script('emcasino_meta', 'emcasino', json_decode(json_encode($json), true));
		echo '<div class="emcasino-container"></div>';
	}

	/* post saving */
	public function save($post_id) {
		if (!get_post_type($post_id) == 'emcasino') return;

		// is on admin screen
		if (!is_admin()) return;

		// user is logged in and has permission
		if (!current_user_can('edit_posts')) return;

		// nonce is sent
		if (!isset($_POST['em_nonce'])) return;

		// nonce is checked
		if (!wp_verify_nonce($_POST['em_nonce'], 'em'.basename(__FILE__))) return;

		/* saves meta with a helper function that sanitizes arrays */
		if (isset($_POST['emcasino'])) update_post_meta($post_id, 'emcasino', $this->sanitize($_POST['emcasino']));
		if (isset($_POST['emcasino_sort'])) update_post_meta($post_id, 'emcasino_sort', $this->sanitize($_POST['emcasino_sort']));

	}

	/*
		recursive sanitizer
		array or text
	*/
	private function sanitize($data) {
		if (!is_array($data)) return sanitize_text_field($data);

		$d = [];
		foreach($data as $key => $value)
			$d[$key] = $this->sanitize($value);

		return $d;
	}
}