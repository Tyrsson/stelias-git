<?php
/**
 * PagesController
 *
 * @author Joey Smith
 * @version 0.9
 */
require_once 'Zend/Controller/Action.php';
class Pages_PagesController extends Dxcore_Controller_Action
{
    public $categories;
    public $files;
    public $page;
    public $isHome = false;
    public $model = 'Pages_Model_Pages';
    public $pages;
    public $found = false;
    //public $context = array('index' => array('ajax'));

//     public function preDispatch()
//     {
    	
//     }
    public function init() {

        parent::init();
        $ajax = $this->_helper->getHelper('AjaxContext');
        $ajax->addActionContext('index', 'html')->initContext();
       // Zend_Debug::dump($ajax);
    }
    /**
     * The default action - show the page if the user has access to it, if its not found then of course we get a 404
     */
    public function indexAction ()
    {
        switch($this->isAjax()) {
            case true :
                //die('is ajax');
                    $this->_helper->layout()->disableLayout();
                
                break;
            case false :
                
                break;
        }
        
        
        $pageData = array();
        $this->pages = new $this->model();
        
//         switch(IS_MOBILE) {
//         	case true :
//         	    if(isset($this->appSettings->mobilePages) && !empty($this->appSettings->mobilePages)) {
//         	        $pageNameArray = explode(',', $this->appSettings->mobilePages);
//         	        $this->view->mobilePages = $this->pages->fetchMobilePages($pageNameArray);
//         	        //Zend_Debug::dump($this->view->mobilePages); die('stop');
//         	    }
//         	    break;
//         	case false :
        	     
//         	    break;
//         }
        
        switch( ( isset($this->_request->pageUrl) && !empty($this->_request->pageUrl) ) ) {
        	case true :
        	    $this->page = $this->pages->fetchByUrl($this->_request->pageUrl);
        
        	    if($this->page === null) {
        	        throw new Zend_Controller_Action_Exception('Page not found', 404);
        	    }
        	    elseif($this->page instanceof Pages_Model_Row_Page) {
        
        	        switch($this->page->pageUrl) {
        	        	case 'home' :
        	        	    $this->isHome = true;
        	        	    $this->view->isHome = $this->isHome;
        
        	        	    break;
        
        	        	default :
        
        	        	    break;
        	        }// end switch
        
        	        if (Zend_Controller_Action_HelperBroker::hasHelper($this->page->pageType)) {
        	            $this->view->widget = $this->page->pageType;
        	        }
        
        	        $this->view->headTitle(ucwords($this->page->pageName));
        	        $role = (string) $this->user->role;
        	        $pageRole = (string) $this->page->role;
        	        switch($this->acl->isAllowed($role, $this->module, 'pages.manage')) {
        	        	case true :
        	        	    $this->view->allowEdit = true;
        	        	    break;
        	        	default:
        	        	    $this->view->allowEdit = false;
        	        	    break;
        	        }
        	        //Zend_Debug::dump($role, 'pages controller - $role');
        	        switch($this->acl->isAllowed($role, $this->module, "pages.$pageRole.view")) {
        	        	case true :
        	        	    break;
        	        	default :
        	        	    throw new Zend_Controller_Action_Exception('Access Denied', 550);
        	        	    break;
        	        }
        
        	        $subPages = $this->pages->fetchChildren($this->page->pageId);
        	        $this->view->subPages = $subPages;
        
        	        if(count($subPages) > 0) {
        	            $this->view->subPageNav = $this->_helper->subPageNav($subPages);
        	            $pageData['hasSubPages'] = true;
        	            $pageData['subPages'] = $subPages;
        	        } else {
        	            $pageData['hasSubPages'] = false;
        	        }
        	        // array_merge this
        	        $this->view->page = array_merge($pageData, $this->page->toArray());
        	    }// end elseif
        	    break;
        
        	case false :
        	    throw new Zend_Controller_Action_Exception('Unknown page', 404);
        	    break;
        }
        
        
        
        
    }

}