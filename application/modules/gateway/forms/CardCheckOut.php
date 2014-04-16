<?php
class Gateway_Form_CardCheckOut extends ZendX_JQuery_Form
{
    public function init($options = null) {

        $this->addElementPrefixPath('Dxcore_Form_Validate', 'Dxcore/Form/Validate/', 'validate');

        $this->setMethod('post');
        $this->setAttrib('class', 'standard');
        
        $total = new Zend_Form_Element_Text('total');
        $total->setAttrib('disabled', 'disabled');
        $total->setAttrib('size', 9);
        $total->setAttrib('maxLength', 9);
        $total->setLabel('Total Purchase Price');
        
        $this->addElement($total);
        

        //<-cardForm
        $country = new Zend_Form_Element_Select('countryCode');
        $country->setRequired(true);
        $country->setMultiOptions(array('US' => 'United States'));
        $country->setLabel('Country *');

        $ccCardNumber = new Zend_Form_Element_Text('accountNumber');
        $ccCardNumber->setAttrib('autocomplete', 'off');
        $ccCardNumber->setAttrib('size', 16);
        $ccCardNumber->setAttrib('maxLength', 16);
        $ccCardNumber->setRequired(true);
        $ccCardNumber->addFilter('StringTrim');

        $ccValid = new Zend_Validate_CreditCard(array(Zend_Validate_CreditCard::AMERICAN_EXPRESS,
                                                      Zend_Validate_CreditCard::VISA,
                                                      Zend_Validate_CreditCard::DISCOVER
                                                ));
        $ccCardNumber->addValidator($ccValid);
        $ccCardNumber->setLabel('Credit Card Number *');


        $visa = new Zend_Form_Element_Image('visa');
        $visa->setAttrib('disabled', 'disabled');
        $visa->setImage('/modules/gateway/images/visa.png');
       // $visa->setDisableLoadDefaultDecorators(true);
        $visa->removeDecorator('Label');

        $amex = new Zend_Form_Element_Image('amex');
        $amex->setAttrib('disabled', 'disabled');
        $amex->setImage('/modules/gateway/images/amex.png');
        //$amex->setDisableLoadDefaultDecorators(true);
        $amex->removeDecorator('Label');

        $discover = new Zend_Form_Element_Image('discover');
        $discover->setAttrib('disabled', 'disabled');
        $discover->setImage('/modules/gateway/images/discover.png');
        //$discover->setDisableLoadDefaultDecorators(true);
        $discover->removeDecorator('Label');

        $mastercard = new Zend_Form_Element_Image('mastercard');
        $mastercard->setAttrib('disabled', 'disabled');
        $mastercard->setImage('/modules/gateway/images/mastercard.png');
        //$mastercard->setDisableLoadDefaultDecorators(true);
        $mastercard->removeDecorator('Label');
        
        $paypal = new Zend_Form_Element_Image('mastercard');
        $mastercard->setAttrib('disabled', 'disabled');
        $mastercard->setImage('/modules/gateway/images/mastercard.png');
        //$mastercard->setDisableLoadDefaultDecorators(true);
        $mastercard->removeDecorator('Label');

        $ccType = new Zend_Form_Element_Select('type');
        $ccType->setRequired(true);
        $ccType->setMultiOptions(array(
                                       '0' => '--',
                                       Gateway_Service_PayPal_Data_CreditCard::TYPE_VISA => Gateway_Service_PayPal_Data_CreditCard::TYPE_VISA,
                                       Gateway_Service_PayPal_Data_CreditCard::TYPE_AMERICANEXPRESS => Gateway_Service_PayPal_Data_CreditCard::TYPE_AMERICANEXPRESS,
                                       Gateway_Service_PayPal_Data_CreditCard::TYPE_DISCOVERY => Gateway_Service_PayPal_Data_CreditCard::TYPE_DISCOVERY,
                                       Gateway_Service_PayPal_Data_CreditCard::TYPE_MASTERCARD => Gateway_Service_PayPal_Data_CreditCard::TYPE_MASTERCARD,
                                       Gateway_Service_PayPal::PAYMENT_TYPE_PAYPAL => Gateway_Service_PayPal::PAYMENT_TYPE_PAYPAL
                                       )
                                );
        $ccType->setLabel('Payment Method:');

        $cardsForm = new Zend_Form_SubForm();
        $cardsForm->addElements(array($visa, $amex, $discover, $mastercard));

        // lets get a little crazy
        $expMonth = new Zend_Form_Element_Select('exp_month');
        $expMonth->setLabel('Card Expiry Date *:')
        ->addMultiOptions(
                array('01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12'))
                ->setRequired(true)
                ->setDescription('/');
        //$this->addElement($expMonth);

        $expYearOptions = array();
        $thisYear = date('Y');
        for ($i = 0; $i < 15; ++$i) {
            $val = $thisYear + $i;
            $expYearOptions[$val] = $val;
        }

        $expYear = new Zend_Form_Element_Select('exp_year');
        $expYear->removeDecorator('label')
        ->addMultiOptions($expYearOptions)
        ->setRequired(true)
        ->setDescription(' (Month / Year)')
        ->addValidator('OnlyFutureMonthYear', false, array('exp_month'));
        //$this->addElement($expYear);
        // end crazy

        $cvv = new Zend_Form_Element_Text('cvv2');
        $cvv->setLabel('CSC *');
        $cvv->setRequired(true);
        //$cvv->set
        $cvv->setDescription('3-4 Digit number on back of card');

        $addressOne = new Zend_Form_Element_Text('street');
        $addressOne->setAttrib('size', 50);
        //$addressOne->setAttrib('maxLength', 100);
        $addressOne->setRequired(true);
        $addressOne->setLabel('Address 1 *');

        $addressTwo = new Zend_Form_Element_Text('streetTwo');
        $addressTwo->setAttrib('size', 50);
        //$addressOne->setAttrib('maxLength', 100);
        //$addressTwo->setRequired(true);
        $addressTwo->setLabel('Address 2');

        $zip = new Zend_Form_Element_Text('zip');
        $zip->setRequired(true);
        $zip->setAttrib('size', 5);
        $zip->setAttrib('maxLength', 5);
        $zip->setLabel('Zip Code *');

        $city = new Zend_Form_Element_Text('city');
        $city->setAttrib('size', 50);
        //$addressOne->setAttrib('maxLength', 100);
        $city->setRequired(true);
        $city->setLabel('City *');


        $state = new Zend_Form_Element_Select('state');
        $state->setRequired(true);
        $state->setMultiOptions(array('Al' => 'Alabama'));
        $state->setLabel('State *');


        //<-cardForm

        //<- payerForm
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email');
        //$email->setRequired(true);
        $email->setAttrib('size', 50);
        $email->setAttrib('maxlength', 127);
        $email->addFilter('StringTrim');
        $email->addValidator('EmailAddress');

        $firstName = new Zend_Form_Element_Text('firstName');
        $firstName->setLabel('First Name *');
        $firstName->setRequired(true);
        $firstName->setAttrib('size', 25);
        $firstName->setAttrib('maxlength', 25);
        $firstName->addFilter('StringTrim');

        $middleName = new Zend_Form_Element_Text('middleName');
        $middleName->setLabel('Middle Initial *');
        $middleName->setRequired(true);
        $middleName->setAttrib('size', 1);
        $middleName->setAttrib('maxlength', 1);
        $middleName->addFilter('StringTrim');
        $middleName->addFilter('StringToUpper');

        $lastName = new Zend_Form_Element_Text('lastName');
        $lastName->setLabel('Last Name *');
        $lastName->setRequired(true);
        $lastName->setAttrib('size', 25);
        $lastName->setAttrib('maxlength', 25);
        $lastName->addFilter('StringTrim');

        $phone = new Zend_Form_Element_Text('phoneNum');
        $phone->setLabel('Phone');
       // $phone->setRequired(true);
        $phone->setAttrib('size', 12);
        $phone->setAttrib('maxlength', 12);
        $phone->addFilter('StringTrim');

        //<- payerForm

        //$this->addElements(array($phone, $email));

        $cardForm = new Zend_Form_SubForm();
        $cardForm->addElements(array($ccType, $country, $ccCardNumber, $expMonth, $expYear, $cvv));

        $payerForm = new Zend_Form_SubForm();
        $payerForm->addElements(array($firstName, $middleName, $lastName));

        $addressForm = new Zend_Form_SubForm();
        $addressForm->addElements(array($addressOne, $addressTwo, $zip, $city, $state, $phone, $email));

        // add the subforms to the form
        $this->addSubForms(array('ccData' => $cardForm, 'payerData' => $payerForm, 'addressData' => $addressForm));

        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setIgnore(true);
        $this->addElement($submit);

        $expMonth->setDecorators(
                array(
                        'ViewHelper',
                        array(
                                array(
                                        'data' => 'HtmlTag'
                                ), //
                                array(
                                        'tag' => 'dd',
                                        'id' => 'card-expire',
                                        'openOnly' => true
                                )
                        ), //
                        array(
                                'Label',
                                array(
                                        'tag' => 'dt'
                                )
                        ), //
                        array(
                                'Description',
                                array(
                                        'tag' => 'span',
                                        'class' => 'seperator'
                                )
                        )
                ));

        $expYear->setDecorators(
                array(
                        'ViewHelper',
                        array(
                                'Description',
                                array(
                                        'tag' => 'span',
                                        'class' => 'greyout'
                                )
                        ),
                        'Errors',
                        array(
                                array(
                                        'row' => 'HtmlTag'
                                ),
                                array(
                                        'tag' => 'dd',
                                        'closeOnly' => true
                                )
                        )
                ));
    }
}