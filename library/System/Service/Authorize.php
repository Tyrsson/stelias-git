<?php

/**
 * Authorize.net payment service.
 *
 */
class System_Service_Authorize
{
	const API_MODE_SIM  = 'SIM';
	const API_MODE_AIM  = 'AIM';

	const PAYMENT_ACTION_SALE          = 'Sale';

	const TRANSACTION_AUTH_CAPTURE		= 'AUTH_CAPTURE';
	const TRANSACTION_AUTH_ONLY			= 'AUTH_ONLY';
	const TRANSACTION_VOID				= 'VOID';
	const TRANSACTION_CREDIT			= 'CREDIT';
	const TRANSACTION_SIMPLE_CHECKOUT	= '';

	const SHOW_FORM						= 'PAYMENT_FORM';

	public static function factory(System_Service_Authorize_Data_AuthInfo $authInfo, $options = array(), $apiMode = null)
	{
		if (is_null($apiMode) || !($apiMode == self::API_MODE_SIM || $apiMode == self::API_MODE_AIM)) {
			throw new System_Service_Authorize_Exception('$apiMode must be either "SIM" or "AIM"');
		}

		if ( $apiMode == self::API_MODE_SIM)
			return new System_Service_Authorize_Sim($authInfo, $options);
		elseif ( $apiMode == self::API_MODE_AIM)
			return new System_Service_Authorize_Aim($authInfo, $options);
	}

	/**
	 * Validates the format of a transaction id
	 *
	 * @return boolean
	 */
	public static function validateTransactionId($transactionId)
	{
		require_once 'Zend/Validate.php';
		$validatorChain = new Zend_Validate();

		require_once 'Zend/Validate/StringLength.php';
		require_once 'Zend/Validate/Alnum.php';
		$validatorChain->addValidator(new Zend_Validate_StringLength(17))
			->addValidator(new Zend_Validate_Alnum());

		return $validatorChain->isValid($transactionId);
	}

	/**
	 * Validate a 3-letter ISO currency code
	 *
	 * @param  string $code
	 * @return boolean
	 */
	public static function validateCurrencyCode($code)
	{
		require_once 'Zend/Currency.php';
		$currency = new Zend_Currency();

		return preg_match('|^[A-Z]{3}$|', $code) &&
			in_array($code, array_keys($currency->getCurrencyList()));
	}
}
