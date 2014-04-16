<?php

/**
 * Gateway_PaypalExpressController
 *
 * @author
 * @version
 */

require_once 'Dxcore/Controller/Action.php';

class Gateway_PaypalController extends Dxcore_Controller_Action
{

    private $_apiUserName = 'paypro_1351100910_biz_api1.att.net';
    private $_apiPassWord = '1351100960';
    private $_apiSignature = 'Ay.Iyi5W2hvZ0xSJihU.GaTjbVxHAY0bbnYFmkGpY0GxmgIelpbDfS8A';
    
    private $_returnUrl = '/gateway/paypal/return';
    
    private $_cancelUrl = '/gateway/paypal/cancel';

    public $amount = 1.00;
    public $confirmAmount = 0.00;

    public $service;

    private $vector = 'hyekdbr';
    private $encAdapter = 'mcrypt';
    private $apiPaymentActions = array(
                                       'Sale' => Gateway_Service_PayPal::PAYMENT_ACTION_SALE, 
                                       'Authorization' => Gateway_Service_PayPal::PAYMENT_ACTION_AUTHORIZATION
            );
    
    private $success = false;
    private $transactionType;
    
    private $paymentMethods = array(Gateway_Service_PayPal_Data_CreditCard::TYPE_VISA, 
                                Gateway_Service_PayPal_Data_CreditCard::TYPE_MASTERCARD,
                                Gateway_Service_PayPal_Data_CreditCard::TYPE_DISCOVERY,
                                Gateway_Service_PayPal_Data_CreditCard::TYPE_AMERICANEXPRESS,
                                Gateway_Service_PayPal::PAYMENT_TYPE_PAYPAL
            );
    
    private $cardData = array('type' => null, 'accountNumber' => null, 'expiration' => null, 'cvv2' => null);
    private $addressData = array('street' => null, 'city' => null, 'state' => null, 'countryCode' => null, 'zip' => null, 'phoneNum' => null);
    private $payerData = array('firstName' => null, 'lastName' => null, 'middleName' => null);

    public $apiErrors;
    protected $paymentMethod = null;

    public function init() {

        parent::init();

        $authInfo = new Gateway_Service_PayPal_Data_AuthInfo($this->_apiUserName, $this->_apiPassWord, $this->_apiSignature);
        //$this->service = Gateway_Service_PayPal::factory($authInfo, array(), 'NVP');
        $this->service = new Gateway_Service_PayPal_Nvp($authInfo);
        $this->apiErrors = new stdClass();
        
        

    }

    public function indexAction ()
    {
    	$form = new Gateway_Form_ChoosePaymentType();
    	if($this->_request->isPost())
    	{
    	    $this->_forward('checkout', $this->_request->getControllerName(), $this->_request->getModuleName(), array('success' => $this->success, 'paymentMethod' => $this->_request->paymentMethod));
    	}
    	$this->view->form = $form;
    }
    public function returnAction()
    {

    }
    public function cancelAction()
    {

    }
    public function reviewAction()
    {

    }
    public function checkoutAction()
    {
        
        $this->view->pMethodForm = new Gateway_Form_ChoosePaymentType();
        

        $ccData = array();

        //$amount = 0.57; // temp value

        $this->paymentMethod = $this->_request->paymentMethod;
        
        
        switch($this->paymentMethod) {

            case Gateway_Service_PayPal::PAYMENT_TYPE_PAYPAL:

                die('Paypal checkout not supported at this time');

            break;

            default:
                //die('card payment');
                //Zend_Debug::dump($this->_request->getPost());
                $form = new Gateway_Form_CardCheckOut();
                if($this->_request->isPost()) {
                    if($form->isValid($this->_request->getPost())) {
                        
                        // ok form is valid
                
                        $postData = $form->getValues(true);
                
                        $ccData = array_intersect_key($postData['ccData'], $this->cardData);
                        $ccData['expiration'] = $postData['ccData']['exp_month'] . $postData['ccData']['exp_year'];
                        //Zend_Debug::dump($ccData, '$ccData');
                
                        $payerData = array_intersect_key($postData['payerData'], $this->payerData);
                        // Zend_Debug::dump($payerData, '$payerData');
                
                        $addressData = array_intersect_key($postData['addressData'], $this->addressData);
                        //Zend_Debug::dump( $addressData, ' $addressData');
                
                        $cc = new Gateway_Service_PayPal_Data_CreditCard($ccData);
                        $billing = new Gateway_Service_PayPal_Data_Address($addressData);
                        //$card = new Gateway_Service_PayPal_Data_CreditCard($ccData);
                        $payer = new Gateway_Service_PayPal_Data_PayerName($payerData);
                        //$this->requestIp = '24.179.4.69';
                        $result = $this->service->doDirectPayment($this->amount, $cc, $billing, $payer, $this->apiPaymentActions['Sale'], $this->requestIp);
                
                        if($result->isSuccess()) {
                            // do something
                            //Zend_Debug::dump($result, '$result - success');
                            $this->success = true;
                            //Zend_Debug::dump($result, '$result - success');
                            $this->_forward('success', $this->_request->getControllerName(), $this->_request->getModuleName(), array('success' => $this->success, 'paymentMethod' => $this->paymentMethod));
                        }
                        if($result->isError()) {
                            $this->_forward('success', $this->_request->getControllerName(), $this->_request->getModuleName(), array('success' => $this->success, 'response' => $result));
                        }
                    }
                } else {
                
                }
                break;
        }
        $this->view->form = $form;
    }
    public function failedAction()
    {

    }
    public function successAction()
    {
        if($this->_request->success) {
            echo $this->_request->success;
        } 
    }
}
