<?php

/**
 * Example Posts Resource
 *
 * @package Dreamr
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Matt Fiocca <matt.fiocca@gmail.com>
 */
class Posts extends DreamResource {

	/**
	 * Optional
	 * Set to TRUE to wipe out the predefined routes below
	 */
	public $reset_routes = FALSE;

	/**
	 * Define custom routes for this resource
	 *
	 * Here are some routes for free:
	 *
	 *	'get' => array(
	 *		"/posts/" => 'find_many',
	 *		"/posts/<#:id>/" => 'find',
	 *	),
	 *	'post' => array(
	 *		"/posts/" => 'create'
	 *	),
	 *	'put' => array(
	 *		"/posts/<#:id>/" => 'update'
	 *	),
	 *	'delete' => array(
	 *		"/posts/<#:id>/" => 'delete'
	 *	)
	 *
	 * @return array
	 */
	public function routes() {
		return array(
			'get' => array(
				'/posts/<#:postid>/comments/' => 'comments'
			),
			'post' => array(
				'/posts/<#:postid>/comments/' => 'create_comment'
			)
		);
	}

	/**
	 * Free Route Method
	 *
	 * @param array $params Associative array of params passed in the dynamic URL segments
	 * @return array Returning an array will auto-encode to JSON
	 */
	public function find( $params ) {
		return array(
			'id' => $params['postid'],
			'post' => array(),
			'method' => 'find'
		);
	}

	/**
	 * Free Route Method
	 *
	 * @return array Returning an array will auto-encode to JSON
	 */
	public function find_many() {
		return array(
			'posts' => array(),
			'method' => 'find_many'
		);
	}

	/**
	 * Free Route Method
	 *
	 * @param array $params Associative array of params passed in the dynamic URL segments
	 * @param array $data Associative array of data passed from a POST or PUT body
	 * @return array Returning an array will auto-encode to JSON
	 */
	public function create( $params, $data ) {
		return array(
			'post_data' => $data,
			'method' => 'create'
		);
	}

	/**
	 * Free Route Method
	 *
	 * Status 200 will respond on a successful return anyway,
	 * but you can call explicitly if you want like below
	 *
	 * Available status codes are in: DreamrFactory::$status_codes
	 */
	public function update() {
		$this->abort(200);
	}

	/**
	 * Free Route Method
	 *
	 * @param array $params Associative array of params passed in the dynamic URL segments
	 * @return array Returning an array will auto-encode to JSON
	 */
	public function delete( $params ) {
		return array(
			'id' => $params['postid'],
			'method' => 'delete'
		);
	}

	/**
	 * Custom Route Method
	 *
	 * @param array $params Associative array of params passed in the dynamic URL segments
	 * @return array Returning an array will auto-encode to JSON
	 */
	public function comments( $params ) {
		return array(
			'postid' => $params['postid'],
			'comments' => array(),
			'method' => 'comments'
		);
	}

	/**
	 * Custom Route Method
	 *
	 * This example shows how to abort with a 'Not Authorized'
	 */
	public function create_comment() {
		$this->abort(401);
	}
}