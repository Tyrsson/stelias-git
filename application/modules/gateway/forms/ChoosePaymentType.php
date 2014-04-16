<?php
class Gateway_Form_ChoosePaymentType extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		//$this->setAction('/gateway/paypal/checkout');
		//$this->setDescription('Please click the desired payment method below');
		
		
		$ccType = new Zend_Form_Element_Select('paymentMethod');
		$ccType->setRequired(true);
		$ccType->setAttrib('onchange', 'submit();');
		$ccType->setMultiOptions(array(
		        '0' => '--',
		        Gateway_Service_PayPal_Data_CreditCard::TYPE_VISA => Gateway_Service_PayPal_Data_CreditCard::TYPE_VISA,
		        Gateway_Service_PayPal_Data_CreditCard::TYPE_AMERICANEXPRESS => Gateway_Service_PayPal_Data_CreditCard::TYPE_AMERICANEXPRESS,
		        Gateway_Service_PayPal_Data_CreditCard::TYPE_DISCOVERY => Gateway_Service_PayPal_Data_CreditCard::TYPE_DISCOVERY,
		        Gateway_Service_PayPal_Data_CreditCard::TYPE_MASTERCARD => Gateway_Service_PayPal_Data_CreditCard::TYPE_MASTERCARD,
		        Gateway_Service_PayPal::PAYMENT_TYPE_PAYPAL => Gateway_Service_PayPal::PAYMENT_TYPE_PAYPAL
		)
		);
		$ccType->setLabel('Select Payment Method:');
		
		$this->addElements(array($ccType));

	}
}