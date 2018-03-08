<?php

namespace BKGGEO;

class Admin {

	public function hooks() {
		add_action('admin_menu', array($this, 'admin_init'));
	}

	public function admin_init() {
		add_submenu_page( 'apppresser_settings', 'Background Geo', 'Background Geo', 'manage_options', 'bkggeo-settings', array($this, 'setting_page') );
		add_menu_page( 'Bkg Geo Data', 'Background Geo Data', 'manage_options', 'bkggeo-data', array($this, 'geo_data_page') );
		remove_menu_page( 'bkggeo-data' ); // hidden page: admin.php?page=bkggeo-data
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
			update_option('bkgeo-debug', $_POST['bkgeo-debug']);
			update_option('bkgeo-disabled', $_POST['bkgeo-disabled']);
			update_option('bkgeo-starting-lat', $_POST['bkgeo-starting-lat']);
			update_option('bkgeo-starting-lng', $_POST['bkgeo-starting-lng']);
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
		$debug = get_option('bkgeo-debug');
		$bkggeo_disabled = get_option('bkgeo-disabled');
		$starting_lat = get_option('bkgeo-starting-lat');
		$starting_lng = get_option('bkgeo-starting-lng');
		

		?>
		<form action="<?php echo esc_url( admin_url( 'admin.php?page=bkggeo-settings' ) ); ?>" method="post" dir="ltr">
			<div class="wrap bkgeo_settings">
			<h2>Background Geolocation Settings</h2>

			<p><b>Note:</b> A user must open their app before new settings are used.</p>

			
			<label for="bkgeo-disabled">
				<input type="checkbox" name="bkgeo-disabled" value="1" <?php checked( '1', $bkggeo_disabled ) ?>> <b>Disable:</b> Stop collecting locations
			</label>
			<p><a href="admin.php?page=bkggeo-data">Geo Data</a> (where you go to delete data)</p>
			
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
				<input type="text" name="bkgeo-recognition-interval" value="<?php echo $recognition_interval ?>"> Milliseconds (Default: 10000 milliseconds)<br>
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
			<label for="bkgeo-starting-lat">
				<input type="text" name="bkgeo-starting-lat" value="<?php echo $starting_lat ?>"> Starting latitude
			</label><br>
			<label for="bkgeo-starting-lng">
				<input type="text" name="bkgeo-starting-lng" value="<?php echo $starting_lng ?>"> Starting longitude
			</label>
			
			<h4>HTTP / SQLite config</h4>
			<label for="bkgeo-autosync">
				<input type="checkbox" name="bkgeo-autosync" value="1" <?php checked( '1', $autosync ) ?>> Auto sync
			</label>

			<h4>Google Map API</h4>
			<label for="bkgeo-gmap-key">
				<input type="text" name="bkgeo-gmap-key" value="<?php echo $bkgeo_gmap_key ?>"> Key
			</label>

			<h4>Development</h4>
			<label for="bkgeo-debug">
				<input type="checkbox" name="bkgeo-debug" value="1" <?php checked( '1', $debug ) ?>> Debug
				<p>Some helpful beeps and tones that get annoying after a while. Use only during development.</p>
			</label>
			<p><input type="submit" class="button-primary" value="Save Settings"></p>
		</form>

		<hr>

		<h3>Shortcodes</h3>
			<p>[bkggeo-debug]</p>
			<p>[bkggeo-clusters]</p>
			<p>[bkggeo-trails]</p>
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

	public function geo_data_page() {

		if( isset( $_POST['confirmed-delete'] ) && $_POST['confirmed-delete'] == 'true' ) {
			$data_deleted = $this->delete_all_data();
		}

		?>

		<form id="geo-data-delete-form" action="<?php echo esc_url( admin_url( 'admin.php?page=bkggeo-data' ) ); ?>" method="post" dir="ltr">

			<div class="wrap bkgeo_data">
			<h2>Background Geolocation Data</h2>

			<!-- <p><b>Note:</b></p> -->

			<?php if( $data_deleted ) : ?>
				<div class="error notice">
					<p>The background geo data has been deleted.</p>
			    </div>
			<?php endif; ?>

			<p><b>Entries:</b> <?php echo  $this->count_all_rows() ?></p>
			<input type="hidden" name="confirmed-delete" value="true">
			
			<?php //submit_button( 'Delete All Data', 'delete' ); ?>
			<input type="submit" class="button delete" value="Delete All Geo Data"> <b>Warning!</b> Irreversible, create a backup of your database first.

			</div>
			<script type="text/javascript">
				jQuery('#geo-data-delete-form .delete').on('click', function(event) {
					event.preventDefault();
					var r = confirm("Are you sure you want to delete all the data you have collected?");
					if (r == true) {
						jQuery('#geo-data-delete-form').submit();
					}
				})
			</script>
		</form>


		<?php
	}

	public function count_all_rows() {
		global $wpdb;

		$sql = "SELECT count(*) as count FROM {$wpdb->prefix}bkg_geo WHERE 1";

		$row = $wpdb->get_row( $sql );

		return $row->count;
	}

	private function delete_all_data() {

		global $wpdb;

		$sql = "DELETE FROM {$wpdb->prefix}bkg_geo WHERE 1";

		$wpdb->query( $sql );

		return true;
	}

}

$admin = new Admin();
$admin->hooks();