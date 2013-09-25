<?php

class Welcome extends DreamResource {

	public function routes() {
		return array(
			'get' => array(
				'/welcome' => 'find_many',
				'/welcome/<#:id>' => 'find',
				'/welcome/<#:id>/comments/' => 'comments',
				'/welcome/<#:id>/<$:name>' => 'find_name'
			),
			'post' => array(
				'/welcome' => 'create'
			),
			'put' => array(
				'/welcome/<#:id>' => 'update'
			),
			'delete' => array(
				'/welcome/<#:id>' => 'delete'
			)
		);
	}

	public function find( $body, $id ) {
		return array(
			'id' => $id,
			'body' => $body,
			'method' => 'find'
		);
	}
	
	public function find_name( $body, $id, $name ) {
		return array(
			'id' => $id,
			'name' => $name,
			'body' => $body,
			'method' => 'find_name'
		);
	}

	public function find_many( $body ) {
		return array(
			'body' => $body,
			'method' => 'find_many'
		);
	}

	public function create( $body ) {
		return array(
			'body' => $body,
			'method' => 'create'
		);
	}

	public function update( $body, $id ) {
		return array(
			'id' => $id,
			'body' => $body,
			'method' => 'update'
		);
	}

	public function delete( $body, $id ) {
		return array(
			'id' => $id,
			'body' => $body,
			'method' => 'delete'
		);
	}

	public function comments( $body, $id ) {
		return array(
			'id' => $id,
			'body' => $body,
			'method' => 'comments'
		);
	}
}