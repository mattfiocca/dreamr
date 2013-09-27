Dreamr
======

A twist on RESTful nano frameworks for PHP 5.3 and up. Supports POST/PUTting JSON bodies or Query Strings, returns JSON encoded data.
This framework uses the route matching system from Bento:

@nramenta
https://github.com/nramenta/bento/blob/master/src/bento.php

#Setup

Just require the dreamr.php library in your index.php file, then fire it up:

```php
<?php

// Bootstrap Dreamr
require_once 'core/dreamr.php';

// Fire it up
DreamFactory::start();
```

You can also pass configuration variables this way:

```php
...
// Fireup it up
DreamFactory::start(array(
	'setting1' => 'mysetting1',
	'setting2' => 'mysetting2'
));
```

Then, create resources in the /resources/ directory. In this example we'll create a Posts resource:

```php
<?php

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
```

#Routing Notes

(Written By @nramenta, Bento)

Routes must always begin with a forward slash. Routes can contain dynamic paths along with their optional rules.
Dynamic paths will be translated to positional parameters passed on to the route handler.
They can also be accessed using the params() function.

The syntax for dynamic paths is:

```
<rule:name>
```

The rule is optional; if you omit it, be sure to also omit the : separator.
Named paths without rules matches all characters up to but not including /.

Some examples of routes:

```
/posts/<#:id>
/users/<username>
/pages/about
/blog/<#:year>/<#:month>/<#:date>/<#:id>-<$:title>
/files/<:path>
```

There are three built-in rules:

```
#: digits only, equivalent to \d+.
$: alphanums and dashes only, equivalent to [a-zA-Z0-9-_]+.
: any characters including /, equivalent to .+.
```

Custom rules are defined using regular expressions:

```
/users/<[a-zA-Z0-9_]+:username>
```

Using the # character inside a custom rule should be avoided;
URL paths cannot contain any # characters as they are interpreted as URL fragments.
If you want to use them to match url-encoded # (encoded as %23), you must escape them as \#.

Routes are matched first-only, meaning if a route matches the request path then its route handler
will be executed and no more routes will be matched. Requests that do not match any
routes will yield a "404 Not Found" error.

#Testing

curl -i -X GET [website]/posts/

curl -i -X GET [website]/posts/50/

curl -i -X GET [website]/posts/50/comments/

curl -i -X POST -d '{"title":"This is a title", "description":"This is a description"}' [website]/posts/

curl -i -X POST -d '{"comment":"This is a comment"}' [website]/posts/50/comments/

curl -i -X PUT -d '{"title":"This is a new title", "description":"This is a new description"}' [website]/posts/50/

curl -i -X DELETE [website]/posts/50/

#Credits

@nramenta - route matching 
@gyatesiii - Coming up with the Dreamr name

#Todo

- implement 'Blankets'
- Configuration options
- Force routes to match resources with or without an ending "/", via 301 or other
- Exceptions?
- Auth?