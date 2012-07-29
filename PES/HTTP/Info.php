<?php

namespace PES\HTTP;

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
  * Instances of this class handle fetching the request headers for a specific
  * URL.  This class is the public facing part of this package, and clients
  * should only need to instantiate instances of this class.  All other
  * classes in this package are managed automatically.
  */
class Info {

	/**
	 * The URL to request information about.  Should be a fully formed
	 * url, such as http://example.com/example.jpg
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * Constructor optionally allows for setting the URL to the checked
	 * at instantiation.
	 *
	 * @param string $url
	 *   The URL to ask for information about
	 */
	public function __construct($url = FALSE)
	{
		if ($url) {
			$this->setUrl($url);
		}
	}

	/**
	 * Attempts to fetch information about the given URL by requesting only the
	 * HTTP headers from the server.
	 *
	 * @return \PES\HTTP\Info\Response|FALSE
	 *   If no headers were able to be fetched, returns FALSE.  Otherwise,
	 *   returns a response object representing the returned header information.	 
	 */
	public function fetch()
	{
		$curl = curl_init();

		// Since we're not actually fetching the body of any assets, don't
		// bother checking SSL certs, etc.  This will also work around
		// the very old set of certs that cURL ships with.
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);     
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); 

		curl_setopt($curl, CURLOPT_URL, $this->url());
		curl_setopt($curl, CURLOPT_HEADER, TRUE);
		curl_setopt($curl, CURLOPT_NOBODY, TRUE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$headers = curl_exec($curl);
		curl_close($curl);

		// If we weren't able to get any valid headers from the server,
		// there is nothing further we can do.
		if ( ! $headers) {

			return FALSE;

		} else {

			return new Info\Response($headers);

		}
	}

	/* ******************** */
	/* ! Getters / Setters  */
	/* ******************** */

	/**
	 * Returns the URL that has been set to request from
	 *
	 * @return string|FALSE
	 *   Returns FALSE if no URL has yet been set.  Otherwise, the
	 *   url as a string
	 */
	public function url()
	{
		return $this->url;
	}

	/**
	 * Sets the URL to request information from.  This should be a full URL,
	 * including protocol and domain information, in the form of
	 * http://example.org/example.jpg
	 *
	 * @param string $a_url
	 *   The URL to request information from
	 *
	 * @return \PES\HTTP\Info
	 *   A reference to the current instance, to allow for method chaining
	 */
	public function setUrl($a_url)
	{
		$this->url = $a_url;
		return $this;
	}
}
