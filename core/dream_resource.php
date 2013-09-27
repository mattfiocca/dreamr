<?php

/**
 * Resource Abstract Class
 *
 * @package Dreamr
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author Matt Fiocca <matt.fiocca@gmail.com>
 */
abstract class DreamResource {

	private $data;
	public $reset_routes = FALSE;

	public function __construct() {}

	public function run() {

		$resource_name = DreamFactory::$resource_name;
		$base_routes = array(
			'get' => array(
				"/{$resource_name}/" => 'find_many',
				"/{$resource_name}/<#:id>/" => 'find'
			),
			'post' => array(
				"/{$resource_name}/" => 'create'
			),
			'put' => array(
				"/{$resource_name}/<#:id>/" => 'update'
			),
			'delete' => array(
				"/{$resource_name}/<#:id>/" => 'delete'
			)
		);

		if( $this->reset_routes === TRUE )
			$routes = $this->routes();
		else
			$routes = array_merge_recursive($base_routes, $this->routes());

		if( is_array( $routes ) && array_key_exists(DreamFactory::$method, $routes) ) {

			foreach($routes[DreamFactory::$method] as $route=>$class_method) {

				if ( DreamFactory::match_route( $route, DreamFactory::$uri, $matches ) ) {

					$data = array(
						'params' => array(),
						'data' => DreamFactory::$body
					);

					foreach ( $matches as $key=>$val ) {
						if (is_string($key))
							$data['params'][$key] = urldecode($val);
					}

					if( method_exists($this, $class_method) ) {
						$this->data = call_user_func_array( array($this, $class_method), $data );
						$this->output();
						return;
					}
				}
			}

			// route doesn't exist
			$this->abort(404);

		} else {

			// method not allowed
			$this->abort(405);
		}
	}

	public function abort( $code ) {
		DreamFactory::abort($code);
	}

	private function output() {
		if( $this->data ) {
			echo json_encode($this->data);
		}
	}

	// abstract methods

	public abstract function routes();
}