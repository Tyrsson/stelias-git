<?php


/** @see Zend_Http_Client */
require_once 'Zend/Http/Client.php';

/** @see System_Service_Authorize_Data_AuthInfo*/
require_once 'System/Service/Authorize/Data/AuthInfo.php';

require_once 'System/Service/Authorize/Aim/Response.php';

class System_Service_Authorize_Aim
{
	/**
	 * Authentication info for the Authorize API service
	 *
	 * @var System_Service_Authorize_Data_AuthInfo
	 */
	protected $_authInfo;

	/**
	 * Zend_Http_Client to use for the service
	 *
	 * @var Zend_Http_Client
	 */
	protected $_httpClient;

	protected $_config = array(
        'endpoint' => 'https://secure.authorize.net/gateway/transact.dll',
        'version'  => 3.1
	);

	protected $_testMode = true;

	/**
	 * Last response.
	 * Useful when performing in steps.
	 *
	 * @var string  Defaults to ''.
	 */
	protected $_lastResponse;

	/**
	 * Initialize payment service through Aim.
	 *
	 * @param System_Service_Authorize_Data_AuthInfo $auth_info
	 * @param Zend_Http_Client                  $httpClient
	 */
	public function __construct(System_Service_Authorize_Data_AuthInfo $authInfo, array $options = array())
	{
		$this->_authInfo    = $authInfo;
		$this->_httpClient = new Zend_Http_Client();

		$this->_config = array_merge($this->_config, $options);
	}

	/**
	 * Perform an AUTH_CAPTURE type payment transaction
	 *
	 * @param float $amount
	 * @param string $sequence
	 * @param long $timeStamp
	 * @return System_Service_Authorize_Aim_Response
	 */
	public function authorizeCapture(float $amount, string $sequence, long $timeStamp,
		 System_Service_Authorize_Data_CreditCard $creditCard = null, System_Service_Authorize_Data_PayerName $payerName = null,
		 System_Service_Authorize_Data_PayerInfo $payerInfo = null)
	{
		if (is_null($sequence)) {
			require_once 'System/Service/Authorize/Exception.php';
			throw new System_Service_Authorize_Exception('The merchant-specific sequence (invoice) number is required');
		}

		if (is_null($timeStamp))
			$timeStamp = time();

		$fields = array (
			'x_type' => System_Service_Authorize::TRANSACTION_AUTH_CAPTURE,
			'x_amount' => $amount,
			'x_card_num' => $creditCard->getAccountNumber(),
			'x_exp_date' => $creditCard->getExpiration(),
			'x_card_code' => $creditCard->getCvv2(),

			'x_show_form' => System_Service_Authorize::SHOW_FORM,
			'x_relay_response' => 'TRUE',
			'x_delim_data' => 'FALSE',
			'x_fp_hash' => $this->getFingerprint($authInfo->getUsername(), $sequence, $timeStamp, $amount),
			'x_fp_sequence' => $sequence,
			'x_fp_timestamp' => $timeStamp,
		);

		// x_type is redundant here, should be refactored
		return $this->_doMethodCall(System_Service_Authorize::TRANSACTION_AUTH_CAPTURE, $fields);
	}

	/**
	 * Authorization only.
	 *
	 * @param float $amount
	 * @param string $sequence
	 * @param long $timeStamp
	 * @return System_Service_Authorize_Aim_Response
	 * @throws System_Service_Authorize_Exception
	 */
	public function authorizationOnly($amount, $sequence, $timeStamp)
	{
		if (is_null($sequence)) {
			require_once 'System/Service/Authorize/Exception.php';
			throw new System_Service_Authorize_Exception('The merchant-specific sequence (invoice) number is required');
		}

		if (is_null($timeStamp))
			$timeStamp = time();

		$fields = array (
			  'x_type' => System_Service_Authorize::TRANSACTION_AUTH_ONLY,
			  'x_amount' => $amount,
			  'x_show_form' => System_Service_Authorize::SHOW_FORM,
			  'x_relay_response' => 'TRUE',
			  'x_delim_data' => 'FALSE',
			  'x_fp_hash' => $this->getFingerprint($authInfo->getUsername(), $sequence, $timeStamp, $amount),
			  'x_fp_sequence' => $sequence,
			  'x_fp_timestamp' => $timeStamp,
		);

		// x_type is redundant here, should be refactored
		return $this->_doMethodCall(System_Service_Authorize::TRANSACTION_AUTH_ONLY, $fields);
	}

	/**
	 * Perform void transaction.
	 * (refund a previously done transaction,
	 * in Authorized/Pending Capture or Captured/Pending Settlement)
	 *
	 * @param string $transactionId
	 * @param string $sequence
	 * @param float $amount
	 * @param long $timeStamp
	 * @return System_Service_Authorize_Aim_Response
	 * @throws System_Service_Authorize_Exception
	 */
	public function voidTransaction($transactionId, $sequence, $amount = null, $timeStamp = null)
	{
		if (! System_Service_Authorize::validateTransactionId($transactionId)) {
			require_once 'System/Service/Authorize/Exception.php';
			throw new System_Service_Authorize_Exception('Transaction key is not a valid Authorize transaction id.  Value given: ' . $transactionId);
		}

		if (! is_null($amount) && (! is_float($amount) || $amount <= 0)) {
			require_once 'System/Service/Authorize/Exception.php';
			throw new System_Service_Authorize_Exception('If passed, amount must be a floating-point number bigger than zero');
		}

		if (is_null($sequence)) {
			require_once 'System/Service/Authorize/Exception.php';
			throw new System_Service_Authorize_Exception('The merchant-specific sequence (invoice) number is required');
		}

		if (is_null($timeStamp))
			$timeStamp = time();

		$fields = array (
			  'x_type' => System_Service_Authorize::TRANSACTION_VOID,
			  'x_amount' => $amount,
			  'x_show_form' => System_Service_Authorize::SHOW_FORM,
			  'x_relay_response' => 'TRUE',
			  'x_delim_data' => 'FALSE',
			  'x_fp_hash' => $this->getFingerprint($authInfo->getUsername(), $sequence, $timeStamp, $amount),
			  'x_fp_sequence' => $sequence,
			  'x_fp_timestamp' => $timeStamp,
		);

		// x_type is redundant here, should be refactored
		return $this->_doMethodCall(System_Service_Authorize::TRANSACTION_VOID, $fields);

	}

	/**
	 * Perform a credit transaction.
	 * (refund a previously done transaction,
	 * in Settled mode.)
	 *
	 * @param string $transactionId
	 * @param string $sequence
	 * @param float $amount
	 * @param long $timeStamp
	 * @return System_Service_Authorize_Aim_Response
	 * @throws System_Service_Authorize_Exception
	 */
	public function creditTransaction($transactionId, $sequence, $amount = null, $timeStamp = null)
	{
		if (! System_Service_Authorize::validateTransactionId($transactionId)) {
			require_once 'System/Service/Authorize/Exception.php';
			throw new System_Service_Authorize_Exception('Transaction key is not a valid Authorize transaction id.  Value given: ' . $transactionId);
		}

		if (! is_null($amount) && (! is_float($amount) || $amount <= 0)) {
			require_once 'System/Service/Authorize/Exception.php';
			throw new System_Service_Authorize_Exception('If passed, amount must be a floating-point number bigger than zero');
		}

		if (is_null($sequence)) {
			require_once 'System/Service/Authorize/Exception.php';
			throw new System_Service_Authorize_Exception('The merchant-specific sequence (invoice) number is required');
		}

		if (is_null($timeStamp))
			$timeStamp = time();

		$fields = array (
			  'x_type' => System_Service_Authorize::TRANSACTION_CREDIT,
			  'x_amount' => $amount,
			  'x_show_form' => System_Service_Authorize::SHOW_FORM,
			  'x_relay_response' => 'TRUE',
			  'x_delim_data' => 'FALSE',
			  'x_fp_hash' => $this->getFingerprint($authInfo->getUsername(), $sequence, $timeStamp, $amount),
			  'x_fp_sequence' => $sequence,
			  'x_fp_timestamp' => $timeStamp,
		);

		// x_type is redundant here, should be refactored
		return $this->_doMethodCall(System_Service_Authorize::TRANSACTION_CREDIT, $fields);

	}
	/**
	 * Sets the version of the API to use
	 *
	 * @param float $version
	 */
	public function setVersion($version)
	{
		$this->_config['version'] = $version;
	}

	/**
	 * Converts keys to uppercase and urlencodes the values
	 *
	 * @return array
	 */
	protected static function _parseParams(array $aParams)
	{
		$data = array();
		foreach($aParams as $name => $value) {
			//do not url encode URL's`!
			if (($name=='x_returnlurl')||($name=='x_cancelurl'))
				$data[strtoupper($name)] = $value;
			else
				$data[strtoupper($name)] = urlencode($value);
		}

		return $data;
	}

	/**
	 * Perform the requested transaction with the payment gateway server.
	 * Posts the data and receives the response.
	 *
	 * @param array $aParams
	 * @deprecated
	 *
	 * @return System_Service_Authorize_Sim_Response
	 */
	protected function _doMethodCall($methodName, array $aParams)
	{
		//--------------------------------------
		// Add auth details to params
		//--------------------------------------
		$aParams['x_login']     = $this->_authInfo->getUsername();
		$aParams['x_tran_key'] 	= $this->_authInfo->getPassword();
		$aParams['x_version']   = $this->_config['version'];
		$aParams['x_test_request'] = 'TRUE';

		// filter any null params
		$aParams = self::_parseParams(array_filter($aParams));

		$this->_httpClient->setParameterPost($aParams);

		$this->_httpClient->setUri($this->_config['endpoint']);
		$this->_lastResponse =
		$this->_httpClient->request(Zend_Http_Client::POST);

		if(! $this->_lastResponse->isSuccessful()) {
			throw new System_Service_Authorize_Exception(
                'HTTP client response was not ok: ' . $this->_lastResponse->getStatus());
		}

		return new System_Service_Authorize_Aim_Response($this->_lastResponse);
	}

	/**
	 * Hash data for sending off.
	 * The fingerprint is required for all authorize.net transactions, SIM mode included.
	 *
	 * @param string $loginID
	 * @param string $sequence
	 * @param long $timeStamp
	 * @param float $amount
	 * @param string $transactionKey
	 * @return string
	 */
	public function getFingerprint($loginID, $sequence, $timeStamp, $amount, $transactionKey = '')
	{
		if (function_exists('hash_hmac'))
		{
			return hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey);
		}
		return bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey));
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
	 * Set or reset test mode.
	 *
	 * @param bool $testMode
	 */
	public function setTestMode($testMode)
	{
		if (is_bool($testMode))
			$this->_testMode = $testMode;

		// This should better be the merchant account in test mode.
		if ($this->_testMode)
			$this->_config['endpoint'] = 'https://test.authorize.net/gateway/transact.dll';
		else
			$this->_config['endpoint'] = 'https://secure.authorize.net/gateway/transact.dll';

	}

	/**
	 * Retrieve whether we're in test mode.
	 *
	 * @return bool
	 */
	public function getTestMode()
	{
		return $this->_testMode;
	}
}
