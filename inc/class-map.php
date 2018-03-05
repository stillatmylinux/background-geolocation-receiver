<?php

class BkgGeoMap {


	public function hooks()  {
		add_shortcode( 'bkggeo-map', array( $this, 'map' ) );
	}

	public function map() {
		ob_start();
		bkgeo_get_template_part( 'bkggeo', 'map' );
		return ob_get_clean();
	}
}

$bkgGeoMap = new BkgGeoMap();
$bkgGeoMap->hooks();