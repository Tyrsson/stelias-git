<?php
/**
 *
 * @author Joey
 * @version
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';
/**
 * PageWidget Action Helper
 *
 * @uses actionHelper System_Controller_Action_Helper
 */
class System_Controller_Action_Helper_Home extends Zend_Controller_Action_Helper_Abstract
{
    /**
     *
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;
    /**
     * Constructor: initialize plugin loader
     *
     * @return void
     */
    protected $view;

    protected $_request;

    protected $params; // request params

    protected $pages;

    public $settings;

    public $recentImages;

    public $data;

    public $suffix;

    public function __construct ()
    {
        $this->data = new stdClass();
        $this->data->showRecent = false;
        $this->data->showInWidget = false;
        // TODO Auto-generated Constructor
        //$this->pluginLoader = new Zend_Loader_PluginLoader();
        $this->settings = Zend_Registry::get('appSettings');
        $this->data->settings = $this->settings;
        $this->pages =  new Page_Model_Page();
        if($this->settings->showRecentInHomeWidget)
        {
           $this->data->showRecent = true;
           $this->data->recentImages = $this->getRecentImages();

           $this->data->mediaPageName = $this->pages->fetchByType('media', $nameOnly = true);
           $this->data->album = new Media_Model_Albums();
        }

    }
    public function preDispatch()
    {
        $this->controller = $this->getActionController();
        $this->renderer = $this->controller->getHelper('viewRenderer');

        $this->view = self::getView();

        $this->_request = $this->getRequest();

        if (null === ($this->controller = $this->getActionController())) {
        	return;
        }
        $this->controller = $this->getActionController();
        $this->renderer = $this->controller->getHelper('viewRenderer');

        //$this->view = self::getView();
        $this->_request = $this->getRequest();
        // Only run this once our pageName is set
        if(isset($this->_request->pageUrl))
        {
        	$this->page = $this->pages->fetchByUrl($this->_request->pageUrl);

        	try {
        		if($this->page == null) {
        			throw new Zend_Controller_Action_Exception('The requested page does not exist', 404);
        		}
        	} catch (Exception $e) {
        		return;
        	}

        	switch($this->page->pageType) {
        		case 'home' :
			        	$subs = $this->pages->fetchChildren($this->page->pageId);
			            //Zend_Debug::dump($subs);
			            if(count($subs) >= 1) {
			            	$this->createPageWidget($subs);
			            }
        			break;
        		default :
        			return;
        			break;
        	}

        }

    }
    public function getRecentImages()
    {
        $images = new Media_Model_Files();
        return $images->fetchRecentImages();
    }
    public function getView()
    {
        if(null !== $this->view) {
            return $this->view;
        }
        $controller = $this->getActionController();
        $view = $controller->view;
        if(!$view instanceof Zend_View_Abstract) {
            return;
        }
        return $view;
    }
    public function createPageWidget($pages)
    {
       $this->data->widgetPages = $pages;
       $this->view->home = $this->view->partial('homewidget.' . $this->renderer->getViewSuffix(), 'default', $this->data);
    }
    /**
     * Strategy pattern: call helper as broker method
     */
    public function direct ()
    {
        // TODO Auto-generated 'direct' method
    }
}
