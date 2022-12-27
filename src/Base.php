<?php
namespace Falcon;

class Base {
	protected $features = [];

	public function __construct() {
		foreach ( $this->features as $feature ) {
			if ( Settings::is_feature_active( $feature ) ) {
				$this->$feature();
			}
		}
	}
}
