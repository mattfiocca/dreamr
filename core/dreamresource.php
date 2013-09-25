<?php

abstract class DreamResource {

	private $data;

	public function __construct() {}

	public function route() {
		
		$routes = $this->routes();
		
		if( is_array( $routes ) && array_key_exists(DreamFactory::$method, $routes) )
		{
			foreach($routes[DreamFactory::$method] as $route=>$method) {
				
				if ( DreamFactory::match_route( $route, DreamFactory::$uri, $matches ) ) {
					
					// start off with the REST body as the first parameter
					$params = array( DreamFactory::$body );
					foreach ( $matches as $key=>$val ) {
						if (is_string($key))
							$params[$key] = urldecode($val);
					}
					
					if( method_exists($this, $method) ) {
						$this->data = call_user_func_array( array($this, $method), $params );
					} else {
						// method doesn't exist
						$this->status_exit(404);
					}
				}
			}
		} else {
			// broken/missing routes
			$this->status_exit(500);
		}
	}

	public function output() {
		if( $this->data ) {
			echo json_encode($this->data);
			$this->status_exit();
		}
	}

	public function status_exit( $code=200 ) {
		DreamFactory::status_exit( $code );
	}

	// abstract methods

	public abstract function routes();
}