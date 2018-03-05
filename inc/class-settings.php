<?php

namespace BKGGEO;

class Settings {

	public $fields;

	public function get( $field ) {

		if( $this->fields === null ) {
			$this->fields = array();
			$this->fields['bkgeo-accuracy'] = $this->validate( 'bkgeo-accuracy', array('integer') );
			$this->fields['bkgeo-distance-filter'] = $this->validate( 'bkgeo-distance-filter', array('integer') );
			$this->fields['bkgeo-recognition-interval'] = $this->validate( 'bkgeo-recognition-interval', array('integer') );
			$this->fields['bkgeo-stop-timeout'] = $this->validate( 'bkgeo-stop-timeout', array('integer') );
			$this->fields['bkgeo-stop-on-terminate'] = $this->validate( 'bkgeo-stop-on-terminate', array('boolean') );
			$this->fields['bkgeo-start-on-boot'] = $this->validate( 'bkgeo-start-on-boot', array('boolean') );
			$this->fields['bkgeo-heartbeat-interval'] = $this->validate( 'bkgeo-heartbeat-interval', array('integer') );
			$this->fields['bkgeo-autosync'] = $this->validate( 'bkgeo-autosync', array('boolean') );
			$this->fields['bkgeo-gmap-key'] = $this->validate( 'bkgeo-gmap-key' );
		}

		return $this->fields[$field];
	}

	public function validate($field, $validators = array() ) {
		
		$value = get_option( $field );

		if( empty( $validators ) )
			return $value;

		if( in_array( 'boolean', $validators ) ) {
			return ((boolean)$value) ? 'true' : 'false';
		}

		if( in_array( 'integer', $validators ) ) {
			return (int)$value;
		}
	}
}