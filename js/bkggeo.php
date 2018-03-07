    <?php
    
    // Drop this into your plugin folder and access it directly
    // https://example.com/wp-content/plugins/your-plugin/js/get-js.php
    
    // It will load a js file named js-template.js
    
    /**
     * Finds the wp root folder.
     * Navigate through the parent directories to find the wp-load.php file.
     * Stop after reaching 10 directories up, so we don't run into a 
     * recursion error.
     * 
     * @param $path directory to look into
     * @param $max_recursive the max number of parent directories to navigate into
     * 
     * @return string|boolean Either a string of the path or false if the max recursion is reached
     */
    function find_parent_wp_root($path = './', $max_recursive = 10) {
    
	    if( $max_recursive === 0 )
		    return false;
    
	    if( file_exists( realpath($path) . '/wp-load.php' ) ) {
		    return realpath($path) . '/';
	    } else {
		    return find_parent_wp_root($path . '../', $max_recursive-1);
	    }
	}
	
	function replace_settings($js, $options) {

		foreach( $options as $opt_key => $opt_val ) {
			$js = str_replace($opt_key, $opt_val, $js);
		}

		return $js;
	}
    
    $www_root = find_parent_wp_root($path = './');
    
    if( ! $www_root ) {
		header('Content-Type: application/javascript');
		header('HTTP/1.1 200 OK');
		$js = file_get_contents('bkggeo-heartbeat.js');
		echo $js;
    } else {
	
		define('WP_USE_THEMES', false);
		require( $www_root . 'wp-blog-header.php');
		
		$js = file_get_contents('bkggeo-template.js');

		$backgroundGeo = new BackgroundGeo();
		$settings = new BKGGEO\Settings();
		
		$options = array(
			'[[location_url]]' => $backgroundGeo->get_api_url(),
			'[[desiredAccuracy]]' => $settings->get('bkgeo-accuracy'),
			'[[distance-filter]]' => $settings->get('bkgeo-distance-filter'),
			'[[recognition-interval]]' => $settings->get( 'bkgeo-recognition-interval' ),
			'[[stop-timeout]]' => $settings->get( 'bkgeo-stop-timeout' ),
			'[[stop-on-terminate]]' => $settings->get( 'bkgeo-stop-on-terminate' ),
			'[[start-on-boot]]' => $settings->get( 'bkgeo-start-on-boot' ),
			'[[heartbeat-interval]]' => $settings->get( 'bkgeo-heartbeat-interval' ),
			'[[autosync]]' => $settings->get( 'bkgeo-autosync' ),
			'[[bkgeo-debug]]' => $settings->get( 'bkgeo-debug' ),
		);
    
		header('Content-Type: application/javascript');
		header('HTTP/1.1 200 OK');
	    echo replace_settings($js, $options);
    }
    
    
