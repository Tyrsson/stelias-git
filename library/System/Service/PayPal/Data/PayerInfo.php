<?php

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class System_Service_PayPal_Data_PayerInfo
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

    public function __construct( array $data = null)
    {
        if(null !== $data) {
            $this->setData($data);
        }
    }
    public function setData(array $data)
    {
        foreach($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
