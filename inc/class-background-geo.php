<?php

class BackgroundGeo {

	public function hooks() {
		add_action('rest_api_init', array($this, 'api_routes'));
		add_shortcode( 'bkggeo-debug', array($this, 'shortcode_debug' ) );
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

		if( isset( $_POST, $_POST['data'] ) ) {
			update_option('bkgeolocation:data', $time);
			$success = true;
		}
		else if( isset( $_POST, $_POST['location'])) {
			update_option('bkgeolocation:location', $time);
			$success = true;
		}
		else if( isset( $_POST, $_POST['coords'])) {
			update_option('bkgeolocation:coords', $time);
			$success = true;
		}
		else if( isset( $_POST, $_POST['auth_token'])) {
			update_option('bkgeolocation:auth_token', $time);
			$success = true;
		}


		if( $success) {
			wp_send_json_success( array('time' => $time ) );
		}
		else {
			update_option('bkgeolocation:fail', $time);
			wp_send_json_error( array('time' => time() ) );
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

	public function save_location_data( $data ) {

		wp_send_json_success( array( 
			'latitude'  => $data->coords->latitude,
			'longitude' => $data->coords->longitude,
			'altitude'  => $data->coords->altitude,
			'uuid'      => $data->uuid,
		) );

	}
}