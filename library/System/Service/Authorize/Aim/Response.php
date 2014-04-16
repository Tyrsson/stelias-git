<?php

/**
 * This class abstracts the response of the authorize.net server to a Sim API call.
 *
 */
class System_Service_Authorize_Aim_Response extends System_Service_Authorize_Response
{
	/**
	 * @var Zend_Http_Response
	 */
	protected $_response;

	/**
	 * @var array
	 */
	protected $_data;

	/**
	 *
	 * @var int One of APPROVED, DECLINED, ERROR, HELD.
	 */
	protected $_response_code;

	/**
	 * TODO: short description.
	 *
	 * @param Zend_Http_Response $response
	 *
	 */
	public function __construct($response)
	{
		$this->_response = $response;
		parse_str($response->getBody(), $this->_data);
		if (isset($response['x_response_code']))
		$this->_response_code = $response['x_response_code'];
	}

	/**
	 * Whether the transaction was successful.
	 *
	 * @return boolean
	 */
	public function isSuccess()
	{
		return (isset($this->_response_code) && ($this->_response_code === self::APPROVED));

	}

	/**
	 * Whether the transaction was declined.
	 *
	 * @return boolean
	 */
	public function isDeclined()
	{
		return (isset($this->_response_code) && ($this->_response_code === self::DECLINED));
	}

	/**
	 * Retrieve timestamp.
	 *
	 * @return long
	 */
	public function getTimestamp()
	{
		return $this->_data['x_timestamp'];
	}

	/**
	 * Retrieve gateway API version.
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return $this->_data['x_version'];
	}
}
