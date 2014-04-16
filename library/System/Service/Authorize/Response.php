<?php
class System_Service_Authorize_Response
{
	const APPROVED = 1;
	const DECLINED = 2;
	const ERROR = 3;
	const HELD = 4;

	protected $_data = array();

	/**
	 * Create a new response object from response string
	 *
	 * @param string $data
	 */
	public function __construct($response) {}

	/**
	 * Check whether the request was successfull
	 *
	 * @return boolean
	 */
	public function isSuccess() {
		return $this->getValue('response_code');
	}

	/**
	 * Check whether the request produced warnings
	 *
	 * Note: Successful requests might produce warnings as well
	 *
	 * @return boolean
	 */
	public function hasWarnings() {

	}

	/**
	 * Retrieve a response value.
	 *
	 * @param  string $name
	 * @return string|null Value or null if not set
	 */
	public function getValue($key)
	{
		if (isset($this->_data[$key])) {
			return $this->_data[$key];
		}

		return null;
	}

	/**
	 * Magic wrapper around getValue()
	 *
	 * Unlike getValue(), accessing parameters through __get() will throw an exception if the
	 * parameter was not set in the response.
	 *
	 * @param  string $name Key
	 * @return string
	 */
	public function __get($name)
	{
		if (! isset($this->_data[$name])) {
			throw new System_Service_Authorize_Exception("Property '$name' does not exist!");
		}

		return $this->_data[$name];
	}
}
