<div id="bkggeo-map" style="width:100%;height:100vh;min-height:480px"></div>

<?php

function bkggeo_get_coords_data() {

	global $wpdb;

	$coords = '';

	$sql = "SELECT * FROM {$wpdb->prefix}bkg_geo;";

	$results = $wpdb->get_results( $sql );

	if( $results ) {
		foreach( $results as $row ) {
			$coords .= '{coords:{';
			$coords .= 'latitude:'.$row->latitude.',';
			$coords .= 'longitude:'.$row->longitude.',';
			$coords .= '}},';
		}
	}

	return $coords;
}

$settings = new BKGGEO\Settings();

?>
<script type="text/javascript">

var bkggeo = {
	currentLocationMarker: null,
	lastLocation: true,
	locationMarkers: null,
	polyline: null,
};
	bkggeo.gmap = {
		callback: bkggeo.configureMap,
		dom: document.getElementById("bkggeo-map"),
	}
	bkggeo.api = {
		key: '<?php echo $settings->get('bkgeo-gmap-key') ?>',
	}

jQuery(document).on('ready', function() {
	bkggeo.add_mapapi()
});

var COLORS = {
  gold: '#fedd1e',
  white: "#fff",
  blue: "#2677FF",
  light_blue: "#3366cc",
  polyline_color: "#00B3FD",
  green: "#16BE42",
  dark_purple: "#2A0A73",
  grey: "#404040",
  red: "#FE381E",
  dark_red: "#A71300",
  black: "#000"
}

bkggeo.init = function() {
	bkggeo.fetchData();
}

bkggeo.fetchData = function() {

	bkggeo.geoData = [
		<?php echo bkggeo_get_coords_data() ?>
	];
	bkggeo.drawMarkers();
}

bkggeo.drawMarkers = function() {
	for(var i=0;i<bkggeo.geoData.length;i++) {
		bkggeo.updateCurrentLocationMarker(bkggeo.geoData[i]);
	}
}

bkggeo.configureMap = function() {
    /**
    * Configure Google Maps
	  * https://github.com/transistorsoft/cordova-background-geolocation-SampleApp/blob/master/src/pages/simple-map/simple-map.ts
    */
	bkggeo.locationMarkers = [];
    
    let latLng = new google.maps.LatLng(<?php echo $settings->get( 'bkgeo-starting-lat' ) ?>, <?php echo $settings->get( 'bkgeo-starting-lng' ) ?>);

    let mapOptions = {
      center: latLng,
      zoom: 15,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      zoomControl: true,
      mapTypeControl: false,
      panControl: false,
      rotateControl: false,
      scaleControl: false,
      streetViewControl: false,
      disableDefaultUI: true
    };
	bkggeo.map = new google.maps.Map(bkggeo.gmap.dom, mapOptions);
    
    // Blue current location marker
    bkggeo.currentLocationMarker = new google.maps.Marker({
      zIndex: 10,
      map: bkggeo.map,
      title: 'Current Location',
      icon: {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 12,
        fillColor: COLORS.blue,
        fillOpacity: 1,
        strokeColor: COLORS.white,
        strokeOpacity: 1,
        strokeWeight: 6
      }
    });
    
    // Red Stationary Geofence
    bkggeo.stationaryRadiusCircle = new google.maps.Circle({
      zIndex: 0,
      fillColor: COLORS.red,
      strokeColor: COLORS.red,
      strokeWeight: 1,
      fillOpacity: 0.3,
      strokeOpacity: 0.7,
      map: bkggeo.map
    });
    // Route polyline
    bkggeo.polyline = new google.maps.Polyline({
      map: bkggeo.map,
      zIndex: 1,
      geodesic: true,
      strokeColor: COLORS.polyline_color,
      strokeOpacity: 0.7,
      strokeWeight: 7,
      icons: [{
        repeat: '30px',
        icon: {
          path: google.maps.SymbolPath.FORWARD_OPEN_ARROW,
          scale: 1,
          fillOpacity: 0,
          strokeColor: COLORS.white,
          strokeWeight: 1,
          strokeOpacity: 1
        }
      }]
	});
	
	bkggeo.init();
}

bkggeo.updateCurrentLocationMarker = function(location) {
    var latlng = new google.maps.LatLng(location.coords.latitude, location.coords.longitude);
    bkggeo.currentLocationMarker.setPosition(latlng);   

    setTimeout(() => {
      bkggeo.map.setCenter(new google.maps.LatLng(location.coords.latitude, location.coords.longitude));
    });

    if (location.sample === true) {
      return;
    }
    if (bkggeo.lastLocation) {
      bkggeo.locationMarkers.push(bkggeo.buildLocationMarker(location));
    }
    // Add breadcrumb to current Polyline path.
    bkggeo.polyline.getPath().push(latlng);
    bkggeo.lastLocation = location;
}

bkggeo.buildLocationMarker = function(location, options) {
    options = options || {};
    
    return new google.maps.Marker({
      zIndex: 1,
      icon: {
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
        rotation: location.coords.heading,
        scale: 2,
        anchor: new google.maps.Point(0, 2.6),
        fillColor: COLORS.polyline_color,
        fillOpacity: 1,
        strokeColor: COLORS.black,
        strokeWeight: 1,
        strokeOpacity: 1
      },
      map: bkggeo.map,
      position: new google.maps.LatLng(location.coords.latitude, location.coords.longitude)
    });
}

bkggeo.add_mapapi = function() {
	var src = sImgMap.api.url+'callback=bkggeo.configureMap&key='+bkggeo.api.key;
	var s = document.createElement('script');
	var async = document.createAttribute('async');
	s.setAttributeNode(async);
	var defer = document.createAttribute('defer');
	s.setAttributeNode(defer);
	s.src = src;
	document.body.appendChild(s);
}

</script>