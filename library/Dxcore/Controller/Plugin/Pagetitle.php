<?php
class Dxcore_Controller_Plugin_Pagetitle extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $view = new Zend_View();
        
        $action = $request->getActionName();
        $controller = $request->getControllerName();
        $module     = $request->getModuleName();
        
        if($module !== 'pages' && $controller !== 'pages' && $action !== 'index')
        {
	        if(strpos($controller, '.') !== false) 
	        {
	            $view->headTitle(ucwords(str_replace('.', ' ', $controller)));
	        }
	        if(strpos($controller, '.') === false && $module !== 'default')
	        {
	            $view->headTitle(ucwords($module.' '.$action));
	        }
        }
    }
}