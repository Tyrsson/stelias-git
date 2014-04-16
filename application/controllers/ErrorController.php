<?php

class ErrorController extends Dxcore_Controller_Action
{
    public $logError = '';
    public function init() {
        parent::init();
    }
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::ERR;
                $this->view->message = '<img src="/skins/'.$this->skinName.'/images/404-error.png" alt="error" height="179" width="438" /><br /><br /><h2>Sorry, the page you requested cannot be found.</h2>';
                $this->logError = $errors->exception;
                break;
            default:

                if($errors->exception->getCode() ==  550) {
                    $this->getResponse()->setHttpResponseCode(550);
                    $priority = Zend_Log::CRIT;
                    $this->view->message = '<img src="/skins/'.$this->skinName.'/images/550-error.png" alt="error" height="179" width="438" /><br /><br />Sorry, it appears you do not have the suffeceint privileges to view this page.';
                    $this->logError = $errors->exception;
                }
                else { // default
                	//$this->_helper->layout()->disableLayout();
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = '<img src="/skins/'.$this->skinName.'/images/500-error.png" alt="error" height="179" width="438" /><br /><br />Sorry, the server encountered an unexpected error.';
                $this->logError = $errors->exception;
                }
                break;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
           // $log->warn($this->logError);
            //$log->log('Request Parameters', $priority, $errors->request->getParams());
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request   = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if ($bootstrap->hasResource('Log')) {
            //return false;
            $log = $bootstrap->getResource('Log');
        } else {
            $log = Zend_Registry::get('log');
        }


        return $log;
    }


}

