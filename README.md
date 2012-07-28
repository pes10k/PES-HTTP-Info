\PES\HTTP\Info
===

About
---

A small set of classes that allows for requesing information about a file from a webserver, without actually fetching the whole file.  This is done by just requesting the headers over HTTP, and then checking those, instead of actually fetching the entire document.

Note that this system will not work in all situations, such as when web applications serve files or HTML w/o checking the HTTP request headers (which is usually the case).  This will be more useful when requesting static assets, when the server (Apache, nginx, etc.) can be relied on to check the headers for you.

Requires
---

* PHP 5.3
* The cUrl extension

Author
---
Peter Snyder <snyderp@gmail.com>
