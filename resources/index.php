<?php

class Index extends DreamResource {

	public $reset_routes = TRUE;

	public function routes() {
		return array(
			'get' => array(
				'/' => 'index'
			)
		);
	}

	public function index() {
		$this->response(400);
	}
}