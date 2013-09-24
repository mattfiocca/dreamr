dreamr
======

A very slim RESTful framework for PHP

NOTES:
Routes must always begin with a forward slash. Routes can contain dynamic paths along with their optional rules.
Dynamic paths will be translated to positional parameters passed on to the route handler.
They can also be accessed using the params() function.

The syntax for dynamic paths is:
<rule:name>

The rule is optional; if you omit it, be sure to also omit the : separator.
Named paths without rules matches all characters up to but not including /.

Some examples of routes:

/posts/<#:id>
/users/<username>
/pages/about
/blog/<#:year>/<#:month>/<#:date>/<#:id>-<$:title>
/files/<:path>

There are three built-in rules:

#: digits only, equivalent to \d+.
$: alphanums and dashes only, equivalent to [a-zA-Z0-9-_]+.
: any characters including /, equivalent to .+.

Custom rules are defined using regular expressions:

/users/<[a-zA-Z0-9_]+:username>

Using the # character inside a custom rule should be avoided;
URL paths cannot contain any # characters as they are interpreted as URL fragments.
If you want to use them to match url-encoded # (encoded as %23), you must escape them as \#.

Routes are matched first-only, meaning if a route matches the request path then its route handler
will be executed and no more routes will be matched. Requests that do not match any
routes will yield a "404 Not Found" error.

Credits for the route matching function go to Bento:

https://github.com/nramenta/bento/blob/master/src/bento.php