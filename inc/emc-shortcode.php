<?php 


defined( 'ABSPATH' ) or die( 'Blank Space' );

final class Emc_Shortcode {
	/* SINGLETON */
	private static $instance = null;
	private $css_added = false;
	private $desktop = EMCASINO_PLUGIN_URL.'assets/css/emcasino.css?v=0.0.4';
	private $mobile = EMCASINO_PLUGIN_URL.'assets/css/emcasino-mobile.css?v=0.0.4';
	private $cssv = 0;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		// $css = get_option('emcasino_css');
		$this->cssv = get_option('emcasino_css');

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
		add_shortcode($tag.'-link', array($this, 'shortcode_signup'));

		// add_shortcode($tag.'-exclusive', array($this, 'shortcode_exclusive'));


        add_filter('pre_get_posts', array($this, 'set_search'), 99);
        add_filter('emtheme_doc', array($this, 'emtheme_doc'), 99);
        // add_filter('emtheme_get_search_func', array($this, 'add_search_func'), 99);
        add_action('emcasino_search', array($this, 'do_search'));
        add_action('emcasino_style', array($this, 'do_style'));
	}

	public function emtheme_doc($data) {
		array_push($data, [
			'index' => '<li>
							<a href="#emcasino-header">Casino Plugin</a>
							<ul>
								<li><a href="#emcasino-shortcodes">Shortcodes</a>
									<ul>
										<li><a href="#emcasino-casino">[casino]</a></li>
										<li><a href="#emcasino-casino-image">[casino-image]</a></li>
										<li><a href="#emcasino-casino-link">[casino-link]</a></li>
									</ul>
								</li>
							</ul>
						</li>',

			'title' => '<span id="emcasino-header">Casino Plugin</span>',
			'title_text' => '',

			'text' => [
				'<h2 id="emcasino-shortcodes">Shortcodes</h2>
				 <h3 id="emcasino-casino">[casino name="abc,def" type="taxonomy" width=810]</h3>
				 <p><strong>name</strong> viser en liste med casinoer som har slug-name i den rekkefølgen der er oppgitt i.</p>
				 <p><strong>type</strong> viser alle som har oppgitt katergori (taxonomy)</p>
				 <p><strong>width</strong> setter bredden på kontainer til listen i pixler. Hvis ingen verdi oppgitt vil elementet ha 100% width. Med bonus og freespin områdene som forandrer lengde.</p>
				 <p>Eksempel:<br>
				 [casino] viser alle kasinoer.<br>
				 [casino name="leovegas"] viser kun ett casino med slug-name "leovegas"
				 </p>
				
				 <h3 id="emcasino-casino-image">[casino-image name="abc"]</h3>
				 <p><strong>name</strong> viser bildet fra slug-name som er oppgitt. Required.</p>
					
				<h3 id="emcasino-casino-link">[casino-link name="abc" width=""]</h3>
				Is centered in element and styling is same as button from casino list.
				<p><strong>name</strong> matching slug-name of casino. Required.</p>
				<p><strong>width</strong> Sets width of button. Default value: length of text.</p>

				'

			]

		]);

		return $data;
	}

	/**
		HOOK FOR ADDING CSS VIA JS FROM FOOTER
	*/
	private function add_css() {
		// if ($this->css_added) return;
		// $this->css_added = true;

		add_action('wp_footer', array($this, 'add_footer'));
	}

	/**
		ADDING CSS WITH JS FROM FOOTER
	*/
	public function add_footer() {
		echo '<script defer>
				(function() {
					var o = document.createElement("link");
					o.classList.add("emcasino-css");
					o.setAttribute("rel", "stylesheet");
					o.setAttribute("href", "'.esc_html($this->desktop).'");
					o.setAttribute("media", "(min-width: 1025px)");
					document.head.appendChild(o);

					var m = document.createElement("link");
					m.classList.add("emcasino-css-mobile");
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
            if ($query->get('post_type') == 'user_request') return;
        	
	        if (!$query->get('post_type')) $query->set('post_type', array('page', 'post', 'emcasino'));
	        else $query->set('post_type', array_merge(array('emcasino'), $query->get('post_type')));
		}
	}

	public function add_head() {
		// wp_die('<xmp>'.print_r('hi', true).'</xmp>');

		$colors = get_option('emcasino_color');

		// checks array, escapes data and sets default
		$col['background'] = isset($colors['background']) ? esc_html($colors['background']) : '#f5f5f5';
		$col['border'] = isset($colors['border']) ? esc_html($colors['border']) : '#aabbaa';
		$col['nr'] = isset($colors['nr']) ? esc_html($colors['nr']) : '#66cc66';
		$col['nr_text'] = isset($colors['nr_text']) ? esc_html($colors['nr_text']) : '#ffffff';
		$col['bonus'] = isset($colors['bonus']) ? esc_html($colors['bonus']) : '#5555ff';
		$col['freespins'] = isset($colors['freespins']) ? esc_html($colors['freespins']) : '#cc55cc';

		$col['playhere'] = isset($colors['playhere']) ? esc_html($colors['playhere']) : '#33cc33';
		$col['playhere_hover'] = isset($colors['playhere_hover']) ? esc_html($colors['playhere_hover']) : '#33ff33';
		$col['playhere_text'] = isset($colors['playhere_text']) ? esc_html($colors['playhere_text']) : '#ffffff';
		$col['playhere_border'] = isset($colors['playhere_border']) ? esc_html($colors['playhere_border']) : '#aabbaa';

		$col['readmore'] = isset($colors['readmore']) ? esc_html($colors['readmore']) : '#aacccc';
		$col['readmore_hover'] = isset($colors['readmore_hover']) ? esc_html($colors['readmore_hover']) : '#aaffff';
		$col['readmore_text'] = isset($colors['readmore_text']) ? esc_html($colors['readmore_text']) : '#ffffff';
		$col['readmore_border'] = isset($colors['readmore_border']) ? esc_html($colors['readmore_border']) : '#aabbaa';
		

		// wp_die('<xmp>'.print_r($colors, true).'</xmp>');
		

		$css = '<style>';
		$css .= "\n.emcasino-container { background-color: $col[background]; border-top: solid 2px; border-bottom: solid 2px; border-color: $col[border] !important; }";
		$css .= "\n.emcasino-nr { background-color: $col[nr]; color: $col[nr_text]; }";
		$css .= "\n.emcasino-bonus { color: $col[bonus]; }";
		$css .= "\n.emcasino-freespins { color: $col[freespins]; }";
		$css .= "\n.emcasino-link-playhere { background-color: $col[playhere]; color: $col[playhere_text]; border: solid 1px $col[playhere_border]; }";
		$css .= "\n.emcasino-link-playhere:hover { background-color: $col[playhere_hover]; }";
		$css .= "\n.emcasino-link-readmore { background-color: $col[readmore]; color: $col[readmore_text]; border: solid 1px $col[readmore_border]; }";
		$css .= "\n.emcasino-link-readmore:hover { background-color: $col[readmore_hover]; }";
		$css .= '</style>';	

		echo $css;
	}

	public function shortcode($atts, $content = null) {

		// true if no name or col given (to be used in ignore algo)
		$general = (isset($atts['name']) || isset($atts['type'])) ? false : true;

		$names = false;
		if (isset($atts['name'])) $names = explode(',', preg_replace('/ /', '', $atts['name']));

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
		if ($names) $args['post_name__in'] = $names;


		// add taxonomy
		if (isset($atts['type'])) 
			$args['tax_query'] = [[
									'taxonomy' => 'emcasinotype',
									'field' => 'slug',
									'terms' => $atts['type']
								 ]];

		// getting the posts
		$posts = get_posts($args);

		// if no posts found, then return nothing
		if (sizeof($posts) == 0) return;


		// sorting the posts
		$temp_posts = [];
		if ($names) {
			foreach($names as $name) foreach($posts as $post)
				if ($post->post_name == $name) array_push($temp_posts, $post);
		
			$posts = $temp_posts;
		}


		$this->do_style();

		$text = get_option('emcasino_text');

		$text['playhere'] = isset($text['playhere']) ? esc_html($text['playhere']) : 'Play Now';
		$text['readmore'] = isset($text['readmore']) ? esc_html($text['readmore']) : 'Read more';

		// making html

		$width = false;
		if (isset($atts['width'])) $width = intval($atts['width']) / 10;

		$html = '<div class="emcasino-list" style="opacity: 0;'.($width ? ' width: '.$width.'rem' : '').'">';
		// $html .= '<div class="emcasino-header"><span>Les mer<i class="material-icons">arrow_downward</i></span><span><i class="material-icons">arrow_downward</i>Spill nå</span></div>';
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
			$html .= $this->make_casino($post, $text, $nr++);
		}


		$html .= '</div>';

		// returns to front-end
		return $html;
	}

	/**
		CREATES AND RETURNS ONE ITEM IN THE CASINO LIST
		Is used by shortcode and by theme search
	*/
	private function make_casino($post, $text = null, $nr = null) {
		$meta = get_post_meta($post->ID, 'emcasino');

		// do nothing if no meta
		if (isset($meta[0])) 	$meta = $meta[0];
		else 					return;

		$version = get_option('emcasino_layout');
		// set styling and layout here

		// get layout for 810px wide frontpage
		
		if ($version == 'one' && !wp_is_mobile()) $html = $this->casino_one($meta, $post, $text);
		else $html = $this->casino($meta, $post, $text, $nr);

		return $html;
		// return $this->casino_two($meta, $post);
	}


	/* FRONTPAGE LAYOUT 

	*/
	private function casino($meta, $post, $text = null, $nr = null) {
		$html = '<div class="emcasino-container">';
		if ($nr) $html .= '<span class="emcasino-nr">'.esc_html($nr).'</span>';

		// thumbnail
		$html .= '<a class="emcasino-logo-container target="_blank" rel="noopener" href="'.esc_url($meta['spill_na_link']).'"><img class="emcasino-logo" src="'.esc_url(get_the_post_thumbnail_url($post, 'full')).'"></a>';

		$html .= '<span class="emcasino-bonus">'.esc_html($meta['bonus_tekst']).'</span>';

		$html .= '<span class="emcasino-freespins">'.esc_html($meta['freespins']).'</span>';

		$html .= '<div class="emcasino-link-container">';
		$html .= '<span class="emcasino-playhere-container"><a target="_blank" rel="noopener" class="emcasino-link emcasino-link-playhere" href="'.esc_url($meta['spill_na_link']).'">'.$text['playhere'].'</a></span>';

		$html .= '<span class="emcasino-readmore-container"><a class="emcasino-link emcasino-link-readmore" href="'.esc_url($meta['les_omtale']).'">'.$text['readmore'].'</a></span>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}


	/* new design */
	private function casino_one($meta, $post, $text = null) {

		if (!$text) $text['playhere'] = 'Spill her';

		$html = '<div class="emcasino-container"><a class="emcasino-o-container" href="'.esc_url($meta['les_omtale']).'">';

		$thumbnail = get_the_post_thumbnail_url($post, 'full');

		if ($thumbnail) $html .= '<span class="emcasino-logo-container"><img class="emcasino-logo" src="'.esc_url($thumbnail).'"></span>';

		$html .= '<span class="emcasino-welcome-container">';
		if ($meta['bonus_tekst']) $html .= '<span class="emcasino-bonus">'.esc_html($meta['bonus_tekst']).'</span>';
		if ($meta['freespins']) $html .= '<span class="emcasino-freespins">'.esc_html($meta['freespins']).'</span>';
		$html .= '</span>';
		$html .= '</a>';

		if ($meta['spill_na_link']) $html .= '<a class="emcasino-link-playhere" href="'.esc_url($meta['spill_na_link']).'">'.$text['playhere'].'</a>';

		$html .= '</div>';

		return $html;
	}

	public function shortcode_image($atts, $content = null) {
		if (!isset($atts['name'])) return;
		// $this->add_css();

		$post = $this->get_post($atts['name']);


		$url = get_the_post_thumbnail_url($post, 'full');

		if (!$url) return;

		return '<div class="emcasino-bilde-container-alene"><img class="emcasino-bilde-alene" src="'.esc_url($url).'"></div>';
	}

	/* [casino-signup name=""] shortcode */
	public function shortcode_signup($atts, $content = null) {
		add_action('wp_head', array($this, 'shortcode_signup_css'));
		add_filter('add_google_fonts', array($this, 'add_google_fonts'));

		if (!isset($atts['name'])) return;

		$width = '';
		if (isset($atts['width'])) $width = ' style="width: '.(intval($atts['width']) / 10).'rem;"';

		$post = $this->get_post($atts['name']);
		$meta = get_post_meta($post->ID, 'emcasino');

		if (!isset($meta[0]) || !isset($meta[0]['spill_na_link'])) return;

		$options = get_option('emcasino_text');
		$playhere = isset($options['playhere']) ? esc_html($options['playhere']) : 'Spill her';

		$html = '<div class="emcasino-standalone-container">
					<a target="_blank" rel="noopener" class="emcasino-standalone-link"'.($width ? $width : '').' href="'.esc_url($meta[0]['spill_na_link']).'">'.$playhere.'</a>
				</div>';

		return $html;
	}

	public function shortcode_signup_css() {
		$options = get_option('emcasino_color');

		$colors['playhere'] = isset($options['playhere']) ? esc_html($options['playhere']) : '#2a2';
		$colors['playhere_hover'] = isset($options['playhere_hover']) ? esc_html($options['playhere_hover']) : '#2f2';
		$colors['playhere_border'] = isset($options['playhere_border']) ? esc_html($options['playhere_border']) : '#777';
		$colors['playhere_text'] = isset($options['playhere_text']) ? esc_html($options['playhere_text']) : '#fff';

		$css = '<style>';
		$css .= "
				.emcasino-standalone-container {
					display: flex;
					justify-content: center;
				}
				.emcasino-standalone-link { 
					background-color: $colors[playhere]; 
					padding: 0.6rem 1rem; 
					border: solid 1px $colors[playhere_border];
					border-radius: 3px;
					text-decoration: none;
					text-align: center; 
					font-size: 2.2rem; 
					color: $colors[playhere_text]; 
					font-family: 'Cabin', verdana;
					text-shadow: -1px 0 1px black, 0 1px 1px black, 1px 0 1px black, 0 -1px 1px black;
				}
				.emcasino-standalone-link:hover {
					background-color: $colors[playhere_hover];
				}
				";
		$css .= '</style>';
		echo $css;
	}

	/* helper functino to get one emcasino post */
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

	// public function shortcode_exclusive($atts, $content = null) {

	// 	$args = [
	// 		'post_type' => 'page',
	// 		// 'category' => 41,
	// 		'category_name' => 'exclusive',
	// 		'orderby' => 'menu_order',
	// 		'sort_order' => 'asc',
	// 		'numberposts' => -1
	// 	];

	// 	$posts = get_posts($args);

	// 	if (sizeof($posts) == 0) return;

	// 	$html = '<div class="emcasino-sidelist"><span class="emcasino-sidelist-header">Eksklusive spill<i class="material-icons">arrow_downward</i></span><ul class="emcasino-sidelist-ul">';

	// 	foreach ($posts as $post) {
	// 		$thumbnail = get_the_post_thumbnail_url($post, 'full');

	// 		if (!$thumbnail) continue;

	// 		$html .= '<a href="'.get_permalink($post->ID).'" class="emcasino-sidelist-link"><li class="emcasino-sidelist-li emcasino-sidelist-image" style="background-image: url(\''.esc_url($thumbnail).'\')">
	// 				  <span class="emcasino-sidelist-text">'.$post->post_title.'</span></li></a>';
	// 		// $html .= '<li class="emcasino-sidelist-li"><img class="emcasino-sidelist-image" src="'.esc_url($thumbnail).'"></li>';

	// 	}

	// 	$html .= '</ul></div>';

	// 	return $html;

	// }

	// public function add_search_func($data) {

		// $data['emcasino'] = $this->casino;

		// return $data;
	// }

	public function do_search($post) {
		$text = get_option('emcasino_text');

		$text['playhere'] = isset($text['playhere']) ? esc_html($text['playhere']) : 'Play Now';
		$text['readmore'] = isset($text['readmore']) ? esc_html($text['readmore']) : 'Read more';

		echo $this->make_casino($post, $text);
	}

	public function do_style() {
		if ($this->css_added) return;
		$this->css_added = true;

		switch (get_option('emcasino_layout')) {
			case 'two': $this->desktop = EMCASINO_PLUGIN_URL.'assets/css/emcasino-two.css?v=0.0.2'; break;
			case 'three' : $this->desktop = EMCASINO_PLUGIN_URL.'assets/css/emcasino-three.css?v=0.0.3'; break;
			case 'four' : $this->desktop = EMCASINO_PLUGIN_URL.'assets/css/emcasino-four.css?v=0.0.2'; break;
		}

		$this->add_css();

		add_action('wp_head', array($this, 'add_head'));
		add_filter('add_google_fonts', array($this, 'add_google_fonts'));
	}

	/* using theme hook to combine font fetch */
	public function add_google_fonts($data) {
		$opt = get_option('emcasino_layout');	

	    if ($opt == 'three') {
			if  (isset($data['Roboto Condensed'])) array_push($data['Roboto Condensed'], '700');
			else $data['Roboto Condensed'] = ['700'];

			if  (isset($data['Cabin'])) array_push($data['Cabin'], '700');
			else $data['Cabin'] = ['700'];
		}
		else {
			if  (isset($data['Roboto'])) array_push($data['Roboto'], '400');
			else $data['Roboto'] = ['400'];
		}

		return $data;
	}
}