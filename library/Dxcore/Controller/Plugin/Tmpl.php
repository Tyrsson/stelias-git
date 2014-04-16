<?php
class Dxcore_Controller_Plugin_Tmpl extends Zend_Controller_Plugin_Abstract
{

//    public $_pageTitle;
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $view = new Zend_View();
        
    	try {
    		$user = Zend_Auth::getInstance();
    		
    		$tmpl = new Dxcore_Model_Templates();
    		$tmpl->init(true);
    		$user = new User_Model_User();
    		if(Zend_Auth::getInstance()->hasIdentity())
    		{
    			$userTmpl = $tmpl->getTmplIdByCompany(Zend_Auth::getInstance()->getIdentity()->company_name);
    			$view->declareVars($userTmpl->toArray());
    			
    			$test = $userTmpl->toArray();
    			
    			//Zend_Debug::dump($view->declareVars($test[0]), true);
    		} 
    		else {
    			$this->style = null;
    		}
    		
    	} catch (Exception $e) {
    		
    		echo $e->getMessage();
    		
    	}
    	
    }

    
}