<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/stephanieland352
 * @since             1.0.0
 * @package           Wp_Job_Manager_Geolocation_Search
 *
 * @wordpress-plugin
 * Plugin Name:       WP Job Manager Geolocation Search
 * Plugin URI:        https://github.com/stephanieland352
 * Description:       Changes the WP Job manager plugin to search locations with a radius.
 * Version:           1.0.0
 * Author:            Stephanie Land
 * Author URI:       https://github.com/stephanieland352
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-job-manager-geolocation-search
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

include_once(ABSPATH.'wp-admin/includes/plugin.php');
if (!function_exists('is_plugin_active') || ! is_plugin_active( 'wp-job-manager/wp-job-manager.php' ) ) {
    // Stop plugin and show error
    deactivate_plugins( plugin_basename( __FILE__ ) );
    wp_die('The WP Job Manager Geolocation Search was deactivated because this plugin requires the WP Job Manager Parent Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-job-manager-geolocation-search-activator.php
 */
function activate_wp_job_manager_geolocation_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-job-manager-geolocation-search-activator.php';
	Wp_Job_Manager_Geolocation_Search_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-job-manager-geolocation-search-deactivator.php
 */
function deactivate_wp_job_manager_geolocation_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-job-manager-geolocation-search-deactivator.php';
	Wp_Job_Manager_Geolocation_Search_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_job_manager_geolocation_search' );
register_deactivation_hook( __FILE__, 'deactivate_wp_job_manager_geolocation_search' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-job-manager-geolocation-search.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_job_manager_geolocation_search() {

	$plugin = new Wp_Job_Manager_Geolocation_Search();
	$plugin->run();

}
run_wp_job_manager_geolocation_search();

if( !function_exists( 'the_distance' ) ) {
    function the_distance( $post_obj = null, $round = false ) {
        Rl_Geolocate_Jobs_Public::the_distance( $post_obj, $round );
    }
}

if( !function_exists( 'get_the_distance' ) ) {
    function get_the_distance( $post_obj = null, $round = false ) {
        return Rl_Geolocate_Jobs_Public::get_the_distance( $post_obj, $round );
    }
}



