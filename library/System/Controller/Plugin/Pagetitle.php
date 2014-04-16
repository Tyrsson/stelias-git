<?php
class System_Controller_Plugin_Pagetitle extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $this->view = $bootstrap->getResource('view');

        $this->view->headTitle()->setDefaultAttachOrder('APPEND');

        if($request->getModuleName() !== 'pages') { // dont do this for the pages module
            $this->view->headTitle(ucfirst($request->getModuleName()));
        }
    }
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        if($request->getActionName() !== 'index' && $request->getActionName() !== 'page')
        {
            $this->view->headTitle(ucfirst($request->getActionName()), 'PREPEND');
        }
    }
}