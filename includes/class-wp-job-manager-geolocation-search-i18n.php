<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/stephanieland352
 * @since      1.0.0
 *
 * @package    Wp_Job_Manager_Geolocation_Search
 * @subpackage Wp_Job_Manager_Geolocation_Search/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Job_Manager_Geolocation_Search
 * @subpackage Wp_Job_Manager_Geolocation_Search/includes
 * @author     Stephanie Land <kwikturnsteph@gmail.com>
 */
class Wp_Job_Manager_Geolocation_Search_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-job-manager-geolocation-search',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
