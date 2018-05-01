<?php 


defined( 'ABSPATH' ) or die( 'Blank Space' );

final class Emc_Shortcode {
	/* SINGLETON */
	private static $instance = null;
	private $css_added = false;
	private $desktop = EMCASINO_PLUGIN_URL.'assets/css/emcasino.css?v=0.0.1';
	private $mobile = EMCASINO_PLUGIN_URL.'assets/css/emcasino-mobile.css?v=0.0.1';

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}	


	/**
		WP HOOKS
	*/
	private function wp_hooks() {
		$tag = 'casino';

		if (shortcode_exists($tag)) $tag = 'emcasino';

		add_shortcode($tag, array($this, 'shortcode'));
		add_shortcode($tag.'-image', array($this, 'shortcode_image'));
		add_shortcode($tag.'-signup', array($this, 'shortcode_signup'));

        add_filter('pre_get_posts', array($this, 'set_search'), 99);

	}

	/**
		HOOK FOR ADDING CSS VIA JS FROM FOOTER
	*/
	private function add_css() {
		if ($this->css_added) return;
		$this->css_added = true;

		add_action('wp_footer', array($this, 'add_footer'));
	}

	/**
		ADDING CSS WITH JS FROM FOOTER
	*/
	public function add_footer() {
		echo '<script defer>
				(function() {
					var o = document.createElement("link");
					o.setAttribute("rel", "stylesheet");
					o.setAttribute("href", "'.esc_html($this->desktop).'");
					o.setAttribute("media", "(min-width: 1025px)");
					document.head.appendChild(o);

					var m = document.createElement("link");
					m.setAttribute("rel", "stylesheet");
					m.setAttribute("href", "'.esc_html($this->mobile).'");
					m.setAttribute("media", "(max-width: 1024px)");
					document.head.appendChild(m);

				})();
			  </script>';
	}

	/**
		ADDING TO IN-SITE SEARCH RESULTS
	*/
	public function set_search($query) {
        if ($query->is_search) {
	        if (!$query->get('post_type')) $query->set('post_type', array('page', 'post', 'emcasino'));
	        else $query->set('post_type', array_merge(array('emcasino'), $query->get('post_type')));
		}
	}

	public function shortcode($atts, $content = null) {

		// true if no name or col given (to be used in ignore algo)
		$general = (isset($atts['name']) || isset($atts['col'])) ? false : true;


		$args = [
			'post_type' 		=> 'emcasino',
			'posts_per_page' 	=> -1,
			'orderby' 			=> [
										'meta_value_num' 	=> 'ASC',
										'title' 			=> 'ASC'
								   ],
			'meta_key' 			=> 'emcasino_sort'
		];

		// adds slug name(s) to search 
		if (isset($atts['name'])) $args['post_name__in'] = explode(',', preg_replace('/ /', '', $atts['name']));


		// add taxonomy
		if (isset($atts['col'])) 
			$args['tax_query'] = [[
									'taxonomy' => 'emcasinotype',
									'field' => 'slug',
									'terms' => $atts['col']
								 ]];

		// getting the posts
		$posts = get_posts($args);

		// if no posts found, then return nothing
		if (sizeof($posts) == 0) return;

		// adding css via js (container element inital state is opacity 0)
		$this->add_css();

		// making html
		$html = '<div class="emcasino-list">';
		$nr = 1;
		// iterating posts
		foreach ($posts as $post) {

				$terms = wp_get_post_terms($post->ID, 'emlantype');
				$ignore = false;
				foreach($terms as $term) {
					if ($term->slug == 'ignore') 					$ignore = true; // ignore all with ignore tag
					elseif ($term->slug == 'duplicate' && $general) $ignore = true; // ignore all with duplicate tag and name/col att not used
				}

				if ($ignore) continue;

			// getting html for each casino-item
			$html .= $this->make_casino($post, $nr++);
		}


		$html .= '</div>';

		// returns to front-end
		return $html;
	}

	/**
		CREATES AND RETURNS ONE ITEM IN THE CASINO LIST
		Is used by shortcode and by theme search
	*/
	private function make_casino($post, $nr = null) {
		$meta = get_post_meta($post->ID, 'emcasino');

		// do nothing if no meta
		if (isset($meta[0])) 	$meta = $meta[0];
		else 					return;


		// set styling and layout here

		// get layout for 810px wide frontpage
		$html = $this->get_frontpage_casino($meta, $post, $nr);

		return $html;
	}


	/* FRONTPAGE LAYOUT 
	   810 pixels wide

	*/
	private function get_frontpage_casino($meta, $post, $nr = null) {
		$star = '<i class="material-icons emcasino-star">stars</i>';

		// $html = '<div><xmp>';
		// $html .= print_r($meta, true);
		// $html .= '</xmp></div>';


		$stars = isset($meta['rating']) ? intval(substr($meta['rating'], 0, 1)) : 0;

		if ($stars > 5) return '';

		// $bg = 'url("'.EMCASINO_PLUGIN_URL.'/assets/img/128-46.jpg")';

		$html =	'<div class="emcasino-nr">'.$nr.'</div>';
		$html .= '<div class="emcasino-container">';

		// $html .= '<div class="emcasino-inner">';
		// thumbnail
		$html .= '<div class="emcasino-logo-container"><img class="emcasino-logo" src="'.esc_url(get_the_post_thumbnail_url($post, 'full')).'"></div>';

		$html .= '<div class="emcasino-bonus-container"><div class="emcasino-bonus">'.esc_html($meta['bonus_tekst']).'</div></div>';

		$html .= '<div class="emcasino-info-one-container emcasino-info-container">'.esc_html($meta['info_1']).'</div>';

		$html .= '<div class="emcasino-playnow-container"><a class="emcasino-link emcasino-link-playnow" href="'.esc_url($meta['spill_na_link']).'">spill her</a></div>';

		$html .= '<div class="emcasino-stars-container">';
		for ($i = 0; $i < $stars; $i++)
			$html .= ($i == 3 ? '<br>' : '') . $star;

		$html .= '</div>';

		$html .= '<div class="emcasino-freespins-container">'.esc_html($meta['freespins']).'</div>';

		$html .= '<div class="emcasino-info-two-container emcasino-info-container">'.esc_html($meta['info_2']).'</div>';

		$html .= '<div class="emcasino-readmore-container"><a class="emcasino-link emcasino-link-readmore" href="'.esc_url($meta['les_omtale']).'">les mer</a></div>';

		$html .= '</div>';

		return $html;
	}

	public function shortcode_image($atts, $content = null) {
		if (!isset($atts['name'])) return;
		$this->add_css();

		$post = $this->get_post($atts['name']);


		$url = get_the_post_thumbnail_url($post, 'full');

		if (!$url) return;

		return '<div class="emcasino-bilde-container-alene"><img class="emcasino-bilde-alene" src="'.esc_url($url).'"></div>';
	}

	public function shortcode_signup($atts, $content = null) {
		if (!isset($atts['name'])) return;
		$this->add_css();

		$post = $this->get_post($atts['name']);
		$meta = get_post_meta($post->ID, 'emcasino');

		if (!isset($meta[0]) || !isset($meta[0]['signup'])) return;

		return '<div class="emcasino-signup-alene"><a class="emcasino-signup-lenke emcasino-signup-lenke-alene" href="'.esc_url($meta[0]['signup']).'">Meld deg på</a></div>';
	}

	private function get_post($name) {
		$args = [
			'name' => sanitize_text_field($name),
			'post_type' => 'emcasino',
			'posts_per_page' => 1
		];

		$post = get_posts($args);

		if (isset($post[0])) return $post[0];

		return null;
	}
}