<?php
/**
 * WP Google Places Reviews
 * Version 1.0.2
 */

if (!defined('ABSPATH')) exit();

class hm_form_HMGPR {

	public function __construct() {
		add_shortcode('hmgpr_form', array($this, 'review_form_shortcode'));
		add_action('enqueue_block_editor_assets', array($this, 'setup_block_editor_assets'));
		add_action('init', array($this, 'setup_block_init'));
	}

	public function add_scripts_styles() {
		wp_enqueue_style('hmgpr-review-style', HMGPR_URL.'css/style.css');
		wp_enqueue_script('hmgpr-clickcopy-script', HMGPR_URL.'js/click-to-copy.js', array('jquery'));
	}

	public function review_form_shortcode($atts) {
		$this->add_scripts_styles();

		$atts = shortcode_atts(array(
			'place_id' => '',
			'btn_class' => '',
			'wrapper_class' => '',
		), $atts);

		$hmgpr_google = 'https://search.google.com/local/writereview?placeid='.$atts['place_id'];
		$form = '<form method="post">
			<div class="rating">
				<div class="stars">
					<select id="example-bootstrap" name="hmgpr_rating" autocomplete="off">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div>
			</div>
			<br />
			<textarea name="hmgpr_reviewtext"></textarea>
			<input name="hmgpr_review" type="submit" class="'.$atts['btn_class'].'" value="Publish" />
		</form>';

		$result = '<div id="hmgpr" class="'.$atts['wrapper_class'].'">';
		if (isset($_POST["hmgpr_review"])) {
			if (empty(sanitize_text_field($_POST["hmgpr_reviewtext"])) || empty(sanitize_text_field($_POST["hmgpr_rating"]))) {
				$result .= '<div class="reviewfail">Please make sure and add a star rating and review before pressing submit.</div>';
				$result .= $form;
			} else {
				if (sanitize_text_field($_POST['hmgpr_rating']) < 5) {
					$result .= "<h2>".esc_attr(get_option('hmgpr_failtitle'))."</h2>";
					$result .= "<p>".esc_attr(get_option('hmgpr_faildescription'))."</p>";
				}
				if (sanitize_text_field($_POST['hmgpr_rating']) == 5) {
					$result .= "<h2>".esc_attr( get_option('hmgpr_successtitle') )."</h2>";
					$result .= "<p>".esc_attr( get_option('hmgpr_successdescription') )."</p>";
					$result .='<span id="copyTarget2">
						<textarea id="copyTarget">'.sanitize_text_field($_POST["hmgpr_reviewtext"]).'</textarea>
					</span>
					<button class="copybutton" id="copyButton">Click to Copy Review</button>
					<span id="msg"></span>
					<div style="display:none;">
						<input id="copyTarget" value="Some initial text">
						<button id="copyButton">Copy</button><br><br>
						<span id="copyTarget2">Some Other Text</span>
						<button id="copyButton2">Copy</button><br><br>
						<input id="pasteTarget"> Click in this Field and hit Ctrl+V to see what is on clipboard<br><br>
						<span id="msg"></span><br>
					</div>
					<h2>Select the sites below to share your review:</h2>';
					if (!empty($hmgpr_google)) {
						$result .= '<a href="'.$hmgpr_google.'" target="_blank"><img src="'.HMGPR_URL.'images/googlelogo.png' .'" width="100%" style="max-width:300px; margin:0px auto;" /></a><br />';
					}
				}
			}
		} else {
			$result .= '<h2>'.esc_attr(get_option('hmgpr_introtitle')).'</h2>';
			$result .= '<p>'.esc_attr(get_option('hmgpr_introdescription')).'</p>';
			$result .= $form;
		}
		$result .= '</div>';
		return $result;
	}

	public function setup_block_editor_assets() {
		wp_enqueue_style('google-review-form-block', HMGPR_URL.'/blocks/form/editor.css');
		wp_enqueue_script('google-review-form-block', HMGPR_URL.'/blocks/form/block.js', array('wp-blocks', 'wp-editor', 'wp-plugins', 'wp-element', 'wp-components'));
	}

	public function setup_block_init() {
		if(!function_exists('register_block_type')){ return; }
		register_block_type('hmp/google-review-form-block', array(
			'show_in_rest' => true,
			'render_callback' => array($this, 'review_form_shortcode'),
			'attributes' => array(
				'place_id' => array(
					'type' => 'string',
				),
				'btn_class' => array(
					'type' => 'string',
				),
			),
		));
	}
}