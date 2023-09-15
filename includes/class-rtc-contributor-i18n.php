<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Rtc_Contributor
 * @subpackage Rtc_Contributor/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Rtc_Contributor
 * @subpackage Rtc_Contributor/includes
 * @author     Dharti Gajera <dhartigajera6218@gmail.com>
 */
class Rtc_Contributor_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'rtc-contributor',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
