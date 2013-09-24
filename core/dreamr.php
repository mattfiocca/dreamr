<?php

require_once CORE_PATH . '/resource.php';

class Dreamr {

	public $uri;
	public $segments;
	public $method;
	public $resource_name;
	public $resource;

	private $status_codes = array(

		// Successful
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',

		// Redirection
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		// 306 => '(Unused)',
		307 => 'Temporary Redirect',

		// Client Error
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',

		// Server Error
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
	);

	public function __construct() {
		$this->uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		$this->segments = array_values(array_filter(explode('/', $this->uri)));
		$this->request_method = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'get';
		$this->resource_name = isset($this->segments[0]) ? preg_replace('/[^a-z0-9]/', '', $this->segments[0]) : null;
	}

	// Thank you for the route matcher, Bento 
	// (https://github.com/nramenta/bento/blob/master/src/bento.php)
	public function route_match($route, $path, &$matches = null)
	{
		$replace = function($match) {
			if ($match['rule'] === '') {
				return '(?P<' . $match['name'] . '>[^\/]+)';
			} elseif ($match['rule'] === '#') {
				return '(?P<' . $match['name'] . '>\d+)';
			} elseif ($match['rule'] === '$') {
				return '(?P<' . $match['name'] . '>[a-zA-Z0-9-_]+)';
			} elseif ($match['rule'] === '*') {
				return '(?P<' . $match['name'] . '>.+)';
			} else {
				return '(?P<' . $match['name'] . '>' . $match['rule'] . ')';
			}
		};

	    $pattern = '/<(?:(?P<rule>.+?):)?(?P<name>[a-z_][a-z0-9_]+)>/i';
	    $trailing = preg_match('/\/$/', $route);

	    return preg_match(
	        '#^' . preg_replace_callback($pattern, $replace, $route) . ($trailing ? '?' : null) . '$#',
	        urldecode($path),
			$matches
	    );
	}

	public function status_out( $code=200 ) {
		if( array_key_exists($code, $this->status_codes) ) {
			header("HTTP/1.1 {$code} ".$this->status_codes[$code], true, $code);
			exit();
		}
	}

	public function run() {
		if( $this->resource_name && file_exists( RESOURCE_PATH . "/{$this->resource_name}.php" ) ) {
			require_once RESOURCE_PATH . "/{$this->resource_name}.php";
			if( class_exists( ucfirst(strtolower($this->resource_name)) ) ) {
				$class = $this->resource_name;
				$this->resource = new $class;
				if( property_exists($this->resource, 'routes') || !is_array($this->resource->routes) )
				{
					foreach($this->resource->routes[$this->method] as $route=>$method) {
						if ( $this->route_match( $route, $uri, $matches ) ) {
							$params = array();
							foreach ( $matches as $key=>$val ) {
								if (is_string($key))
									$params[$key] = urldecode($val);
							}

							if( method_exists($this->resource, $method) ) {
								call_user_func_array( array($this->resource, $method), $params );
							} else {
								// @todo: 404 error here, method doesn't exist
								$this->status_out(404);
							}
						}
					}
				} else {
					// @todo: 500 error here, broken/missing routes
					$this->status_out(500);
				}
			} else {
				// @todo: 500 error here, broken/missing resource class
				$this->status_out(500);
			}
		} else {
			// @todo: 404 error here, resource doesn't exist
			$this->status_out(404);
		}
	}
}

$Dreamr = new Dreamr();

function show_404() {
	global $Dreamr;
	$Dreamr->status_out(404);
}

function show_500() {
	global $Dreamr;
	$Dreamr->status_out(500);
}

$Dreamr->run();