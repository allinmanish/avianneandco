<?php
/**
 * Eye4fraud Connector Magento Module
 *
 * @category    Eye4fraud
 * @package     Eye4fraud_Connector
 */

class Eye4Fraud_Connector_Helper_Curl
    extends Mage_Core_Helper_Abstract
{
    const HTTP_STATUS_BAD_RESPONSE = -1; // Not an HTTP response code
    const HTTP_STATUS_OK = 200;
    const HTTP_STATUS_BAD_REQUEST = 400;
    const HTTP_STATUS_UNAUTHORIZED = 401;
    const HTTP_STATUS_FORBIDDEN = 403;
    const HTTP_STATUS_NOT_FOUND = 404;
    const HTTP_STATUS_NOT_ACCEPTABLE = 406;
    const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;
    const HTTP_STATUS_SERVICE_UNAVAILABLE = 503;

    const COOKIES_FILE = 'cookies.txt';

    /**
     * @param int $timeout Timeout in seconds. Default value is 10 minutes
     */
    function __construct($timeout = 600) {
        $this->curlHandle = curl_init();
        curl_setopt($this->curlHandle, CURLOPT_COOKIEJAR, self::COOKIES_FILE);
        curl_setopt($this->curlHandle, CURLOPT_COOKIEFILE, self::COOKIES_FILE);
        curl_setopt($this->curlHandle, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curlHandle, CURLOPT_TIMEOUT, $timeout);
    }

    function close() {
        if ($this->curlHandle) {
            curl_close($this->curlHandle);
        }
    }

	public function get($url, $get_data = array(), $headers = array()) 
	{
		// Build url
        if ($get_data) {
			$url = $url.'/?'.http_build_query($get_data);
        }
        curl_setopt($this->curlHandle, CURLOPT_URL, $url);

		// Add headers
        if ($headers) {
            curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($this->curlHandle);

        // make sure there's no errors
        $errno = curl_errno($this->curlHandle);
        if ($errno !== 0) {
            $error = curl_error($this->curlHandle);
            trigger_error("CURL error: $errno - $error, url: $url");
        }

        // make sure we got a 200
        $code = (int)curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE);
        if ($code != self::HTTP_STATUS_OK) {
            trigger_error("HTTP status code: $code, response=$response, url=$url");
        }
        return $response;
	}
	
	public function post($url, $post_data = array(), $headers = array()) 
	{            





        curl_setopt($this->curlHandle, CURLOPT_URL, $url);
        if ($post_data) {
            curl_setopt($this->curlHandle, CURLOPT_POST, 1);
            curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, http_build_query($post_data));
        }
        if ($headers) {
            curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($this->curlHandle);

        // make sure there's no errors
        $errno = curl_errno($this->curlHandle);
        if ($errno !== 0) {
            $error = curl_error($this->curlHandle);
            trigger_error("CURL error: $errno - $error, url: $url");
        }

        // make sure we got a 200
        $code = (int)curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE);
        if ($code != self::HTTP_STATUS_OK) {
            trigger_error("HTTP status code: $code, response=$response, url=$url");
        }                 
        
        
        return $response;
	}
}
