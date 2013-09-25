<?php

class Index extends DreamResource {

	public function routes() {
		return array(
			'get' => array(
				'/' => 'index'
			)
		);
	}

	public function index( $body ) {
		return array( );
	}
}