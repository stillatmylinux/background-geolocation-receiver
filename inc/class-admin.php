<?php

namespace BKGGEO;

class Admin {

	public function hooks() {
		add_action('admin_menu', array($this, 'admin_init'));
	}

	public function admin_init() {
		add_submenu_page( 'apppresser_settings', 'Background Geo', 'Background Geo', 'manage_options', 'bkggeo-settings', array($this, 'setting_page') );
	}

	public function setting_page() {

		if(isset($_POST['bkgeo-accuracy'], $_POST['bkgeo-distance-filter'], $_POST['bkgeo-recognition-interval'], $_POST['bkgeo-stop-timeout'] ) ) {
			update_option('bkgeo-accuracy', $_POST['bkgeo-accuracy']);
			update_option('bkgeo-distance-filter', (int)$_POST['bkgeo-distance-filter']);
			update_option('bkgeo-recognition-interval', (int)$_POST['bkgeo-recognition-interval']);
			update_option('bkgeo-stop-timeout', $_POST['bkgeo-stop-timeout']);
			update_option('bkgeo-stop-on-terminate', $_POST['bkgeo-stop-on-terminate']);
			update_option('bkgeo-start-on-boot', $_POST['bkgeo-start-on-boot']);
			update_option('bkgeo-heartbeat-interval', (int)$_POST['bkgeo-heartbeat-interval']);
			update_option('bkgeo-autosync', $_POST['bkgeo-autosync']);
			update_option('bkgeo-gmap-key', $_POST['bkgeo-gmap-key']);
		}

		$bkgeo_accuracy = get_option('bkgeo-accuracy');
		$distance_filter = get_option('bkgeo-distance-filter');
		$recognition_interval = get_option('bkgeo-recognition-interval');
		$stop_timeout = get_option('bkgeo-stop-timeout');
		$stop_on_terminate = get_option('bkgeo-stop-on-terminate');
		$start_on_boot = get_option('bkgeo-start-on-boot');
		$heartbeat_interval = get_option('bkgeo-heartbeat-interval');
		$autosync = get_option('bkgeo-autosync');
		$bkgeo_gmap_key = get_option('bkgeo-gmap-key');
		

		?>
		<form action="<?php echo esc_url( admin_url( 'admin.php?page=bkggeo-settings' ) ); ?>" method="post" dir="ltr">
			<div class="wrap bkgeo_settings">
			<h2>Background Geolocation Settings</h2>

			<b>Note:</b> A user must open their app before new settings are used.
			<hr>
			
			<h4>Geolocation config</h4>
			<label for="bkgeo-accuracy">
				Accuracy
				<select class="regular-text" name="bkgeo-accuracy">
					<option value="0" <?php selected( $bkgeo_accuracy, '0' ); ?>>Highest Power (GPS + Wifi + Cellular)</option>
					<option value="10" <?php selected( $bkgeo_accuracy, '10' ); ?>>Highest Accuracy (Wifi + Cellular)</option>
					<option value="100" <?php selected( $bkgeo_accuracy, '100' ); ?>>Lowest Power (Wifi (low power) + Cellular)</option>
					<option value="1000" <?php selected( $bkgeo_accuracy, '1000' ); ?>>Lowest Accuracy (Cellular only)</option>
				</select>
			</label>
			<p></p>

			<label for="bkgeo-distance-filter">
				Distance
				<input type="text" name="bkgeo-distance-filter" value="<?php echo $distance_filter ?>"> Meters
				<p>The minimum distance a device must move horizontally before an update event is generated.</p>
			</label>
			
			<hr>

			<h4>Activity Recognition config</h4>

			<label for="bkgeo-recognition-interval">
				<input type="text" name="bkgeo-recognition-interval" value="<?php echo $recognition_interval ?>"> Default: 10000 milliseconds<br>
				<p>The desired time between activity detections. Larger values will result in fewer activity detections while improving battery life. A value of 0 will result in activity detections at the fastest possible rate.</p>
			</label>

			<label for="bkgeo-stop-timeout">
				<input type="text" name="bkgeo-stop-timeout" value="<?php echo $stop_timeout ?>"> Stop timeout (default 5 minutes)
				<p>The number of <b>minutes</b> to wait before turning off location-services after the ActivityRecognition System (ARS) detects the device is STILL</p>
			</label>
			
			<h4>Application config</h4>
			<label for="bkgeo-stop-on-terminate">
				<input type="checkbox" name="bkgeo-stop-on-terminate" value="1" <?php checked( '1', $stop_on_terminate ) ?>> Stop on terminate
				<p>Set to off to continue tracking after user teminates the app.</p>
			</label>
			<label for="bkgeo-start-on-boot">
				<input type="checkbox" name="bkgeo-start-on-boot" value="1" <?php checked( '1', $start_on_boot ) ?>> Start on boot
				<p>Set to on to enable background-tracking after the device reboots.</p>
			</label>
			<label for="bkgeo-heartbeat-interval">
				<input type="text" name="bkgeo-heartbeat-interval" value="<?php echo $heartbeat_interval ?>"> Heartbeat interval (seconds)
				<p>Rate in <b>seconds</b> to fire heartbeat events.</p>
			</label>
			<h4>HTTP / SQLite config</h4>
			<label for="bkgeo-autosync">
				<input type="checkbox" name="bkgeo-autosync" value="1" <?php checked( '1', $autosync ) ?>> Auto sync
			</label>

			<h4>Google Map API</h4>
			<label for="bkgeo-gmap-key">
				<input type="text" name="bkgeo-gmap-key" value="<?php echo $bkgeo_gmap_key ?>"> Key
			</label>
			<p><input type="submit" class="button-primary" value="Save Settings"></p>
		</form>

		<hr>

		<h3>Shortcodes</h3>
			<p>[bkggeo-debug]</p>
			<p>[bkggeo-map]</p>
		<?php
	}

	public function page_dropdown( $setting_key ) {

		$selected_page = get_option( $setting_key );

		?>
		<select name="<?php echo $setting_key ?>"> 
			<option value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option> 
			<?php
				$pages = get_pages(); 
				foreach ( $pages as $page ) {
					$option = '<option value="' . $page->ID . '" ';
					$option .= ( $page->ID == $selected_page ) ? 'selected="selected"' : '';
					$option .= '>';
					$option .= $page->post_title;
					$option .= '</option>';
					echo $option;
				}
			?>
		</select>
		<?php
	}
}

$admin = new Admin();
$admin->hooks();