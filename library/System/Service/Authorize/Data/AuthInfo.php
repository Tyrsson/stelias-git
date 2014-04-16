<?php

/**
 * The required authentication information for authorize.net service.
 *
 * The loginID (or username) is complex value that is at least eight characters
 *  in length, includes uppercase and lowercase letters, numbers, and/or symbols
 *  and identifies a merchant's account to the payment gateway.
 * The API Login ID one of the values that makes up the fingerprint essential
 *  for authenticating SIM transactions.
 *
 */
class System_Service_Authorize_Data_AuthInfo
{
	protected $_username;
	
	protected $_password;

	public function __construct( $username = null, $password = null )
	{
		$this->_username = $username;
		$this->_password = $password;
	}

	public function getUsername()
	{
		return $this->_username;
	}
	
	public function getPassword()
	{
		return $this->_password;
	}
	
	public function setPassword($password)
	{
		$this->_password = $password;
	}
}