<?php

/**
 * This class abstracts the response of the authorize.net server to a Sim API call.
 * So far, SIM response is similar with AIM response, but there are potential differences.
 *
 */
class System_Service_Authorize_Sim_Response extends System_Service_Authorize_Response
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
	 *
	 * @param Zend_Http_Response $response
	 *
	 */
	public function __construct($response)
	{
		$this->_response = $response;
		parse_str($response->getBody(), $this->_data);
		if (isset($this->_data['x_response_code']))
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
	 * Whether the transaction attempt resulted in errors.
	 * Error code should be returned as well.
	 *
	 * @return boolean
	 */
	public function isError()
	{
		return (isset($this->_response_code) && ($this->_response_code === self::ERROR));
	}

	/**
	 * Whether the transaction attempt has resulted in a held-for-review.
	 *
	 * @return boolean
	 */
	public function isHeld()
	{
		return (isset($this->_response_code) && ($this->_response_code === self::HELD));
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
