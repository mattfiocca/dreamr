<?php

/**
 * Example Resource
 *
 * @package Dreamr
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Matt Fiocca <matt.fiocca@gmail.com>
 */
class Posts extends DreamResource {

	// set to TRUE to wipe out the predefined routes
	public $reset_routes = FALSE;

	/**
	 * Define custom routes for the Posts resource
	 *
	 * These routes are created for you automatically:
	 *
	 *	'get' => array(
	 *		"/posts" => 'find_many',
	 *		"/posts/<#:id>" => 'find',
	 *	),
	 *	'post' => array(
	 *		"/posts" => 'create'
	 *	),
	 *	'put' => array(
	 *		"/posts/<#:id>" => 'update'
	 *	),
	 *	'delete' => array(
	 *		"/posts/<#:id>" => 'delete'
	 *	)
	 *
	 * @return array
	 */
	public function routes() {
		return array(
			'get' => array(
				'/posts/<#:postid>/comments' => 'comments'
			),
			'post' => array(
				'/posts/<#:postid>/comments' => 'create_comment'
			)
		);
	}

	public function find( $params ) {
		return array(
			'id' => $params['id'],
			'post' => array(),
			'method' => 'find'
		);
	}

	public function find_many() {
		return array(
			'posts' => array(),
			'method' => 'find_many'
		);
	}

	public function create( $params, $data ) {
		return array(
			'post_data' => $data,
			'method' => 'create'
		);
	}

	public function update() {
		// 200 will respond on success anyway, but you can call explicitly if you want
		$this->response(200);
	}

	public function delete( $params ) {
		return array(
			'id' => $params['id'],
			'method' => 'delete'
		);
	}

	public function comments( $params ) {
		return array(
			'postid' => $params['postid'],
			'comments' => array(),
			'method' => 'comments'
		);
	}

	public function create_comment() {
		// Not Authorized to post comments
		$this->response(401);
	}
}