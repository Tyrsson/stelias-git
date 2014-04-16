<?php
/**
 * PagesController
 *
 * @author
 * @version
 */
require_once 'Zend/Controller/Action.php';
class IndexController extends Dxcore_Controller_Action
{

	public function init() {
		parent::init();
		
	}
    public function indexAction ()
    {
        // TODO Auto-generated PagesController::indexAction() default action
        $settings = new Admin_Model_AppSettings();
        $page = new Pages_Model_Pages();
        
        switch($this->requestUri) {
            case '/' :
                $this->_forward($action = 'index', $controller = 'pages', $module = 'pages', $params = array('pageUrl' => 'home'));
                break;
           
            case '/admin' :
                $this->_forward($action = 'index', $controller = 'admin', $module = 'admin', $params = array('pageUrl' => $this->_request->pageUrl));
                break;
                
            default :
                $this->_forward($action = 'index', $controller = 'pages', $module = 'pages', $params = array('pageUrl' => $this->_request->pageUrl));
                break;
        }
        
//         if($this->requestUri === '/')
//         {
//         	$this->_forward($action = 'index', $controller = 'pages', $module = 'pages', $params = array('pageUrl' => 'home'));
//         } else {
//         	$this->_forward($action = 'index', $controller = 'pages', $module = 'pages', $params = array('pageUrl' => $this->_request->pageUrl));
//         }
    }
}
