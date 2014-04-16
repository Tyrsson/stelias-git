<?php

/**
 * TODO: short description.
 *
 * TODO: long description.
 *
 */
class System_Service_Authorize_Data_PayerName
{

	/**
	 * TODO: description.
	 *
	 * @var string
	 */
	public $salutation;

	/**
	 * TODO: description.
	 *
	 * @var mixed
	 */
	public $firstName;

	/**
	 * TODO: description.
	 *
	 * @var mixed
	 */
	public $lastName;

	/**
	 * TODO: description.
	 *
	 * @var mixed
	 */
	public $middleName;

	/**
	 * TODO: description.
	 *
	 * @var string
	 */
	public $suffix;

	/**
	 * TODO: short description.
	 *
	 * @return TODO
	 */
	public function toSim()
	{
		$data = array();
		$data['SALUTATION'] = $this->salutation;
		$data['FIRSTNAME']  = $this->firstName;
		$data['LASTNAME']   = $this->lastName;
		$data['MIDDLENAME'] = $this->middleName;
		$data['SUFFIX']     = $this->suffix;

		return array_filter( $data );
	}

}
