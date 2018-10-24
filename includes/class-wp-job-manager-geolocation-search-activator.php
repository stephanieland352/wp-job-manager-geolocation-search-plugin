<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/stephanieland352
 * @since      1.0.0
 *
 * @package    Wp_Job_Manager_Geolocation_Search
 * @subpackage Wp_Job_Manager_Geolocation_Search/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Job_Manager_Geolocation_Search
 * @subpackage Wp_Job_Manager_Geolocation_Search/includes
 * @author     Stephanie Land <kwikturnsteph@gmail.com>
 */
class Wp_Job_Manager_Geolocation_Search_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
// Require parent plugin
        if ( ! is_plugin_active( 'wp-job-manager/wp-job-manager.php' ) and current_user_can( 'activate_plugins' ) ) {
            // Stop activation redirect and show error
            wp_die('Sorry, but this plugin requires the WP Job Manager Parent Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
        }
	}

}
