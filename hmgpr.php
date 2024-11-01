<?php
/**
 * Plugin Name: WP Google Places Reviews
 * Plugin URI: https://wordpress.org/plugins/wp-google-places-reviews/
 * Description: Request reviews from your customers with this simple plugin and push only the 5 star reviews through to Google.
 * Version: 1.0.5
 * Author: Hawp Media
 * Author URI: http://hawpmedia.com/
 */

if (!defined('ABSPATH')) exit();

define('HMGPR_PATH', plugin_dir_path(__FILE__));
define('HMGPR_URL', plugin_dir_url(__FILE__));

include_once(HMGPR_PATH.'base/admin.php');
$hm_admin_HMGPR = new hm_admin_HMGPR();

include_once(HMGPR_PATH.'modules/form.php');
$hm_form_HMGPR = new hm_form_HMGPR();

include_once(HMGPR_PATH.'modules/reviews.php');
$hm_review_HMGPR = new hm_review_HMGPR();