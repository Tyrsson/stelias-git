<?php
class Festival_Service_Directions
{



	public $scheme;

	public $endpointUri;

	public $httpClient;

	public $origin;

	public $destination;

	public $response;

	public $jsonHandler;

	public $xmlHandler;

	public $responseType;

	public $apiParams;

	public $log;

	public $options = array();

	public $params = array(
					'mode' => 'driving', // api default
					'sensor' => false,
					'origin' => "",
					'destination' => "",
	);

	public $uri;

	public $config = array('baseUri' => 'maps.googleapis.com/maps/api/directions/');

	public function __construct()
	{
		try {
			$this->getLog(); // lazy loads Dxcore_Log
			$this->setResponseType();
			$this->getHttpClient();
			$this->setScheme();

			$this->setUri();

			$this->httpClient->setUri($this->uri . $this->parseParams($this->params));


		} catch (Exception $e) {

		}
	}
	public function parseParams($rParams)
	{
		$params = '';
		foreach($rParams as $k => $v)
		{
			$params .= '&'.$k.'='.$v;
		}
		return $params;
	}
	public function getUri()
	{
		return $this->uri;
	}
	public function setUri()
	{
		$this->uri = "$this->getScheme().$this->config['baseUri'].$this->getResponseType()?";
	}
	public function __set($name, $value)
	{
		if(array_key_exists($name, $this->params))
		{
			$this->setParam($name, $value);
		}

		$this->$name = $value;

	}
	public function setParam($param, $value) {
		if(array_key_exists($param, $this->params)) {
			$this->params[$param] = $value;
		}
	}
	public function request()
	{
		$this->setResponse($this->_httpClient->request(Zend_Http_Client::GET));
	}
	/**
	 * @return the $xmlHandler
	 */
	public function getXmlHandler() {
		return $this->xmlHandler;
	}

	/**
	 * @param field_type $xmlHandler
	 */
	public function setXmlHandler($xmlHandler) {
		$this->xmlHandler = $xmlHandler;
	}

	/**
	 * @return the $responseType
	 */
	public function getResponseType() {
		return $this->responseType;
	}

	/**
	 * @param field_type $responseType
	 */
	public function setResponseType($responseType = 'json') {
		$this->responseType = $responseType;
	}

	/**
	 * @return the $apiParams
	 */
	public function getApiParams() {
		return $this->apiParams;
	}

	/**
	 * @param field_type $apiParams
	 */
	public function setApiParams($apiParams) {
		$this->apiParams = $apiParams;
	}

	/**
	 * @return the $config
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * @param field_type $config
	 */
	public function setConfig($config) {
		$this->config = $config;
	}

	/**
	 * @return the $origin
	 */
	public function getOrigin() {
		return $this->origin;
	}

	/**
	 * @param field_type $origin
	 */
	public function setOrigin($origin) {
		$this->origin = $origin;
	}

	/**
	 * @return the $destination
	 */
	public function getDestination() {
		return $this->destination;
	}

	/**
	 * @param field_type $destination
	 */
	public function setDestination($destination) {
		$this->destination = $destination;
	}

	/**
	 * @return the $response
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * @param field_type $response
	 */
	public function setResponse($response) {
		$this->response = $response;
	}

	/**
	 * @return the $jsonHandler
	 */
	public function getJsonHandler() {
		return $this->jsonHandler;
	}

	/**
	 * @param field_type $jsonHandler
	 */
	public function setJsonHandler($jsonHandler) {
		$this->jsonHandler = $jsonHandler;
	}

	/**
	 * @return the $sheme
	 */
	public function getScheme() {
		return $this->scheme;
	}

	/**
	 * @param field_type $scheme
	 */
	public function setScheme($scheme = null) {
		if($scheme == null) {
			$scheme = Zend_Registry::get('serverScheme');
		}
		$this->scheme = $scheme;
	}

	/**
	 * @return the $endpointUri
	 */
	public function getEndpointUri() {
		return $this->endpointUri;
	}

	/**
	 * @param field_type $endpointUri
	 */
	public function setEndpointUri($endpointUri) {
		$this->endpointUri = $endpointUri;
	}

	/**
	 * @return the $httpClient
	 */
	public function getHttpClient() {

		if($this->httpClient == null || !isset($this->httpClient) || empty($this->httpClient)) {
			$this->httpClient = new Zend_Http_Client();
		}

		return $this->httpClient;
	}

	/**
	 * @param field_type $httpClient
	 */
	public function setHttpClient($httpClient) {
		$this->httpClient = $httpClient;
	}
	/**
	 * @return the $log
	 */
	public function getLog() {
		if(!$this->log instanceof Dxcore_Log)
		{
			$this->log = Zend_Registry::get('log');
		}
		return $this->log;
	}

	/**
	 * @param field_type $log
	 */
	public function setLog($log) {
		$this->log = $log;
	}



}