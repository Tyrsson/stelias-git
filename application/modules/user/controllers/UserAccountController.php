<?php
/**
 * User_UserAccountController
 *
 * @author Joey Smith
 * @version 0.9
 */
require_once 'Dxcore/Controller/Action.php';
class User_UserAccountController extends Dxcore_Controller_Action
{
    public $params;
    public function init()
    {
    	parent::init();
        //$this->_helper->layout->setLayout('user');
        if(!$this->isLogged) {
        	$this->_redirect('/user/login');
        }
//         if($this->isLogged && $this->user->userId != $this->_request->uid) {
//         	if(!$this->acl->isAllowed($this->user->role, 'useraccount', 'admin.edit.user')) {
//         		$this->messenger->addMessage('You do not have the proper privileges to view another user\'s summary');
//         		$this->_redirect('/');
//         	}
//         }
    }
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated UserAccountController::indexAction() default action
    }
    public function profileAction()
    {
        if(isset($this->_request->userId) && $this->isLogged)
        {
            //Zend_Debug::dump($this->user->init(null, $this->_request->userId), true);
        }
    }
    public function summaryAction()
    {

    	if(!$this->isLogged) {
    		$this->messenger->addMessage('Please login to view your account summary!');
    		$this->_redirect('/user/login');
    	} else {
    		if($this->_request->uid && $this->acl->isAllowed($this->user->role, 'useraccount', 'admin:summary')) {
    			$this->view->user = $this->user = $this->_uManager->fetch($this->_request->uid);
    		} else {
    			$this->view->user = $this->user;
    		}
    	}

    }
    public function successAction()
    {
        if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
            $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();
        }
        else {
            $this->_redirect('/user/success');
        }
    }
    public function resetpassAction()
    {
        $form = new User_Form_ResetPass();
        if(isset($this->_request->uid))
        {

        }
        $this->view->form = $form;
    }
}
