=== Geotargeted Banner ===
Contributors: jennettefulda
Donate link: https://www.makeworthymedia.com/
Tags: banner,geotargeting,geotarget,geotargeted,privacy
Requires at least: 5.0
Tested up to: 5.0
Stable tag: 1.0

Displays a banner to IP addresses with certain ISO 3166 continent codes using the geoplugin.net database.

== Description ==

Displays a banner to IP addresses with certain ISO 3166 continent codes using the geoplugin.net database. Requires the Advanced Custom Fields Pro plugin to work. Settings are under the "Options" tab. Due to the nature of IP addresses and the existence of VPN connections, there is no way to ensure the banner will only appear to people in a certain continent. There is no guarantee that this plugin will meet GDPR guidelines. Please consult a lawyer for all legal matters regarding GDPR.

== Installation ==

1. Upload the folder 'mwm-geotargeted-banner' to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Install and activate the Advanced Custom Fields Pro plugin.
4. Click on the "Options" tab to configure the banner settings.

== Frequently Asked Questions ==

= How do I edit my banner? =

You must have the Pro version of Advanced Custom Fields installed. <a href="https://www.advancedcustomfields.com/pro/" target="_blank">(Visit the ACF site here.)</a> Once that is activated, click on the "Options" tab to configure the banner settings.

= How do I insert a button to close the banner? =

If you include a &lt;button&gt;> element in the banner code, it will automatically close the banner when clicked. Example: &lt;button&gt;Close&lt;/button&gt;'

= I am using SSL on my site and I'm getting an error. How do I fix that? =

If you're using SSL on your site, any calls to javascript must use SSL too for security reasons. Otherwise, your browser won't load the script. You'll need to purchase an SSL access key from GeoPlugin.net at <a href="https://www.geoplugin.com/premium#ssl_access_per_year" target="_blank">https://www.geoplugin.com/premium#ssl_access_per_year</a>. Once you have a key, enter it in the SSL field of the settings and the banner will automatically use SSL to call the script.

== Changelog ==

= 1.0 =
* Original version of the plugin.