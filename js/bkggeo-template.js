( function() {

	var bkgGeo = {
		location_count: 0,
	};

	bkgGeo.init = function() {

		if( window.BackgroundGeolocation ) {
			bkgGeo.plugin = window.BackgroundGeolocation;
			bkgGeo.listeners();
			bkgGeo.config();
		}

	}

	bkgGeo.config = function() {
		bkgGeo.plugin.configure({
			// Geolocation config
			desiredAccuracy: 0,
			distanceFilter: 10,
			// Activity Recognition config
			activityRecognitionInterval: 10000,
			stopTimeout: 5,
			// Application config        
			stopOnTerminate: false,
			startOnBoot: true,
			heartbeatInterval: 3,
			// HTTP / SQLite config
			url: "[[location_url]]",
			method: "POST",
			autoSync: true,
			maxDaysToPersist: 3,
			headers: {  // <-- Optional HTTP headers
				"X-FOO": "bar"
			},
			params: {   // <-- Optional HTTP params
				"device_id": (device && device.uuid) ? device.uuid : '',
			},
			// Logging and Debug
			debug: true,  // <-- Debug sounds & notifications.
			logLevel: bkgGeo.plugin.LOG_LEVEL_VERBOSE,
			logMaxDays: 1 // <-- 3 days of logs
		}, function(state) {
			// This callback is executed when the plugin is ready to use.
			console.log("BackgroundGeolocation ready: ", state);
			if (!state.enabled) {
				bkgGeo.plugin.start();
			}
		}, function(error) {
			// This callback is executed when the plugin is not ready to use.
			console.log("BackgroundGeolocation config failure: ", error);
		});
	}

	bkgGeo.listeners = function() {
		bkgGeo.plugin.on('location', bkgGeo.onLocation, bkgGeo.onLocationFailure);
		bkgGeo.plugin.on('motionchange', bkgGeo.onMotionChange);
		bkgGeo.plugin.on('providerchange', bkgGeo.onProviderChange);
		bkgGeo.plugin.on('motionchange', function(isMoving) {
			console.log('- onMotionChange: ', isMoving);
		});
		bkgGeo.plugin.on('geofence', function(geofence) {
			console.log('- onGeofence: ', geofence.identifier, geofence.location);
		});
		bkgGeo.plugin.on('http', function(response) {
			console.log('http success: ', response.responseText);
		}, function(response) {
			console.log('http failure: ', response.status);
		});
	}

	bkgGeo.onLocation = function(location, taskId) {
		var coords = location.coords;
		var lat    = coords.latitude;
		var lng    = coords.longitude;
		console.log('- Location: ', JSON.stringify(location));
	}

	bkgGeo.onLocationFailure = function() {
		console.log('bkgGeo.onLocationFailure');
	}

	bkgGeo.onMotionChange = function() {
		console.log('bkgGeo.onMotionChange');
	}

	bkgGeo.onProviderChange = function() {
		console.log('bkgGeo.onProviderChange');
	}

	window.bkgGeo = bkgGeo;

	// use this custom ready function to make sure your element is available
	ready('body', function(element) {
		window.bkgGeo.init();
	});

})();