\PES\HTTP\Info
===

About
---

A small set of classes that allows for requesing information about a file from a webserver, without actually fetching the whole file.  This is done by just requesting the headers over HTTP, and then checking those, instead of actually fetching the entire document.

Note that this system will not work in all situations, such as when web applications serve files or HTML w/o checking the HTTP request headers (which is usually the case).  This will be more useful when requesting static assets, when the server (Apache, nginx, etc.) can be relied on to check the headers for you.

Requires
---

* PHP 5.3
* The cURL extension

Usage
---

	$request = new \PES\HTTP\Info('http://assets.github.com/images/modules/footer/blacktocat.svg');
	$response = $request->fetch();

	if ( ! $request) {

		echo 'The GitHub cat icon has gone away!';

	} else {

		echo sprintf(
			'The GitHub cat icon is where we though it would be.  It is %d bytes big, and in %s format.',
			$response->size(),
			$response->type()
		);
	}


Author
---
Peter Snyder <snyderp@gmail.com>
