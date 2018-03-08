<?php

class BkgGeoMap {


	public function hooks()  {
		add_shortcode( 'bkggeo-clusters', array( $this, 'clusters' ) );
		add_shortcode( 'bkggeo-trails', array( $this, 'trails' ) );
	}

	public function clusters() {
		ob_start();
		bkgeo_get_template_part( 'bkggeo', 'clusters' );
		return ob_get_clean();
	}

	public function trails() {
		ob_start();
		bkgeo_get_template_part( 'bkggeo', 'trails' );
		return ob_get_clean();
	}
}

$bkgGeoMap = new BkgGeoMap();
$bkgGeoMap->hooks();