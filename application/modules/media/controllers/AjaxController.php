<?php

/**
 * AjaxController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class Media_AjaxController extends Dxcore_Controller_Action
{
    public $data;
    public function init()
    {
        parent::init();
        $ajaxHelper = $this->getHelper('AjaxContext');
        $ajaxHelper->addActionContext('getimage', 'html')
                   ->addActionContext('subalbums', 'html')
                   ->addActionContext('filepage', 'html')
                   ->initContext();
        
        $this->albums = new Media_Model_Albums();
        $this->files = new Media_Model_Files();
        if($this->_request->isXmlHttpRequest()) {
            // by default this is an array but we are going to cast it to an object
            $this->data = (object) $this->_request->getParams();
        }
    }

    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated AjaxController::indexAction() default action
    }
    public function getimageAction() {
        if($this->_request->isXmlHttpRequest())
        {
            //$params = $this->_request->getParams();
            $this->view->data = (object) $this->_request->getParams();
            $this->view->appSettings = Zend_Registry::get('appSettings');
        }
    }
    public function subalbumsAction()
    {
        
    }
    public function filepageAction()
    {
        
    }
}
