<?php

/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class System_Service_PayPal_Api_RefundTransaction
    extends System_Service_PayPal_Api_AbstractApi
{
    public function execute()
    {
        $amount         = $this->getField( 'amt' );
        $transactionId  = $this->getField( 'transactionId' );
        $refundType     = $this->getField( 'refundType' );

        if (! System_Service_PayPal::validateTransactionId( $transactionId ) ) {
            // require_once 'Zend/Service/PayPal/Exception.php';
            throw new System_Service_PayPal_Exception('$transactionId is not a
            valid PayPal transaction id.  Value given: ' . $transactionId);
        }
        
        if (! in_array($refundType, array(
            System_Service_PayPal::REFUND_TYPE_OTHER, System_Service_PayPal::REFUND_TYPE_FULL, System_Service_PayPal::REFUND_TYPE_PARTIAL)))
        {
            // require_once 'Zend/Service/PayPal/Exception.php';
            throw new System_Service_PayPal_Exception('Refund Type must be set to one of: "Other", "Full", or "Partial"');
        }
        
        if (! is_null($amount) && (! is_float($amount) || $amount <= 0)) {
            // require_once 'Zend/Service/PayPal/Exception.php';
            throw new System_Service_PayPal_Exception('If passed, amount must be a floating-point number bigger than zero');
        }
        
        if ($refundType == System_Service_PayPal::REFUND_TYPE_PARTIAL && is_null($amount)) {
            // require_once 'Zend/Service/PayPal/Exception.php';
            throw new System_Service_PayPal_Exception('Amount must be set if refund type is ' . System_Service_PayPal::REFUND_TYPE_PARTIAL);
        } elseif ($refundType == System_Service_PayPal::REFUND_TYPE_FULL && ! is_null($amount)) {
            // require_once 'Zend/Service/PayPal/Exception.php';
            throw new System_Service_PayPal_Exception('Amount may not be set if refund type is ' . System_Service_PayPal::REFUND_TYPE_FULL);
        }
        
        
        return $this->sendRequest();
    }
}
