<?php

/**
 * The user submitting the payment.
 *
 */
class System_Service_Authorize_Data_PayerInfo
{
	const STATUS_VERIFIED   = 'verified';
	const STATUS_UNVERIFIED = 'unverified';

	/**
	 * TODO: description.
	 *
	 * @var mixed
	 */
	public $email;

	/**
	 * TODO: description.
	 *
	 * @var mixed
	 */
	public $payerId;

	/**
	 * TODO: description.
	 *
	 * @var mixed
	 */
	public $payerStatus;

	/**
	 * TODO: description.
	 *
	 * @var mixed
	 */
	public $countryCode;

	/**
	 * TODO: description.
	 *
	 * @var bool
	 */
	public $business;

}
