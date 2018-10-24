<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/stephanieland352
 * @since      1.0.0
 *
 * @package    Wp_Job_Manager_Geolocation_Search
 * @subpackage Wp_Job_Manager_Geolocation_Search/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Job_Manager_Geolocation_Search
 * @subpackage Wp_Job_Manager_Geolocation_Search/public
 * @author     Stephanie Land <kwikturnsteph@gmail.com>
 */
class Wp_Job_Manager_Geolocation_Search_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;



        add_filter( 'job_manager_get_listings', array($this, 'filter_by_geolocation_field_query_args'), 10, 2 );



        // filtering by lat/long

        add_filter( 'posts_fields' , array( $this, 'posts_fields'  ), 10, 2 );
        add_filter( 'posts_join'   , array( $this, 'posts_join'    ), 10, 2 );
        add_filter( 'posts_where'  , array( $this, 'posts_where'   ), 10, 2 );
        add_filter( 'posts_orderby', array( $this, 'posts_orderby' ), 10, 2 );

        // add distance on listing page
        add_action('job_listing_meta_end', array($this, 'add_distance_to_job_list'));


    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-job-manager-geolocation-search-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-job-manager-geolocation-search-public.js', array( 'jquery' ), $this->version, false );

	}




    function filter_by_geolocation_field_query_args( $query_args, $args ) {
        if ( isset( $_POST['form_data'] ) ) {
            parse_str( $_POST['form_data'], $form_data );
            // If this is set, we are filtering by location
            if ( ! empty( $form_data['search_location'] ) ) {
                // get field value from form
                $searchlocation = sanitize_text_field( $form_data['search_location'] );

                    // get geolocation of form value
                    $geolocation = WP_Job_Manager_Geocode::get_location_data($searchlocation);
                if ( is_wp_error( $geolocation ) ) {
                    $query_args['meta_query']=array();
                    $query_args['post__in'] = array(0);
                    return $query_args;

                }
                    $latitude = $geolocation [lat];
                    $longitude = $geolocation [long];
                    $city = $geolocation['city'];
                    $zip = $geolocation['postcode'];
                    $state = $geolocation['state_long'];
                    $country = $geolocation['country_long'];
                    if ($city || $zip ) {
                        if(get_option( 'geolocation_radius_unit' ) && get_option( 'geolocation_radius_distance' )) {
                            $query_args['meta_query']=array();
                            $geolocationQuery = array(
                                'geo_query' => array(
                                    'lat_field' => 'geolocation_lat',  // name of the meta field storing latitude
                                    'lng_field' => 'geolocation_long', // name of the meta field storing longitude
                                    'latitude'  => $latitude,    // latitude of the point we are getting distance from
                                    'longitude' => $longitude,   // longitude of the point we are getting distance from
                                    'distance'  => get_option( 'geolocation_radius_distance' ),           // maximum distance to search
                                    'units'     => get_option( 'geolocation_radius_unit' )       // supports options: miles, mi, kilometers, km
                                )
                            );
                            // Meta query by distance if the geolocation finds a city or a zip code
                            $query_args = array_merge($query_args, $geolocationQuery);
                            $query_args['order'] = 'ASC';
                            $query_args['orderby'] = 'distance';
                        }


                    } else if ($state) {
                        $query_args['meta_query']=array();
                    $stateOnlyQuery = array(
                        'meta_query' => array(
                                'relation' => 'OR',
                            array(
                                'key' => 'geolocation_state_long',
                                'value' => $state
                                ),
                            array(
                                    'key'     => '_job_location',
                                    'value'   => $state,
                                    'compare' => 'LIKE',
                            )
                        )
                    );
                        //query by state if
                        $query_args = array_merge($query_args, $stateOnlyQuery);
                        $query_args['order'] = 'ASC';
                        $query_args['orderby'] = 'date';
                    } else if ($country) {
                        $query_args['meta_query']=array();
                        $countryOnlyQuery = array(
                            'meta_query' => array(
                                'relation' => 'OR',
                                array(
                                    'key' => 'geolocation_country_long',
                                    'value' => $country
                                ),
                                array(
                                    'key'     => '_job_location',
                                    'value'   => $country,
                                )
                            )
                        );
                        $query_args = array_merge($query_args, $countryOnlyQuery);
                        $query_args['order'] = 'ASC';
                        $query_args['orderby'] = 'date';
                    }
            }
        }
        return $query_args;
    }

    // From plugin https://gschoppe.com/wordpress/location-searches/

    // add a calculated "distance" parameter to the sql query, using a haversine formula
    public function posts_fields( $sql, $query ) {
        global $wpdb;
        $geo_query = $query->get('geo_query');
        if( $geo_query ) {

            if( $sql ) {
                $sql .= ', ';
            }
            $sql .= $this->haversine_term( $geo_query ) . " AS geo_query_distance";
        }
        return $sql;
    }

    public function posts_join( $sql, $query ) {
        global $wpdb;
        $geo_query = $query->get('geo_query');
        if( $geo_query ) {

            if( $sql ) {
                $sql .= ' ';
            }
            $sql .= "INNER JOIN " . $wpdb->prefix . "postmeta AS geo_query_lat ON ( " . $wpdb->prefix . "posts.ID = geo_query_lat.post_id ) ";
            $sql .= "INNER JOIN " . $wpdb->prefix . "postmeta AS geo_query_lng ON ( " . $wpdb->prefix . "posts.ID = geo_query_lng.post_id ) ";
        }
        return $sql;
    }

    // match on the right metafields, and filter by distance
    public function posts_where( $sql, $query ) {
        global $wpdb;
        $geo_query = $query->get('geo_query');
        if( $geo_query ) {
            $lat_field = 'latitude';
            if( !empty( $geo_query['lat_field'] ) ) {
                $lat_field =  $geo_query['lat_field'];
            }
            $lng_field = 'longitude';
            if( !empty( $geo_query['lng_field'] ) ) {
                $lng_field =  $geo_query['lng_field'];
            }
            $distance = 20;
            if( isset( $geo_query['distance'] ) ) {
                $distance = $geo_query['distance'];
            }
            if( $sql ) {
                $sql .= " AND ";
            }
            $haversine = $this->haversine_term( $geo_query );
            $new_sql = "( geo_query_lat.meta_key = %s AND geo_query_lng.meta_key = %s AND " . $haversine . " <= %f )";
            $sql .= $wpdb->prepare( $new_sql, $lat_field, $lng_field, $distance );
        }
        return $sql;
    }

    // handle ordering
    public function posts_orderby( $sql, $query ) {
        $geo_query = $query->get('geo_query');
        if( $geo_query ) {
            $orderby = $query->get('orderby');
            $order   = $query->get('order');
            if( $orderby == 'distance' ) {
                if( !$order ) {
                    $order = 'ASC';
                }
                $sql = 'geo_query_distance ' . $order;
            }
        }
        return $sql;
    }

    public static function the_distance( $post_obj = null, $round = false ) {
        echo self::get_the_distance( $post_obj, $round );
    }

    public static function get_the_distance( $post_obj = null, $round = false ) {
        global $post;
        if( !$post_obj ) {
            $post_obj = $post;
        }
        if( property_exists( $post_obj, 'geo_query_distance' ) ) {
            $distance = $post_obj->geo_query_distance;
            if( $round !== false ) {
                $distance = round( $distance, $round );
            }
            return $distance;
        }
        return false;
    }

    private function haversine_term( $geo_query ) {
        global $wpdb;
        $units = "miles";
        if( !empty( $geo_query['units'] ) ) {
            $units = strtolower( $geo_query['units'] );
        }
        $radius = 3959;
        if( in_array( $units, array( 'km', 'kilometers' ) ) ) {
            $radius = 6371;
        }
        $lat_field = "geo_query_lat.meta_value";
        $lng_field = "geo_query_lng.meta_value";
        $lat = 0;
        $lng = 0;
        if( isset( $geo_query['latitude'] ) ) {
            $lat = $geo_query['latitude' ];
        }
        if(  isset( $geo_query['longitude'] ) ) {
            $lng = $geo_query['longitude'];
        }
        $haversine  = "( " . $radius . " * ";
        $haversine .=     "acos( cos( radians(%f) ) * cos( radians( " . $lat_field . " ) ) * ";
        $haversine .=     "cos( radians( " . $lng_field . " ) - radians(%f) ) + ";
        $haversine .=     "sin( radians(%f) ) * sin( radians( " . $lat_field . " ) ) ) ";
        $haversine .= ")";
        $haversine  = $wpdb->prepare( $haversine, array( $lat, $lng, $lat ) );
        return $haversine;
    }

    public function add_distance_to_job_list() {
        $postID = get_the_ID();
        $isDefault = get_post_meta($postID, 'isdefaultsearch', true);
        $distance = self::get_the_distance();
        if($isDefault== '') {
        echo '<div class="geolocation-distance" style="display: none">Distance: ' . round($distance). ' '. get_option( 'geolocation_radius_unit' ).'</div>';
    }

    }


}


