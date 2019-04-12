<?php
/**
 * @package MWM_Geotargeted_Banner
 * @version 1.0
 */
/*
Plugin Name: Geotargeted Banner
Plugin URI: https://www.makeworthymedia.com/
Description: Displays a banner to IP addresses with certain ISO 3166 continent codes using the geoplugin.net database. Requires Advanced Custom Fields Pro plugin. Settings are under the "Options" tab.
Author: Makeworthy Media
Version: 1.0
Author URI: https://www.makeworthymedia.com/
License: GPL2
*/

/*  Copyright 2019

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

//* Enqueue scripts
add_action( 'wp_enqueue_scripts', 'mwm_gtb_load_scripts' );
function mwm_gtb_load_scripts() {
	wp_enqueue_script( 'js-cookie', plugin_dir_url( __FILE__ ) . 'js/js.cookie.js', array( 'jquery' ), '2.1.1' );
	wp_enqueue_script( 'mwm-gtb-js', plugin_dir_url( __FILE__ ) . 'js/mwm-geotargeted-banner.js', array( 'jquery' ), '1.0' );
	
	wp_enqueue_style( 'mwm-gtb-css', plugin_dir_url( __FILE__ ) . 'mwm-geotargeted-banner.css');
}

// Add empty div to bottom of the page so we can insert the banner in it via javascript
add_action( 'wp_footer', 'mwm_gtb_display_banner_js' );
function mwm_gtb_display_banner_js() {
	echo '<div class="geotarget-footer-container"></div>';
	
	return true;
}

function mwm_gtb_js_variables() {
	
	if (function_exists('get_field') ) {
		$mwm_banner_html = get_field('mwm_banner_html', 'option');
		$mwm_continents = get_field('mwm_continents', 'option');
		$mwm_ssl_key = get_field('mwm_ssl_key', 'option');
		$mwm_cookie_expiration = get_field('mwm_cookie_expiration', 'option');
	}

	// Remove line breaks so javascript will work.
	$mwm_banner_html = str_replace("\n", '', $mwm_banner_html);
	$mwm_banner_html = str_replace("\r", '', $mwm_banner_html);

	if (!$mwm_banner_html) {
		$mwm_banner_html = 'This website uses cookies to ensure you get the best experience on our website. <button class="button">Accept</button>';
	} else {
		$mwm_banner_html = str_replace("'", "\'", $mwm_banner_html); // Escape ' characters so javascript will work.
	}
	
	// Format continent codes so we can set the javascript array variable
	if (! $mwm_continents) {
		$mwm_continent_codes = '';
	} else {
		$mwm_continent_codes = implode("', '", $mwm_continents);
		$mwm_continent_codes = "'" . $mwm_continent_codes . "'";
	}
	
	// Set cookie Expiration time
	if (!$mwm_cookie_expiration || !is_numeric($mwm_cookie_expiration) ) {
		$mwm_cookie_expiration = 30;    // Defaults to 30 days.
	}
	
	// Geoplugin script URL
	$mwm_geoplugin_script = 'http://www.geoplugin.net/json.gp?jsoncallback=?';
	
	if ($mwm_ssl_key) {
		$mwm_geoplugin_script = sprintf('https://ssl.geoplugin.net/json.gp?k=%s&jsoncallback=?', $mwm_ssl_key);
	}

	?>
	
	<script type="text/javascript">
		// Variables for the geotargeted privacy banner
		var mwmBannerContent = '<?php echo $mwm_banner_html; ?>';
		var mwmDisplayContinents = [<?php echo $mwm_continent_codes; ?>];
		var mwmCookieExpiration = <?php echo $mwm_cookie_expiration; ?>;
		var mwmGeopluginScript = '<?php echo $mwm_geoplugin_script; ?>';
	</script>
	
	<?php
}
add_action( 'wp_head', 'mwm_gtb_js_variables' );

// Add options page
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}

// Add custom field groups
if( function_exists('acf_add_local_field_group') ) :

acf_add_local_field_group(array(
	'key' => 'group_5c8ad1e05a74e',
	'title' => 'Geotargeted Banner Settings',
	'fields' => array(
		array(
			'key' => 'field_5c8ad1f418d6a',
			'label' => 'Banner HTML',
			'name' => 'mwm_banner_html',
			'type' => 'wysiwyg',
			'instructions' => 'Insert a &lt;button&gt; element in the HTML and it will automatically be able to close the banner. Example: &lt;button&gt;Close&lt;/button&gt;',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
			'delay' => 0,
		),
		array(
			'key' => 'field_5c8ad22e079bd',
			'label' => 'Display to these continents',
			'name' => 'mwm_continents',
			'type' => 'checkbox',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'AF' => 'Africa',
				'NA' => 'North America',
				'OC' => 'Oceania',
				'AN' => 'Antarctica',
				'AS' => 'Asia',
				'EU' => 'Europe',
				'SA' => 'South America',
			),
			'allow_custom' => 0,
			'default_value' => array(
			),
			'layout' => 'vertical',
			'toggle' => 0,
			'return_format' => 'value',
			'save_custom' => 0,
		),
		array (
			'key' => 'field_5cb1113ff6e97',
			'label' => 'Cookie expiration (in days)',
			'name' => 'mwm_cookie_expiration',
			'type' => 'number',
			'instructions' => 'Number of days until the banner reappears after being closed. Defaults to 30 days.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => '',
			'max' => '',
			'step' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_59248728d4ae8',
			'label' => 'SSL access key',
			'name' => 'mwm_ssl_key',
			'type' => 'text',
			'instructions' => 'If your site uses SSL, you must call the geolocation script with SSL to avoid security errors. To do so, you must purchase an access key here: <a href="https://www.geoplugin.com/premium#ssl_access_per_year" target="_blank">https://www.geoplugin.com/premium#ssl_access_per_year</a>	Enter the key in this field and the plugin will automatically use SSL to call the script.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;


