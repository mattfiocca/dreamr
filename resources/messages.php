<?php

class Messages extends Resource {

	public function routes() {
		return array(
			'get' => array(
				'/messages' => 'find_many',
				'/messages/<#:id>' => 'find',
				'/messages/<#:id>/comments/' => 'comments'
			),
			'post' => array(
				'/messages' => 'create'
			),
			'delete' => array(
				'/messages/<#:id>' => 'delete'
			)
		);
	}

	public function find( $messageid ) {
		print_r("Message: " . $messageid);
	}

	public function find_many() {
		print_r("All messages");
	}

	public function comments( $messageid ) {
		print_r("Comments for: " . $messageid);
	}

	public function create() {
		print_r("Created a new message");
	}

	public function delete( $messageid ) {
		print_r("Deleting: " . $messageid);
	}
}