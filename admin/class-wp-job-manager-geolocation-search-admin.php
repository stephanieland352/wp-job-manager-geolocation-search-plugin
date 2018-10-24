<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/stephanieland352
 * @since      1.0.0
 *
 * @package    Wp_Job_Manager_Geolocation_Search
 * @subpackage Wp_Job_Manager_Geolocation_Search/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Job_Manager_Geolocation_Search
 * @subpackage Wp_Job_Manager_Geolocation_Search/admin
 * @author     Stephanie Land <kwikturnsteph@gmail.com>
 */
class Wp_Job_Manager_Geolocation_Search_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        // add new settings tab
        add_filter( 'job_manager_settings', array($this, 'add_job_manager_geolocation_settings' ), 1 );
        // add column to job listing page
        add_filter( 'manage_job_listing_posts_columns', array( $this, 'my_custom_jobs_columns' ));
        add_action('manage_job_listing_posts_custom_column', array($this, 'custom_job_column_content'), 10, 2);
        // make it sortable
        add_filter( 'manage_edit-job_listing_sortable_columns', array( $this, 'my_sortable_custom_jobs_columns' ));
        add_action( 'pre_get_posts', array($this, 'geolocate_orderby') );

        add_action('update_option', array($this, 'geo_key_check'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Job_Manager_Geolocation_Search_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Job_Manager_Geolocation_Search_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	//	wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-job-manager-geolocation-search-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Job_Manager_Geolocation_Search_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Job_Manager_Geolocation_Search_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	//	wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-job-manager-geolocation-search-admin.js', array( 'jquery' ), $this->version, false );

	}

    // Add settings tab to job listing settings page

     function add_job_manager_geolocation_settings( $settings )
    {

        $settings['geolocation_settings'] = array(
            __( 'Geolocation Settings', 'wp-job-manager' ),
            array(
                array(
                    'name'    => 'geolocation_radius_unit',
                    'std'     => 'distance',
                    'label'   => __( 'Distance Unit', 'wp-job-manager' ),
                    'desc'    => __( 'Select the unit measure of distance.', 'wp-job-manager' ),
                    'type'    => 'select',
                    'options' => array(
                            ''=> 'Select',
                        'miles' => 'Miles',
                        'kilometers' => 'Kilometers'
                    ),
                ),
                array(
                    'name'        => 'geolocation_radius_distance',
                    'std'         => '10',
                    'placeholder' => '100',
                    'label'       => __( 'Radius Distance', 'wp-job-manager' ),
                    'desc'        => __( 'Search radius distance.', 'wp-job-manager' ),
                    'type' => 'number',
                    'attributes'  => array(),
                )
            ),
            array(
                'before' => __( 'Select geolocation settings.', 'wp-job-manager' ),
            ),
        );
        return $settings;
    }

    // Add Geolocated column to job listing all jobs page so that you can see if your listing has been geolocated
     function custom_job_column_content( $column_name, $post_id ) {
        if ( $column_name == 'geolocated' ) {
            //check if geolocated meta key exists
            $geolocated_meta = get_post_meta($post_id, 'geolocated', true);
            if ( ! empty ( $geolocated_meta ) ) {
                echo 'Yes';
            } else {
                echo 'No';
            }

        }
    }
     function my_custom_jobs_columns( $columns ) {

        /** Add a Geolocated Column **/
        $myCustomColumns = array(
            'geolocated' => __( 'Geolocated', 'wp-jobs-manager' )
        );
        $columns = array_merge( $columns, $myCustomColumns );

        return $columns;
    }
     function my_sortable_custom_jobs_columns() {
        $columns['geolocated'] = 'geolocated';

        return $columns;
    }
     function geolocate_orderby( $query ) {

        if (  admin_url( 'edit.php?post_type=job_listing' ) && ( $orderby = $query->get( 'orderby' ) ) ) {
            if($orderby == 'geolocated') {
                $query->set( 'meta_query', array(
                    'relation' => 'OR',
                    array(
                        'key' => 'geolocated',
                        'compare' => 'EXISTS'
                    ),
                    array(
                        'key' => 'geolocated',
                        'compare' => 'NOT EXISTS'
                    )
                ) );
                $query->set( 'orderby', 'meta_value' );
            }


        }
    }


    public function geo_key_check() {
        $geotest = WP_Job_Manager_Geocode::get_location_data('Dallas, Texas');
        $apikey = WP_Job_Manager_Geocode::get_google_maps_api_key($key);
        if ( is_wp_error( $geotest ) ) {
            $error_string = $geotest->get_error_message();

            echo '<div class="updated fade job-manager-updated"><p>' . $error_string . '</p></div>';
        }
        if(!get_option( 'geolocation_radius_unit' ) || !get_option( 'geolocation_radius_distance' )) {
            echo '<div class="error fade "><p>Please Check Geolocation Settings</p></div>';
        }
    }
}
