<?php
/**
 * WP Google Places Reviews
 * Version 1.0.2
 */

if (!defined('ABSPATH')) exit();

class hm_review_HMGPR {

	public function __construct() {
		add_shortcode('hmgpr_reviews', array($this, 'review_shortcode'));
		add_action('enqueue_block_editor_assets', array($this, 'setup_block_editor_assets'));
		add_action('init', array($this, 'setup_block_init'));
	}

	public function add_scripts_styles() {
		wp_enqueue_style('hmgpr-review-style', HMGPR_URL. 'css/style.css');
	}

	public function review_shortcode($atts) {
		$this->add_scripts_styles();

		$atts = shortcode_atts(array(
			'place_id' => '',
			'format' => '', // column, row
			'quote_color' => '#ccc',
			'star_color' => '#e3661a',
			'wrapper_class' => '',
		), $atts);

		$icon = array(
			'quote' => '<svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class=""><path fill="'.$atts['quote_color'].'" d="M464 256h-80v-64c0-35.3 28.7-64 64-64h8c13.3 0 24-10.7 24-24V56c0-13.3-10.7-24-24-24h-8c-88.4 0-160 71.6-160 160v240c0 26.5 21.5 48 48 48h128c26.5 0 48-21.5 48-48V304c0-26.5-21.5-48-48-48zm-288 0H96v-64c0-35.3 28.7-64 64-64h8c13.3 0 24-10.7 24-24V56c0-13.3-10.7-24-24-24h-8C71.6 32 0 103.6 0 192v240c0 26.5 21.5 48 48 48h128c26.5 0 48-21.5 48-48V304c0-26.5-21.5-48-48-48z" class=""></path></svg>',
			'google' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="44" width="44"> <g fill="none" fill-rule="evenodd"> <path d="M482.56 261.36c0-16.73-1.5-32.83-4.29-48.27H256v91.29h127.01c-5.47 29.5-22.1 54.49-47.09 71.23v59.21h76.27c44.63-41.09 70.37-101.59 70.37-173.46z" fill="#4285f4"></path> <path d="M256 492c63.72 0 117.14-21.13 156.19-57.18l-76.27-59.21c-21.13 14.16-48.17 22.53-79.92 22.53-61.47 0-113.49-41.51-132.05-97.3H45.1v61.15c38.83 77.13 118.64 130.01 210.9 130.01z" fill="#34a853"></path> <path d="M123.95 300.84c-4.72-14.16-7.4-29.29-7.4-44.84s2.68-30.68 7.4-44.84V150.01H45.1C29.12 181.87 20 217.92 20 256c0 38.08 9.12 74.13 25.1 105.99l78.85-61.15z" fill="#fbbc05"></path> <path d="M256 113.86c34.65 0 65.76 11.91 90.22 35.29l67.69-67.69C373.03 43.39 319.61 20 256 20c-92.25 0-172.07 52.89-210.9 130.01l78.85 61.15c18.56-55.78 70.59-97.3 132.05-97.3z" fill="#ea4335"></path> <path d="M20 20h472v472H20V20z"></path> </g> </svg>',
			'star' => '<svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class=""><path fill="'.$atts['star_color'].'" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" class=""></path></svg>',
			'star-empty' => '<svg aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class=""><path fill="'.$atts['star_color'].'" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z" class=""></path></svg>',
		);

		$ch_options = array(
			CURLOPT_URL => 'https://maps.googleapis.com/maps/api/place/details/json?key='.esc_attr(get_option('hmgpr_google_api_key')).'&placeid='.$atts['place_id'],
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6',
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CONNECTTIMEOUT => 60,
			CURLOPT_TIMEOUT => 60,
		);
		$ch = curl_init(); // init curl object
		curl_setopt_array($ch, $ch_options); // apply those options
		$output = curl_exec($ch); // execute request and get response
		curl_close($ch); // close curl session

		// decode json api result
		$data = json_decode($output, true);

		// return api result
		$reviews = $data['result']['reviews'];

		// return html
		$result = '<div id="hawp_google-reviews" class="review-format-'.$atts['format'].' '.$atts['wrapper_class'].'">';
		foreach ($reviews as $review) {
			$result .= '<div class="review-item">';
			if (($review['rating'])>=4) {
				$result .= '<div class="review-content">';
					$result .= '<div class="review-quote-icon">'.$icon['quote'].'</div>';
					$result .= '<div class="review-stars"><ul>';
						for ($i=1; $i<=($review['rating']); $i++) {
							$result .= '<li>'.$icon['star'].'</li>';
						}
						for ($i=1; $i<=5-($review['rating']); $i++) {
							$result .= '<li>'.$icon['star-empty'].'</li>';
						}
					$result .= '</ul></div>';
					$result .= '<span class="review-text">'.$review['text'].'</span><span class="google-icon">'.$icon['google'].'</span>';
				$result .= '</div>';
				$result .= '<div class="review-meta">';
					$result .= '<div class="review-photo"><img src="'.$review['profile_photo_url'].'"></div>';
					$result .= '<div class="review-author"><a href="'.$review['author_url'].'">'.$review['author_name'].'</a></span><span class="review-date">'.$review['relative_time_description'].'</div>';
				$result .= '</div>';
			}
			$result .= '</div>';
		}
		$result .= '</div>';
		return $result;
	}

	public function setup_block_editor_assets() {
		wp_enqueue_style('google-review-block', HMGPR_URL.'/blocks/reviews/editor.css');
		wp_enqueue_script('google-review-block', HMGPR_URL.'/blocks/reviews/block.js', array('wp-blocks', 'wp-editor', 'wp-plugins', 'wp-element', 'wp-components'));
	}

	public function setup_block_init() {
		if(!function_exists('register_block_type')){ return; }
		register_block_type('hmp/google-review-block', array(
			'show_in_rest' => true,
			'render_callback' => array($this, 'review_shortcode'),
			'attributes' => array(
				'place_id' => array(
					'type' => 'string',
				),
				'format' => array(
					'type' => 'string',
					'default' => '',
				),
				'quote_color' => array(
					'type' => 'string',
					'default' => '#ccc',
				),
				'star_color' => array(
					'type' => 'string',
					'default' => '#e3661a',
				),
			),
		));
	}
}