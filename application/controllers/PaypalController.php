<?php
require_once 'Dxcore/Controller/Action.php';
class PaypalController extends Dxcore_Controller_Action
{

	protected  $amount = 100.00;
	public     $returnUrl = 'http://www.thecountryescape.com/paypal/success';
	public     $cancelUrl = 'http://www.thecountryescape.com/paypal/cancel';
    public function init()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    	$options = array('NOSHIPPING' => 1);
    	$authInfo = new Zend_Service_PayPal_Data_AuthInfo($username = 'jsmith_1334721521_biz_api1.webinertia.net', $password = '1334721559', $signature = 'AiPC9BjkCyDFQXbSkoZcgqH3hpacAvaumWasO9idUde0EQxjnVN8UrYe');
    	$service = Zend_Service_PayPal::factory($authInfo, $options, $apiMode = null);
    	$response = $service->setExpressCheckout( (float) $this->amount, $this->returnUrl, $this->cancelUrl, $params = array());
    	
    	//Zend_Debug::dump($response);
    	
    	if ( $response->isSuccess() && ( $token = $response->getValue('token') ) ) {
    		// Redirect to PayPal's service
    		header("Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=$token");
    		//header("Location: https://api-3t.sandbox.paypal.com/nvp?cmd=_express-checkout&token=$token");
    	} else {
    		// Error
    		echo 'Error initiating PayPal transaction...';
    	}
    	
    	//Zend_Debug::dump($service);

    }

    public function successAction()
    {	
     
    }

    public function indexAction()
    {
    	
    }
    
    public function test()
    {
    	$authInfo = new Zend_Service_PayPal_Data_AuthInfo($user = 'jsmith_1334721521_biz_api1.webinertia.net', $pass = '1334721559', $sig = 'AiPC9BjkCyDFQXbSkoZcgqH3hpacAvaumWasO9idUde0EQxjnVN8UrYe');
    	$service = new Zend_Service_PayPal($authInfo, Zend_Service_PayPal::SANDBOX_URI);
    	//Zend_Debug::dump($paypal);
    	 
    	$response = $service->setExpressCheckout(self::$amount, 'http://www.thecountryescape.com/paypal/?status=ok', 'http://www.thecountryescape.com/paypal/?status=cancel', $options);
    	$_SESSION['last_ammount'] = self::$amount; // PayPal requires us to send the same amount when finalizing the transaction
    	 
    	if ( $response->isSuccess() && ( $token = $response->getValue('TOKEN') ) ) {
    		// Redirect to PayPal's service
    		header("Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=$token");
    		//header("Location: https://api-3t.sandbox.paypal.com/nvp?cmd=_express-checkout&token=$token");
    	} else {
    		// Error
    		echo 'Error initiating PayPal transaction...';
    	}
    }
}

