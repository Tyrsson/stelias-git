<?php

/**
 * Adapter for authorize.net gateway.
 * Implementation with PayPal similarities:
 * @todo these adapters should implement the generic Payment adapter.
 *
 */
abstract class System_Service_Authorize_Adapter_AbstractAdapter
{
	/**
	 * API Username
	 * @var string
	 */
	protected $_username;

	/**
	 * Logger
	 *
	 * @var Zend_Log
	 */
	protected $_log;

	/**
	 * Constructor
	 *
	 * @param array|Zend_Config $options
	 * @return void
	 */
	public function __construct( $options = null )
	{
		parent::__construct( $options );
	}

	/**
	 * Executes a method. All calls are made through this method.
	 *
	 * @param string $method the API method to call
	 * @param array $fields
	 *
	 * @return System_Service_Payment_Response
	 */
	public function callApi( $method, array $fields )
	{

		$this->_validateCredentials();
		$auth = array(
            'username'  => $this->_username
		);
		$fields = $auth + $fields;

		$this->_callApi( $method, $fields );
	}

	protected function _callApi( $method, array $fields )
	{}

	/**
	 * Username setter
	 *
	 * @param string $username
	 */
	public function setUsername( $username )
	{
		$this->_username = $username;
		return $this;
	}

	/**
	 * Retrieve the username
	 * (for authorize.net, this is actually the loginID)
	 *
	 * @return string
	 */
	public function getUsername()
	{
		return $this->_username;
	}

	/**
	 * Set adapter state from array of options
	 *
	 * @param array $options
	 */
	public function setOptions( array $options )
	{
		if( isset( $options['username'] ) ) {
			$this->setUsername( $options['username'] );
			unset( $options['username']);
		}
	}

	/**
	 * Get the HTTP client to be used for this service
	 *
	 * @return Zend_Http_Client
	 */
	public function getHttpClient()
	{
		return $this->_httpClient;
	}

	/**
	 * Set the HTTP client to be used for this service
	 *
	 * @param Zend_Http_Client $client
	 */
	public function setHttpClient(Zend_Http_Client $client)
	{
		$this->_httpClient = $client;
	}

	/**
	 * Sets options based on a config object
	 *
	 * @param Zend_Config $config
	 * @return System_Service_Authorize_AdapterAbstract
	 */
	public function setConfig( Zend_Config $config )
	{
		return $this->setOptions( $config->toArray() );
	}

	/**
	 * If not currently set, a default logger will be created.
	 *
	 * @return void
	 */
	protected function _setDefaultLog()
	{
		if( ! $this->_log instanceOf Zend_Log ) {
			require_once 'Zend/Log.php';
			require_once 'Zend/Log/Writer/Null.php';
			$writer = new Zend_Log_Writer_Null();
			$this->setLog( new Zend_Log( $writer ) );
		}
	}
}
