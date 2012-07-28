<?php

namespace \PES\HTTP\Info;

/**
 * PES\HTTP\Info
 *
 * This package for requesting information about a file served from a webserver
 * without having to fetch the entire file/document.  So if you're looking
 * to find out how large the file hosted at http://example.org/file.zip
 * is, this class wraps a technique that allows you to findout that it
 * is 1.5GB, w/o having to download 1.5GB of data first.
 *
 * @category    Net
 * @package     PES\HTTP\Info
 * @author      Peter Snyder <snyderp@gmail.com>
 * @version 	1.0
 */

/**
 * Instances of this class wrap information about files and resources served
 * from webservers over HTTP.  Instances are created and managed by instances
 * of \PES\HTTP\Info;  Clients should not need to create instances of this
 * class directly.
 */
class Response {

	/**
	 * The raw HTTP header returned from the webserver for this request.
	 *
	 * @var string
	 */
	private $headers;

	/**
	 * The size of the resource, as described by the response header.
	 *
	 * @var int|NULL
	 */
	private $file_size;

	/**
	 * The MIMEType of the resource, as advertised by the web server
	 * in the response header
	 *
	 * @var string|NULL
	 */
	private $mime_type;

	/**
	 * The name file being requested, as it exists on the server.  This
	 * may be different that the file as it appears in the request.  For
	 * example, if you request http://example.org/foo.zip, the web server
	 * could actually be fulfilling that request with a file called bar.zip.
	 * This property would store "bar.zip" in the above case.
	 *
	 * @var string|NULL
	 */
	private $file_name;

	/**
	 * The right most extension of the file used to satisfy the HTTP request.
	 * For example, if you request http://example.org/foo.zip, the web server
	 * could actually be fulfilling that request with a file called bar.zip.
	 * This property would store "zip" in the above case.
	 *
	 * @var string|NULL
	 */
	private $file_extension;

	/**
	 * Instances of this class must be initilized with the raw header text of
	 * the response being wrapped.
	 *
	 * @param string $headers
	 *   The response headers from the HTTP response being wrapped
	 */
	public function __construct($headers)
	{
		$this->setHeaders($headers);
	}

	/* ******************* */
	/* ! Getter / Setters  */
	/* ******************* */

	/**
	 * Returns the HTTP headers for this request.  This is the raw string
	 * returned by the server.
	 *
	 * @return string
	 *   The HTTP response headers
	 */
	public function headers()
	{
		return $this->headers;
	}

	/**
	 * Returns the size of the file or asset serving the URL request, if
	 * if available.
	 *
	 * @return int|NULL
	 *   Returns the file size, in bytes, if available.  Otherwise, if the
	 *   size wasn't included in the response headers, NULL.
	 */
	public function size()
	{
		return $this->file_size;
	}

	/**
	 * Returns the MIMEType of the file, as advertised in the HTTP response
	 * headers.
	 *
	 * @return string|NULL
	 *   Either the mime type, in string form, of the resource, or NULL
	 *   if the server didn't advertise a mime type / content type
	 *   for the resource.
	 */
	public function type()
	{
		return $this->mime_type;
	}

	/**
	 * The name file being requested, as it exists on the server.  This
	 * may be different that the file as it appears in the request.  For
	 * example, if you request http://example.org/foo.zip, the web server
	 * could actually be fulfilling that request with a file called bar.zip.
	 * This property would store "bar.zip" in the above case.
	 *
	 * @return string|NULL
	 *   The name of the file used to satisfy the request, if available.
	 *   Otherwise, NULL
	 */
	public function name()
	{
		return $this->file_name;
	}

	/**
	 * The right most extension of the file used to satisfy the HTTP request.
	 * For example, if you request http://example.org/foo.zip, the web server
	 * could actually be fulfilling that request with a file called bar.zip.
	 * This property would store "zip" in the above case.
	 *
	 * @return string|NULL
	 *   The right most file extension of the served file, if it exists.
	 *   Otherwise, NULL
	 */
	public function extension()
	{
		return $this->file_extension;
	}

	/**
	 * Sets the HTTP response headers being represented by this class.   This
	 * method also handles parsing the headers to build out the useful
	 * information clients may be interested in (filesize, etc.).
	 *
	 * @param string $headers
	 *   The HTTP response headers
	 *
	 * @return \PES\HTTP\Info\Response
	 *   Returns a reference to the current object, to allow for method chaining
	 */
	protected function setHeaders($headers)
	{
		$this->headers = $headers;

		// Now we can try to extract various useful pieces of information
		// from the headers via regexes.
		$file_type_pattern = '/Content-Type:\s?([^\s]+)/i';
		$file_type_matches = array();
		if (preg_match($file_type_pattern, $this->headers, $file_type_matches)) {
			$this->mime_type = trim($file_type_matches[1]);
		}

		$file_name_pattern = '/filename=([^\s]+)/i';
		$file_name_matches = array();
		if (preg_match($file_name_pattern, $this->headers, $file_name_matches)) {
			$this->file_name = trim($file_name_matches[1]);
		
			if (($index = strripos($this->file_name, '.')) !== FALSE) {
				$this->file_extension = substr($results['filename'], $index + 1);
			}
		}

		$file_size_pattern = '/content-length:\s?([^s]+)/i';
		$file_size_matches = array();
		if (preg_match($file_size_pattern, $this->headers, $file_size_matches)) {
			$this->file_size = trim($file_size_matches[1])''
		}

		return $this;
	}
}
