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
	    http_response_code(404);
    } else {
	
		define('WP_USE_THEMES', false);
		require( $www_root . 'wp-blog-header.php');
		
		$js = file_get_contents('bkggeo-template.js');

		$backgroundGeo = new BackgroundGeo();
		
		$options = array(
			'[[location_url]]' => $backgroundGeo->get_api_url(),
		);
    
	    header('Content-Type: application/javascript');
	    echo replace_settings($js, $options);
    }
    
    
