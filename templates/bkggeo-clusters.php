<div id="bkggeo-clusters" style="width:100%;height:100vh;min-height:480px"></div>
<?php

function bkggeo_get_coords_data() {

	global $wpdb;

	$coords = '';

	$sql = "SELECT * FROM {$wpdb->prefix}bkg_geo;";

	$results = $wpdb->get_results( $sql );

	if( $results ) {
		foreach( $results as $row ) {
			$coords .= '{';
			$coords .= 'lat:'.$row->latitude.',';
			$coords .= 'lng:'.$row->longitude;
			$coords .= '},';
		}
	}

	return $coords;
}

$settings = new BKGGEO\Settings();

?>


<script>

var bkggeo = {};

  bkggeo.configureMap = function() {

    var map = new google.maps.Map(document.getElementById('bkggeo-clusters'), {
      zoom: 8,
      center: {lat: <?php echo $settings->get( 'bkgeo-starting-lat' ) ?>, lng: <?php echo $settings->get( 'bkgeo-starting-lng' ) ?>}
    });

    // Create an array of alphabetical characters used to label the markers.
    var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Add some markers to the map.
    // Note: The code uses the JavaScript Array.prototype.map() method to
    // create an array of markers based on a given "locations" array.
    // The map() method here has nothing to do with the Google Maps API.
    var markers = locations.map(function(location, i) {
      return new google.maps.Marker({
        position: location,
        label: labels[i % labels.length]
      });
    });

    // Add a marker clusterer to manage the markers.
    var markerCluster = new MarkerClusterer(map, markers,
        {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
  }
  var locations = [
    <?php echo bkggeo_get_coords_data() ?>
  ]
</script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?callback=bkggeo.configureMap&key=<?php echo $settings->get('bkgeo-gmap-key') ?>"></script>
