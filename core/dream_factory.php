<?php

class DreamFactory {

	public static $core_path;
	public static $resource_path;
	public static $blanket_path;

	public static $uri;
	public static $segments;
	public static $method;
	public static $resource_name;
	public static $body;
	public static $status_codes = array(

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

	public function __construct() {}

	// // static methods

	public static function fireup() {
		self::set_headers();
		
		self::$core_path = dirname( __FILE__ );
		self::$resource_path = realpath( dirname( __FILE__ ) . '/../' ) . '/resources';
		self::$blanket_path = realpath( dirname( __FILE__ ) . '/../' ) . '/blankets';
		self::$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		self::$segments = array_values(array_filter(explode('/', self::$uri)));
		self::$method = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'get';
		self::$resource_name = isset(self::$segments[0]) ? preg_replace('/[^a-z0-9]/', '', self::$segments[0]) : 'index';

		// read STDIN for REST POST and PUT bodies
		self::$body = file_get_contents('php://input'); 
	}

	public static function create_resource() {
		
		$class_file = self::$resource_path.'/'.self::$resource_name.'.php'; 
		
		if( self::$resource_name && file_exists( $class_file ) ) {
			
			require_once $class_file;
			
			$resource_class = ucfirst( strtolower( self::$resource_name ) );
			if( class_exists( $resource_class ) ) {

				return new $resource_class;

			} else {
				// broken/missing resource class
				self::status_exit(500);
			}
		} else {
			// resource doesn't exist
			self::status_exit(404);
		}
	}

	// Credit where credit is due: https://github.com/nramenta/bento/blob/master/src/bento.php
	public static function match_route($route, $path, &$matches = null)
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

	public static function set_headers() {
		header('Content-Type: application/json');
	}

	public static function status_exit( $code=200 ) {
		if( array_key_exists($code, self::$status_codes) ) {
			header("HTTP/1.1 {$code} ".self::$status_codes[$code], true, $code);
		}
		exit();
	}
}