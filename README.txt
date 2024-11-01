=== WP Google Places Reviews ===
Contributors: hawpmedia
Tags: google reviews, reviews, blocks, review form, filter reviews
Requires at least: 3.5
Tested up to: 5.3.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add your Google Places profile reviews to your website.

== Description ==

**Description:** Welcome to WP Google Places Reviews: Easily add your Google Places profile reviews to your website. Provides a simple Gutenberg Block and Classic Editor Shortcode.

== Gutenberg Compatibility ==

As of version 1.0.0, there is a Gutenberg block for outputting the reviews and the form to filter out potentially harmful reviews.

== Installation ==

1. Upload the `wp-google-places-reviews` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You can configure the Plugin Settings in `Settings` -> `WP Google Places Reviews`

== Requirements ==

1. This plugin requires a Google Places API key in order to work. Check out this tutorial on how to get yours set up [here](https://www.youtube.com/watch?v=PsWaDosk2gc)
2. You will also need your Google Place ID. Search your listing to retrieve your Place ID [here](https://developers.google.com/places/place-id)

== Limitations ==

* Unfortunately, the Google Places API only allows a limit of 5 reviews to be fetched at one time, this is a limitation that Google controls and we are unable to get more reviews with their API at the moment.

== Developers ==

* This plugin uses the Google Places API to retrieve review data.

== Screenshots ==
1. Column layout.
2. Row layout.
3. Gutenberg block.

== Changelog ==

= 1.0.5 =
* Fix css issue where max width is not defined.

= 1.0.4 =
* Add shortcode documentation

= 1.0.3 =
* Fix potentially fatal error with a generic variable name that gets the version of the plugin, it is now removed

= 1.0.2 =
* Update meta, class and other prefix values
* Clean css
* Remove Schema plugin admin page

= 1.0.1 =
* Fix issue where files were being enqueued from the wrong path
* Increment version number

= 1.0.0 =
* Initial commit