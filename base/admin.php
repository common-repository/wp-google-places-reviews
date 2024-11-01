<?php
/**
 * WP Google Places Reviews
 * Version 1.0.4
 */

if (!defined('ABSPATH')) exit();

class hm_admin_HMGPR {

	public function __construct() {
		if (is_admin()) {
			add_action('admin_menu', array($this, 'add_plugin_menu'));
			add_action('admin_init', array($this, 'register_settings'));
			add_action('admin_enqueue_scripts', array($this, 'admin_scripts_styles'));
		}
	}

	public function add_plugin_menu() {
		add_menu_page(
			'Google Reviews',
			'Google Reviews',
			'manage_options',
			'hawp_wp-google-reviews',
			array($this, 'add_settings_page'),
			'dashicons-star-filled'
		);
	}

	public function register_settings() {
		register_setting('hmgpr_settings-group', 'hmgpr_google_api_key');
		register_setting('hmgpr_settings-group', 'hmgpr_failtitle');
		register_setting('hmgpr_settings-group', 'hmgpr_faildescription');
		register_setting('hmgpr_settings-group', 'hmgpr_successtitle');
		register_setting('hmgpr_settings-group', 'hmgpr_successdescription');
		register_setting('hmgpr_settings-group', 'hmgpr_introtitle');
		register_setting('hmgpr_settings-group', 'hmgpr_introdescription');
	}

	public function admin_scripts_styles() {
		wp_enqueue_style('hmp-admin-style', HMGPR_URL.'css/admin-style.css');
		wp_enqueue_script('hmp-admin-script', HMGPR_URL.'js/admin-script.js', array('jquery'));
	}

	public function add_settings_page() { ?>
		<div id="hmp_wrap">
			<div id="hmp_body">

				<header id="hmp_header">
					<div class="hmp_header-logo">
						<img src="<?php echo HMGPR_URL.'images/hmp_logo.svg'; ?>" width="163" height="44" alt="Logo Hawp 5 Star Reviews">
						<div class="hmp_name">WP Google Reviews</div>
					</div>
					<div class="hmp_header-nav">
						<span data-tab="hmgpr_general" class="hmp_menuItem show">
							<div class="hmp_menuItem-title">General Settings</div>
							<div class="hmp_menuItem-description">Basic plugin settings</div>
						</span>
						<span data-tab="hmgpr_apiconnection" class="hmp_menuItem">
							<div class="hmp_menuItem-title">Google API</div>
							<div class="hmp_menuItem-description">Google Places API Key</div>
						</span>
						<span data-tab="hmgpr_documentation" class="hmp_menuItem">
							<div class="hmp_menuItem-title">Documentation</div>
							<div class="hmp_menuItem-description">Shortcodes & how-to</div>
						</span>
					</div>
				</header>

				<section id="hmp_content">
					<form action="options.php" method="post">

						<div id="hmgpr_general" class="hmp_page show">
							<div class="hmp_section-header">
								<h1><span class="hmp_section-header-icon dashicons dashicons-admin-settings"></span> General Settings</h1>
							</div>
							<?php settings_fields('hmgpr_settings-group'); ?>
							<?php do_settings_sections('hmgpr_settings-group'); ?>
							<h2>Title at Introduction</h2>
							<p>Example: <code>Please Leave Us A Review Below.</code></p>
							<div class="hmp_field-container">
								<input type="text" name="hmgpr_introtitle" value="<?php echo esc_attr(get_option('hmgpr_introtitle')); ?>" />
							</div>

							<h2>Description at Introduction</h2>
							<p>Example: <code>Please check the amount of stars based on your experience.</code></p>
							<div class="hmp_field-container">
								<textarea name="hmgpr_introdescription"><?php echo esc_attr(get_option('hmgpr_introdescription')); ?></textarea>
							</div>

							<h2>Title if User Leaves 5 Star Review</h2>
							<p>Example: <code>Thank You For Leaving A Review!</code></p>
							<div class="hmp_field-container">
								<input type="text" name="hmgpr_successtitle" value="<?php echo esc_attr(get_option('hmgpr_successtitle')); ?>" />
							</div>

							<h2>Description if User Leaves 5 Star Review</h2>
							<p>Example: <code>Would you mind sharing your review on Google?</code></p>
							<div class="hmp_field-container">
								<textarea name="hmgpr_successdescription"><?php echo esc_attr(get_option('hmgpr_successdescription')); ?></textarea>
							</div>

							<h2>Title if User Leaves 4 Star or Less Review</h2>
							<p>Example: <code>Thank You For Leaving A Review!</code></p>
							<div class="hmp_field-container">
								<input type="text" name="hmgpr_failtitle" value="<?php echo esc_attr(get_option('hmgpr_failtitle')); ?>" />
							</div>

							<h2>Description if User Leaves 4 Star or Less Review</h2>
							<p>Example: <code>We are Sorry Your experience wasn't great.</code></p>
							<div class="hmp_field-container">
								<textarea name="hmgpr_faildescription"><?php echo esc_attr(get_option('hmgpr_faildescription')); ?></textarea>
							</div>
						</div>

						<div id="hmgpr_apiconnection" class="hmp_page">
							<div class="hmp_section-header">
								<h1><span class="hmp_section-header-icon dashicons dashicons-admin-multisite"></span> Google API Connection</h1>
							</div>

							<h2>Google PlaceID</h2>
							<p>Get your <a href="https://developers.google.com/places/place-id" target="_blank" title="Click to get your ID">PlaceID</a></p>

							<h2>Google Places API Key</h2>
							<p>Get your <a href="https://console.developers.google.com/cloud-resource-manager" target="_blank" title="Click to get your ID">Google Places API Key</a></p>
							<div class="hmp_field-container">
								<input type="text" name="hmgpr_google_api_key" placeholder="Google Places API Key" value="<?php echo esc_attr(get_option('hmgpr_google_api_key')); ?>" />
							</div>
						</div>

						<div id="hmgpr_documentation" class="hmp_page">
							<div class="hmp_section-header">
								<h1><span class="hmp_section-header-icon dashicons dashicons-media-text"></span> Documentation</h1>
							</div>

							<h2>Google Reviews</h2>
							<p>To show your google places listing reviews you can use our Gutenberg block under 'common' or the shortcode below:</p>
							<div class="hmp_field-container">
								<p><code>[hmgpr_reviews place_id="YOUR_PLACE_ID_HERE"]</code></p>
								<h4>Attributes</h4>
								<ul>
									<li><strong>place_id:</strong> Your google place id</li>
									<li><strong>format:</strong> 'row' or 'column'.</li>
									<li><strong>quote_color:</strong> Hex color code.</li>
									<li><strong>star_color:</strong> Hex color code.</li>
									<li><strong>wrapper_class:</strong> Your custom class.</li>
								</ul>
							</div>

							<h2>Review Lead Form</h2>
							<p>To add the review form to any page, please use the shortcode below:</p>
							<div class="hmp_field-container">
								<p><code>[hmgpr_form place_id="YOUR_PLACE_ID_HERE"]</code></p>
								<p>To show your google places review form you can use our Gutenberg block under 'common' or the shortcode below:</p>
								<p>This shortcode will display a review form on your website. If the user leaves a 5 star review they will be forwarded to your google business page. Any reviews with 4 stars or less will not redirect the user and will not be posted anywhere.</p>
								<p>This is a perfect way to filter out potentially harmful reviews being published on your google profile.</p>
								<h4>Attributes</h4>
								<ul>
									<li><strong>place_id:</strong> Your google place id</li>
									<li><strong>btn_class:</strong> Your custom class.</li>
									<li><strong>wrapper_class:</strong> Your custom class.</li>
								</ul>
							</div>
						</div>

						<?php submit_button(); ?>
					</form>
				</section>
			</div>
		</div><?php
	}
}