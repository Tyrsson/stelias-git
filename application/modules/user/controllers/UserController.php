<?php
/**
 * UserController
 * 
 * @author Jsmith
 * @version 1.0 Beta
 */
require_once 'Zend/Controller/Action.php';
class User_UserController extends Dxcore_Controller_Action
{
    public $action;
    public $controller;
    public $module;
    public $user;
    public function preDispatch()
    {
        // set admin layout
        // check if user is authenticated
        // if not, redirect to login page
        $url = $this->getRequest()->getRequestUri();
        // get action for the request and set the profile view if needed
        $this->controller = $this->getRequest()->getParam('controller');
        $this->action = $this->getRequest()->getParam('action');
        $this->module = $this->_request->getParam('module');
        //Zend_Debug::dump($profile, true);
        if (! Zend_Auth::getInstance()->hasIdentity()) 
        {
            $session = new Zend_Session_Namespace('custom.auth');
            $session->requestURL = $url;
            $this->_redirect('/user/login');
        } 
        
    }
    public function init()
    {
        $userParams = array('products' => 'Products_Model_Products');
        $this->user = new User_Model_User();
        $this->user->init();
        $this->_helper->layout->setLayout('user');
        
        if ($this->_helper->getHelper('FlashMessenger')->getMessages()) 
        {
            $this->view->messages = $this->_helper
                            ->getHelper('FlashMessenger')
                            ->getMessages();
        }
        //$sidebar = $this->_helper->getHelper('sideBar');
    }
    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        
        if($this->user->checkSession())
        {
//             if($this->user->logOut())
//             {
//                 $this->_redirect('/user/login');
//             }
        }
    }
    public function productsAction()
    {
        
        // Get the requested page from the request
        // If by chance its not set, set a default and start them at page = 1
        if ($this->_prodPage === null || empty($this->_prodPage))
        {
            $this->_prodPage = 1;
        }
        // Query the resource object for the reqested page
        
        $paginator = $this->user->products->getOnePage($this->_prodPage);
        // The pagination control will need this.
        $this->view->page = $this->_prodPage;
        // Assign the paginator to the view for use
        $this->view->paginator = $paginator;
    }
}
