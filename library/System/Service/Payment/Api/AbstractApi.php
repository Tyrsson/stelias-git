<?php

/**
 * Abstract class for use when calling methods of a particular gateway API.
 *
 */
abstract class System_Service_Payment_Api_AbstractApi
{
	/**
	 * The adapter used to send requests.
	 *
	 * @var System_Service_Authorize_Adapter_AbstractAdapter
	 */
	protected $_adapter;

	/**
	 * the current fields setup for the request.
	 *
	 * @var array
	 */
	protected $_fields;

	protected $_methodName;

	/**
	 * Constructor.
	 * The System_Service_Payment_Adapter_AbstractAdapter is the adapter to the particular
	 * gateway API implementation.
	 * This class will delegate requests to it.
	 *
	 * @param System_Service_Payment_Adapter_AbstractAdapter $adapter
	 *
	 */
	public function __construct( System_Service_Payment_Adapter_AbstractAdapter $adapter )
	{
		$this->_adapter = $adapter;
		$this->init();
	}

	public function init()
	{}

	public abstract function execute();

	/**
	 * Sends the request using the Adapter, and returns the response.
	 *
	 * @return System_Service_Payment_Response
	 */
	public function sendRequest()
	{
		$fields = $this->getFields();
		return $this->_adapter->callApi( $this->_methodName, $fields );
	}

	/**
	 * set a single field.
	 *
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function setField( $name, $value )
	{
		$this->_fields[ $name ] = $value;
	}

	/**
	 * Set fields.
	 *
	 * @param array $fields
	 * @return void
	 */
	public function setFields( array $fields )
	{
		foreach( $fields  as $name => $value ) {
			$this->setField( $name, $value );
		}
	}

	/**
	 * Get all fields for this api method.
	 *
	 * @return array
	 */
	public function getFields()
	{
		return $this->_fields;
	}

	/**
	 * @param mixed $name
	 *
	 * @return string
	 */
	public function getField( $name )
	{
		return (empty($this->_fields[ $name ]) ? '' : $this->_fields[ $name ]);
	}

	public function __get( $name )
	{
		return $this->getField( $name );
	}

	public function __set( $name, $value )
	{
		$this->setField( $name, $value );
	}
}
