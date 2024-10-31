=== My Envato ===
Contributors: polevaultweb
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=R6BY3QARRQP2Q
Plugin URI: http://www.polevaultweb.co.uk/plugins/my-envato/
Author URI: http://www.polevaultweb.com/
Tags: envato, marketplace, codecanyon, themeforest, audiojungle, activeden, graphicriver, videohive, photodune, 3docean, api
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A super simple plugin to display your recent 25 items from an Envato Marketplace.

== Description ==

This is a very simple plugin to display the last 25 items from an Envato marketplace such as ThemeForest or CodeCanyon for a particular user.

Each item's thumbnail is wrapped in a link to the item with the user's username added at the end as a referral link.

The items can be served up via a shortcode or a widget. The plugin uses the WordPress HTTP API and makes use of the Transients API to cache data from Envato for quicker pageloads. 

The output of the plugin can be changed using the following filters:

* *my_envato_items_start* - before the items list, default: 
`<ul>`
* *my_envato_item_start* - before the item link, default: 
`<li style="padding: 0 10px 10px 0; float:left;">`
* *my_envato_item_anchor_class* - class of item link, default:
* *my_envato_item_image_class* - class of item image, default:
* *my_envato_item_end* - after the item link, default:
`</li>`
* *my_envato_items_end* - after the items list, default:
`</ul><div style="clear: both"></div>`
* *my_envato_cache* - caching time in seconds, default: 60 * 60 = 1 hour

Shortcode usage in a post or page:
`[my-envato marketplace="codecanyon" user="pvw"]`

Shortcode usage outside of the loop, in a theme file:
`<?php echo do_shortcode('[my-envato marketplace="codecanyon" user="pvw"]'); ?>`

See the plugin in action in the sidebar [here](http://www.polevaultweb.com/plugins/)

If you have any issues or feature requests please visit and use the [Support Forum](http://www.polevaultweb.com/support/forum/my-envato-plugin/)

[Plugin Page](http://www.polevaultweb.com/plugins/my-envato/) | [@polevaultweb](http://www.twitter.com/polevaultweb/)

== Installation ==

This section describes how to install the plugin and get it working.

You can use the built in installer and upgrader, or you can install the plugin manually.

1. Delete any existing `my-envato` folder from the `/wp-content/plugins/` directory
2. Upload `my-envato` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

If you have to upgrade manually simply repeat the installation steps and re-enable the plugin.

== Changelog ==

= 1.0 =

* First release, please report any issues.

== Frequently Asked Questions ==

= I have an issue with the plugin =

Please visit the [Support Forum](http://www.polevaultweb.com/support/forum/my-envato-plugin/) and see what has been raised before, if not raise a new topic.

== Disclaimer ==

This plugin uses the Envato API and is not endorsed or certified by Envato. All Envato logoes and trademarks displayed on this website are property of Envato.