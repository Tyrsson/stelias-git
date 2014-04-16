<?php
require_once 'Zend/Date.php';

/**
 * Credit card information. For some transaction types, this information
 * may be stored/handled on the merchant site.
 * (SIM API mode does not use it.)
 *
 */
class System_Service_Payment_Data_CreditCard
{

	const TYPE_VISA = 'Visa';
	const TYPE_MASTERCARD = 'MasterCard';
	const TYPE_DISCOVER  = 'Discover';
	const TYPE_AMERICANEXPRESS = 'American Express';

	/**
	 * Card type.
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Account number. This should be validated.
	 *
	 * @var string
	 */
	public $accountNumber;

	/**
	 * Expiration date.
	 *
	 * @var mixed
	 */
	public $expiration;

	/**
	 * cvv2 number
	 *
	 * @var mixed
	 */
	public $cvv2;

	/**
	 * Start date.
	 *
	 * @var string
	 */
	public $startDate;

	/**
	 * Issue
	 *
	 * @var int
	 */
	public $issueNumber;

	/**
	 * TODO: short description.
	 *
	 * @param mixed
	 *
	 */
	public function __construct( array $params = array() )
	{
		foreach ($params as $prop => $value) {
			$this->{$prop} = $value;
		}
	}

	/**
	 * TODO: short description.
	 *
	 * @param string $type
	 *
	 * @return void
	 */
	public function setCardType( $type )
	{
		$this->type = $type;
	}

	/**
	 * TODO: short description.
	 *
	 * @return string
	 */
	public function getCardType()
	{
		return $this->type;
	}

	/**
	 * TODO: short description.
	 *
	 * @param string $number
	 *
	 * @return void
	 */
	public function setAcctNumber( $number )
	{
		$this->accountNumber = $number;
	}

	/**
	 * TODO: short description.
	 *
	 * @return string
	 */
	public function getAcctNumber()
	{
		return $this->accountNumber;
	}

	/**
	 * TODO: short description.
	 *
	 * @param double $date
	 *
	 * @return void
	 */
	public function setExpiration( $date )
	{
		$this->expiration = $date;
	}

	/**
	 * TODO: short description.
	 *
	 * @return string
	 */
	public function getExpiration()
	{
		return $this->expiration;
	}

	/**
	 * TODO: short description.
	 *
	 * @param mixed $cvv2
	 *
	 * @return void
	 */
	public function setCvv2( $cvv2 )
	{
		$this->cvv2 = $cvv2;
	}

	/**
	 * TODO: short description.
	 *
	 * @return string
	 */
	public function getCvv2()
	{
		return $this->cvv2;
	}

	/**
	 * TODO: short description.
	 *
	 * @param double $date
	 *
	 * @return void
	 */
	public function setStartDate( $date )
	{
		$this->startDate = $date;
	}

	/**
	 * TODO: short description.
	 *
	 * @return double
	 */
	public function getStartdate()
	{
		return $this->startdate;
	}

	/**
	 * Reformat the data for sending off, with the respective protocol.
	 *
	 * @return array
	 */
	public function toSim()
	{
		$data = array();
		$data['CREDITCARDTYPE'] = $this->type;
		$data['ACCT']           = $this->accountNumber;
		$data['EXPDATE']        = $this->expiration;
		$data['CVV2']           = $this->cvv2;
		$data['STARTDATE']      = $this->startDate;

		return array_filter( $data );
	}

	private function _validateAccountNumber($accountNumber)
	{
		require_once 'Zend/Validate/CreditCard.php';
		$ccValidator = new Zend_Validate_CreditCard(array('type', $this->getCardType()));
		if(!$ccValidator->isValid($accountNumber)) {
			$messages = $ccValidator->getMessages();
			require_once 'System/Service/Payment/Exception.php';
			throw new System_Service_Payment_Exception($messages[0]);
		}
	}
}
