<?php

use BKGGEO\Location;

class BackgroundGeo {

	/** db version
	 * @var string
	 */
	protected   $_db_version                =   '1.0.1';

	public static $api_url = '/wp-json/background-geo/v1/location';

	public function __construct() {
		if( !defined( 'BKGL_DB_VERSION' ) )
			define ('BKGL_DB_VERSION', $this->_db_version );
	}
	
	public function hooks() {
		add_action('rest_api_init', array($this, 'api_routes'));
		add_shortcode( 'bkggeo-debug', array($this, 'shortcode_debug' ) );
		add_action( 'plugins_loaded', array($this, 'bkgeo_install'), 11 );
	}

	public function bkgeo_install() {

		require_once( BKGGEO_INC . 'class-bkg-install.php' );

		global $wpdb;
		$wpdb->stil_bkggeo_table  =   STIL_BKGGEO_Install()->_table_name;

		if( is_admin() && !defined( 'DOING_AJAX' ) )
			$this->create_table();
	}

	/**create the table for savelist
	 * @author YITHEMES
	 * @since 1.0.0
	 */
	public function create_table() {
		$curr_db_version    =    get_option( 'bkggeo_db_version');

		if( $curr_db_version == '1.0.0' ) {

			// add_action( 'init', array( STIL_BKGGEO_Install(), 'update' ) );
			// do_action('bkggeo_installed');
			// do_action('bkggeo_updated');
		} else if( $curr_db_version!=$this->_db_version || !STIL_BKGGEO_Install()->is_table_created() ){
			add_action( 'init', array( STIL_BKGGEO_Install() , 'init' ) );
			// do_action('bkggeo_installed');
		}
	}

	public function api_routes() {

		/*
		 * /wp-json/background-geo/v1/location/
		 */

		register_rest_route( 
			'background-geo/v1',
			'/location', 
			array(
				'methods' => 'POST,GET',
				'callback' => array( $this, 'post_location' ),
			)
		);
	}

	public function post_location( $data ) {

		$time = time();
		$success = false;

		if( isset( $data ) ) {
			$json_store = $data->get_body();
			$json = json_decode( $json_store );

			$location = new Location();

			update_option('bkgeolocation:data', $time);
			update_option('bkgeolocation:data_json', $json_store);

			if( isset( $json->location, $json->location->coords ) ) {
				$coords = json_encode( $json->location->coords );
				
				if( isset( $json->location->coords->latitude, $json->location->coords->longitude ) ) {
					$location->latitude = $json->location->coords->latitude;
					$location->longitude = $json->location->coords->longitude;
				}

				if( isset( $json->location->coords->accuracy ) ) {
					$location->accuracy = $json->location->coords->accuracy;
				}

				if( isset( $json->location->coords->speed ) ) {
					$location->speed = $json->location->coords->speed;
				}

				if( isset( $json->location->coords->altitude ) ) {
					$location->altitude = $json->location->coords->altitude;
				}

				if( isset( $json->location->uuid ) ) {
					$location->uuid = $json->location->uuid;
				}

				if( isset( $json->device_id ) ) {
					$location->uuid = $json->device_id;
				}

				$location->save();
			}

			$success = true;
		}
		
		if( $success ) {
			wp_send_json_success( array( 'time' => time() ) );
		} else {
			update_option('bkgeolocation:fail', $time);
			wp_send_json_error( array( 'time' => time() ) );
		}
	}

	public function shortcode_debug($attr, $content) {
		global $wpdb;

		$sql = "SELECT * FROM wp_options WHERE option_name LIKE 'bkgeolocation%'";

		$results = $wpdb->get_results( $sql );

		foreach($results as $row) {
			echo $row->option_value . ': ' . $row->option_name . '<br>' . PHP_EOL;
		}
	}

	public function get_api_url() {
		return get_bloginfo('wpurl') . self::$api_url;
	}

	public function save_location_data( $data ) {

		wp_send_json_success( array( 
			'latitude'  => $data->coords->latitude,
			'longitude' => $data->coords->longitude,
			'altitude'  => $data->coords->altitude,
			'uuid'      => $data->uuid,
		) );

	}
}