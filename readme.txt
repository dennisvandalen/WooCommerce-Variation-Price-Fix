=== WooCommerce Variation Price Fix ===
Contributors: Dennis
Tags: woocommerce, variable product, variation price, price fix
Requires at least: 6.0
Tested up to: 6.8.1
Requires PHP: 7.2
Stable tag: 1.0
License: GPLv2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Custom fix for WooCommerce to replace the variable price range by the chosen variation's price on product pages.

== Description ==

This plugin replaces the default WooCommerce variable product price range with the price of the selected variation. It fixes the bug where the previously selected variation price was shown and avoids displaying "undefined" prices.

It uses the WooCommerce `found_variation` event to update price and availability dynamically.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory, or install via GitHub plugin.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Works automatically on variable WooCommerce product pages.

== Frequently Asked Questions ==

= Does this plugin work with all WooCommerce versions? =

It is tested up to WooCommerce 3.5.5 and PHP 7.2+. Should work on most modern WooCommerce versions, but verify on your setup.

= Can I customize the output? =

Yes, you can edit the plugin code or extend it via hooks if needed.

== Screenshots ==

1. Shows the price range replaced by the selected variation price.
2. Availability status updates accordingly.

== Changelog ==

= 1.0 =
* Initial release based on Laura Díaz plugin with fixes for variation price display bug.

== Upgrade Notice ==

None yet.

== License ==

This plugin is licensed under GPLv2+.

== Acknowledgments ==

Based on the original plugin "Replace the Variable Price range by the chosen variation price in WooCommerce" by Laura Díaz.