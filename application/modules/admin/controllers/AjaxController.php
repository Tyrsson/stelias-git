<?php

/**
 * AjaxController
 *
 * @author
 * @version
 */
require_once 'Zend/Controller/Action.php';

class Admin_AjaxController extends Dxcore_Controller_AdminAction
{
    public $data;
    public function init(){
        parent::init();

        $ajax = $this->_helper->getHelper('AjaxContext');
        $ajax->addActionContext('welcome', array('html'))
        	 ->addActionContext('tools', array('html'))
        	 ->addActionContext('content', array('html'))
        	 ->initContext();
        if(!$this->isAjax()) {
            throw new Zend_Controller_Action_Exception('Not Found', 404);
        }
        $this->view->user = $this->user;
    }
    public function welcomeAction()
    {

    }
    public function toolsAction()
    {

    }
    public function contentAction()
    {

    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated AjaxController::indexAction() default action
    }
}
