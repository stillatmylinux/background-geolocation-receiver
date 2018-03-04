<?php

namespace BKGGEO;

class Location {
	public $id;
	public $latitude;
	public $longitude;
	public $accuracy;
	public $speed;
	public $altitude;
	public $uuid;
	public $timestamp;

	public function __construct( $data = array() ) {
		if( ! empty( $data ) ) {
			$this->id        = ( isset( $data['id'] ) )        ? $data['id'] : null;
			$this->latitude  = ( isset( $data['latitude'] ) )  ? $data['latitude'] : null;
			$this->longitude = ( isset( $data['longitude'] ) ) ? $data['longitude'] : null;
			$this->accuracy  = ( isset( $data['accuracy'] ) )  ? $data['accuracy'] : null;
			$this->speed     = ( isset( $data['speed'] ) )     ? $data['speed'] : null;
			$this->altitude  = ( isset( $data['altitude'] ) )  ? $data['altitude'] : null;
			$this->uuid      = ( isset( $data['uuid'] ) )      ? $data['uuid'] : null;
			$this->timestamp = ( isset( $data['timestamp'] ) ) ? $data['timestamp'] : null;
		}
	}

	public function save() {
		global $wpdb;

		$table_name = STIL_BKGGEO_Install()->_table_name;

		$data = array();
		$format = array();

		$items = array(
			'latitude',
			'longitude',
			'accuracy',
			'speed',
			'altitude',
			'uuid',
		);

		foreach( $items as $item ) {
			if( $this->$item ) {
				$data[$item] = $this->$item;
				$format[] = '%s';
			}
		}

		$wpdb->insert( $table_name, $data, $format );
	}
}